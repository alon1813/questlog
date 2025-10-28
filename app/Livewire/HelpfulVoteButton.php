<?php

namespace App\Livewire;

use App\Models\Pivots\ItemUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class HelpfulVoteButton extends Component
{
    public ItemUser $reviewPivot;
    public int $voteCount;
    public bool $hasVoted;

    public function mount(){
        $this->voteCount = $this->reviewPivot->helpfulVotes()->count();
        $this->hasVoted = Auth::check() ? $this->reviewPivot->helpfulVotes()->where('user_id', Auth::id())->exists() : false;
    }

    public function toggleVote(){
        if (!Auth::check()) {
            return $this->redirect(route('login'), navigate: true);
        }

        if ($this->hasVoted) {
            $this->reviewPivot->helpfulVotes()->detach(Auth::id());
            $this->voteCount--;
            $this->hasVoted = false;
        }else{
            $this->reviewPivot->helpfulVotes()->attach(Auth::id());
            $this->voteCount++;
            $this->hasVoted = true;
        }
    }

    public function render()
    {
        return view('livewire.helpful-vote-button');
    }
}
