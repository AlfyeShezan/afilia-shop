<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class ProductDetail extends Component
{
    use \App\Traits\HandlesCart, \App\Traits\HandlesWishlist;

    public $product;
    public $quantity = 1;
    public $selectedColor = null;
    public $selectedSize = null;
    public $selectedAttributes = [];

    public function mount($slug)
    {
        $this->product = Product::query()
            ->where('slug', $slug)
            ->with([
                'category:id,name',
                'images:id,product_id,image_path,is_primary,sort_order',
            ])
            ->firstOrFail();
        
        // Auto-select first options if available
        if (isset($this->product->metadata['attributes'])) {
            $attrs = $this->product->metadata['attributes'];
            if (!empty($attrs['colors'])) $this->selectedColor = $attrs['colors'][0]['name'];
            if (!empty($attrs['sizes'])) $this->selectedSize = $attrs['sizes'][0];
        }

        // Initialize selectedAttributes based on selectedColor and selectedSize
        if ($this->selectedColor) $this->selectedAttributes['color'] = $this->selectedColor;
        if ($this->selectedSize) $this->selectedAttributes['size'] = $this->selectedSize;

        // Track Recently Viewed
        $recent = session()->get('recently_viewed', []);
        $recent = array_unique(array_merge([$this->product->id], $recent));
        session()->put('recently_viewed', array_slice($recent, 0, 10));
    }

    public function addToCart()
    {
        $this->handleAddToCart($this->product->id, $this->quantity, $this->selectedAttributes);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', [
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'type' => 'success'
        ]);
    }

    public function buyNow()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk untuk melakukan pembelian.');
        }

        session()->put('buy_now', [
            'product_id' => $this->product->id,
            'quantity' => $this->quantity,
            'attributes' => $this->selectedAttributes,
        ]);

        return redirect()->route('checkout', ['mode' => 'buynow']);
    }

    public function render()
    {
        return view('livewire.product-detail', [
            'relatedProducts' => Product::where('category_id', $this->product->category_id)
                ->where('id', '!=', $this->product->id)
                ->limit(4)
                ->select(['id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'status', 'created_at'])
                ->with([
                    'category:id,name',
                    'primaryImage:id,product_id,image_path,is_primary,sort_order',
                ])
                ->get()
        ]);
    }
}
