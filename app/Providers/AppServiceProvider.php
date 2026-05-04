<?php

namespace App\Providers;

use App\Models\Institucion;
use App\Support\Instituciones\InstitucionContext;
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

        $hasAnyRoleInActiveInstitucion = fn ($user, array $roles): bool => $user->hasAnyRoleInInstitucion(
            $roles,
            app(InstitucionContext::class)->id()
        );

        Gate::define('ver-horarios', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['admin', 'preceptor', 'aprobador']));
        Gate::define('editar-horarios', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['admin']));
        Gate::define('abm-cursos', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['admin', 'aprobador']));
        Gate::define('abm-docentes', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['admin', 'aprobador']));
        Gate::define('activar-docentes', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['aprobador']));
        Gate::define('abm-espacios', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['admin', 'preceptor', 'aprobador']));
        Gate::define('crear-cambios-horario', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['admin', 'preceptor', 'aprobador', 'solicitante']));
        Gate::define('gestionar-cambios-horario', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['aprobador']));
        Gate::define('firmar-cambios-horario', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['secretario']));
        Gate::define('ver-cambios-horario', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['admin', 'preceptor', 'aprobador', 'secretario', 'solicitante']));

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
