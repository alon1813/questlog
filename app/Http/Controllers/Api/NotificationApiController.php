<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'error' => 'No autenticado',
                'message' => 'Debes iniciar sesión para ver notificaciones'
            ], 401);
        }
        $user = $request->user();

        return response()->json([
            'unread_count' => $user->unreadNotifications->count(),
            'notifications' => $user->notifications()->take(20)->get()->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => class_basename($n->type), 
                    'data' => $n->data, 
                    'read_at' => $n->read_at, 
                    'created_at' => $n->created_at->diffForHumans() 
                ];
            }),
        ]);
    }

    public function markAsRead(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead(); 

        return response()->json([
            'success' => true,
            'message' => 'Notificaciones marcadas como leídas.' 
        ]);
    }
}