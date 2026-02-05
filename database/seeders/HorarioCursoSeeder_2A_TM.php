<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\HorarioBase;

class HorarioCursoSeeder_2A_TM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Curso
        $curso = Curso::where([
            'anio' => 2,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => ['Matemática', 'Ivana Ribodino'],                 // LUNES
                2 => ['Ciudadanía y Participación', 'Luciana Sosa Grión'], // MARTES
                3 => ['Historia', 'Verónica Gizzi'],                   // MIÉRCOLES
                4 => ['Biología', 'Marianela Pecorari'],               // JUEVES
                5 => ['Ed. Tecnológica', 'Vanesa Farías'],             // VIERNES
            ],
            2 => [ // M2
                1 => ['Matemática', 'Ivana Ribodino'],                 // LUNES
                2 => ['Ciudadanía y Participación', 'Luciana Sosa Grión'], // MARTES
                3 => ['Historia', 'Verónica Gizzi'],                   // MIÉRCOLES
                4 => ['Biología', 'Marianela Pecorari'],               // JUEVES
                5 => ['Ed. Tecnológica', 'Vanesa Farías'],             // VIERNES
            ],
            3 => [ // M3
                1 => ['Matemática', 'Ivana Ribodino'],                 // LUNES
                2 => ['Ciudadanía y Participación', 'Luciana Sosa Grión'], // MARTES
                3 => ['Historia', 'Verónica Gizzi'],                   // MIÉRCOLES
                4 => ['Biología', 'Marianela Pecorari'],               // JUEVES
                5 => ['Matemática', 'Ivana Ribodino'],                 // VIERNES
            ],
            5 => [ // M4
                1 => ['Química', 'Yanina Funes'],                // LUNES
                2 => ['Lengua y Lit.', 'Noelia Martinez Villegas'], // MARTES
                3 => ['Ed. Tecnológica', 'Vanesa Farías'],       // MIÉRCOLES
                4 => ['Inglés', 'Carina Chialva'],               // JUEVES
                5 => ['Matemática', 'Ivana Ribodino'],           // VIERNES
            ],
            6 => [ // M5
                1 => ['Química', 'Yanina Funes'],                // LUNES
                2 => ['Lengua y Lit.', 'Noelia Martinez Villegas'], // MARTES
                3 => ['Ed. Tecnológica', 'Vanesa Farías'],       // MIÉRCOLES
                4 => ['Inglés', 'Carina Chialva'],               // JUEVES
                5 => ['Inglés', 'Carina Chialva'],               // VIERNES
            ],
            8 => [ // M6
                1 => ['Química', 'Yanina Funes'],                    // LUNES
                2 => ['Lengua y Lit.', 'Noelia Martinez Villegas'],  // MARTES
                3 => ['Música', 'Franco Morano'],                    // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Noelia Martinez Villegas'],  // JUEVES
                5 => [null, null],                                  // VIERNES
            ],
            9 => [ // M7
                1 => ['Dib. Técnico', 'Nadia Llarrull'],              // LUNES
                2 => ['Historia', 'Verónica Gizzi'],                 // MARTES
                3 => ['Música', 'Franco Morano'],                    // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Noelia Martinez Villegas'],  // JUEVES
                5 => [null, null],                                  // VIERNES
            ],
            10 => [ // M8
                1 => ['Dib. Técnico', 'Nadia Llarrull'],  // LUNES
                2 => ['Historia', 'Verónica Gizzi'],     // MARTES
                3 => ['Música', 'Franco Morano'],        // MIÉRCOLES
                4 => [null, null],                       // JUEVES
                5 => [null, null],                       // VIERNES
            ],
        ];

        // 3 Persistencia
        foreach ($grilla as $orden => $dias) {
            foreach ($dias as $dia => [$materiaNombre, $docenteNombre]) {
                
                // EN CASO DE QUE NO TENGA MATERIA ASIGNADA, SALTEAR
                if ($materiaNombre === null) {
                    continue;
                }

                $bloque = BloqueHorario::where([
                    'turno' => 'maniana',
                    'orden' => $orden,
                ])->firstOrFail();

                $materia = Materia::where([
                    'nombre' => $materiaNombre,
                    'curso_id' => $curso->id,
                ])->firstOrFail();

                $docente = Docente::where('nombre', $docenteNombre)
                    ->firstOrFail();

                HorarioBase::updateOrCreate(
                    [
                        'curso_id' => $curso->id,
                        'dia_semana' => $dia,
                        'bloque_id' => $bloque->id,
                    ],
                    [
                        'materia_id' => $materia->id,
                        'docente_id' => $docente->id,
                    ]
                );
            }
        }
    }
}
