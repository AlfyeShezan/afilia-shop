<?php

namespace App\Traits;

use App\Models\Notification;

trait SendsNotifications
{
    public function sendNotification($userId, $title, $message, $type = 'info', $link = null)
    {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link,
            'is_read' => false,
        ]);

        $this->dispatch('notification-sent');
    }
}
