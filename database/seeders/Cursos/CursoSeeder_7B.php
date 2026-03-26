<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_7B extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 7,
            'division' => 'B',
            'turno' => 'tarde',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Inglés Técnico', 'horas_totales' => 4, 'docente' => 'Ana Bertezzolo', 'espacio' => 'Aula 1'], 
            ['nombre' => 'Emprendimientos', 'horas_totales' => 4, 'docente' => 'Laura Perez', 'espacio' => 'Aula 1'], 
            ['nombre' => 'Marco Jur. de las Act. Ind.', 'horas_totales' => 3, 'docente' => 'Ana Bertezzolo', 'espacio' => 'Aula 1'], 
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO TARDE
            'tarde' => [
                1 => [ // M1
                    1 => ['Estadística'],                    // LUNES
                    2 => ['Estadística'],                    // MARTES
                    3 => ['Sist. y Telecom.'],               // MIÉRCOLES
                    4 => ['Econ. y Gest. de la Prod. Ind.'], // JUEVES
                    5 => ['Sist. y Telecom.'],               // VIERNES
                ],
                2 => [ // M2
                    1 => ['Estadística'],                    // LUNES
                    2 => ['Estadística'],                    // MARTES
                    3 => ['Sist. y Telecom.'],               // MIÉRCOLES
                    4 => ['Econ. y Gest. de la Prod. Ind.'], // JUEVES
                    5 => ['Sist. y Telecom.'],               // VIERNES
                ],
                3 => [ // M3
                    1 => ['Recursos Humanos'],               // LUNES
                    2 => ['Leng. Ext. - Inglés'],            // MARTES
                    3 => ['Sist. y Telecom.'],               // MIÉRCOLES
                    4 => ['Econ. y Gest. de la Prod. Ind.'], // JUEVES
                    5 => ['Sist. y Telecom.'],               // VIERNES
                ],
                5 => [ // M4
                    1 => ['Recursos Humanos'],               // LUNES
                    2 => ['Leng. Ext. - Inglés'],            // MARTES
                    3 => ['An. Matemático'],                 // MIÉRCOLES
                    4 => ['Econ. y Gest. de la Prod. Ind.'], // JUEVES
                    5 => ['Filosofía'],                      // VIERNES
                ],
                6 => [ // M5
                    1 => ['Recursos Humanos'],               // LUNES
                    2 => ['Leng. Ext. - Inglés'],            // MARTES
                    3 => ['An. Matemático'],                 // MIÉRCOLES
                    4 => ['Ciud. y Política'],               // JUEVES
                    5 => ['Filosofía'],                      // VIERNES
                ],
                8 => [ // M6
                    1 => ['An. Matemático'],                 // LUNES
                    2 => [null],                             // MARTES
                    3 => ['Lengua y Literatura'],            // MIÉRCOLES
                    4 => ['Ciud. y Política'],               // JUEVES
                    5 => ['Filosofía'],                      // VIERNES
                ],
                9 => [ // M7
                    1 => ['An. Matemático'],                 // LUNES
                    2 => [null],                             // MARTES
                    3 => ['Lengua y Literatura'],            // MIÉRCOLES
                    4 => ['Ciud. y Política'],               // JUEVES
                    5 => [null],                             // VIERNES
                ],
                10 => [ // M8
                    1 => ['An. Matemático'],                 // LUNES
                    2 => [null],                             // MARTES
                    3 => ['Lengua y Literatura'],            // MIÉRCOLES
                    4 => [null],                             // JUEVES
                    5 => [null],                             // VIERNES
                ],
            ],

            // Si tuviera contraturno:
            'contraturno_tarde' => [
                1 => [ // M1
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                2 => [ // M2
                    1 => [null],  // LUNES
                    2 => [null],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                3 => [ // M3
                    1 => [null],  // LUNES
                    2 => ['Programación III'],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => [null],  // VIERNES
                ],
                5 => [ // M4
                    1 => [null],  // LUNES
                    2 => ['Programación III'],  // MARTES
                    3 => ['Base de datos I'],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => ['Programación III'],  // VIERNES
                ],
                6 => [ // M5
                    1 => [null],  // LUNES
                    2 => ['Programación III'],  // MARTES
                    3 => ['Base de datos I'],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => ['Programación III'],  // VIERNES
                ],
                8 => [ // M6
                    1 => [null],  // LUNES
                    2 => ['Base de datos I'],  // MARTES
                    3 => ['Base de datos I'],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => ['Programación III'],  // VIERNES
                ],
                9 => [ // M7
                    1 => [null],  // LUNES
                    2 => ['Base de datos I'],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => ['Ed. Art. - Teatro'],  // VIERNES
                ],
                10 => [ // M8
                    1 => [null],  // LUNES
                    2 => ['Base de datos I'],  // MARTES
                    3 => [null],  // MIÉRCOLES
                    4 => [null],  // JUEVES
                    5 => ['Ed. Art. - Teatro'],  // VIERNES
                ],
            ],
        ];
    }
}