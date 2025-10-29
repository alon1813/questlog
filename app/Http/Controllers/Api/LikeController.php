<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pivots\ItemUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log; // Para depuración

// class LikeController extends Controller
// {

//     public function store(ItemUser $itemUser): JsonResponse
//     {
//         /** @var \App\Models\User $user */
//         $user = Auth::user();

//         // Depuración para ver si Auth::check() funciona ahora
//         if (!Auth::check()) {
//             Log::warning('LikeController@store: Usuario NO AUTENTICADO. Auth::check() falló.');
//             return response()->json(['message' => 'No autenticado.'], 401);
//         }
        
//         Log::info('LikeController@store: Usuario ' . $user->id . ' autenticado. Dando like a ItemUser ' . $itemUser->id);

//         if ($itemUser->user_id === $user->id) {
//             return response()->json(['message' => 'No puedes dar "Me Gusta" a tu propio ítem.'], 403);
//         }

//         if ($itemUser->likes()->where('user_id', $user->id)->exists()) {
//             return response()->json(['message' => 'Ya has dado "Me Gusta" a este ítem.'], 409);
//         }

//         $itemUser->likes()->create(['user_id' => $user->id]);
//         $itemUser->loadCount('likes'); 

//         return response()->json([
//             'message' => 'Me Gusta añadido.',
//             'likes_count' => $itemUser->likes_count,
//             'is_liked' => true
//         ], 201);
//     }

//     public function destroy(ItemUser $itemUser): JsonResponse
//     {
//         /** @var \App\Models\User $user */
//         $user = Auth::user();

//         if (!Auth::check()) {
//             Log::warning('LikeController@destroy: Usuario NO AUTENTICADO. Auth::check() falló.');
//             return response()->json(['message' => 'No autenticado.'], 401);
//         }

//         Log::info('LikeController@destroy: Usuario ' . $user->id . ' autenticado. Quitand o like a ItemUser ' . $itemUser->id);

//         $deletedCount = $itemUser->likes()->where('user_id', $user->id)->delete();

//         if ($deletedCount > 0) {
//             $itemUser->loadCount('likes');
//             return response()->json([
//                 'message' => 'Me Gusta eliminado.',
//                 'likes_count' => $itemUser->likes_count,
//                 'is_liked' => false
//             ], 200);
//         }

//         return response()->json(['message' => 'No se encontró el "Me Gusta" para eliminar.'], 404);
//     }
// }

class LikeController extends Controller
{
    public function store(ItemUser $itemUser): JsonResponse
{
    Log::debug('LikeController@store: Petición RECIBIDA y FORZANDO ÉXITO con 200 OK.');
    return response()->json([
        'message' => '¡Éxito forzado de Like!',
        'likes_count' => $itemUser->likes_count + 1,
        'is_liked' => true
    ], 200);
}

    public function destroy(ItemUser $itemUser): JsonResponse
    {
        Log::debug('LikeController@destroy: Petición RECIBIDA y FORZANDO ÉXITO.');
        return response()->json([
            'message' => '¡Éxito forzado de Unlike!',
            'likes_count' => max(0, $itemUser->likes_count - 1), // Restamos 1
            'is_liked' => false
        ], 200); // Devuelve 200 OK
    }
}