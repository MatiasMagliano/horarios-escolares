<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\BloqueHorario;
use App\Models\HorarioBase;

class HorarioCursoSeeder_4A_CT extends Seeder
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
                1 => [null, null],                          // LUNES
                2 => ['Biología', 'Yanina Lerda'],          // MARTES
                3 => ['Electrotecnia I', 'Pablo Bulacio'],    // MIÉRCOLES
                4 => [null, null],                          // JUEVES
                5 => ['Elect. Analógica I', 'Pablo Bulacio'], // VIERNES
            ],
            2 => [ // M2
                1 => [null, null],                          // LUNES
                2 => ['Biología', 'Yanina Lerda'],          // MARTES
                3 => ['Electrotecnia I', 'Pablo Bulacio'],    // MIÉRCOLES
                4 => [null, null],                          // JUEVES
                5 => ['Elect. Analógica I', 'Pablo Bulacio'], // VIERNES
            ],
            3 => [ // M3
                1 => [null, null],                          // LUNES
                2 => [null, null],                          // MARTES
                3 => ['Electrotecnia I', 'Pablo Bulacio'],    // MIÉRCOLES
                4 => [null, null],                          // JUEVES
                5 => ['Elect. Analógica I', 'Pablo Bulacio'], // VIERNES
            ],
            5 => [ // M4
                1 => [null, null],                          // LUNES
                2 => [null, null],                          // MARTES
                3 => [null, null],                          // MIÉRCOLES
                4 => [null, null],                          // JUEVES
                5 => [null, null],                          // VIERNES
            ],
            6 => [ // M5
                1 => [null, null],                          // LUNES
                2 => [null, null],                          // MARTES
                3 => [null, null],                          // MIÉRCOLES
                4 => [null, null],                          // JUEVES
                5 => [null, null],                          // VIERNES
            ],
            8 => [ // M6
                1 => [null, null],                          // LUNES
                2 => [null, null],                          // MARTES
                3 => [null, null],                          // MIÉRCOLES
                4 => [null, null],                          // JUEVES
                5 => [null, null],                          // VIERNES
            ],
            9 => [ // M7
                1 => [null, null],                          // LUNES
                2 => [null, null],                          // MARTES
                3 => [null, null],                          // MIÉRCOLES
                4 => [null, null],                          // JUEVES
                5 => [null, null],                          // VIERNES
            ],
            10 => [ // M8
                1 => [null, null],                          // LUNES
                2 => [null, null],                          // MARTES
                3 => [null, null],                          // MIÉRCOLES
                4 => [null, null],                          // JUEVES
                5 => [null, null],                          // VIERNES
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
