<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\HorarioBase;

class HorarioCursoSeeder_5A_TM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Curso
        $curso = Curso::where([
            'anio' => 5,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => ['Química', 'Yanina Funes'],              // LUNES
                2 => ['Música', 'Franco Morano'],              // MARTES
                3 => ['Matemática', 'Ivana Ribodino'],         // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Marisa Morales'],      // JUEVES
                5 => ['Matemática', 'Ivana Ribodino'],         // VIERNES
            ],
            2 => [ // M2
                1 => ['Química', 'Yanina Funes'],              // LUNES
                2 => ['Música', 'Franco Morano'],              // MARTES
                3 => ['Matemática', 'Ivana Ribodino'],         // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Marisa Morales'],      // JUEVES
                5 => ['Matemática', 'Ivana Ribodino'],         // VIERNES
            ],
            3 => [ // M3
                1 => ['Química', 'Yanina Funes'],              // LUNES
                2 => ['Inglés', 'Sandra Occhipinti'],          // MARTES
                3 => ['Matemática', 'Ivana Ribodino'],         // MIÉRCOLES
                4 => ['Psicología', 'Yolanda Sucheyre'],       // JUEVES
                5 => ['Geografía', 'Belén Ramos'],             // VIERNES
            ],
            5 => [ // M4
                1 => ['Electrotecnia II', 'Pablo Barac'],      // LUNES
                2 => ['Historia', 'Ariel Ardiles'],            // MARTES
                3 => ['Historia', 'Ariel Ardiles'],            // MIÉRCOLES
                4 => ['Psicología', 'Yolanda Sucheyre'],       // JUEVES
                5 => ['Geografía', 'Belén Ramos'],             // VIERNES
            ],
            6 => [ // M5
                1 => ['Electrotecnia II', 'Pablo Barac'],      // LUNES
                2 => ['Historia', 'Ariel Ardiles'],            // MARTES
                3 => ['Inglés', 'Sandra Occhipinti'],          // MIÉRCOLES
                4 => ['Psicología', 'Yolanda Sucheyre'],       // JUEVES
                5 => ['Geografía', 'Belén Ramos'],             // VIERNES
            ],
            8 => [ // M6
                1 => ['Electrotecnia II', 'Pablo Barac'],      // LUNES
                2 => ['Física', 'Martín Andrada'],             // MARTES
                3 => ['Inglés', 'Sandra Occhipinti'],          // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Marisa Morales'],      // JUEVES
                5 => ['Física', 'Martín Andrada'],             // VIERNES
            ],
            9 => [ // M7
                1 => ['Electrotecnia II', 'Pablo Barac'],      // LUNES
                2 => ['Física', 'Martín Andrada'],             // MARTES
                3 => [null, null],                             // MIÉRCOLES
                4 => ['Elect. Digital II', 'Ian Concepción'],  // JUEVES
                5 => ['Física', 'Martín Andrada'],             // VIERNES
            ],
            10 => [ // M8
                1 => ['Electrotecnia II', 'Pablo Barac'],      // LUNES
                2 => [null, null],                             // MARTES
                3 => [null, null],                             // MIÉRCOLES
                4 => ['Elect. Digital II', 'Ian Concepción'],  // JUEVES
                5 => ['Elect. Digital II', 'Ian Concepción'],  // VIERNES
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
