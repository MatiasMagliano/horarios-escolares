<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\HorarioBase;

class HorarioCursoSeeder_3A_TM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Curso
        $curso = Curso::where([
            'anio' => 3,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => ['Matemática', 'Martín Andrada'], // LUNES
                2 => [null, null],                     // MARTES
                3 => ['Química', 'Yanina Funes'],      // MIÉRCOLES
                4 => ['Geografía', 'Belén Ramos'],     // JUEVES
                5 => ['Geografía', 'Belén Ramos'],     // VIERNES
            ],
            2 => [ // M2
                1 => ['Matemática', 'Martín Andrada'], // LUNES
                2 => ['Matemática', 'Martín Andrada'], // MARTES
                3 => ['Química', 'Yanina Funes'],      // MIÉRCOLES
                4 => ['Geografía', 'Belén Ramos'],     // JUEVES
                5 => ['Geografía', 'Belén Ramos'],     // VIERNES
            ],
            3 => [ // M3
                1 => ['Matemática', 'Martín Andrada'],        // LUNES
                2 => ['Matemática', 'Martín Andrada'],        // MARTES
                3 => ['Química', 'Yanina Funes'],             // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Marisa Morales'],     // JUEVES
                5 => ['Física', 'Natacha Marangón'],           // VIERNES
            ],
            5 => [ // M4
                1 => ['Ed. Tecnológica', 'Brenda Quiroga'],   // LUNES
                2 => ['F.V.T', 'Yolanda Sucheyre'],           // MARTES
                3 => ['Ed. Tecnológica', 'Brenda Quiroga'],   // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Marisa Morales'],     // JUEVES
                5 => ['F.V.T', 'Yolanda Sucheyre'],           // VIERNES
            ],
            6 => [ // M5
                1 => ['Ed. Tecnológica', 'Brenda Quiroga'],   // LUNES
                2 => ['F.V.T', 'Yolanda Sucheyre'],           // MARTES
                3 => ['Ed. Tecnológica', 'Brenda Quiroga'],   // MIÉRCOLES
                4 => ['Física', 'Natacha Marangón'],     // JUEVES
                5 => ['F.V.T', 'Yolanda Sucheyre'],           // VIERNES
            ],
            8 => [ // M6
                1 => ['Dib. Técnico', 'Ivana Ribodino'],      // LUNES
                2 => ['Artística/Música', 'Bruno/Morano'],       // MARTES
                3 => ['Lengua y Lit.', 'Marisa Morales'],     // MIÉRCOLES
                4 => ['Física', 'Natacha Marangón'],          // JUEVES
                5 => ['Inglés', 'Carina Chialva'],            // VIERNES
            ],
            9 => [ // M7
                1 => ['Dib. Técnico', 'Ivana Ribodino'],      // LUNES
                2 => ['Artística/Música', 'Bruno/Morano'],       // MARTES
                3 => ['Lengua y Lit.', 'Marisa Morales'],     // MIÉRCOLES
                4 => ['Historia', 'Erick Zaccagnini'],        // JUEVES
                5 => ['Inglés', 'Carina Chialva'],            // VIERNES
            ],
            10 => [ // M8
                1 => ['Dib. Técnico', 'Ivana Ribodino'],      // LUNES
                2 => ['Artística/Música', 'Bruno/Morano'],       // MARTES
                3 => ['Lengua y Lit.', 'Marisa Morales'],     // MIÉRCOLES
                4 => ['Historia', 'Erick Zaccagnini'],        // JUEVES
                5 => ['Inglés', 'Carina Chialva']             // VIERNES
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
