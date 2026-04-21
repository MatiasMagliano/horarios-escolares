<?php

namespace App\Http\Middleware;

use App\Models\Institucion;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class SetInstitucionActiva
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $institucionId = (int) ($request->session()->get('institucion_id') ?? $user->institucion_activa_id ?? 0);

        if ($institucionId <= 0 || !$user->puedeAccederInstitucion($institucionId)) {
            $request->session()->forget('institucion_id');
            app(PermissionRegistrar::class)->setPermissionsTeamId(null);

            return redirect()->route('instituciones.select');
        }

        $institucion = Institucion::query()
            ->whereKey($institucionId)
            ->where('activo', true)
            ->first();

        if (!$institucion) {
            $request->session()->forget('institucion_id');
            app(PermissionRegistrar::class)->setPermissionsTeamId(null);

            return redirect()->route('instituciones.select');
        }

        $request->session()->put('institucion_id', $institucion->id);
        app(PermissionRegistrar::class)->setPermissionsTeamId($institucion->id);

        if ((int) $user->institucion_activa_id !== (int) $institucion->id) {
            $user->forceFill([
                'institucion_activa_id' => $institucion->id,
            ])->save();
        }

        $user->setRelation('institucionActiva', $institucion);

        return $next($request);
    }
}
