<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class RecentlyViewedProducts extends Component
{
    use \App\Traits\HandlesWishlist;

    public $products = [];

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $recentIds = session()->get('recently_viewed', []);
        
        if (empty($recentIds)) {
            $this->products = collect();
            return;
        }

        // Fetch products, excluding the one currently being viewed (if on product detail)
        // Actually, it's often better to show all recently viewed including current, 
        // but typically the "Recently Viewed" section is at the bottom.
        
        $this->products = Product::whereIn('id', $recentIds)
            ->with(['images' => function($q) {
                $q->where('is_primary', true);
            }, 'category'])
            ->get()
            ->sortBy(function($product) use ($recentIds) {
                return array_search($product->id, $recentIds);
            });
    }

    public function render()
    {
        return view('livewire.recently-viewed-products');
    }
}
