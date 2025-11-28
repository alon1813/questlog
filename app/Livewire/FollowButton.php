<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Notifications\NewFollowerNotification;
use Illuminate\Support\Facades\Auth;

class FollowButton extends Component
{
    public User $user;
    public bool $isFollowing;

    public function mount()
    {
        /** @var \App\Models\User $currentUser */ 
        $currentUser = Auth::user();

        $this->isFollowing = $currentUser->isFollowing($this->user);
    }

    public function toggleFollow()
    {
        /** @var \App\Models\User $currentUser */ 
        $currentUser = Auth::user();

        if ($this->isFollowing) {
            $currentUser->following()->detach($this->user->id);
            $this->isFollowing = false;
        } else {
            $currentUser->following()->attach($this->user->id);
            $this->user->notify(new NewFollowerNotification($currentUser));
            $this->isFollowing = true;
        }
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
