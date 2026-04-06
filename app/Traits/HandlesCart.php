<?php

namespace App\Traits;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

trait HandlesCart
{
    public function handleAddToCart($productId, $quantity = 1, $options = [])
    {
        $product = Product::find($productId);
        if (!$product || $product->stock < $quantity) {
            $this->dispatch('notify', [
                'message' => 'Produk habis atau tidak valid!',
                'type' => 'error'
            ]);
            return;
        }

        if (Auth::check()) {
            $cartQuery = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId);
            
            if (!empty($options)) {
                $cartQuery->where('metadata', json_encode($options));
            } else {
                $cartQuery->whereNull('metadata');
            }

            $cartItem = $cartQuery->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;
                if ($product->stock < $newQuantity) {
                    $this->dispatch('notify', [
                        'message' => 'Tidak dapat menambah lebih banyak, batas stok tercapai!',
                        'type' => 'error'
                    ]);
                    return;
                }
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'metadata' => !empty($options) ? $options : null
                ]);
            }
        } else {
            $cart = session()->get('cart', []);
            $cartKey = $productId . (empty($options) ? '' : '_' . md5(json_encode($options)));
            
            if (isset($cart[$cartKey])) {
                $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
                if ($product->stock < $newQuantity) {
                    $this->dispatch('notify', [
                        'message' => 'Tidak dapat menambah lebih banyak, batas stok tercapai!',
                        'type' => 'error'
                    ]);
                    return;
                }
                $cart[$cartKey]['quantity'] = $newQuantity;
            } else {
                $cart[$cartKey] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'metadata' => !empty($options) ? $options : null
                ];
            }
            session()->put('cart', $cart);
        }

        $this->dispatch('cart-updated');
        $this->dispatch('notify', [
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'type' => 'success'
        ]);
    }

    public function syncCart()
    {
        if (!Auth::check()) return;

        $sessionCart = session()->get('cart', []);
        if (empty($sessionCart)) return;

        foreach ($sessionCart as $productId => $details) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $details['quantity']);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $details['quantity']
                ]);
            }
        }

        session()->forget('cart');
        $this->dispatch('cart-updated');
    }
}
