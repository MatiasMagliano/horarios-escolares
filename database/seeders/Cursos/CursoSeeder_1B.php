<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_1B extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 1,
            'division' => 'B',
            'turno' => 'tarde',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Cs. Ss. - Geografía', 'horas_totales' => 5, 'docente' => 'Miriam Porcel'],
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 5, 'docente' => 'Marisa Morales'],
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Martín Andrada'],
            ['nombre' => 'Cs. Ns. - Biología', 'horas_totales' => 3, 'docente' => 'Yanina Lerda'],
            ['nombre' => 'Ed. Tecnológica', 'horas_totales' => 4, 'docente' => 'Nicolás Coria'],
            ['nombre' => 'Ed. Art. - Art. Visuales', 'horas_totales' => 3, 'docente' => 'M. Elena Mansilla'],
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Sandra Occhipinti'],
            ['nombre' => 'Cs. Ns. - Física', 'horas_totales' => 3, 'docente' => 'Marianela Pecorari'],
            ['nombre' => 'Ciud. y Participación', 'horas_totales' => 3, 'docente' => 'Flavia Eberhardt'],
            ['nombre' => 'Dib. Técnico', 'horas_totales' => 2, 'docente' => 'Nadia Llarrull']
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO TARDE
            'tarde' => [
                1 => [ // M1
                    1 => ['Lengua y Literatura'],       // LUNES
                    2 => ['Matemática'],                // MARTES
                    3 => ['Lengua y Literatura'],       // MIÉRCOLES
                    4 => ['Cs. Ns. - Física'],          // JUEVES
                    5 => ['Ciud. y Participación'],     // VIERNES
                ],
                2 => [ // M2
                    1 => ['Lengua y Literatura'],       // LUNES
                    2 => ['Matemática'],                // MARTES
                    3 => ['Lengua y Literatura'],       // MIÉRCOLES
                    4 => ['Cs. Ns. - Física'],          // JUEVES
                    5 => ['Ciud. y Participación'],     // VIERNES
                ],
                3 => [ // M3
                    1 => ['Lengua y Literatura'],       // LUNES
                    2 => ['Matemática'],                // MARTES
                    3 => ['Ed. Tecnológica'],           // MIÉRCOLES
                    4 => ['Cs. Ns. - Física'],          // JUEVES
                    5 => ['Ciud. y Participación'],     // VIERNES
                ],
                5 => [ // M4
                    1 => ['Ed. Art. - Art. Visuales'],  // LUNES
                    2 => ['Leng. Ext. - Inglés'],       // MARTES
                    3 => ['Ed. Tecnológica'],           // MIÉRCOLES
                    4 => ['Cs. Ss. - Geografía'],       // JUEVES
                    5 => ['Cs. Ns. - Biología'],        // VIERNES
                ],
                6 => [ // M5
                    1 => ['Ed. Art. - Art. Visuales'],  // LUNES
                    2 => ['Leng. Ext. - Inglés'],       // MARTES
                    3 => ['Dib. Técnico'],              // MIÉRCOLES
                    4 => ['Cs. Ss. - Geografía'],       // JUEVES
                    5 => ['Cs. Ns. - Biología'],        // VIERNES
                ],
                8 => [ // M6
                    1 => ['Ed. Art. - Art. Visuales'],  // LUNES
                    2 => ['Leng. Ext. - Inglés'],       // MARTES
                    3 => ['Dib. Técnico'],              // MIÉRCOLES
                    4 => ['Cs. Ss. - Geografía'],       // JUEVES
                    5 => ['Cs. Ns. - Biología'],        // VIERNES
                ],
                9 => [ // M7
                    1 => ['Matemática'],                // LUNES
                    2 => [null],                        // MARTES
                    3 => ['Cs. Ss. - Geografía'],       // MIÉRCOLES
                    4 => ['Ed. Tecnológica'],           // JUEVES
                    5 => [null],                        // VIERNES
                ],
                10 => [ // M8
                    1 => ['Matemática'],                // LUNES
                    2 => [null],                        // MARTES
                    3 => ['Cs. Ss. - Geografía'],       // MIÉRCOLES
                    4 => ['Ed. Tecnológica'],           // JUEVES
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
