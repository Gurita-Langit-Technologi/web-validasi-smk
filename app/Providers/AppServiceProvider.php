<?php

namespace App\Providers;

use Filament\Facades\Filament;
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
        // Memaksa plural dari 'guru' tetap 'guru'
        Filament::serving(function () {
            Filament::registerRenderHook(
                'footer.start',
                fn() => '' // Kosongkan render hook footer
            );
        });
    }
}
