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
        // ── SUPER ADMIN BYPASS ──────────────────────────────────────────────
        // Memberikan akses penuh ke semua permission bagi role 'super_admin'.
        // Jadi tidak perlu satu-per-satu assign permission ke super_admin.
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
