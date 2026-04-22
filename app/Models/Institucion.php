<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institucion extends Model
{
    protected $table = 'datos_institucionales';

    protected $fillable = [
        'nombre_institucion',
        'slug',
        'direccion',
        'telefono',
        'email',
        'anio_maximo',
        'tiene_turno_maniana',
        'tiene_turno_tarde',
        'tiene_contraturno_maniana',
        'tiene_contraturno_tarde',
        'genero_director',
        'nombre_director',
        'telefono_director',
        'email_director',
        'genero_vicedirector',
        'nombre_vicedirector',
        'telefono_vicedirector',
        'email_vicedirector',
        'activo',
    ];

    protected $casts = [
        'anio_maximo' => 'integer',
        'tiene_turno_maniana' => 'boolean',
        'tiene_turno_tarde' => 'boolean',
        'tiene_contraturno_maniana' => 'boolean',
        'tiene_contraturno_tarde' => 'boolean',
        'activo' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'institucion_user')
            ->withPivot('activo')
            ->withTimestamps();
    }

    public function usuarios(): BelongsToMany
    {
        return $this->users();
    }

    public function usuariosConInstitucionActiva(): HasMany
    {
        return $this->hasMany(User::class, 'institucion_activa_id');
    }

    public function tieneTurno(string $turno): bool
    {
        return match ($turno) {
            'maniana' => $this->tiene_turno_maniana,
            'tarde' => $this->tiene_turno_tarde,
            'contraturno_maniana' => $this->tiene_contraturno_maniana,
            'contraturno_tarde' => $this->tiene_contraturno_tarde,
            default => false,
        };
    }

    public function turnosVisiblesParaCurso(string $turnoBase): array
    {
        $turnos = [];

        if ($this->tieneTurno($turnoBase)) {
            $turnos[] = $turnoBase;
        }

        $contraturno = match ($turnoBase) {
            'maniana' => 'contraturno_maniana',
            'tarde' => 'contraturno_tarde',
            default => null,
        };

        if ($contraturno && $this->tieneTurno($contraturno)) {
            $turnos[] = $contraturno;
        }

        return $turnos;
    }

    public function turnosConfigurados(): array
    {
        return collect([
            'maniana',
            'tarde',
            'contraturno_maniana',
            'contraturno_tarde',
        ])->filter(fn (string $turno) => $this->tieneTurno($turno))
            ->values()
            ->all();
    }
}
