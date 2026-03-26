<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_6B extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 6,
            'division' => 'B',
            'turno' => 'tarde',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 3, 'docente' => 'Noelia Martinez Villegas', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Filosofía', 'horas_totales' => 3, 'docente' => 'Yolanda Sucheyre', 'espacio' => 'Aula 7'], 
            ['nombre' => 'An. Matemático', 'horas_totales' => 5, 'docente' => 'Claudia Farías', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Ciud. y Política', 'horas_totales' => 3, 'docente' => 'Erick Zaccagnini', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Econ. y Gest. de la Prod. Ind.', 'horas_totales' => 4, 'docente' => 'Laura Perez', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Estadística', 'horas_totales' => 4, 'docente' => 'Carla Rava', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Recursos Humanos', 'horas_totales' => 3, 'docente' => 'Yolanda Sucheyre', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Ana Bertezzolo', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Ed. Art. - Teatro', 'horas_totales' => 2, 'docente' => 'Kajna Gamin', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Base de datos I', 'horas_totales' => 6, 'docente' => 'Andrea Chiappori', 'espacio' => 'Laboratorio de Informática'], 
            ['nombre' => 'Sist. y Telecom.', 'horas_totales' => 6, 'docente' => 'Priscila Calizaya', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Programación III', 'horas_totales' => 6, 'docente' => 'Matías Magliano', 'espacio' => 'Laboratorio de Informática'],
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