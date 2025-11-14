<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->status === 'suspended') {
            // Si el usuario está suspendido
            Auth::logout(); // Cierra su sesión

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirige al login con un mensaje de error
            return redirect()->route('login')->with('error', 'Tu cuenta ha sido suspendida. Contacta al administrador.');
        }

        return $next($request);
    }
}