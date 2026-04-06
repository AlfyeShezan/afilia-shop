<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    use \App\Traits\HandlesCart;

    public $cartItems = [];
    public $subtotal = 0;
    public $selectedItems = [];
    public $selectAll = false;

    protected $listeners = ['cart-updated' => 'loadCart'];

    public function mount()
    {
        $this->syncCart();
        $this->loadCart();
    }

    public function loadCart()
    {
        if (Auth::check()) {
            $this->cartItems = CartItem::where('user_id', Auth::id())
                ->with(['product.vendor', 'product.images' => function($q) {
                    $q->where('is_primary', true);
                }])
                ->get();
        } else {
            $sessionCart = session()->get('cart', []);
            $items = [];
            foreach ($sessionCart as $key => $details) {
                $product = Product::with(['vendor', 'images' => function($q) {
                    $q->where('is_primary', true);
                }])->find($details['product_id']);
                
                if ($product) {
                    $items[] = (object)[
                        'id' => $key,
                        'product_id' => $details['product_id'],
                        'product' => $product,
                        'quantity' => $details['quantity'],
                        'metadata' => $details['metadata'] ?? null
                    ];
                }
            }
            $this->cartItems = collect($items);
        }

        // Clean up selectedItems that might have been removed
        $validIds = $this->cartItems->pluck('id')->toArray();
        $this->selectedItems = array_values(array_intersect($this->selectedItems, $validIds));
        
        $this->updateSelectAllState();
        $this->calculateSubtotal();
    }

    public function calculateSubtotal()
    {
        // Subtotal only for selected items
        $this->subtotal = $this->cartItems->sum(function ($item) {
            return in_array($item->id, $this->selectedItems) 
                ? $item->product->price * $item->quantity 
                : 0;
        });
    }

    public function updateSelectAllState()
    {
        $this->selectAll = count($this->cartItems) > 0 && count($this->selectedItems) === count($this->cartItems);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = $this->cartItems->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedItems = [];
        }
        $this->calculateSubtotal();
    }

    public function updatedSelectedItems()
    {
        $this->updateSelectAllState();
        $this->calculateSubtotal();
    }

    public function updateQuantity($itemId, $quantity)
    {
        if ($quantity < 1) return;

        if (Auth::check()) {
            $item = CartItem::find($itemId);
            if ($item && $item->product->stock >= $quantity) {
                $item->update(['quantity' => $quantity]);
            } else {
                $this->dispatch('notify', ['message' => 'Stok tidak mencukupi!', 'type' => 'error']);
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$itemId])) {
                $product = Product::find($cart[$itemId]['product_id']);
                if ($product && $product->stock >= $quantity) {
                    $cart[$itemId]['quantity'] = $quantity;
                    session()->put('cart', $cart);
                } else {
                    $this->dispatch('notify', ['message' => 'Stok tidak mencukupi!', 'type' => 'error']);
                }
            }
        }
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function removeItem($itemId)
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->where('id', $itemId)
                ->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$itemId]);
            session()->put('cart', $cart);
        }

        if (($key = array_search($itemId, $this->selectedItems)) !== false) {
            unset($this->selectedItems[$key]);
        }
        
        $this->loadCart();
        $this->dispatch('cart-updated');
    }

    public function clearCart()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('notify', ['message' => 'Pilih produk yang ingin dihapus!', 'type' => 'info']);
            return;
        }

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->whereIn('id', $this->selectedItems)
                ->delete();
        } else {
            $cart = session()->get('cart', []);
            foreach ($this->selectedItems as $id) {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }
        
        $this->selectedItems = [];
        $this->loadCart();
        $this->dispatch('cart-updated');
        $this->dispatch('notify', [
            'message' => 'Produk terpilih berhasil dihapus.',
            'type' => 'success'
        ]);
    }

    public function checkout()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('notify', ['message' => 'Pilih setidaknya satu produk untuk checkout!', 'type' => 'info']);
            return;
        }

        session()->put('checkout_selections', $this->selectedItems);
        return redirect()->route('checkout');
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
