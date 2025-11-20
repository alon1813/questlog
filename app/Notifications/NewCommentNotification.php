<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public function __construct(public Comment $comment)
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
            'comment_id' => $this->comment->id,
            'commenter_id' => $this->comment->user->id,
            'commenter_name' => $this->comment->user->name,
            'commenter_username' => $this->comment->user->username, 
            'post_title' => $this->comment->post->title,
            'post_id' => $this->comment->post->id,
            'message' => $this->comment->user->name . ' ha comentado en tu publicación "' . $this->comment->post->title . '".',
        ];
    }
}