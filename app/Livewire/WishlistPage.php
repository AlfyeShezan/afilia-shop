<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistPage extends Component
{
    use \App\Traits\HandlesCart, \App\Traits\HandlesWishlist;

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function removeFromWishlist($productId)
    {
        $this->toggleWishlist($productId);
    }

    public function render()
    {
        $wishlistItems = Auth::user()->wishlists()
            ->with(['product', 'product.images' => function($q) {
                $q->where('is_primary', true);
            }, 'product.category'])
            ->get();

        return view('livewire.wishlist-page', [
            'wishlistItems' => $wishlistItems
        ])->layout('layouts.app');
    }
}
