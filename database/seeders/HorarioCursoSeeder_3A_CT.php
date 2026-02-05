<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\HorarioBase;

class HorarioCursoSeeder_3A_CT extends Seeder
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

        // 2 grilla de horarios
        $grilla = [
            1 => [ // M1
                1 => ['Dib. Técnico', 'Ivana Ribodino'], // Lunes
                2 => [null, null],                    // Martes
                3 => ['Historia', 'Erick Zaccagnini'], // Miércoles
                4 => [null, null],                    // Jueves
                5 => [null, null],                    // Viernes
            ],
            2 => [ // M2
                1 => [null, null],                    // Lunes
                2 => [null, null],                    // Martes
                3 => ['Historia', 'Erick Zaccagnini'], // Miércoles
                4 => [null, null],                    // Jueves
                5 => [null, null],                    // Viernes
            ],
            3 => [ // M3
                1 => [null, null],      // Lunes
                2 => [null, null],      // Martes
                3 => [null, null], // Miércoles
                4 => [null, null],                        // Jueves
                5 => [null, null],                        // Viernes
            ],
            5 => [ // M4
                1 => [null, null],   // Lunes
                2 => [null, null],   // Martes
                3 => [null, null],                      // Miércoles
                4 => [null, null],                      // Jueves
                5 => [null, null],                      // Viernes
            ],
            6 => [ // M5
                1 => [null, null],   // Lunes
                2 => [null, null],   // Martes
                3 => [null, null],                      // Miércoles
                4 => [null, null],                      // Jueves
                5 => [null, null],                      // Viernes
            ],
            8 => [ // M6
                1 => [null, null],   // Lunes
                2 => [null, null],   // Martes
                3 => [null, null],                      // Miércoles
                4 => [null, null],                      // Jueves
                5 => [null, null],                      // Viernes
            ],
            9 => [ // M7
                1 => [null, null],   // Lunes
                2 => [null, null],   // Martes
                3 => [null, null],                      // Miércoles
                4 => [null, null],                      // Jueves
                5 => [null, null],                      // Viernes
            ],
            10 => [ // M8
                1 => [null, null],   // Lunes
                2 => [null, null],   // Martes
                3 => [null, null],                      // Miércoles
                4 => [null, null],                      // Jueves
                5 => [null, null],                      // Viernes
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
