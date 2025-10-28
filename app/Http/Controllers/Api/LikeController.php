<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pivots\ItemUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    //
    public function store(Request $request, ItemUser $itemUser){
        $user = Auth::user();

        if ($itemUser->user_id === $user->id) {
            return response()->json(['message' => 'No puedes dar like a tu propio Item']);
        }

        $itemUser->likes()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Like added',
            'likes_count' => $itemUser->likes()->count(),
            'is_liked' => true
        ], 200);
    }

    public function destroy(Request $request, ItemUser $itemUser){
        $user = Auth::user();

        $deleted = $itemUser->likes()->where('user_id', $user->id)->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Like removed',
                'likes_count' => $itemUser->likes()->count(),
                'is_liked' => false
            ], 200);
        }

        return response()->json(['message' => 'Like no encontrado o eliminado por el usuario'], 404);
    }
}
