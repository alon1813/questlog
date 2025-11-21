<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NewFollowerNotification;

class FollowController extends Controller
{
    //

    public function follow(Request $request, User $user)
    {
        
        if ($request->user()->id !== $user->id) { 
            $request->user()->following()->attach($user->id);
            
            $user->notify(new NewFollowerNotification($request->user()));
        }

        return back()->with('success', 'Has comenzado a seguir a ' . $user->name);
    }

   
    public function unfollow(Request $request, User $user)
    {
        $request->user()->following()->detach($user->id);       
        return back()->with('success', 'Has dejado de seguir a ' . $user->name);
    }
}
