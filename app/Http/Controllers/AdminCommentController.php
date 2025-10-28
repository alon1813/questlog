<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index(){
        $comments = Comment::with('user', 'post')->latest()->paginate(20);
        return view('admin.comments.index', ['comments' => $comments]);
    }

    public function updateStatus(Request $request, Comment $comment){
        $validated = $request->validate([
            'status' => 'required|in:visible,hidden',
        ]);

        $comment->update(['status' => $validated['status']]);
        return back()->with('success', 'Estado del comentario actualizado.');

    }

    public function destroy(Comment $comment){
        $comment->delete();
        return back()->with('success', 'Comentario eliminado.');
    }
}
