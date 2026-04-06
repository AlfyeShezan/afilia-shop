<?php

namespace App\Livewire;

use Livewire\Component;

class Notifications extends Component
{
    protected $listeners = ['notification-sent' => '$refresh', 'order-placed' => '$refresh'];

    public function markAsRead($id)
    {
        $notification = \App\Models\Notification::where('user_id', auth()->id())->find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
        }
    }

    public function markAllAsRead()
    {
        \App\Models\Notification::where('user_id', auth()->id())->update(['is_read' => true]);
    }

    public function render()
    {
        $unreadCount = auth()->check() ? auth()->user()->notifications()->where('is_read', false)->count() : 0;
        $notifications = auth()->check() ? auth()->user()->notifications()->latest()->take(10)->get() : collect();

        return view('livewire.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
}
