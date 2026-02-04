<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\HorarioBase;
use App\Models\BloqueHorario;

class HorarioCursoSeeder_5B_CT extends Seeder
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
                1 => ['Física', 'Lorena Vera'], // LUNES
                2 => ['Sist. de Información', 'Priscila Calizaya'], // MARTES
                3 => [null, null], // MIÉRCOLES
                4 => [null, null], // JUEVES
                5 => ['Inf. Aplicada II', 'Andrea Chiappori'], // VIERNES
            ],
            2 => [ // M2
                1 => ['Física', 'Lorena Vera'], // LUNES
                2 => ['Sist. de Información', 'Priscila Calizaya'], // MARTES
                3 => [null, null], // MIÉRCOLES
                4 => [null, null], // JUEVES
                5 => ['Sist. de Información', 'Priscila Calizaya'], // VIERNES
            ],
            3 => [ // M3
                1 => [null, null], // LUNES
                2 => ['Sist. de Información', 'Priscila Calizaya'], // MARTES
                3 => [null, null], // MIÉRCOLES
                4 => [null, null], // JUEVES
                5 => ['Sist. de Información', 'Priscila Calizaya'], // VIERNES
            ],
            4 => [ // M4
                1 => [null, null], // LUNES
                2 => ['Sist. de Información', 'Priscila Calizaya'], // MARTES
                3 => [null, null], // MIÉRCOLES
                4 => [null, null], // JUEVES
                5 => [null, null], // VIERNES
            ],
            5 => [ // M5
                1 => [null, null], // LUNES
                2 => [null, null], // MARTES
                3 => [null, null], // MIÉRCOLES
                4 => [null, null], // JUEVES
                5 => [null, null], // VIERNES
            ],
            6 => [ // M6
                1 => [null, null], // LUNES
                2 => [null, null], // MARTES
                3 => [null, null], // MIÉRCOLES
                4 => [null, null], // JUEVES
                5 => [null, null], // VIERNES
            ],
            7 => [ // M7
                1 => [null, null], // LUNES
                2 => [null, null], // MARTES
                3 => [null, null], // MIÉRCOLES
                4 => [null, null], // JUEVES
                5 => [null, null], // VIERNES
            ],
            8 => [ // M8
                1 => [null, null], // LUNES
                2 => [null, null], // MARTES
                3 => [null, null], // MIÉRCOLES
                4 => [null, null], // JUEVES
                5 => [null, null], // VIERNES
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
                    'turno' => 'contraturno_maniana',
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
