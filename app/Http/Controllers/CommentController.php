<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Notifications\NewCommentNotification;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:2500',
        ]);

        $comment = $post->comments()->create([
            
            'user_id'=> $request->user()->id,
            'body' => $request->body,
        ]);
        
        if ($post->user_id !== $request->user()->id) {
            $post->user->notify(new NewCommentNotification($comment));
        }

        return back()->with('success', '¡Comentario publicado!');
    }
}
