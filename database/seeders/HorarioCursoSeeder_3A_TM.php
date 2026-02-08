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
                1 => ['Matemática', 'Martín Andrada'],              // LUNES
                2 => ['Matemática', 'Martín Andrada'],              // MARTES
                3 => ['Química', 'Yanina Funes'],                   // MIÉRCOLES
                4 => ['Lengua y Literatura', 'Marisa Morales'],     // JUEVES
                5 => ['Física', 'Natacha Marangón'],                // VIERNES
            ],
            5 => [ // M4
                1 => ['Ed. Tecnológica', 'Brenda Quiroga'],         // LUNES
                2 => ['F.V.T', 'Yolanda Sucheyre'],                 // MARTES
                3 => ['Ed. Tecnológica', 'Brenda Quiroga'],         // MIÉRCOLES
                4 => ['Lengua y Literatura', 'Marisa Morales'],     // JUEVES
                5 => ['F.V.T', 'Yolanda Sucheyre'],                 // VIERNES
            ],
            6 => [ // M5
                1 => ['Ed. Tecnológica', 'Brenda Quiroga'],    // LUNES
                2 => ['F.V.T', 'Yolanda Sucheyre'],            // MARTES
                3 => ['Ed. Tecnológica', 'Brenda Quiroga'],    // MIÉRCOLES
                4 => ['Física', 'Natacha Marangón'],           // JUEVES
                5 => ['F.V.T', 'Yolanda Sucheyre'],            // VIERNES
            ],
            8 => [ // M6
                1 => ['Dib. Técnico', 'Ivana Ribodino'],             // LUNES
                2 => ['Ed. Art. - Música/Teatro', 'Bruno/Morano'],   // MARTES
                3 => ['Lengua y Literatura', 'Marisa Morales'],      // MIÉRCOLES
                4 => ['Física', 'Natacha Marangón'],                 // JUEVES
                5 => ['Leng. Ext. - Inglés', 'Carina Chialva'],      // VIERNES
            ],
            9 => [ // M7
                1 => ['Dib. Técnico', 'Ivana Ribodino'],             // LUNES
                2 => ['Ed. Art. - Música/Teatro', 'Bruno/Morano'],   // MARTES
                3 => ['Lengua y Literatura', 'Marisa Morales'],      // MIÉRCOLES
                4 => ['Historia', 'Erick Zaccagnini'],               // JUEVES
                5 => ['Leng. Ext. - Inglés', 'Carina Chialva'],      // VIERNES
            ],
            10 => [ // M8
                1 => ['Dib. Técnico', 'Ivana Ribodino'],             // LUNES
                2 => ['Ed. Art. - Música/Teatro', 'Bruno/Morano'],   // MARTES
                3 => ['Lengua y Literatura', 'Marisa Morales'],      // MIÉRCOLES
                4 => ['Historia', 'Erick Zaccagnini'],               // JUEVES
                5 => ['Leng. Ext. - Inglés', 'Carina Chialva']       // VIERNES
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
                    'turno' => 'maniana',
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
    }
}
