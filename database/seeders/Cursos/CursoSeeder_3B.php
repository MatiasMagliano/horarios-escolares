<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_3B extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 3,
            'division' => 'B',
            'turno' => 'tarde',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 5, 'docente' => 'Carolina Rojas', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Matemática', 'horas_totales' => 5, 'docente' => 'Vanesa Farías', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Física', 'horas_totales' => 3, 'docente' => 'Marianela Pecorari', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Química', 'horas_totales' => 3, 'docente' => 'Natacha Marangón', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Geografía', 'horas_totales' => 4, 'docente' => 'Marianela Gatti', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Historia', 'horas_totales' => 4, 'docente' => 'Facundo Zurita', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Monica Rosso', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Ed. Art. - Música/Teatro', 'horas_totales' => 3, 'docente' => 'Bruno/Morano', 'espacio' => 'Aula 6'], 
            ['nombre' => 'Ed. Tecnológica', 'horas_totales' => 4, 'docente' => 'Patricia Solís', 'espacio' => 'Aula 6'], 
            ['nombre' => 'F.V.T', 'horas_totales' => 4, 'docente' => 'Yolanda Sucheyre', 'espacio' => 'Aula 6'],
            ['nombre' => 'Dib. Técnico', 'horas_totales' => 4, 'docente' => 'Florentina Arinci', 'espacio' => 'Aula 6']
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO TARDE
            'tarde' => [
                1 => [ // M1
                    1 => ['F.V.T'],                     // LUNES
                    2 => ['Historia'],                  // MARTES
                    3 => ['Matemática'],                // MIÉRCOLES
                    4 => ['Química'],                   // JUEVES
                    5 => ['F.V.T'],                     // VIERNES
                ],
                2 => [ // M2
                    1 => ['F.V.T'],                     // LUNES
                    2 => ['Historia'],                  // MARTES
                    3 => ['Matemática'],                // MIÉRCOLES
                    4 => ['Química'],                   // JUEVES
                    5 => ['F.V.T'],                     // VIERNES
                ],
                3 => [ // M3
                    1 => ['Historia'],                  // LUNES
                    2 => ['Leng. Ext. - Inglés'],       // MARTES
                    3 => ['Matemática'],                // MIÉRCOLES
                    4 => ['Química'],                   // JUEVES
                    5 => ['Dib. Técnico'],              // VIERNES
                ],
                5 => [ // M4
                    1 => ['Historia'],                  // LUNES
                    2 => ['Leng. Ext. - Inglés'],       // MARTES
                    3 => ['Geografía'],                 // MIÉRCOLES
                    4 => ['Dib. Técnico'],              // JUEVES
                    5 => ['Dib. Técnico'],              // VIERNES
                ],
                6 => [ // M5
                    1 => ['Ed. Tecnológica'],           // LUNES
                    2 => ['Leng. Ext. - Inglés'],       // MARTES
                    3 => ['Geografía'],                 // MIÉRCOLES
                    4 => ['Dib. Técnico'],              // JUEVES
                    5 => ['Matemática'],                // VIERNES
                ],
                8 => [ // M6
                    1 => ['Ed. Tecnológica'],           // LUNES
                    2 => ['Geografía'],                 // MARTES
                    3 => ['Ed. Art. - Música/Teatro'],  // MIÉRCOLES
                    4 => ['Lengua y Literatura'],       // JUEVES
                    5 => ['Matemática'],                // VIERNES
                ],
                9 => [ // M7
                    1 => ['Ed. Tecnológica'],           // LUNES
                    2 => ['Geografía'],                 // MARTES
                    3 => ['Ed. Art. - Música/Teatro'],  // MIÉRCOLES
                    4 => ['Lengua y Literatura'],       // JUEVES
                    5 => ['Lengua y Literatura'],       // VIERNES
                ],
                10 => [ // M8
                    1 => ['Ed. Tecnológica'],           // LUNES
                    2 => [null, null],                  // MARTES
                    3 => ['Ed. Art. - Música/Teatro'],  // MIÉRCOLES
                    4 => ['Lengua y Literatura'],       // JUEVES
                    5 => ['Lengua y Literatura'],       // VIERNES
                ],
            ],

            // Si tuviera contraturno:
            'contraturno_tarde' => [
                1 => [ // M1
                    1 => [null, null],  // LUNES
                    2 => [null, null],  // MARTES
                    3 => [null, null],  // MIÉRCOLES
                    4 => [null, null],  // JUEVES
                    5 => [null, null],  // VIERNES
                ],
                2 => [ // M2
                    1 => [null, null],  // LUNES
                    2 => [null, null],  // MARTES
                    3 => [null, null],  // MIÉRCOLES
                    4 => [null, null],  // JUEVES
                    5 => [null, null],  // VIERNES
                ],
                3 => [ // M3
                    1 => [null, null],  // LUNES
                    2 => [null, null],  // MARTES
                    3 => [null, null],  // MIÉRCOLES
                    4 => [null, null],  // JUEVES
                    5 => [null, null],  // VIERNES
                ],
                5 => [ // M4
                    1 => [null, null],  // LUNES
                    2 => [null, null],  // MARTES
                    3 => [null, null],  // MIÉRCOLES
                    4 => [null, null],  // JUEVES
                    5 => [null, null],  // VIERNES
                ],
                6 => [ // M5
                    1 => [null, null],  // LUNES
                    2 => [null, null],  // MARTES
                    3 => [null, null],  // MIÉRCOLES
                    4 => [null, null],  // JUEVES
                    5 => [null, null],  // VIERNES
                ],
                8 => [ // M6
                    1 => [null, null],  // LUNES
                    2 => [null, null],  // MARTES
                    3 => ['Física'],  // MIÉRCOLES
                    4 => [null, null],  // JUEVES
                    5 => [null, null],  // VIERNES
                ],
                9 => [ // M7
                    1 => [null, null],  // LUNES
                    2 => [null, null],  // MARTES
                    3 => ['Física'],  // MIÉRCOLES
                    4 => [null, null],  // JUEVES
                    5 => [null, null],  // VIERNES
                ],
                10 => [ // M8
                    1 => [null, null],  // LUNES
                    2 => [null, null],  // MARTES
                    3 => ['Física'],  // MIÉRCOLES
                    4 => [null, null],  // JUEVES
                    5 => [null, null],  // VIERNES
                ],
            ],
        ];
    }
}
