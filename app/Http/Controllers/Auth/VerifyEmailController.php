<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Si ya está verificado, redirigir
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1&already=true');
        }

        // Marcar como verificado
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            
            // ✅ ENVIAR EMAIL DE BIENVENIDA
            try {
                Mail::to($request->user()->email)->send(new WelcomeMail($request->user()));
                Log::info('Email de bienvenida enviado a: ' . $request->user()->email);
            } catch (\Exception $e) {
                Log::error('Error al enviar email de bienvenida: ' . $e->getMessage());
            }
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1&welcome=sent');
    }
}