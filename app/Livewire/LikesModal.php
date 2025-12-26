<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class LikesModal extends Component
{
    public $likeable;
    public $likeableType;
    public $showModal = false;
    public $likes = [];

    protected $listeners = ['openLikesModal'];

    public function openLikesModal($likeableId, $likeableType)
    {
        $modelClass = match($likeableType) {
            'post' => \App\Models\Post::class,
            'comment' => \App\Models\Comment::class,
            'itemUser' => \App\Models\Pivots\ItemUser::class,
            default => null
        };

        if (!$modelClass) {
            return;
        }

        $this->likeable = $modelClass::find($likeableId);
        
        if ($this->likeable) {
            $this->likes = $this->likeable->likes()
                ->with('user:id,name,username,avatar_path')
                ->latest()
                ->get();
            
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->likes = [];
    }

    public function render()
    {
        return view('livewire.likes-modal');
    }
}