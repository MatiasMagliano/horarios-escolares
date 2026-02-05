<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\HorarioBase;

class HorarioCursoSeeder_6A_TM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Curso
        $curso = Curso::where([
            'anio' => 6,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => ['Elect. Digital III', 'Ian Concepción'],   // LUNES
                2 => ['Inglés', 'Sandra Occhipinti'],           // MARTES
                3 => ['Telecomunicaciones', 'Martín Franch'],   // MIÉRCOLES
                4 => ['Telecomunicaciones', 'Martín Franch'],   // JUEVES
                5 => ['Filosofía', 'Yolanda Sucheyre'],         // VIERNES
            ],
            2 => [ // M2
                1 => ['Elect. Digital III', 'Ian Concepción'],   // LUNES
                2 => ['Inglés', 'Sandra Occhipinti'],           // MARTES
                3 => ['Telecomunicaciones', 'Martín Franch'],   // MIÉRCOLES
                4 => ['Telecomunicaciones', 'Martín Franch'],   // JUEVES
                5 => ['Filosofía', 'Yolanda Sucheyre'],         // VIERNES
            ],
            3 => [ // M3
                1 => ['Elect. Digital III', 'Ian Concepción'],               // LUNES
                2 => ['Econ. y Gestión de la Prod. Ind.', 'Laura Perez'],   // MARTES
                3 => ['Telecomunicaciones', 'Martín Franch'],               // MIÉRCOLES
                4 => ['Telecomunicaciones', 'Martín Franch'],               // JUEVES
                5 => ['Filosofía', 'Yolanda Sucheyre'],                     // VIERNES
            ],
            5 => [ // M4
                1 => ['Elect. Industrial I', 'Ian Concepción'],             // LUNES
                2 => ['Econ. y Gestión de la Prod. Ind.', 'Laura Perez'],   // MARTES
                3 => ['An. matemático', 'Claudia Farías'],                  // MIÉRCOLES
                4 => ['An. matemático', 'Claudia Farías'],                  // JUEVES
                5 => ['Elect. Industrial I', 'Ian Concepción'],             // VIERNES
            ],
            6 => [ // M5
                1 => ['Elect. Industrial I', 'Ian Concepción'],             // LUNES
                2 => ['Econ. y Gestión de la Prod. Ind.', 'Laura Perez'],   // MARTES
                3 => ['An. matemático', 'Claudia Farías'],                  // MIÉRCOLES
                4 => ['An. matemático', 'Claudia Farías'],                  // JUEVES
                5 => ['Elect. Industrial I', 'Ian Concepción'],             // VIERNES
            ],
            8 => [ // M6
                1 => ['Ciud. y Política', 'Greca Colazo'],      // LUNES
                2 => ['Econ. y Gestión de la Prod. Ind.', 'Laura Perez'],             // MARTES
                3 => ['Lengua y Lit.', 'Noelia Martinez Villegas'],          // MIÉRCOLES
                4 => ['An. matemático', 'Claudia Farías'],      // JUEVES
                5 => ['Elect. Industrial I', 'Ian Concepción'],             // VIERNES
            ],
            9 => [ // M7
                1 => ['Ciud. y Política', 'Greca Colazo'],      // LUNES
                2 => ['Inglés', 'Sandra Occhipinti'],             // MARTES
                3 => ['Lengua y Lit.', 'Noelia Martinez Villegas'],                             // MIÉRCOLES
                4 => ['Teatro', 'Laura Díaz'],  // JUEVES
                5 => ['Elect. Industrial I', 'Ian Concepción'],             // VIERNES
            ],
            10 => [ // M8
                1 => ['Ciud. y Política', 'Greca Colazo'],      // LUNES
                2 => ['Elect. Digital III', 'Ian Concepción'],             // MARTES
                3 => ['Lengua y Lit.', 'Noelia Martinez Villegas'],                             // MIÉRCOLES
                4 => ['Teatro', 'Laura Díaz'],  // JUEVES
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
