<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use Illuminate\View\View;

class VerifyEmailController extends Controller
{

public function __invoke(EmailVerificationRequest $request): RedirectResponse|View
{
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->route('dashboard')
            ->with('status', 'Tu email ya estaba verificado.');
    }

    if ($request->user()->markEmailAsVerified()) {
        event(new Verified($request->user()));
        
        try {
            Mail::to($request->user()->email)->send(new WelcomeMail($request->user()));
        } catch (\Exception $e) {
            Log::error('Error enviando email de bienvenida: ' . $e->getMessage());
        }
    }

    return redirect()->route('dashboard')
        ->with('status', '¡Email verificado! Te hemos enviado un email de bienvenida.');
}
}