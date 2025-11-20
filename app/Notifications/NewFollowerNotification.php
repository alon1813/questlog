<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NewFollowerNotification extends Notification
{
    use Queueable;

    public function __construct(public User $follower)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'follower_username' => $this->follower->username, 
            'message' => $this->follower->name . ' ha comenzado a seguirte.',
        ];
    }
}