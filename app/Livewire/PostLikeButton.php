<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Notifications\NewLikeNotification;
use Illuminate\Support\Facades\Auth;

class PostLikeButton extends Component
{
    public Post $post;
    public int $likesCount;
    public bool $isLikedByCurrentUser;

    public function mount(Post $post){
        $this->post = $post;
        $this->updateLikeStatus();
    }

    public function updateLikeStatus(){
        $this->post->refresh();
        $this->likesCount = $this->post->likes()->count();

        if (Auth::check()) {
            $user = Auth::user();
            $this->isLikedByCurrentUser = $this->post->isLikedBy($user);
        }else{
            $this->isLikedByCurrentUser = false;
        }
    }

    public function toggleLike(){
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true); // Opcional: manejar usuarios no autenticados
        }

        $user = Auth::user();

        if ($this->post->user_id === $user->id) {
            return; // No permitir que el usuario le dé like a su propio post
        }

        if ($this->isLikedByCurrentUser) {
            // Quitar like
            $this->post->likes()->where('user_id', $user->id)->delete();
        } else {
            // Agregar like
            $this->post->likes()->create(['user_id' => $user->id]);
            
            $owner = $this->post->user;
            if ($owner->id !== $user->id) {
                $owner->notify(new NewLikeNotification($user, $this->post));
            }
        }

        $this->updateLikeStatus();
    }

    public function render()
    {
        return view('livewire.post-like-button');
    }
}
