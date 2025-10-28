<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        // Obtenemos todos los posts, con la información de su autor,
        // ordenados del más nuevo al más antiguo, y los paginamos.
        $posts = Post::with('user')->where('status', 'published')->latest()->paginate(9);
        return view('posts.index', ['posts' => $posts]);
    
    }  
    
    public function show(Post $post)
{
    // Precargamos los comentarios Y, para cada comentario, su autor (user).
    //$post->load('comments.user');

    //Ahora solo cargamos los comentarios que son visibles
    $post->load(['comments' =>function($query){
        $query->where('status', 'visible')->latest();

    }, 'comments.user']);

    return view('posts.show', ['post' => $post]);
}

    public function create(){
        return view('posts.create');
    }

    public function store(Request $request){

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        if ($request->user()->role === 'admin') {
            $validated['status'] = 'published';
        } else {
            $validated['status'] = 'pending_review';
        }
        
        //crear el post asociado al usuario autenticado
        $post = $request->user()->posts()->create($validated);

        $post->user->activities()->create([
            'type' => 'created_post',
            'subject_id' => $post->id,
            'subject_type' => Post::class,
        ]);

        $request->user()->posts()->create($validated);

        return redirect()->route('profile.edit')->with('success', 'Post creado con éxito.');
    }

    // Mostrar la lista de posts para que el admin pueda gestionarlos
    public function adminIndex(){
        $posts = Post::latest()->paginate(15);
        return view('posts.admin.index', ['posts' => $posts]);
    }

    // Actualizar un post existente
    public function edit(Post $post){
        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, Post $post){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        $post->update($validated);

        $user = $request->user();
        $user->activities()->create([
            'type' => 'updated_post',
            'subject_id' => $post->id,
            'subject_type' => Post::class,
        ]);

        return redirect()->route('posts.admin.index')->with('success', 'Post actualizado con éxito.');
    }

    // Eliminar un post
    public function destroy(Post $post){
        $post->delete();
        return redirect()->route('posts.admin.index')->with('success', 'Post eliminado con éxito.');
    }

    // Actualizar el estado de un post (publicado, pendiente, rechazado)
    public function updateStatus(Request $request, Post $post){
        $validated = $request->validate([
            'status' => 'required|in:published,hidden, pending_review',
        ]);

        $post->update(['status' => $validated['status']]);

        return back()->with('success', 'Estado del post actualizado con éxito.'); 
    }  
}
