<?php

namespace App\Traits;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

trait HandlesWishlist
{
    public function toggleWishlist($productId)
    {
        if (!Auth::check()) {
            $this->dispatch('notify', [
                'message' => 'Silakan masuk untuk menyimpan produk!',
                'type' => 'error'
            ]);
            return;
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            if (property_exists($this, 'wishlistIds') && is_array($this->wishlistIds)) {
                $this->wishlistIds = array_values(array_diff($this->wishlistIds, [(int) $productId]));
            }
            $this->dispatch('notify', [
                'message' => 'Produk dihapus dari wishlist.',
                'type' => 'success'
            ]);
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId
            ]);
            if (property_exists($this, 'wishlistIds') && is_array($this->wishlistIds)) {
                $this->wishlistIds[] = (int) $productId;
                $this->wishlistIds = array_values(array_unique(array_map('intval', $this->wishlistIds)));
            }
            $this->dispatch('notify', [
                'message' => 'Produk ditambahkan ke wishlist!',
                'type' => 'success'
            ]);
        }

        $this->dispatch('wishlist-updated');
    }

    public function isInWishlist($productId)
    {
        if (!Auth::check()) return false;

        if (property_exists($this, 'wishlistIds') && is_array($this->wishlistIds) && !empty($this->wishlistIds)) {
            return in_array((int) $productId, array_map('intval', $this->wishlistIds), true);
        }

        return Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();
    }
}
