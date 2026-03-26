<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_5B extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 5,
            'division' => 'B',
            'turno' => 'maniana',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 3, 'docente' => 'Adam Luna', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Psicología', 'horas_totales' => 3, 'docente' => 'Matías Magliano', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Lorena Vera', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Física', 'horas_totales' => 4, 'docente' => 'Lorena Vera', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Química', 'horas_totales' => 3, 'docente' => 'Carolina Molina', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Geografía', 'horas_totales' => 3, 'docente' => 'Marianela Gatti', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Historia', 'horas_totales' => 3, 'docente' => 'Erick Zaccagnini', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Carina Chialva', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Ed. Art. - Música', 'horas_totales' => 2, 'docente' => 'Franco Morano', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Inf. Aplicada II', 'horas_totales' => 8, 'docente' => 'Andrea Chiappori', 'espacio' => 'Laboratorio de Informática'], 
            ['nombre' => 'Sist. de Información', 'horas_totales' => 6, 'docente' => 'Jimena Godoy', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Programación II', 'horas_totales' => 6, 'docente' => 'Andrea Chiappori', 'espacio' => 'Laboratorio de Informática']
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO MAÑANA
            'maniana' => [
                1 => [ // M1
                    1 => ['Historia'],             // LUNES
                    2 => ['Leng. Ext. - Inglés'],  // MARTES
                    3 => ['Psicología'],           // MIÉRCOLES
                    4 => ['Ed. Art. - Música'],    // JUEVES
                    5 => ['Lengua y Literatura'],  // VIERNES
                ],
                2 => [ // M2
                    1 => ['Historia'],             // LUNES
                    2 => ['Leng. Ext. - Inglés'],  // MARTES
                    3 => ['Psicología'],           // MIÉRCOLES
                    4 => ['Ed. Art. - Música'],    // JUEVES
                    5 => ['Lengua y Literatura'],  // VIERNES
                ],
                3 => [ // M3
                    1 => ['Historia'],             // LUNES
                    2 => ['Leng. Ext. - Inglés'],  // MARTES
                    3 => ['Psicología'],           // MIÉRCOLES
                    4 => ['Química'],              // JUEVES
                    5 => ['Lengua y Literatura'],  // VIERNES
                ],
                5 => [ // M4
                    1 => ['Programación II'],     // LUNES
                    2 => ['Matemática'],           // MARTES
                    3 => ['Física'],               // MIÉRCOLES
                    4 => ['Química'],              // JUEVES
                    5 => ['Sist. de Información'],     // VIERNES
                ],
                6 => [ // M5
                    1 => ['Programación II'],     // LUNES
                    2 => ['Matemática'],           // MARTES
                    3 => ['Física'],               // MIÉRCOLES
                    4 => ['Química'],              // JUEVES
                    5 => ['Sist. de Información'],     // VIERNES
                ],
                8 => [ // M6
                    1 => ['Programación II'],      // LUNES
                    2 => ['Geografía'],            // MARTES
                    3 => ['Matemática'],           // MIÉRCOLES
                    4 => ['Programación II'],     // JUEVES
                    5 => ['Sist. de Información'],     // VIERNES
                ],
                9 => [ // M7
                    1 => ['Inf. Aplicada II'],      // LUNES
                    2 => ['Geografía'],            // MARTES
                    3 => ['Matemática'],           // MIÉRCOLES
                    4 => ['Programación II'],     // JUEVES
                    5 => ['Sist. de Información'],     // VIERNES
                ],
                10 => [ // M8
                    1 => ['Inf. Aplicada II'],      // LUNES
                    2 => ['Geografía'],            // MARTES
                    3 => ['Matemática'],           // MIÉRCOLES
                    4 => ['Programación II'],     // JUEVES
                    5 => [null],     // VIERNES
                ],
            ],

            // Si tuviera contraturno:
            'contraturno_maniana' => [
                1 => [ // M1
                    1 => ['Física'],  // LUNES
                    2 => ['Inf. Aplicada II'],  // MARTES
                    3 => ['Inf. Aplicada II'],  // MIÉRCOLES
                    4 => ['Sist. de Información'],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                2 => [ // M2
                    1 => ['Física'],  // LUNES
                    2 => ['Inf. Aplicada II'],  // MARTES
                    3 => ['Inf. Aplicada II'],  // MIÉRCOLES
                    4 => ['Sist. de Información'],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                3 => [ // M3
                    1 => [null],  // LUNES
                    2 => ['Inf. Aplicada II'],  // MARTES
                    3 => ['Inf. Aplicada II'],  // MIÉRCOLES
                    4 => [null],  // JUEVES
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