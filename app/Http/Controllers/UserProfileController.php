<?php
// app/Http/Controllers/UserProfileController.php

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
        
        // Cargar los likes solo si hay un usuario autenticado
        if (Auth::check()) {
            $user->loadMissing('items.pivot'); // Asegurar que pivot está cargado
            
            // Cargar los likes manualmente para cada pivot
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

        return view('profiles.show', ['user' => $user]);
    }
}