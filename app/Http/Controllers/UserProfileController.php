<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        
        foreach ($user->items as $item) {
            $itemUser = $item->pivot; 

            
            $itemUser->loadCount('likes'); 

            if (Auth::check()) {
                $itemUser->load(['likes' => function ($q) {
                    $q->where('user_id', Auth::id());
                }]);
            }
        }

        $user->loadCount(['followers', 'following']);

        return view('profiles.show', ['user' => $user]);
    }
}