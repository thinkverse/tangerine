<?php

namespace App\Providers;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Folio\Folio;

class FolioServiceProvider extends ServiceProvider
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
        Folio::route(resource_path('views/pages'), middleware: [
            'verify-email.blade.php' => [
                'auth',
                fn (Request $request, Closure $next) => $request->user()?->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : $next($request),
            ],
        ]);
    }
}
