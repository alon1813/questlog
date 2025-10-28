<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function ($view) { // <-- 2. AHORA PHP SABE QUÉ ES "View"
            if (Auth::check()) {
                $unreadNotificationsCount = Auth::user()->unreadNotifications->count();
            } else {
                $unreadNotificationsCount = 0;
            }
            
            $view->with('unreadNotificationsCount', $unreadNotificationsCount);
        });
    }
}
