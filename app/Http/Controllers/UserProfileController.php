<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function show(User $user): View
    {
        $user->load([
            'items' => function($query) {
                $query->withPivot('id', 'status', 'score', 'episodes_watched');
            }
        ]);
        
        if (Auth::check()) {
            $user->loadMissing('items.pivot');
            
            foreach ($user->items as $item) {
                if ($item->pivot) {
                    $item->pivot->load([
                        'likes' => function($query) {
                            $query->where('user_id', Auth::id());
                        }
                    ]);
                }
            }
        }

        $user->loadCount(['followers', 'following']);

        // 🆕 Calcular estadísticas de likes
        $likeStats = $this->calculateLikeStats($user);

        return view('profiles.show', [
            'user' => $user,
            'likeStats' => $likeStats
        ]);
    }

    // 🆕 Método para calcular estadísticas de likes
    private function calculateLikeStats(User $user): array
    {
        // Likes recibidos en posts
        $postLikes = DB::table('likes')
            ->join('posts', 'likes.likeable_id', '=', 'posts.id')
            ->where('likes.likeable_type', 'App\\Models\\Post')
            ->where('posts.user_id', $user->id)
            ->count();

        // Likes recibidos en comentarios
        $commentLikes = DB::table('likes')
            ->join('comments', 'likes.likeable_id', '=', 'comments.id')
            ->where('likes.likeable_type', 'App\\Models\\Comment')
            ->where('comments.user_id', $user->id)
            ->count();

        // Likes recibidos en items de colección
        $itemLikes = DB::table('likes')
            ->join('item_user', 'likes.likeable_id', '=', 'item_user.id')
            ->where('likes.likeable_type', 'App\\Models\\Pivots\\ItemUser')
            ->where('item_user.user_id', $user->id)
            ->count();

        // Likes dados por el usuario
        $likesGiven = DB::table('likes')
            ->where('user_id', $user->id)
            ->count();

        return [
            'posts' => $postLikes,
            'comments' => $commentLikes,
            'items' => $itemLikes,
            'total_received' => $postLikes + $commentLikes + $itemLikes,
            'given' => $likesGiven,
        ];
    }
}