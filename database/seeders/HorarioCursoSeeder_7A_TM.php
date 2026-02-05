<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\HorarioBase;

class HorarioCursoSeeder_7A_TM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Curso
        $curso = Curso::where([
            'anio' => 7,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => ['Telecomunicaciones II', 'Yanina Sanchez'],  // LUNES
                2 => ['Emprendimientos', 'Laura Perez'],           // MARTES
                3 => [null, null],                                 // MIÉRCOLES
                4 => ['Inglés técnico', 'Carina Chialva'],         // JUEVES
                5 => ['Elect. Industrial II', 'Ian Concepción'],   // VIERNES
            ],
            2 => [ // M2
                1 => ['Telecomunicaciones II', 'Yanina Sanchez'],  // LUNES
                2 => ['Emprendimientos', 'Laura Perez'],           // MARTES
                3 => ['Proy. Integrador', 'Ian Concepción'],       // MIÉRCOLES
                4 => ['Inglés técnico', 'Carina Chialva'],         // JUEVES
                5 => ['Elect. Industrial II', 'Ian Concepción']    // VIERNES
            ],
            3 => [ // M3
                1 => ['Telecomunicaciones II', 'Yanina Sanchez'],  // LUNES
                2 => ['Proy. Integrador', 'Ian Concepción'],       // MARTES
                3 => ['Proy. Integrador', 'Ian Concepción'],       // MIÉRCOLES
                4 => ['Inglés técnico', 'Carina Chialva'],         // JUEVES
                5 => ['Elect. Industrial II', 'Ian Concepción']    // VIERNES
            ],
            5 => [ // M4
                1 => [null, null],                                 // LUNES
                2 => ['Proy. Integrador', 'Ian Concepción'],       // MARTES
                3 => ['Telecomunicaciones II', 'Yanina Sanchez'],  // MIÉRCOLES
                4 => ['Elect. Industrial II', 'Ian Concepción'],   // JUEVES
                5 => ['Elect. Digital IV', 'Ian Concepción'],      // VIERNES
            ],
            6 => [ // M5
                1 => [null, null],                                 // LUNES
                2 => ['Proy. Integrador', 'Ian Concepción'],       // MARTES
                3 => ['Telecomunicaciones II', 'Yanina Sanchez'],  // MIÉRCOLES
                4 => ['Elect. Industrial II', 'Ian Concepción'],   // JUEVES
                5 => ['Elect. Digital IV', 'Ian Concepción'],      // VIERNES
            ],
            8 => [ // M6
                1 => [null, null],                                         // LUNES
                2 => ['Proy. Integrador', 'Ian Concepción'],               // MARTES
                3 => ['M. Jurídico de las Act. Indust.', 'Greca Colazo'],  // MIÉRCOLES
                4 => ['Higiene y Seguridad', 'Domingo Greggio'],           // JUEVES
                5 => ['Elect. Digital IV', 'Ian Concepción'],              // VIERNES
            ],
            9 => [ // M7
                1 => [null, null],                                         // LUNES
                2 => ['Emprendimientos', 'Laura Perez'],                   // MARTES
                3 => ['M. Jurídico de las Act. Indust.', 'Greca Colazo'],  // MIÉRCOLES
                4 => ['Higiene y Seguridad', 'Domingo Greggio'],           // JUEVES
                5 => ['Elect. Digital IV', 'Ian Concepción'],              // VIERNES
            ],
            10 => [ // M8
                1 => [null, null],                                         // LUNES
                2 => ['Emprendimientos', 'Laura Perez'],                   // MARTES
                3 => ['M. Jurídico de las Act. Indust.', 'Greca Colazo'],  // MIÉRCOLES
                4 => ['Higiene y Seguridad', 'Domingo Greggio'],           // JUEVES
                5 => ['Elect. Digital IV', 'Ian Concepción'],              // VIERNES
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
