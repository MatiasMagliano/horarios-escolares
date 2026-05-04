<?php

namespace App\Providers;

use App\Models\Institucion;
use Illuminate\Support\Facades\Gate;
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
        Gate::before(function ($user, string $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        Gate::define('ver-horarios', fn ($user) => $user->hasAnyRole(['admin', 'preceptor', 'aprobador']));
        Gate::define('editar-horarios', fn ($user) => $user->hasRole('admin'));
        Gate::define('abm-cursos', fn ($user) => $user->hasAnyRole(['admin', 'aprobador']));
        Gate::define('abm-docentes', fn ($user) => $user->hasAnyRole(['admin', 'aprobador']));
        Gate::define('activar-docentes', fn ($user) => $user->hasRole('aprobador'));
        Gate::define('abm-espacios', fn ($user) => $user->hasAnyRole(['preceptor', 'aprobador']));
        Gate::define('crear-cambios-horario', fn ($user) => $user->hasAnyRole(['admin', 'preceptor', 'aprobador', 'solicitante']));
        Gate::define('gestionar-cambios-horario', fn ($user) => $user->hasRole('aprobador'));
        Gate::define('firmar-cambios-horario', fn ($user) => $user->hasRole('secretario'));
        Gate::define('ver-cambios-horario', fn ($user) => $user->hasAnyRole(['admin', 'preceptor', 'aprobador', 'secretario', 'solicitante']));

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
