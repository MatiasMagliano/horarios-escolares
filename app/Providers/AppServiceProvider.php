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
        // Provee los datos institucionales a todas las vistas
        view()->composer('*', function ($view) {
            $view->with(
                'institucion_global',
                \App\Models\DatosInstitucionales::where('vigente', true)->first()
            );
        });
    }
}
