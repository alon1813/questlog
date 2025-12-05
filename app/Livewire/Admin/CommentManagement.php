<?php
// app/Livewire/Admin/CommentManagement.php

namespace App\Livewire\Admin;

use App\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CommentManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $comments = Comment::with('user', 'post')
            ->when($this->search, function ($query) {
                $query->where('body', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(20);

        return view('livewire.admin.comment-management', [
            'comments' => $comments,
        ]);
    }

    // ✅ MÉTODO FALTANTE
    public function updateStatus($commentId, $newStatus)
    {
        $comment = Comment::findOrFail($commentId);
        
        if (in_array($newStatus, ['visible', 'hidden'])) {
            $comment->status = $newStatus;
            $comment->save();
            
            $statusNames = [
                'visible' => 'Visible',
                'hidden' => 'Oculto',
            ];
            
            session()->flash('success', "Comentario actualizado a: {$statusNames[$newStatus]}");
        }
    }

    // ✅ MÉTODO FALTANTE
    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();
        
        session()->flash('success', 'Comentario eliminado correctamente.');
    }
}