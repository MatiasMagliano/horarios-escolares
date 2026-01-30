<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\BloqueHorario;
use App\Models\HorarioBase;

class HorarioCursoSeeder_4A_TM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Curso
        $curso = Curso::where([
            'anio' => 4,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => ['Biología', 'Yanina Lerda'],                  // LUNES
                2 => ['Lengua y Lit.', 'Noelia Martinez Villegas'], // MARTES
                3 => ['Inf. Electrónica', 'Andrea Chiappori'],      // MIÉRCOLES
                4 => ['Química', 'Natacha Marangón'],               // JUEVES
                5 => ['Inglés', 'Carina Chialva'],                  // VIERNES
            ],
            2 => [ // M2
                1 => ['Biología', 'Yanina Lerda'],                  // LUNES
                2 => ['Lengua y Lit.', 'Noelia Martinez Villegas'], // MARTES
                3 => ['Inf. Electrónica', 'Andrea Chiappori'],      // MIÉRCOLES
                4 => ['Química', 'Natacha Marangón'],               // JUEVES
                5 => ['Inglés', 'Carina Chialva'],                  // VIERNES
            ],
            3 => [ // M3
                1 => ['Física', 'Natacha Marangón'],                // LUNES
                2 => ['Lengua y Lit.', 'Noelia Martinez Villegas'], // MARTES
                3 => ['Inf. Electrónica', 'Andrea Chiappori'],      // MIÉRCOLES
                4 => ['Química', 'Natacha Marangón'],               // JUEVES
                5 => ['Inglés', 'Carina Chialva'],                  // VIERNES
            ],
            4 => [ // M4
                1 => ['Matemática', 'Ivana Ribodino'],               // LUNES
                2 => ['Ed. Artística', 'Sonia Bruno'],               // MARTES
                3 => ['Matemática', 'Ivana Ribodino'],               // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Noelia Martinez Villegas'],  // JUEVES
                5 => ['Física', 'Natacha Marangón'],                 // VIERNES
            ],
            5 => [ // M5
                1 => ['Matemática', 'Ivana Ribodino'],               // LUNES
                2 => ['Ed. Artística', 'Sonia Bruno'],               // MARTES
                3 => ['Matemática', 'Ivana Ribodino'],               // MIÉRCOLES
                4 => ['Elect. Digital I', 'Pablo Bulacio'],          // JUEVES
                5 => ['Física', 'Natacha Marangón'],                 // VIERNES
            ],
            6 => [ // M6
                1 => ['Geografía', 'Marianela Gatti'],               // LUNES
                2 => ['Historia', 'Facundo Zurita'],                 // MARTES
                3 => ['Matemática', 'Ivana Ribodino'],               // MIÉRCOLES
                4 => ['Elect. Digital I', 'Pablo Bulacio'],          // JUEVES
                5 => ['Física', 'Natacha Marangón'],                 // VIERNES
            ],
            7 => [ // M7
                1 => ['Geografía', 'Marianela Gatti'],               // LUNES
                2 => ['Historia', 'Facundo Zurita'],                 // MARTES
                3 => ['Electrotecnia', 'Pablo Bulacio'],             // MIÉRCOLES
                4 => ['Elect. Analógica', 'Pablo Bulacio'],          // JUEVES
                5 => ['Elect. Digital I', 'Pablo Bulacio'],          // VIERNES
            ],
            8 => [ // M8
                1 => ['Geografía', 'Marianela Gatti'],               // LUNES
                2 => ['Historia', 'Facundo Zurita'],                 // MARTES
                3 => ['Electrotecnia', 'Pablo Bulacio'],             // MIÉRCOLES
                4 => ['Elect. Analógica', 'Pablo Bulacio'],          // JUEVES
                5 => ['Elect. Digital I', 'Pablo Bulacio'],          // VIERNES
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
