<?php

namespace Database\Seeders\Cursos;

use Database\Seeders\BaseCursoSeeder;

class CursoSeeder_6A extends BaseCursoSeeder
{
    protected function cursoData(): array
    {
        return [
            'anio' => 6,
            'division' => 'A',
            'turno' => 'maniana',
        ];
    }

    protected function materias(): array
    {
        return [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 3, 'docente' => 'Noelia Martinez Villegas', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Filosofía', 'horas_totales' => 3, 'docente' => 'Yolanda Sucheyre', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Ciud. y Política', 'horas_totales' => 3, 'docente' => 'Greca Colazo', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3, 'docente' => 'Sandra Occhipinti', 'espacio' => 'Aula 7'],
            ['nombre' => 'Ed. Art. - Teatro', 'horas_totales' => 2, 'docente' => 'Laura Díaz', 'espacio' => 'Aula 7'], 
            ['nombre' => 'An. Matemático', 'horas_totales' => 5, 'docente' => 'Claudia Farías', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Econ. y Gest. de la Prod. Ind.', 'horas_totales' => 4, 'docente' => 'Laura Perez', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Elect. Digital III', 'horas_totales' => 6, 'docente' => 'Ian Concepción', 'espacio' => 'Aula 7'], 
            ['nombre' => 'Elect. Industrial I', 'horas_totales' => 6, 'docente' => 'Ian Concepción', 'espacio' => 'Aula 7'],
            ['nombre' => 'Telecomunicaciones I', 'horas_totales' => 6, 'docente' => 'Martín Franch', 'espacio' => 'Laboratorio de Electrónica'],
            ['nombre' => 'Inst. Industriales', 'horas_totales' => 7, 'docente' => 'Nancy Scipioni', 'espacio' => 'Aula 7'], 
        ];
    }

    protected function grillas(): array
    {
        return [

            // TURNO MAÑANA
            'maniana' => [
                1 => [ // M1
                    1 => ['Elect. Digital III'],               // LUNES
                    2 => ['Leng. Ext. - Inglés'],              // MARTES
                    3 => ['Telecomunicaciones I'],             // MIÉRCOLES
                    4 => ['Telecomunicaciones I'],             // JUEVES
                    5 => ['Filosofía'],                        // VIERNES
                ],
                2 => [ // M2
                    1 => ['Elect. Digital III'],               // LUNES
                    2 => ['Leng. Ext. - Inglés'],              // MARTES
                    3 => ['Telecomunicaciones I'],             // MIÉRCOLES
                    4 => ['Telecomunicaciones I'],             // JUEVES
                    5 => ['Filosofía'],                        // VIERNES
                ],
                3 => [ // M3
                    1 => ['Elect. Digital III'],               // LUNES
                    2 => ['Econ. y Gest. de la Prod. Ind.'],   // MARTES
                    3 => ['Telecomunicaciones I'],             // MIÉRCOLES
                    4 => ['Telecomunicaciones I'],             // JUEVES
                    5 => ['Filosofía'],                        // VIERNES
                ],
                5 => [ // M4
                    1 => ['Elect. Industrial I'],              // LUNES
                    2 => ['Econ. y Gest. de la Prod. Ind.'],   // MARTES
                    3 => ['An. Matemático'],                   // MIÉRCOLES
                    4 => ['An. Matemático'],                   // JUEVES
                    5 => ['Elect. Industrial I'],              // VIERNES
                ],
                6 => [ // M5
                    1 => ['Elect. Industrial I'],              // LUNES
                    2 => ['Econ. y Gest. de la Prod. Ind.'],   // MARTES
                    3 => ['An. Matemático'],                   // MIÉRCOLES
                    4 => ['An. Matemático'],                   // JUEVES
                    5 => ['Elect. Industrial I'],              // VIERNES
                ],
                8 => [ // M6
                    1 => ['Ciud. y Política'],                 // LUNES
                    2 => ['Econ. y Gest. de la Prod. Ind.'],   // MARTES
                    3 => ['Lengua y Literatura'],              // MIÉRCOLES
                    4 => ['An. Matemático'],                   // JUEVES
                    5 => ['Elect. Industrial I'],              // VIERNES
                ],
                9 => [ // M7
                    1 => ['Ciud. y Política'],                 // LUNES
                    2 => ['Leng. Ext. - Inglés'],              // MARTES
                    3 => ['Lengua y Literatura'],              // MIÉRCOLES
                    4 => ['Ed. Art. - Teatro'],                // JUEVES
                    5 => ['Elect. Industrial I'],              // VIERNES
                ],
                10 => [ // M8
                    1 => ['Ciud. y Política'],                 // LUNES
                    2 => ['Elect. Digital III'],               // MARTES
                    3 => ['Lengua y Literatura'],              // MIÉRCOLES
                    4 => ['Ed. Art. - Teatro'],                // JUEVES
                    5 => [null],                               // VIERNES
                ],
            ],

            // Si tuviera contraturno:
            'contraturno_maniana' => [
                1 => [ // M1
                    1 => ['Inst. Industriales'],               // LUNES
                    2 => ['Elect. Digital III'],               // MARTES
                    3 => [null],                               // MIÉRCOLES
                    4 => ['Inst. Industriales'],               // JUEVES
                    5 => [null],                               // VIERNES
                ],
                2 => [ // M2
                    1 => [null],                               // LUNES
                    2 => ['Elect. Digital III'],               // MARTES
                    3 => [null],                               // MIÉRCOLES
                    4 => ['Inst. Industriales'],               // JUEVES
                    5 => [null],                               // VIERNES
                ],
                3 => [ // M3
                    1 => ['Inst. Industriales'],               // LUNES
                    2 => [null],                               // MARTES
                    3 => [null],                               // MIÉRCOLES
                    4 => ['Inst. Industriales'],               // JUEVES
                    5 => [null],                               // VIERNES
                ],
                5 => [ // M4
                    1 => [null],                               // LUNES
                    2 => [null],                               // MARTES
                    3 => [null],                               // MIÉRCOLES
                    4 => ['Inst. Industriales'],               // JUEVES
                    5 => [null],                               // VIERNES
                ],
                6 => [ // M5
                    1 => [null],                               // LUNES
                    2 => [null],                               // MARTES
                    3 => [null],                               // MIÉRCOLES
                    4 => ['Inst. Industriales'],               // JUEVES
                    5 => [null],                               // VIERNES
                ],
                8 => [ // M6
                    1 => [null],                               // LUNES
                    2 => [null],                               // MARTES
                    3 => [null],                               // MIÉRCOLES
                    4 => [null],                               // JUEVES
                    5 => [null],                               // VIERNES
                ],
                9 => [ // M7
                    1 => [null],                               // LUNES
                    2 => [null],                               // MARTES
                    3 => [null],                               // MIÉRCOLES
                    4 => [null],                               // JUEVES
                    5 => [null],                               // VIERNES
                ],
                10 => [ // M8
                    1 => [null],                               // LUNES
                    2 => [null],                               // MARTES
                    3 => [null],                               // MIÉRCOLES
                    4 => [null],                               // JUEVES
                    5 => [null],                               // VIERNES
                ],
            ],
        ];
    }
}
