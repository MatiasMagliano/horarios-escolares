<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_4B extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 4,
            'division' => 'B',
            'turno' => 'maniana',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 4, 'docente' => 'Adam Luna', 'espacio' => 'Aula 9'], 
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Martín Andrada', 'espacio' => 'Aula 9'], 
            ['nombre' => 'Física', 'horas_totales' => 4, 'docente' => 'Natacha Marangón', 'espacio' => 'Aula 9'], 
            ['nombre' => 'Química', 'horas_totales' => 3, 'docente' => 'Soledad González', 'espacio' => 'Aula 9'], 
            ['nombre' => 'Biología', 'horas_totales' => 4, 'docente' => 'Natacha Marangón', 'espacio' => 'Aula 9'], 
            ['nombre' => 'Geografía', 'horas_totales' => 3, 'docente' => 'Marcos Morales', 'espacio' => 'Aula 9'], 
            ['nombre' => 'Historia', 'horas_totales' => 3, 'docente' => 'Erick Zaccagnini', 'espacio' => 'Aula 9'], 
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Carina Chialva', 'espacio' => 'Aula 9'], 
            ['nombre' => 'Ed. Art. - Art. Visuales', 'horas_totales' => 2, 'docente' => 'Vanina Ibarra', 'espacio' => 'Aula 9'], 
            ['nombre' => 'Inf. Aplicada I', 'horas_totales' => 4, 'docente' => 'Andrea Chiappori', 'espacio' => 'Laboratorio de Informática'], 
            ['nombre' => 'Lógica Matemática', 'horas_totales' => 4, 'docente' => 'Martín Andrada', 'espacio' => 'Aula 9'],
            ['nombre' => 'Programación I', 'horas_totales' => 6, 'docente' => 'Andrea Chiappori', 'espacio' => 'Laboratorio de Informática']
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO MAÑANA
            'maniana' => [
                1 => [ // M1
                    1 => [null],                 // LUNES
                    2 => ['Química'],            // MARTES
                    3 => ['Historia'],           // MIÉRCOLES
                    4 => ['Matemática'],         // JUEVES
                    5 => ['Lógica Matemática'],  // VIERNES
                ],
                2 => [ // M2
                    1 => ['Inf. Aplicada I'],    // LUNES
                    2 => ['Química'],            // MARTES
                    3 => ['Historia'],           // MIÉRCOLES
                    4 => ['Matemática'],         // JUEVES
                    5 => ['Lógica Matemática'],  // VIERNES
                ],
                3 => [ // M3
                    1 => ['Inf. Aplicada I'],    // LUNES
                    2 => ['Química'],            // MARTES
                    3 => ['Historia'],           // MIÉRCOLES
                    4 => ['Matemática'],         // JUEVES
                    5 => ['Lógica Matemática'],  // VIERNES
                ],
                5 => [ // M4
                    1 => ['Biología'],           // LUNES
                    2 => ['Leng. Ext. - Inglés'],// MARTES
                    3 => ['Matemática'],         // MIÉRCOLES
                    4 => ['Biología'],           // JUEVES
                    5 => ['Lengua y Literatura'],// VIERNES
                ],
                6 => [ // M5
                    1 => ['Biología'],           // LUNES
                    2 => ['Leng. Ext. - Inglés'],// MARTES
                    3 => ['Matemática'],         // MIÉRCOLES
                    4 => ['Lengua y Literatura'],           // JUEVES
                    5 => ['Lengua y Literatura'],// VIERNES
                ],
                8 => [ // M6
                    1 => ['Biología'],           // LUNES
                    2 => ['Leng. Ext. - Inglés'],// MARTES
                    3 => ['Lógica Matemática'],  // MIÉRCOLES
                    4 => ['Lengua y Literatura'],// JUEVES
                    5 => ['Geografía'],          // VIERNES
                ],
                9 => [ // M7
                    1 => ['Ed. Art. - Art. Visuales'], // LUNES
                    2 => ['Física'],                   // MARTES
                    3 => ['Inf. Aplicada I'],          // MIÉRCOLES
                    4 => ['Física'],                   // JUEVES
                    5 => ['Geografía'],                // VIERNES
                ],
                10 => [ // M8
                    1 => ['Ed. Art. - Art. Visuales'], // LUNES
                    2 => ['Física'],                   // MARTES
                    3 => ['Inf. Aplicada I'],          // MIÉRCOLES
                    4 => ['Física'],                   // JUEVES
                    5 => ['Geografía'],                // VIERNES
                ],
            ],

            // Si tuviera contraturno:
            'contraturno_maniana' => [
                1 => [ // M1
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => ['Programación I'],  // MIÉRCOLES
                    4 => ['Programación I'],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                2 => [ // M2
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => ['Programación I'],  // MIÉRCOLES
                    4 => ['Programación I'],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                3 => [ // M3
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => ['Programación I'],  // MIÉRCOLES
                    4 => ['Programación I'],  // JUEVES
                    5 => [null],  // VIERNES
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
