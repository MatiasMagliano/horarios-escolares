<?php

namespace App\Support\Instituciones;

use App\Models\Institucion;

class InstitucionContext
{
    public function id(): ?int
    {
        if (!app()->bound('request')) {
            return null;
        }

        $request = request();
        $user = $request->user();

        if (!$user) {
            return null;
        }

        $institucionId = $request->session()->get('institucion_id') ?? $user->institucion_activa_id;

        return $institucionId ? (int) $institucionId : null;
    }

    public function institucion(): ?Institucion
    {
        $institucionId = $this->id();

        if (!$institucionId) {
            return null;
        }

        return Institucion::query()->find($institucionId);
    }
}
