<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_2A extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 2,
            'division' => 'A',
            'turno' => 'maniana',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 5, 'docente' => 'Noelia Martinez Villegas'],
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Ivana Ribodino'],
            ['nombre' => 'Biología', 'horas_totales' => 3, 'docente' => 'Marianela Pecorari'],
            ['nombre' => 'Cs. Ns. - Química', 'horas_totales' => 3, 'docente' => 'Yanina Funes'],
            ['nombre' => 'Cs. Ss. - Historia', 'horas_totales' => 5, 'docente' => 'Verónica Gizzi'],
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Carina Chialva'],
            ['nombre' => 'Ed. Art. - Música', 'horas_totales' => 3, 'docente' => 'Franco Morano'],
            ['nombre' => 'Ed. Tecnológica', 'horas_totales' => 4, 'docente' => 'Vanesa Farías'],
            ['nombre' => 'Ciud. y Participación', 'horas_totales' => 3, 'docente' => 'Luciana Sosa Grión'],
            ['nombre' => 'Dib. Técnico', 'horas_totales' => 2, 'docente' => 'Nadia Llarrull']
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO MAÑANA
            'maniana' => [
                1 => [ // M1
                    1 => ['Matemática'],             // LUNES
                    2 => ['Ciud. y Participación'],  // MARTES
                    3 => ['Cs. Ss. - Historia'],     // MIÉRCOLES
                    4 => ['Biología'],               // JUEVES
                    5 => ['Ed. Tecnológica'],        // VIERNES
                ],
                2 => [ // M2
                    1 => ['Matemática'],             // LUNES
                    2 => ['Ciud. y Participación'],  // MARTES
                    3 => ['Cs. Ss. - Historia'],     // MIÉRCOLES
                    4 => ['Biología'],               // JUEVES
                    5 => ['Ed. Tecnológica'],        // VIERNES
                ],
                3 => [ // M3
                    1 => ['Matemática'],             // LUNES
                    2 => ['Ciud. y Participación'],  // MARTES
                    3 => ['Cs. Ss. - Historia'],     // MIÉRCOLES
                    4 => ['Biología'],               // JUEVES
                    5 => ['Matemática'],             // VIERNES
                ],
                5 => [ // M4
                    1 => ['Cs. Ns. - Química'],      // LUNES
                    2 => ['Lengua y Literatura'],    // MARTES
                    3 => ['Ed. Tecnológica'],        // MIÉRCOLES
                    4 => ['Leng. Ext. - Inglés'],    // JUEVES
                    5 => ['Matemática'],             // VIERNES
                ],
                6 => [ // M5
                    1 => ['Cs. Ns. - Química'],      // LUNES
                    2 => ['Lengua y Literatura'],    // MARTES
                    3 => ['Ed. Tecnológica'],        // MIÉRCOLES
                    4 => ['Leng. Ext. - Inglés'],    // JUEVES
                    5 => ['Leng. Ext. - Inglés'],    // VIERNES
                ],
                8 => [ // M6
                    1 => ['Cs. Ns. - Química'],      // LUNES
                    2 => ['Lengua y Literatura'],    // MARTES
                    3 => ['Ed. Art. - Música'],      // MIÉRCOLES
                    4 => ['Lengua y Literatura'],    // JUEVES
                    5 => [null, null],               // VIERNES
                ],
                9 => [ // M7
                    1 => ['Dib. Técnico'],           // LUNES
                    2 => ['Cs. Ss. - Historia'],     // MARTES
                    3 => ['Ed. Art. - Música'],      // MIÉRCOLES
                    4 => ['Lengua y Literatura'],    // JUEVES
                    5 => [null, null],               // VIERNES
                ],
                10 => [ // M8
                    1 => ['Dib. Técnico'],           // LUNES
                    2 => ['Cs. Ss. - Historia'],     // MARTES
                    3 => ['Ed. Art. - Música'],      // MIÉRCOLES
                    4 => [null, null],               // JUEVES
                    5 => [null, null],               // VIERNES
                ],
            ],

            // Si tuviera contraturno:
            /*
            'contraturno_maniana' => [
                8 => [
                    3 => 'Física',
                ],
            ],
            */

        ];
    }
}
