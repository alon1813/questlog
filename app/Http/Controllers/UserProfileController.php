<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    //mostrar el perfil publico de un usuario
    public function show(User $user){
        $user->load('items')->loadCount(['followers', 'following']);
        return view('profiles.show', ['user' => $user]);
    }
}
