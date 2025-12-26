<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Notifications\NewLikeNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CommentLikeButton extends Component
{
    public Comment $comment;
    public int $likesCount;
    public bool $isLikedByCurrentUser;

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
        $this->updateLikeStatus();
    }

    public function updateLikeStatus()
    {
        $this->comment->refresh();
        $this->likesCount = $this->comment->likes()->count();

        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $this->isLikedByCurrentUser = $this->comment->isLikedBy($user);
        } else {
            $this->isLikedByCurrentUser = false;
        }
    }

    public function toggleLike()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // No permitir que el autor se dé like a sí mismo
        if ($this->comment->user_id === $user->id) {
            return;
        }

        if ($this->isLikedByCurrentUser) {
            $this->comment->likes()->where('user_id', $user->id)->delete();
        } else {
            $this->comment->likes()->create(['user_id' => $user->id]);

            // Opcional: notificar al autor del comentario
            $this->comment->user->notify(new NewLikeNotification($user, $this->comment));
        }

        $this->updateLikeStatus();
    }

    public function render()
    {
        return view('livewire.comment-like-button');
    }
}