<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Pivots\ItemUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLikeNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $liker,           
        public ItemUser $itemUser     
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'liker_username' => $this->liker->username,
            'item_title' => $this->itemUser->item->title,
            'item_id' => $this->itemUser->item->id,
            'item_user_id' => $this->itemUser->id, 
            'message' => $this->liker->name . ' le ha dado me gusta a "' . $this->itemUser->item->title . '" en tu colección.'
        ];
    }
}