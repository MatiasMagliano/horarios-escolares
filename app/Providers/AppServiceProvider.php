<?php

namespace App\Providers;

use App\Http\Middleware\SetInstitucionActiva;
use App\Models\Institucion;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        Gate::before(function ($user, string $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        Livewire::addPersistentMiddleware([
            SetInstitucionActiva::class,
        ]);

        view()->composer('*', function ($view) {
            $institucionActiva = null;

            if (auth()->check()) {
                $user = auth()->user();
                $institucionId = session('institucion_id') ?? $user->institucion_activa_id;

                if ($institucionId) {
                    $institucionActiva = $user->relationLoaded('institucionActiva')
                        ? $user->institucionActiva
                        : Institucion::query()->find($institucionId);
                }
            }

            $view->with(
                'institucion_global',
                $institucionActiva
            );
        });
    }
}
