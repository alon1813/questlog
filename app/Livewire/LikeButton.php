<?php

namespace App\Livewire;

use App\Models\Pivots\ItemUser;
use App\Notifications\NewLikeNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LikeButton extends Component
{
    public ItemUser $itemUser;
    public int $likesCount;
    public bool $isLikedByCurrentUser;

    public function mount(ItemUser $itemUser){
        $this->itemUser = $itemUser;
        $this->updateLikeStatus();
    }

    public function updateLikeStatus(){
        $this->itemUser->refresh();
        $this->likesCount = $this->itemUser->likes()->count();

        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $this->isLikedByCurrentUser = $this->itemUser->isLikedBy($user);
        }else{
            $this->isLikedByCurrentUser = false;
        }
    }

    public function toggleLike(){
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();

        
        if ($this->itemUser->user_id === $user->id) {
            return;
        }

        
        if ($this->isLikedByCurrentUser) {
            $this->itemUser->likes()->where('user_id', $user->id)->delete();
        }else{
            $this->itemUser->likes()->create(['user_id' => $user->id]);

            $owner = $this->itemUser->user;
            if($owner->id !== $user->id){
                $owner->notify(new NewLikeNotification($user, $this->itemUser));
            }
        }

        $this->updateLikeStatus();
    }

    public function render()
    {
        return view('livewire.like-button');
    }
}
