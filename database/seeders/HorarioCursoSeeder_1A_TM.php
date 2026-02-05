<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\HorarioBase;

class HorarioCursoSeeder_1A_TM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2 Curso
        $curso = Curso::where([
            'anio' => 1,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => ['Geografía', 'Marcos Morales'], // LUNES
                2 => [null, null], // MARTES
                3 => ['Biología', 'Soledad González'], // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Noelia Martinez Villegas'], // JUEVES
                5 => [null, null], // VIERNES
            ],
            2 => [ // M2
                1 => ['Geografía', 'Marcos Morales'], // LUNES
                2 => ['Ed. Tecnológica', 'Sofía Rodriguez'], // MARTES
                3 => ['Biología', 'Soledad Gonzalez'],  // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Noelia Martinez Villegas'], // JUEVES
                5 => [null, null], // VIERNES
            ],
            3 => [ // M3
                1 => ['Ed. Artística', 'M. Elena Mansilla'],          // LUNES
                2 => ['Ed. Tecnológica', 'Sofía Rodriguez'],         // MARTES
                3 => ['Biología', 'Soledad Gonzalez'],               // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Noelia Martinez Villegas'],  // JUEVES
                5 => ['Ed. Tecnológica', 'Sofía Rodriguez'],         // VIERNES
            ],
            5 => [ // M4
                1 => ['Ed. Artística', 'M. Elena Mansilla'],   // LUNES
                2 => ['Inglés', 'Claudia Ramadán'],            // MARTES
                3 => ['Física', 'Yanina Funes'],               // MIÉRCOLES
                4 => ['Geografía', 'Marcos Morales'],          // JUEVES
                5 => ['Ed. Tecnológica', 'Sofía Rodriguez'],  // VIERNES
            ],
            6 => [ // M5
                1 => ['Ed. Artística', 'M. Elena Mansilla'],   // LUNES
                2 => ['Inglés', 'Claudia Ramadán'],            // MARTES
                3 => ['Física', 'Yanina Funes'],               // MIÉRCOLES
                4 => ['Geografía', 'Marcos Morales'],          // JUEVES
                5 => ['Matemática', 'Ivana Ribodino'],         // VIERNES
            ],
            8 => [ // M6
                1 => ['Ciudadanía y Participación', 'Ariel Ardiles'], // LUNES
                2 => ['Inglés', 'Claudia Ramadán'],                    // MARTES
                3 => ['Física', 'Yanina Funes'],                       // MIÉRCOLES
                4 => ['Geografía', 'Marcos Morales'],                  // JUEVES
                5 => ['Matemática', 'Ivana Ribodino'],                 // VIERNES
            ],
            9 => [ // M7
                1 => ['Ciudadanía y Participación', 'Ariel Ardiles'],   // LUNES
                2 => ['Lengua y Lit.', 'Noelia Martinez Villegas'],     // MARTES
                3 => ['Matemática', 'Ivana Ribodino'],                  // MIÉRCOLES
                4 => ['Dib. Técnico', 'Nadia Llarrull'],                // JUEVES
                5 => ['Matemática', 'Ivana Ribodino'],                  // VIERNES
            ],
            10 => [ // M8
                1 => ['Ciudadanía y Participación', 'Ariel Ardiles'], // LUNES
                2 => ['Lengua y Lit.', 'Noelia Martinez Villegas'],   // MARTES
                3 => ['Matemática', 'Ivana Ribodino'],                // MIÉRCOLES
                4 => ['Dib. Técnico', 'Nadia Llarrull'],              // JUEVES
                5 => [null, null],                                    // VIERNES
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
                        'bloque_id' => $bloque->id
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
