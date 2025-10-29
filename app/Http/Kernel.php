<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \Illuminate\Http\Middleware\TrustProxies::class, // <-- Apunta a Illuminate
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class, // <-- Apunta a Illuminate
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, // <-- Apunta a Illuminate
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class, // <-- Apunta a Illuminate
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // <-- Apunta a Illuminate
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class, // <-- Apunta a Illuminate
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // <-- Apunta a Illuminate
            \Illuminate\Session\Middleware\StartSession::class, // <-- Apunta a Illuminate
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // <-- Apunta a Illuminate
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class, // <-- Apunta a Illuminate
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // <-- Apunta a Illuminate
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // <-- ¡Este es el de Sanctum!
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class, // <-- Apunta a Illuminate
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class, // <-- Apunta a Illuminate
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // <-- Este lo creamos con make:middleware
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // <-- Apunta a Illuminate
    ];
}