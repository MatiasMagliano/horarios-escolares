<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_3A extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 3,
            'division' => 'A',
            'turno' => 'maniana',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 5, 'docente' => 'Marisa Morales'], 
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Martín Andrada'], 
            ['nombre' => 'Física', 'horas_totales' => 3, 'docente' => 'Natacha Marangón'], 
            ['nombre' => 'Química', 'horas_totales' => 3, 'docente' => 'Yanina Funes'], 
            ['nombre' => 'Geografía', 'horas_totales' => 4, 'docente' => 'Belén Ramos'], 
            ['nombre' => 'Historia', 'horas_totales' => 4, 'docente' => 'Erick Zaccagnini'], 
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Carina Chialva'], 
            ['nombre' => 'Ed. Art. - Música/Teatro', 'horas_totales' => 3, 'docente' => 'Bruno/Morano'], 
            ['nombre' => 'Ed. Tecnológica', 'horas_totales' => 4, 'docente' => 'Brenda Quiroga'], 
            ['nombre' => 'F.V.T', 'horas_totales' => 4, 'docente' => 'Yolanda Sucheyre'],
            ['nombre' => 'Dib. Técnico', 'horas_totales' => 4, 'docente' => 'Ivana Ribodino']
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO MAÑANA
            'maniana' => [
                1 => [ // M1
                    1 => ['Matemática'], // LUNES
                    2 => [null],                     // MARTES
                    3 => ['Química'],      // MIÉRCOLES
                    4 => ['Geografía'],     // JUEVES
                    5 => ['Geografía'],     // VIERNES
                ],
                2 => [ // M2
                    1 => ['Matemática'],                // LUNES
                    2 => ['Matemática'],                // MARTES
                    3 => ['Química'],                   // MIÉRCOLES
                    4 => ['Geografía'],                 // JUEVES
                    5 => ['Geografía'],                 // VIERNES
                ],
                3 => [ // M3
                    1 => ['Matemática'],                // LUNES
                    2 => ['Matemática'],                // MARTES
                    3 => ['Química'],                   // MIÉRCOLES
                    4 => ['Lengua y Literatura'],       // JUEVES
                    5 => ['Física'],                    // VIERNES
                ],
                5 => [ // M4
                    1 => ['Ed. Tecnológica'],           // LUNES
                    2 => ['F.V.T'],                     // MARTES
                    3 => ['Ed. Tecnológica'],           // MIÉRCOLES
                    4 => ['Lengua y Literatura'],       // JUEVES
                    5 => ['F.V.T'],                     // VIERNES
                ],
                6 => [ // M5
                    1 => ['Ed. Tecnológica'],           // LUNES
                    2 => ['F.V.T'],                     // MARTES
                    3 => ['Ed. Tecnológica'],           // MIÉRCOLES
                    4 => ['Física'],                    // JUEVES
                    5 => ['F.V.T'],                     // VIERNES
                ],
                8 => [ // M6
                    1 => ['Dib. Técnico'],              // LUNES
                    2 => ['Ed. Art. - Música/Teatro'],  // MARTES
                    3 => ['Lengua y Literatura'],       // MIÉRCOLES
                    4 => ['Física'],                    // JUEVES
                    5 => ['Leng. Ext. - Inglés'],       // VIERNES
                ],
                9 => [ // M7
                    1 => ['Dib. Técnico'],              // LUNES
                    2 => ['Ed. Art. - Música/Teatro'],  // MARTES
                    3 => ['Lengua y Literatura'],       // MIÉRCOLES
                    4 => ['Historia'],                  // JUEVES
                    5 => ['Leng. Ext. - Inglés'],       // VIERNES
                ],
                10 => [ // M8
                    1 => ['Dib. Técnico'],              // LUNES
                    2 => ['Ed. Art. - Música/Teatro'],  // MARTES
                    3 => ['Lengua y Literatura'],       // MIÉRCOLES
                    4 => ['Historia'],                  // JUEVES
                    5 => ['Leng. Ext. - Inglés']        // VIERNES
                ],
            ],

            // Si tuviera contraturno:
            'contraturno_maniana' => [
                1 => [ // M1
                    1 => ['Dib. Técnico', 'Ivana Ribodino'], // Lunes
                    2 => [null, null],                    // Martes
                    3 => ['Historia', 'Erick Zaccagnini'], // Miércoles
                    4 => [null, null],                    // Jueves
                    5 => [null, null],                    // Viernes
                ],
                2 => [ // M2
                    1 => [null, null],                    // Lunes
                    2 => [null, null],                    // Martes
                    3 => ['Historia', 'Erick Zaccagnini'], // Miércoles
                    4 => [null, null],                    // Jueves
                    5 => [null, null],                    // Viernes
                ],
                3 => [ // M3
                    1 => [null, null],      // Lunes
                    2 => [null, null],      // Martes
                    3 => [null, null], // Miércoles
                    4 => [null, null],                        // Jueves
                    5 => [null, null],                        // Viernes
                ],
                5 => [ // M4
                    1 => [null, null],   // Lunes
                    2 => [null, null],   // Martes
                    3 => [null, null],                      // Miércoles
                    4 => [null, null],                      // Jueves
                    5 => [null, null],                      // Viernes
                ],
                6 => [ // M5
                    1 => [null, null],   // Lunes
                    2 => [null, null],   // Martes
                    3 => [null, null],                      // Miércoles
                    4 => [null, null],                      // Jueves
                    5 => [null, null],                      // Viernes
                ],
                8 => [ // M6
                    1 => [null, null],   // Lunes
                    2 => [null, null],   // Martes
                    3 => [null, null],                      // Miércoles
                    4 => [null, null],                      // Jueves
                    5 => [null, null],                      // Viernes
                ],
                9 => [ // M7
                    1 => [null, null],   // Lunes
                    2 => [null, null],   // Martes
                    3 => [null, null],                      // Miércoles
                    4 => [null, null],                      // Jueves
                    5 => [null, null],                      // Viernes
                ],
                10 => [ // M8
                    1 => [null, null],   // Lunes
                    2 => [null, null],   // Martes
                    3 => [null, null],                      // Miércoles
                    4 => [null, null],                      // Jueves
                    5 => [null, null],                      // Viernes
                ],
            ],
        ];
    }
}
