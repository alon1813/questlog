<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLikeNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $liker,
        public $likeable // Puede ser ItemUser, Post o Comment
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $likeableType = class_basename(get_class($this->likeable));
        
        $data = [
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'liker_username' => $this->liker->username,
            'likeable_type' => $likeableType,
            'likeable_id' => $this->likeable->id,
        ];

        // Personalizar mensaje según el tipo
        if ($likeableType === 'Post') {
            $data['message'] = $this->liker->name . ' le ha dado me gusta a tu publicación "' . $this->likeable->title . '".';
            $data['post_id'] = $this->likeable->id;
        } elseif ($likeableType === 'Comment') {
            $data['message'] = $this->liker->name . ' le ha dado me gusta a tu comentario.';
            $data['comment_id'] = $this->likeable->id;
            $data['post_id'] = $this->likeable->post_id;
        } elseif ($likeableType === 'ItemUser') {
            $data['message'] = $this->liker->name . ' le ha dado me gusta a "' . $this->likeable->item->title . '" en tu colección.';
            $data['item_title'] = $this->likeable->item->title;
            $data['item_id'] = $this->likeable->item->id;
            $data['item_user_id'] = $this->likeable->id;
        }

        return $data;
    }
}