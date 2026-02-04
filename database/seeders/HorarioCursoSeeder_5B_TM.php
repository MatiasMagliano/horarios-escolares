<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\HorarioBase;
use App\Models\BloqueHorario;

class HorarioCursoSeeder_5B_TM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Curso
        $curso = Curso::where([
            'anio' => 5,
            'division' => 'B',
            'turno' => 'maniana',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => ['Historia', 'Erick Zaccagnini'], // LUNES
                2 => ['Inglés', 'Carina Chialva'], // MARTES
                3 => ['Psicología', 'Matías Magliano'], // MIÉRCOLES
                4 => ['Música', 'Franco Morano'], // JUEVES
                5 => ['Lengua y Lit.', 'Adam Luna'], // VIERNES
            ],
            2 => [ // M2
                1 => ['Historia', 'Erick Zaccagnini'], // LUNES
                2 => ['Inglés', 'Carina Chialva'], // MARTES
                3 => ['Psicología', 'Matías Magliano'], // MIÉRCOLES
                4 => ['Música', 'Franco Morano'], // JUEVES
                5 => ['Lengua y Lit.', 'Adam Luna'], // VIERNES
            ],
            3 => [ // M3
                1 => ['Historia', 'Erick Zaccagnini'], // LUNES
                2 => ['Inglés', 'Carina Chialva'], // MARTES
                3 => ['Psicología', 'Matías Magliano'], // MIÉRCOLES
                4 => ['Química', 'Carolina Molina'], // JUEVES
                5 => ['Lengua y Lit.', 'Adam Luna'], // VIERNES
            ],
            4 => [ // M4
                1 => ['Inf. Aplicada II', 'Andrea Chiappori'], // LUNES
                2 => ['Matemática', 'Lorena Vera'], // MARTES
                3 => ['Física', 'Lorena Vera'], // MIÉRCOLES
                4 => ['Química', 'Carolina Molina'], // JUEVES
                5 => ['Inf. Aplicada II', 'Andrea Chiappori'], // VIERNES
            ],
            5 => [ // M5
                1 => ['Inf. Aplicada II', 'Andrea Chiappori'], // LUNES
                2 => ['Matemática', 'Lorena Vera'], // MARTES
                3 => ['Física', 'Lorena Vera'], // MIÉRCOLES
                4 => ['Química', 'Carolina Molina'], // JUEVES
                5 => ['Inf. Aplicada II', 'Andrea Chiappori'], // VIERNES
            ],
            6 => [ // M6
                1 => ['Programación II', 'Andrea Chiappori'], // LUNES
                2 => ['Geografía', 'Marianela Gatti'], // MARTES
                3 => ['Matemática', 'Lorena Vera'], // MIÉRCOLES
                4 => ['Inf. Aplicada II', 'Andrea Chiappori'], // JUEVES
                5 => ['Programación II', 'Andrea Chiappori'], // VIERNES
            ],
            7 => [ // M7
                1 => ['Programación II', 'Andrea Chiappori'], // LUNES
                2 => ['Geografía', 'Marianela Gatti'], // MARTES
                3 => ['Matemática', 'Lorena Vera'], // MIÉRCOLES
                4 => ['Inf. Aplicada II', 'Andrea Chiappori'], // JUEVES
                5 => ['Programación II', 'Andrea Chiappori'], // VIERNES
            ],
            8 => [ // M8
                1 => ['Programación II', 'Andrea Chiappori'], // LUNES
                2 => ['Geografía', 'Marianela Gatti'], // MARTES
                3 => ['Matemática', 'Lorena Vera'], // MIÉRCOLES
                4 => ['Inf. Aplicada II', 'Andrea Chiappori'], // JUEVES
                5 => ['Programación II', 'Andrea Chiappori'], // VIERNES
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
