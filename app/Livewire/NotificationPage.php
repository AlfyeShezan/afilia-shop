<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationPage extends Component
{
    use WithPagination;

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
        }
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
    }

    public function render()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(15);

        return view('livewire.notification-page', [
            'notifications' => $notifications
        ])->layout('layouts.app');
    }
}
