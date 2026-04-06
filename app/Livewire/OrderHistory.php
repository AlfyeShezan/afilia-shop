<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderHistory extends Component
{
    use \App\Traits\HandlesCart;

    public $status = 'all';

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function buyAgain($orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->with('items')
            ->find($orderId);

        if (!$order) {
            $this->dispatch('notify', [
                'message' => 'Pesanan tidak ditemukan.',
                'type' => 'error'
            ]);
            return;
        }

        foreach ($order->items as $item) {
            $this->handleAddToCart($item->product_id, $item->quantity, $item->metadata ?? []);
        }

        return redirect()->route('cart');
    }

    public function render()
    {
        $query = Order::where('user_id', Auth::id())
            ->with(['items', 'items.product', 'items.product.images'])
            ->orderBy('created_at', 'desc');

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $orders = $query->get();

        return view('livewire.order-history', [
            'orders' => $orders
        ])->layout('layouts.app');
    }
}
