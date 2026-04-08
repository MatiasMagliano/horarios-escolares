<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_4A extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 4,
            'division' => 'A',
            'turno' => 'maniana',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 4, 'docente' => 'Noelia Martinez Villegas', 'espacio' => 'Aula 5'], 
            ['nombre' => 'Biología', 'horas_totales' => 4, 'docente' => 'Yanina Lerda', 'espacio' => 'Aula 5'],
            ['nombre' => 'Geografía', 'horas_totales' => 3, 'docente' => 'Marianela Gatti', 'espacio' => 'Aula 5'], 
            ['nombre' => 'Historia', 'horas_totales' => 3, 'docente' => 'Facundo Zurita', 'espacio' => 'Aula 5'],
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Carina Chialva', 'espacio' => 'Aula 5'],
            ['nombre' => 'Ed. Art. - Art. Visuales', 'horas_totales' => 2, 'docente' => 'Sonia Bruno', 'espacio' => 'Aula 5'], 
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Ivana Ribodino', 'espacio' => 'Aula 5'], 
            ['nombre' => 'Física', 'horas_totales' => 4, 'docente' => 'Natacha Marangón', 'espacio' => 'Aula 5'], 
            ['nombre' => 'Química', 'horas_totales' => 3, 'docente' => 'Natacha Marangón', 'espacio' => 'Aula 5'], 
            ['nombre' => 'Elect. Digital I', 'horas_totales' => 4, 'docente' => 'Pablo Bulacio', 'espacio' => 'Aula 5'], 
            ['nombre' => 'Elect. Analógica I', 'horas_totales' => 5, 'docente' => 'Pablo Bulacio', 'espacio' => 'Aula 5'],
            ['nombre' => 'Electrotecnia I', 'horas_totales' => 5, 'docente' => 'Pablo Bulacio', 'espacio' => 'Laboratorio de Electrónica'],
            ['nombre' => 'Inf. Electrónica I', 'horas_totales' => 3, 'docente' => 'Andrea Chiappori', 'espacio' => 'Aula 5'], 
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO MAÑANA
            'maniana' => [
                1 => [ // M1
                    1 => ['Química'],              // LUNES
                    2 => ['Lengua y Literatura'],  // MARTES
                    3 => ['Inf. Electrónica I'],     // MIÉRCOLES
                    4 => ['Biología'],             // JUEVES
                    5 => ['Leng. Ext. - Inglés'],  // VIERNES
                ],
                2 => [ // M2
                    1 => ['Química'],              // LUNES
                    2 => ['Lengua y Literatura'],  // MARTES
                    3 => ['Inf. Electrónica I'],     // MIÉRCOLES
                    4 => ['Biología'],             // JUEVES
                    5 => ['Leng. Ext. - Inglés'],  // VIERNES
                ],
                3 => [ // M3
                    1 => ['Química'],              // LUNES
                    2 => ['Lengua y Literatura'],  // MARTES
                    3 => ['Inf. Electrónica I'],     // MIÉRCOLES
                    4 => ['Física'],               // JUEVES
                    5 => ['Leng. Ext. - Inglés'],  // VIERNES
                ],
                5 => [ // M4
                    1 => ['Matemática'],           // LUNES
                    2 => ['Ed. Art. - Art. Visuales'],// MARTES
                    3 => ['Matemática'],           // MIÉRCOLES
                    4 => ['Lengua y Literatura'],  // JUEVES
                    5 => ['Física'],               // VIERNES
                ],
                6 => [ // M5
                    1 => ['Matemática'],           // LUNES
                    2 => ['Ed. Art. - Art. Visuales'],// MARTES
                    3 => ['Matemática'],           // MIÉRCOLES
                    4 => ['Elect. Digital I'],       // JUEVES
                    5 => ['Física'],               // VIERNES
                ],
                8 => [ // M6
                    1 => ['Biología'],           // LUNES
                    2 => ['Historia'],// MARTES
                    3 => ['Matemática'],  // MIÉRCOLES
                    4 => ['Elect. Digital I'],// JUEVES
                    5 => ['Física'],          // VIERNES
                ],
                9 => [ // M7
                    1 => ['Biología'], // LUNES
                    2 => ['Historia'],                   // MARTES
                    3 => ['Electrotecnia I'],          // MIÉRCOLES
                    4 => ['Elect. Analógica I'],                   // JUEVES
                    5 => ['Elect. Digital I'],                // VIERNES
                ],
                10 => [ // M8
                    1 => [null], // LUNES
                    2 => ['Historia'],                   // MARTES
                    3 => ['Electrotecnia I'],          // MIÉRCOLES
                    4 => ['Elect. Analógica I'],                   // JUEVES
                    5 => ['Elect. Digital I'],                // VIERNES
                ],
            ],

            // Si tuviera contraturno:
            'contraturno_maniana' => [
                1 => [ // M1
                    1 => [null],  // LUNES
                    2 => ['Geografía'],  // MARTES
                    3 => ['Electrotecnia I'],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => ['Elect. Analógica I'],  // VIERNES
                ],
                2 => [ // M2
                    1 => [null],  // LUNES
                    2 => ['Geografía'],  // MARTES
                    3 => ['Electrotecnia I'],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => ['Elect. Analógica I'],  // VIERNES
                ],
                3 => [ // M3
                    1 => [null],  // LUNES
                    2 => ['Geografía'],  // MARTES
                    3 => ['Electrotecnia I'],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => ['Elect. Analógica I'],  // VIERNES
                ],
                5 => [ // M4
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                6 => [ // M5
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                8 => [ // M6
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => [null],  // VIERNES
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
