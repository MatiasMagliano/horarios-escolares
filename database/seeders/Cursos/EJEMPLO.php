<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_1A extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 1,
            'division' => 'A',
            'turno' => 'maniana',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Ivana Ribodino'],
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 5, 'docente' => 'Noelia Martinez Villegas'],
            ['nombre' => 'Cs. Ns. - Física', 'horas_totales' => 3, 'docente' => 'Yanina Funes'],
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO MAÑANA
            'maniana' => [
                1 => [
                    1 => 'Matemática',
                    2 => null,
                    3 => 'Lengua y Literatura',
                    4 => null,
                    5 => null,
                ],
                2 => [
                    1 => 'Matemática',
                    2 => 'Cs. Ns. - Física',
                    3 => null,
                    4 => null,
                    5 => null,
                ],
            ],

            // Si tuviera contraturno:
            /*
            'contraturno_tarde' => [
                8 => [
                    3 => 'Física',
                ],
            ],
            */

        ];
    }
}
