<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewFollowerNotification;

class FollowController extends Controller
{
    //

    //Función para seguir a un usuario
    public function follow(Request $request, User $user)
    {
        
        if ($request->user()->id !== $user->id) { // Evitar que un usuario se siga a sí mismo
            $request->user()->following()->attach($user->id);
            // Notificar al usuario que ha sido seguido
            $user->notify(new NewFollowerNotification($request->user()));
        }

        return back()->with('success', 'Has comenzado a seguir a ' . $user->name);
    }

    //Función para dejar de seguir a un usuario
    public function unfollow(Request $request, User $user)
    {
        $request->user()->following()->detach($user->id);       
        return back()->with('success', 'Has dejado de seguir a ' . $user->name);
    }
}
