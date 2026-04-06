<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Address;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class Checkout extends Component
{
    use \App\Traits\HandlesCart, \App\Traits\SendsNotifications;

    public $currentStep = 1;
    
    // Step 1: Shipping
    public $name, $email, $phone, $address, $city, $state, $zip, $country = 'Indonesia', $notes;
    
    // Saved Addresses
    public $savedAddresses = [];
    public $selectedAddressId = null;

    // Step 2: Review (Calculated)
    public $cartItems = [];
    public $isBuyNow = false;
    public $subtotal = 0;
    public $tax = 0;
    public $shippingCost = 0;
    public $total = 0;
    public $pointsDiscount = 0;
    public $usePoints = false;
    public $userPoints = 0;
    public $pointsToRedeem = 0;
    public $pointsToEarn = 0;
    public $totalSavings = 0;

    // Voucher
    public string $voucherCode = '';
    public ?int $appliedVoucherId = null;
    public string $appliedVoucherCode = '';
    public string $voucherMessage = '';
    public string $voucherMessageType = 'success';
    public float $voucherDiscount = 0;

    // Step 3: Payment (Dummy)
    public $paymentMethod = 'midtrans';
    public $cardName, $cardNumber, $expiry, $cvv;

    public function rules()
    {
        $rules = [];
        
        if ($this->currentStep == 1) {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'zip' => 'required|string|max:10',
                'country' => 'required|string',
            ];
        }
        
        if ($this->currentStep == 3 && $this->paymentMethod == 'credit_card') {
            $rules = [
                'cardName' => 'required|string',
                'cardNumber' => 'required|numeric|digits:16',
                'expiry' => 'required|string|regex:/^[0-9]{2}\/[0-9]{2}$/',
                'cvv' => 'required|numeric|digits:3',
            ];
        }

        return $rules;
    }

    public function mount()
    {
        $this->syncCart();
        $this->loadCart();
        
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->userPoints = $user->points ?? 0;

            // Load saved addresses
            $this->savedAddresses = Address::where('user_id', $user->id)
                ->orderBy('is_default', 'desc')
                ->get()
                ->toArray();

            // Auto-fill from default address
            $default = Address::where('user_id', $user->id)
                ->where('is_default', true)
                ->first();

            if ($default) {
                $this->selectedAddressId = $default->id;
                $this->fillFromAddress($default);
            }
        }
    }

    public function selectAddress($id)
    {
        $address = Address::where('user_id', Auth::id())->find($id);
        if ($address) {
            $this->selectedAddressId = $address->id;
            $this->fillFromAddress($address);
        }
    }

    protected function fillFromAddress(Address $address)
    {
        $this->name    = $address->recipient_name;
        $this->phone   = $address->phone_number;
        $this->address = $address->full_address;
        $this->city    = $address->city;
        $this->state   = $address->state;
        $this->zip     = $address->postal_code;
    }

    public function loadCart()
    {
        if (!Auth::check()) {
            return redirect()->route('cart')->with('error', 'Silakan masuk untuk melanjutkan pembayaran.');
        }

        if (request()->query('mode') === 'buynow' && session()->has('buy_now')) {
            $this->isBuyNow = true;
            $buyNowData = session()->get('buy_now');
            
            $product = Product::with(['images' => function($q) {
                $q->where('is_primary', true);
            }])->find($buyNowData['product_id']);

            if ($product) {
                // Mock a cart item structure
                $this->cartItems = collect([(object)[
                    'product_id' => $product->id,
                    'product' => $product,
                    'quantity' => $buyNowData['quantity'],
                    'metadata' => ['attributes' => $buyNowData['attributes']]
                ]]);
            }
        } else {
            $selectedIds = session()->get('checkout_selections', []);
            
            $query = CartItem::where('user_id', Auth::id())
                ->with(['product.vendor', 'product.images' => function($q) {
                    $q->where('is_primary', true);
                }]);

            if (!empty($selectedIds)) {
                $query->whereIn('id', $selectedIds);
            }
            
            $this->cartItems = $query->get();
        }

        if ($this->cartItems->isEmpty()) {
            return redirect()->route('cart');
        }

        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $this->totalSavings = $this->cartItems->sum(function ($item) {
            $price_original = $item->product->price_original > 0 ? $item->product->price_original : $item->product->price;
            return ($price_original - $item->product->price) * $item->quantity;
        });
        
        $this->tax = $this->subtotal * 0.1; // 10%
        $this->shippingCost = $this->subtotal > 1000000 ? 0 : 20000; // Free shipping over Rp 1.000.000
        
        // Point Logic
        $this->pointsToEarn = floor($this->subtotal * 0.01); // 1% points
        
        if ($this->usePoints) {
            // 1 point = Rp 1
            $maxRedeemable = min($this->userPoints, $this->subtotal + $this->tax + $this->shippingCost);
            $this->pointsToRedeem = $maxRedeemable;
            $this->pointsDiscount = $maxRedeemable;
        } else {
            $this->pointsToRedeem = 0;
            $this->pointsDiscount = 0;
        }

        // Voucher discount
        if ($this->appliedVoucherId) {
            $voucher = Voucher::find($this->appliedVoucherId);
            if ($voucher && $voucher->isValid()) {
                $this->voucherDiscount = $voucher->calculateDiscount($this->subtotal);
            } else {
                // Voucher became invalid since applying
                $this->appliedVoucherId = null;
                $this->appliedVoucherCode = '';
                $this->voucherDiscount = 0;
                $this->voucherMessage = 'Voucher sudah tidak berlaku dan telah dihapus.';
                $this->voucherMessageType = 'error';
            }
        } else {
            $this->voucherDiscount = 0;
        }

        $this->total = max(0, $this->subtotal + $this->tax + $this->shippingCost - $this->pointsDiscount - $this->voucherDiscount);
    }

    public function updatedUsePoints()
    {
        $this->loadCart(); // Ensure items are fresh
        $this->calculateTotals();
    }

    public function updatedSelectedAddressId($value)
    {
        if ($value) {
            $this->selectAddress($value);
        }
    }

    public function applyVoucher()
    {
        $this->voucherMessage = '';
        $code = strtoupper(trim($this->voucherCode));

        if (empty($code)) {
            $this->voucherMessage = 'Masukkan kode voucher terlebih dahulu.';
            $this->voucherMessageType = 'error';
            return;
        }

        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            $this->voucherMessage = 'Kode voucher tidak ditemukan.';
            $this->voucherMessageType = 'error';
            return;
        }

        if (!$voucher->isValid()) {
            $this->voucherMessage = 'Voucher ini sudah tidak berlaku atau kuota habis.';
            $this->voucherMessageType = 'error';
            return;
        }

        if ($this->subtotal < $voucher->min_spend) {
            $this->voucherMessage = 'Minimum belanja untuk voucher ini adalah Rp ' . number_format($voucher->min_spend, 0, ',', '.');
            $this->voucherMessageType = 'error';
            return;
        }

        $this->appliedVoucherId = $voucher->id;
        $this->appliedVoucherCode = $voucher->code;
        $this->voucherCode = '';
        $this->voucherMessage = 'Voucher "' . $voucher->code . '" berhasil diterapkan!';
        $this->voucherMessageType = 'success';
        $this->calculateTotals();
    }

    public function removeVoucher()
    {
        $this->appliedVoucherId = null;
        $this->appliedVoucherCode = '';
        $this->voucherCode = '';
        $this->voucherMessage = '';
        $this->voucherDiscount = 0;
        $this->calculateTotals();
    }

    public function nextStep()
    {
        $rules = $this->rules();
        if (!empty($rules)) {
            $this->validate($rules);
        }
        
        if ($this->currentStep < 3) {
            if ($this->currentStep == 2) {
                // Final stock check before payment step
                foreach ($this->cartItems as $item) {
                    if ($item->product->stock < $item->quantity) {
                        $this->dispatch('notify', [
                            'message' => "Maaf, stok {$item->product->name} tidak mencukupi!",
                            'type' => 'error'
                        ]);
                        return;
                    }
                }
            }
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function placeOrder()
    {
        $rules = $this->rules();
        if (!empty($rules)) {
            $this->validate($rules);
        }

        try {
            DB::transaction(function () use (&$order) {
                // 1. Double check stock inside transaction
                foreach ($this->cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);
                    if (!$product || $product->stock < $item->quantity) {
                        throw new \Exception("Stok untuk {$item->product->name} tidak mencukupi.");
                    }
                }

                $this->calculateTotals();

                // 2. Create Order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                    'subtotal' => $this->subtotal,
                    'tax' => $this->tax,
                    'shipping_cost' => $this->shippingCost,
                    'discount' => $this->voucherDiscount, // Only voucher discount
                    'points_earned' => $this->pointsToEarn,
                    'points_redeemed' => $this->pointsToRedeem,
                    'points_discount' => $this->pointsDiscount,
                    'total' => $this->total,
                    'status' => in_array($this->paymentMethod, ['cod', 'credit_card']) ? 'processing' : 'pending',
                    'shipping_name' => $this->name,
                    'shipping_email' => $this->email,
                    'shipping_phone' => $this->phone,
                    'shipping_address' => $this->address,
                    'shipping_city' => $this->city,
                    'shipping_state' => $this->state,
                    'shipping_zip' => $this->zip,
                    'shipping_country' => $this->country,
                    'payment_method' => $this->paymentMethod,
                    'payment_status' => $this->paymentMethod === 'credit_card' ? 'paid' : 'unpaid',
                    'notes' => $this->notes,
                ]);

                // Increment voucher usage count
                if ($this->appliedVoucherId) {
                    Voucher::where('id', $this->appliedVoucherId)->increment('usage_count');
                }

                // 2.5 Update User Points
                $user = Auth::user();
                if ($this->pointsToRedeem > 0) {
                    $user->decrement('points', $this->pointsToRedeem);
                }
                $user->increment('points', $this->pointsToEarn);

                // 3. Create Order Items & Reduce Stock & Calculate Commission
                foreach ($this->cartItems as $item) {
                    $product = $item->product;
                    $vendor = $product->vendor;
                    
                    $commissionAmount = 0;
                    $netAmount = $product->price * $item->quantity;

                    if ($vendor) {
                        $commissionAmount = ($product->price * $item->quantity) * ($vendor->commission_rate / 100);
                        $netAmount = ($product->price * $item->quantity) - $commissionAmount;
                    }
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'vendor_id' => $vendor ? $vendor->id : null,
                        'product_name' => $product->name,
                        'sku' => $product->sku ?? 'N/A',
                        'quantity' => $item->quantity,
                        'price' => $product->price,
                        'commission_amount' => $commissionAmount,
                        'net_amount' => $netAmount,
                    ]);

                    $product->decrement('stock', $item->quantity);
                }

                // 4. Clear Cart or Buy Now Session
                if ($this->isBuyNow) {
                    session()->forget('buy_now');
                } else {
                    CartItem::where('user_id', Auth::id())->delete();
                }

                // 5. Midtrans Token Generation
                if ($this->paymentMethod === 'midtrans') {
                    Config::$serverKey = config('midtrans.server_key');
                    Config::$isProduction = config('midtrans.is_production');
                    Config::$isSanitized = config('midtrans.is_sanitized');
                    Config::$is3ds = config('midtrans.is_3ds');

                    $params = [
                        'transaction_details' => [
                            'order_id' => $order->order_number,
                            'gross_amount' => (int) $order->total,
                        ],
                        'customer_details' => [
                            'first_name' => $order->shipping_name,
                            'email' => $order->shipping_email,
                            'phone' => $order->shipping_phone,
                        ],
                        'item_details' => $this->cartItems->map(fn($item) => [
                            'id' => $item->product_id,
                            'price' => (int) $item->product->price,
                            'quantity' => $item->quantity,
                            'name' => substr($item->product->name, 0, 50)
                        ])->toArray()
                    ];

                    $snapToken = Snap::getSnapToken($params);
                    $order->update(['snap_token' => $snapToken]);
                }

                // 6. Send Notification
                $this->sendNotification(
                    auth()->id(),
                    'Pesanan Berhasil!',
                    'Pesanan #' . $order->order_number . ' telah berhasil dibuat. Silakan selesaikan pembayaran.',
                    'order',
                    route('order.history')
                );
            });

            if ($this->paymentMethod === 'midtrans') {
                $this->dispatch('pay-midtrans', [
                    'snapToken' => $order->snap_token,
                    'orderNumber' => $order->order_number
                ]);
            } else {
                session()->flash('message', 'Pesanan berhasil dibuat! Terima kasih atas pembelian Anda.');
                return redirect()->route('order.history');
            }

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function handleMidtransSuccess($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if ($order && $order->payment_status !== 'paid') {
            DB::transaction(function () use ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
                
                // Post-payment logic: Add to vendor balance
                foreach ($order->items as $item) {
                    $vendor = $item->vendor;
                    if ($vendor) {
                        $vendor->increment('balance', $item->net_amount);
                    }
                }
            });
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
