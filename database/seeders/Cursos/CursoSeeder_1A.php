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
            ['nombre' => 'Cs. Ss. - Geografía', 'horas_totales' => 5, 'docente' => 'Marcos Morales'],
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 5, 'docente' => 'Noelia Martinez Villegas'],
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Ivana Ribodino'],
            ['nombre' => 'Cs. Ns. - Biología', 'horas_totales' => 3, 'docente' => 'Soledad González'],
            ['nombre' => 'Ed. Tecnológica', 'horas_totales' => 4, 'docente' => 'Sofía Rodriguez'],
            ['nombre' => 'Ed. Art. - Art. Visuales', 'horas_totales' => 3, 'docente' => 'M. Elena Mansilla'],
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Claudia Ramadán'],
            ['nombre' => 'Cs. Ns. - Física', 'horas_totales' => 3, 'docente' => 'Yanina Funes'],
            ['nombre' => 'Ciud. y Participación', 'horas_totales' => 3, 'docente' => 'Ariel Ardiles'],
            ['nombre' => 'Dib. Técnico', 'horas_totales' => 2, 'docente' => 'Nadia Llarrull']
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO MAÑANA
            'maniana' => [
                1 => [ // M1
                    1 => ['Cs. Ss. - Geografía'],       // LUNES
                    2 => [null],                        // MARTES
                    3 => ['Cs. Ns. - Biología'],        // MIÉRCOLES
                    4 => ['Lengua y Literatura'],       // JUEVES
                    5 => [null],                        // VIERNES
                ],
                2 => [ // M2
                    1 => ['Cs. Ss. - Geografía'],       // LUNES
                    2 => ['Ed. Tecnológica'],           // MARTES
                    3 => ['Cs. Ns. - Biología'],        // MIÉRCOLES
                    4 => ['Lengua y Literatura'],       // JUEVES
                    5 => [null],                        // VIERNES
                ],
                3 => [ // M3
                    1 => ['Ed. Art. - Art. Visuales'],  // LUNES
                    2 => ['Ed. Tecnológica'],           // MARTES
                    3 => ['Cs. Ns. - Biología'],        // MIÉRCOLES
                    4 => ['Lengua y Literatura'],       // JUEVES
                    5 => ['Ed. Tecnológica'],           // VIERNES
                ],
                5 => [ // M4
                    1 => ['Ed. Art. - Art. Visuales'],  // LUNES
                    2 => ['Leng. Ext. - Inglés'],       // MARTES
                    3 => ['Cs. Ns. - Física'],          // MIÉRCOLES
                    4 => ['Cs. Ss. - Geografía'],       // JUEVES
                    5 => ['Ed. Tecnológica'],           // VIERNES
                ],
                6 => [ // M5
                    1 => ['Ed. Art. - Art. Visuales'],  // LUNES
                    2 => ['Leng. Ext. - Inglés'],       // MARTES
                    3 => ['Cs. Ns. - Física'],          // MIÉRCOLES
                    4 => ['Cs. Ss. - Geografía'],       // JUEVES
                    5 => ['Matemática'],                // VIERNES
                ],
                8 => [ // M6
                    1 => ['Ciud. y Participación'],     // LUNES
                    2 => ['Leng. Ext. - Inglés'],       // MARTES
                    3 => ['Cs. Ns. - Física'],          // MIÉRCOLES
                    4 => ['Cs. Ss. - Geografía'],       // JUEVES
                    5 => ['Matemática'],                // VIERNES
                ],
                9 => [ // M7
                    1 => ['Ciud. y Participación'],     // LUNES
                    2 => ['Lengua y Literatura'],       // MARTES
                    3 => ['Matemática'],                // MIÉRCOLES
                    4 => ['Dib. Técnico'],              // JUEVES
                    5 => ['Matemática'],                // VIERNES
                ],
                10 => [ // M8
                    1 => ['Ciud. y Participación'],     // LUNES
                    2 => ['Lengua y Literatura'],       // MARTES
                    3 => ['Matemática'],                // MIÉRCOLES
                    4 => ['Dib. Técnico'],              // JUEVES
                    5 => [null],                        // VIERNES
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
