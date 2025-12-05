<?php
// app/Livewire/Admin/PostManagement.php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PostManagement extends Component
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
        $posts = Post::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.post-management', [
            'posts' => $posts,
            'statuses' => ['pending_review', 'published', 'hidden'],
        ]);
    }

    // ✅ MÉTODO FALTANTE
    public function updateStatus($postId, $newStatus)
    {
        $post = Post::findOrFail($postId);
        
        if (in_array($newStatus, ['pending_review', 'published', 'hidden'])) {
            $post->status = $newStatus;
            $post->save();
            
            $statusNames = [
                'pending_review' => 'Pendiente de Revisión',
                'published' => 'Publicado',
                'hidden' => 'Oculto',
            ];
            
            session()->flash('success', "Estado actualizado a: {$statusNames[$newStatus]}");
        }
    }

    // ✅ MÉTODO FALTANTE
    public function deletePost($postId)
    {
        $post = Post::findOrFail($postId);
        $title = $post->title;
        $post->delete();
        
        session()->flash('success', "Noticia '{$title}' eliminada correctamente.");
    }
}