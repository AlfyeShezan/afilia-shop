<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class OrderManager extends Component
{
    use WithPagination, \App\Traits\SendsNotifications;

    public $search = '';
    public $statusFilter = '';
    public $confirmingDeletion = null;
    public $selectedOrder = null;
    public $newStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewOrder($id)
    {
        $this->selectedOrder = Order::with(['user', 'items.product'])->findOrFail($id);
        $this->newStatus = $this->selectedOrder->status;
    }

    public function closeOrderView()
    {
        $this->selectedOrder = null;
    }

    public function updateStatus()
    {
        if ($this->selectedOrder) {
            $oldStatus = $this->selectedOrder->status;
            $this->selectedOrder->update(['status' => $this->newStatus]);
            
            // Notify User
            if ($oldStatus !== $this->newStatus) {
                $statusLabels = [
                    'pending' => 'Menunggu Pembayaran',
                    'paid' => 'Pembayaran Berhasil',
                    'processing' => 'Sedang Dikemas',
                    'shipped' => 'Dalam Pengiriman',
                    'delivered' => 'Telah Diterima',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ];
                
                $newLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;
                
                $this->sendNotification(
                    $this->selectedOrder->user_id,
                    'Update Status Pesanan',
                    'Pesanan #' . $this->selectedOrder->order_number . ' kini berstatus: ' . $newLabel,
                    'order',
                    route('order.history')
                );
            }

            session()->flash('message', 'Status pesanan berhasil diperbarui.');
            $this->closeOrderView();
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = $id;
    }

    public function deleteOrder()
    {
        if ($this->confirmingDeletion) {
            Order::destroy($this->confirmingDeletion);
            $this->confirmingDeletion = null;
            session()->flash('message', 'Pesanan berhasil dihapus.');
        }
    }

    public function render()
    {
        $query = Order::with(['user', 'items.product']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.order-manager', [
            'orders' => $query->latest()->paginate(10),
        ]);
    }
}
