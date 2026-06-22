<?php

namespace App\Providers;

use App\Models\CambioHorario;
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
        Gate::define('crear-cambios-horario', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['admin', 'administrador', 'aprobador', 'solicitante']));
        Gate::define('aprobar-cambios-horario', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['aprobador']));
        Gate::define('gestionar-cambios-horario', fn ($user) => Gate::forUser($user)->allows('aprobar-cambios-horario'));
        Gate::define('efectivizar-cambios-horario', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['admin', 'administrador', 'aprobador']));
        Gate::define('firmar-cambios-horario', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['secretario']));
        Gate::define('ver-cambios-horario', fn ($user) => $hasAnyRoleInActiveInstitucion($user, ['admin', 'administrador', 'preceptor', 'aprobador', 'secretario', 'solicitante']));
        Gate::define('anular-cambios-horario', function ($user, CambioHorario $cambio) use ($hasAnyRoleInActiveInstitucion): bool {
            if (!$cambio->puedeAnular()) {
                return false;
            }

            if ($hasAnyRoleInActiveInstitucion($user, ['admin', 'administrador', 'aprobador'])) {
                return true;
            }

            return $hasAnyRoleInActiveInstitucion($user, ['solicitante'])
                && (int) $cambio->pedido_por === (int) $user->id;
        });

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
