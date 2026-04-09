<?php

namespace App\Support\Horarios;

final class TurnoHelper
{
    public static function contraturnoDe(string $turnoCurso): string
    {
        return match ($turnoCurso) {
            'maniana' => 'contraturno_maniana',
            'tarde' => 'contraturno_tarde',
            default => throw new \LogicException("Turno inválido: {$turnoCurso}"),
        };
    }

    public static function franjaDeTurno(string $turno): string
    {
        return match ($turno) {
            'maniana', 'contraturno_tarde' => 'maniana',
            'tarde', 'contraturno_maniana' => 'tarde',
            default => $turno,
        };
    }

    public static function designacionTurno(string $turno): string
    {
        return match ($turno) {
            'maniana' => 'Mañana',
            'tarde' => 'Tarde',
            'contraturno_maniana' => 'Contraturno Mañana',
            'contraturno_tarde' => 'Contraturno Tarde',
            default => 'Contraturno',
        };
    }

    public static function designacionDia(int $dia): string
    {
        return match ($dia) {
            1 => 'lunes',
            2 => 'martes',
            3 => 'miércoles',
            4 => 'jueves',
            5 => 'viernes',
            default => 'día no definido',
        };
    }
}
