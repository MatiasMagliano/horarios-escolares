<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_5A extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 5,
            'division' => 'A',
            'turno' => 'maniana',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 3, 'docente' => 'Noelia Martinez Villegas', 'espacio' => 'Aula 8'], 
            ['nombre' => 'Psicología', 'horas_totales' => 3, 'docente' => 'Yolanda Sucheyre', 'espacio' => 'Aula 8'], 
            ['nombre' => 'Geografía', 'horas_totales' => 3, 'docente' => 'Marianela Gatti', 'espacio' => 'Aula 8'], 
            ['nombre' => 'Historia', 'horas_totales' => 3, 'docente' => 'Facundo Zurita', 'espacio' => 'Aula 8'],
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Carina Chialva', 'espacio' => 'Aula 8'],
            ['nombre' => 'Ed. Art. - Música', 'horas_totales' => 2, 'docente' => 'Sonia Bruno', 'espacio' => 'Aula 8'], 
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Ivana Ribodino', 'espacio' => 'Aula 8'], 
            ['nombre' => 'Física', 'horas_totales' => 4, 'docente' => 'Natacha Marangón', 'espacio' => 'Aula 8'], 
            ['nombre' => 'Química', 'horas_totales' => 3, 'docente' => 'Natacha Marangón', 'espacio' => 'Aula 8'], 
            ['nombre' => 'Elect. Digital II', 'horas_totales' => 4, 'docente' => 'Pablo Bulacio', 'espacio' => 'Aula 8'], 
            ['nombre' => 'Elect. Analógica II', 'horas_totales' => 6, 'docente' => 'Pablo Bulacio', 'espacio' => 'Aula 8'],
            ['nombre' => 'Electrotecnia II', 'horas_totales' => 6, 'docente' => 'Pablo Bulacio', 'espacio' => 'Laboratorio de Electrónica'],
            ['nombre' => 'Inf. Electrónica II', 'horas_totales' => 5, 'docente' => 'Andrea Chiappori', 'espacio' => 'Aula 8'], 
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO MAÑANA
            'maniana' => [
                1 => [ // M1
                    1 => ['Química'],               // LUNES
                    2 => ['Ed. Art. - Música'],     // MARTES
                    3 => ['Matemática'],            // MIÉRCOLES
                    4 => ['Lengua y Literatura'],   // JUEVES
                    5 => ['Matemática'],            // VIERNES
                ],
                2 => [ // M2
                    1 => ['Química'],               // LUNES
                    2 => ['Ed. Art. - Música'],     // MARTES
                    3 => ['Matemática'],            // MIÉRCOLES
                    4 => ['Lengua y Literatura'],   // JUEVES
                    5 => ['Matemática'],            // VIERNES
                ],
                3 => [ // M3
                    1 => ['Química'],               // LUNES
                    2 => ['Leng. Ext. - Inglés'],   // MARTES
                    3 => ['Matemática'],    // MIÉRCOLES
                    4 => ['Psicología'],                // JUEVES
                    5 => ['Geografía'],  // VIERNES
                ],
                5 => [ // M4
                    1 => ['Electrotecnia II'],         // LUNES
                    2 => ['Historia'],                 // MARTES
                    3 => ['Historia'],                 // MIÉRCOLES
                    4 => ['Psicología'],               // JUEVES
                    5 => ['Geografía'],                // VIERNES
                ],
                6 => [ // M5
                    1 => ['Electrotecnia II'],         // LUNES
                    2 => ['Historia'],                 // MARTES
                    3 => ['Leng. Ext. - Inglés'],   // MIÉRCOLES
                    4 => ['Psicología'],               // JUEVES
                    5 => ['Geografía'],                // VIERNES
                ],
                8 => [ // M6
                    1 => ['Electrotecnia II'],         // LUNES
                    2 => ['Física'], // MARTES
                    3 => ['Leng. Ext. - Inglés'],               // MIÉRCOLES
                    4 => ['Lengua y Literatura'],         // JUEVES
                    5 => ['Física'],                   // VIERNES
                ],
                9 => [ // M7
                    1 => ['Electrotecnia II'], // LUNES
                    2 => ['Física'],                   // MARTES
                    3 => [null],          // MIÉRCOLES
                    4 => ['Elect. Digital II'],                   // JUEVES
                    5 => ['Física'],                // VIERNES
                ],
                10 => [ // M8
                    1 => ['Electrotecnia II'], // LUNES
                    2 => [null],                   // MARTES
                    3 => [null],          // MIÉRCOLES
                    4 => ['Elect. Digital II'],                   // JUEVES
                    5 => ['Elect. Digital II'],                // VIERNES
                ],
            ],

            // Si tuviera contraturno:
            'contraturno_maniana' => [
                1 => [ // M1
                    1 => ['Electrotecnia II'],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => ['Elect. Analógica II'],  // JUEVES
                    5 => ['Elect. Digital II'],  // VIERNES
                ],
                2 => [ // M2
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => ['Elect. Analógica II'],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                3 => [ // M3
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => ['Elect. Analógica II'],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                5 => [ // M4
                    1 => [null],  // LUNES
                    2 => ['Inf. Electrónica II'],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => ['Elect. Analógica II'],  // JUEVES
                    5 => ['Inf. Electrónica II'],  // VIERNES
                ],
                6 => [ // M5
                    1 => [null],  // LUNES
                    2 => ['Inf. Electrónica II'],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => ['Elect. Analógica II'],  // JUEVES
                    5 => ['Inf. Electrónica II'],  // VIERNES
                ],
                8 => [ // M6
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => ['Elect. Analógica II'],  // JUEVES
                    5 => ['Inf. Electrónica II'],  // VIERNES
                ],
                9 => [ // M7
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                10 => [ // M8
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => [null],  // VIERNES
                ],
            ],
        ];
    }
}
