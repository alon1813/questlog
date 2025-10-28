<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user =$request->user()->load('items'); 

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    // app/Http/Controllers/ProfileController.php

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Rellena los datos validados del ProfileUpdateRequest
        $user->fill($request->validated());

        // Si el email ha cambiado, resetea la verificación
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        
        if ($request->hasFile('avatar')) {
            // Valida el archivo
            $request->validate([
                'avatar' => 'image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Borra el avatar antiguo si existe
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            // Guarda el nuevo avatar y obtiene su ruta
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
