<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_2B extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 2,
            'division' => 'B',
            'turno' => 'tarde',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 5, 'docente' => 'Noelia Martinez Villegas'],
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Micaela Acuña'],
            ['nombre' => 'Biología', 'horas_totales' => 3, 'docente' => 'Soledad González'],
            ['nombre' => 'Cs. Ns. - Química', 'horas_totales' => 3, 'docente' => 'Soledad González'],
            ['nombre' => 'Cs. Ss. - Historia', 'horas_totales' => 5, 'docente' => 'Verónica Gizzi'],
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Carina Chialva'],
            ['nombre' => 'Ed. Art. - Música', 'horas_totales' => 3, 'docente' => 'Franco Morano'],
            ['nombre' => 'Ed. Tecnológica', 'horas_totales' => 4, 'docente' => 'Patricia Solís'],
            ['nombre' => 'Ciud. y Participación', 'horas_totales' => 3, 'docente' => 'Flavia Eberhardt'],
            ['nombre' => 'Dib. Técnico', 'horas_totales' => 2, 'docente' => 'Florentina Arinci']
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO TARDE
            'tarde' => [
                1 => [ // M1
                    1 => ['Cs. Ss. - Historia'],     // LUNES
                    2 => ['Ed. Art. - Música'],      // MARTES
                    3 => ['Cs. Ns. - Química'],      // MIÉRCOLES
                    4 => ['Lengua y Literatura'],    // JUEVES
                    5 => [null, null],               // VIERNES
                ],
                2 => [ // M2
                    1 => ['Cs. Ss. - Historia'],     // LUNES
                    2 => ['Ed. Art. - Música'],      // MARTES
                    3 => ['Cs. Ns. - Química'],      // MIÉRCOLES
                    4 => ['Lengua y Literatura'],    // JUEVES
                    5 => ['Ed. Tecnológica'],        // VIERNES
                ],
                3 => [ // M3
                    1 => ['Leng. Ext. - Inglés'],    // LUNES
                    2 => ['Ed. Art. - Música'],      // MARTES
                    3 => ['Biología'],               // MIÉRCOLES
                    4 => ['Lengua y Literatura'],    // JUEVES
                    5 => ['Ed. Tecnológica'],        // VIERNES
                ],
                5 => [ // M4
                    1 => ['Leng. Ext. - Inglés'],    // LUNES
                    2 => ['Matemática'],             // MARTES
                    3 => ['Lengua y Literatura'],    // MIÉRCOLES
                    4 => ['Matemática'],             // JUEVES
                    5 => ['Ciud. y Participación'],  // VIERNES
                ],
                6 => [ // M5
                    1 => ['Leng. Ext. - Inglés'],    // LUNES
                    2 => ['Matemática'],             // MARTES
                    3 => ['Lengua y Literatura'],    // MIÉRCOLES
                    4 => ['Matemática'],             // JUEVES
                    5 => ['Ciud. y Participación'],  // VIERNES
                ],
                8 => [ // M6
                    1 => ['Cs. Ns. - Química'],      // LUNES
                    2 => ['Cs. Ss. - Historia'],     // MARTES
                    3 => ['Ed. Tecnológica'],        // MIÉRCOLES
                    4 => ['Matemática'],             // JUEVES
                    5 => ['Ciud. y Participación'],  // VIERNES
                ],
                9 => [ // M7
                    1 => ['Biología'],               // LUNES
                    2 => ['Cs. Ss. - Historia'],     // MARTES
                    3 => ['Ed. Tecnológica'],        // MIÉRCOLES
                    4 => ['Dib. Técnico'],           // JUEVES
                    5 => [null, null],               // VIERNES
                ],
                10 => [ // M8
                    1 => ['Biología'],               // LUNES
                    2 => ['Cs. Ss. - Historia'],     // MARTES
                    3 => [null, null],               // MIÉRCOLES
                    4 => ['Dib. Técnico'],           // JUEVES
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
