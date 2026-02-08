<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\CursoMateria;
use App\Models\Docente;
use App\Models\HorarioBase;
use Exception;

class HorarioCursoSeeder_3B_CT extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Curso
        $curso = Curso::where([
            'anio' => 3,
            'division' => 'B',
            'turno' => 'tarde',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => [null, null],  // LUNES
                2 => [null, null],  // MARTES
                3 => [null, null],  // MIÉRCOLES
                4 => [null, null],  // JUEVES
                5 => [null, null],  // VIERNES
            ],
            2 => [ // M2
                1 => [null, null],  // LUNES
                2 => [null, null],  // MARTES
                3 => [null, null],  // MIÉRCOLES
                4 => [null, null],  // JUEVES
                5 => [null, null],  // VIERNES
            ],
            3 => [ // M3
                1 => [null, null],  // LUNES
                2 => [null, null],  // MARTES
                3 => [null, null],  // MIÉRCOLES
                4 => [null, null],  // JUEVES
                5 => [null, null],  // VIERNES
            ],
            5 => [ // M4
                1 => [null, null],  // LUNES
                2 => [null, null],  // MARTES
                3 => [null, null],  // MIÉRCOLES
                4 => [null, null],  // JUEVES
                5 => [null, null],  // VIERNES
            ],
            6 => [ // M5
                1 => [null, null],  // LUNES
                2 => [null, null],  // MARTES
                3 => [null, null],  // MIÉRCOLES
                4 => [null, null],  // JUEVES
                5 => [null, null],  // VIERNES
            ],
            8 => [ // M6
                1 => [null, null],  // LUNES
                2 => [null, null],  // MARTES
                3 => ['Física', 'Marianela Pecorari'],  // MIÉRCOLES
                4 => [null, null],  // JUEVES
                5 => [null, null],  // VIERNES
            ],
            9 => [ // M7
                1 => [null, null],  // LUNES
                2 => [null, null],  // MARTES
                3 => ['Física', 'Marianela Pecorari'],  // MIÉRCOLES
                4 => [null, null],  // JUEVES
                5 => [null, null],  // VIERNES
            ],
            10 => [ // M8
                1 => [null, null],  // LUNES
                2 => [null, null],  // MARTES
                3 => ['Física', 'Marianela Pecorari'],  // MIÉRCOLES
                4 => [null, null],  // JUEVES
                5 => [null, null],  // VIERNES
            ],
        ];

        // 3 Persistencia
        foreach ($grilla as $orden => $dias) {
            foreach ($dias as $dia => [$materiaNombre, $docenteNombre]) {

                // EN CASO DE QUE NO TENGA MATERIA ASIGNADA, SALTEAR
                if ($materiaNombre === null) {
                    continue;
                }

                // SELECCIÓN DE BLOQUE HORARIO
                $bloque = BloqueHorario::where([
                    'turno' => 'contraturno_tarde',
                    'orden' => $orden,
                ])->firstOrFail();

                // SELECCIÓN DE MATERIA
                $cursoMateria = CursoMateria::where('curso_id', $curso->id)
                    ->whereHas('materia', fn($q) =>
                        $q->where('nombre', $materiaNombre)
                    )->firstOrFail();

                $docente = Docente::where('nombre', $docenteNombre)->firstOrFail();

                // PERSISTENCIA EN LA BASE DE DATOS
                HorarioBase::updateOrCreate(
                    [
                        'curso_id' => $curso->id,
                        'dia_semana' => $dia,
                        'bloque_id' => $bloque->id
                    ],
                    [
                        'curso_materia_id' => $cursoMateria->id,
                        'docente_id' => $docente->id,
                    ]
                );
            }
        }

        // ¿VALIDACIÓN?
        $inconsistencias = CursoMateria::where('curso_id', $curso->id)
            ->with('materia')
            ->withCount('horarioBase')
            ->get()
            ->filter(fn ($cm) => $cm->horario_base_count != $cm->horas_totales);

        if ($inconsistencias->isNotEmpty()) {
            foreach ($inconsistencias as $cm) {
                echo "Error en {$cm->materia->nombre} "
                    . "(declaradas: {$cm->horas_totales}, "
                    . "cargadas: {$cm->horario_base_count})"
                    . PHP_EOL;
            }

            throw new Exception("Carga horaria inconsistente en {$curso->division}");
        }
    }
}
