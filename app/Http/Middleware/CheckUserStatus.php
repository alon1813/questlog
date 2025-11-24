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
            
            Auth::logout(); 

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            
            return redirect()->route('login')->with('error', 'Tu cuenta ha sido suspendida. Contacta al administrador.');
        }

        return $next($request);
    }
}