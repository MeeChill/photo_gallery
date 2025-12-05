<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
        if (auth()->check() && auth()->user()->email === 'admin@example.com') { // Ganti dengan email admin Anda
            $view->with('isAdmin', true);
        } else {
            $view->with('isAdmin', false);
        }
    });
    }
}
