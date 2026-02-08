<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\Docente;
use App\Models\HorarioBase;
use App\Models\CursoMateria;
use Exception;

class HorarioCursoSeeder_1A_TM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Curso
        $curso = Curso::where([
            'anio' => 1,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => ['Cs. Ss. - Geografía', 'Marcos Morales'], // LUNES
                2 => [null, null], // MARTES
                3 => ['Cs. Ns. - Biología', 'Soledad González'], // MIÉRCOLES
                4 => ['Lengua y Literatura', 'Noelia Martinez Villegas'], // JUEVES
                5 => [null, null], // VIERNES
            ],
            2 => [ // M2
                1 => ['Cs. Ss. - Geografía', 'Marcos Morales'], // LUNES
                2 => ['Ed. Tecnológica', 'Sofía Rodriguez'], // MARTES
                3 => ['Cs. Ns. - Biología', 'Soledad González'],  // MIÉRCOLES
                4 => ['Lengua y Literatura', 'Noelia Martinez Villegas'], // JUEVES
                5 => [null, null], // VIERNES
            ],
            3 => [ // M3
                1 => ['Ed. Art. - Art. Visuales', 'M. Elena Mansilla'],         // LUNES
                2 => ['Ed. Tecnológica', 'Sofía Rodriguez'],         // MARTES
                3 => ['Cs. Ns. - Biología', 'Soledad Gonzalez'],               // MIÉRCOLES
                4 => ['Lengua y Literatura', 'Noelia Martinez Villegas'],  // JUEVES
                5 => ['Ed. Tecnológica', 'Sofía Rodriguez'],         // VIERNES
            ],
            5 => [ // M4
                1 => ['Ed. Art. - Art. Visuales', 'M. Elena Mansilla'],   // LUNES
                2 => ['Leng. Ext. - Inglés', 'Claudia Ramadán'],            // MARTES
                3 => ['Cs. Ns. - Física', 'Yanina Funes'],               // MIÉRCOLES
                4 => ['Cs. Ss. - Geografía', 'Marcos Morales'],          // JUEVES
                5 => ['Ed. Tecnológica', 'Sofía Rodriguez'],  // VIERNES
            ],
            6 => [ // M5
                1 => ['Ed. Art. - Art. Visuales', 'M. Elena Mansilla'],   // LUNES
                2 => ['Leng. Ext. - Inglés', 'Claudia Ramadán'],            // MARTES
                3 => ['Cs. Ns. - Física', 'Yanina Funes'],               // MIÉRCOLES
                4 => ['Cs. Ss. - Geografía', 'Marcos Morales'],          // JUEVES
                5 => ['Matemática', 'Ivana Ribodino'],         // VIERNES
            ],
            8 => [ // M6
                1 => ['Ciud. y Participación', 'Ariel Ardiles'], // LUNES
                2 => ['Leng. Ext. - Inglés', 'Claudia Ramadán'],                    // MARTES
                3 => ['Cs. Ns. - Física', 'Yanina Funes'],                       // MIÉRCOLES
                4 => ['Cs. Ss. - Geografía', 'Marcos Morales'],                  // JUEVES
                5 => ['Matemática', 'Ivana Ribodino'],                 // VIERNES
            ],
            9 => [ // M7
                1 => ['Ciud. y Participación', 'Ariel Ardiles'],   // LUNES
                2 => ['Lengua y Literatura', 'Noelia Martinez Villegas'],     // MARTES
                3 => ['Matemática', 'Ivana Ribodino'],                  // MIÉRCOLES
                4 => ['Dib. Técnico', 'Nadia Llarrull'],                // JUEVES
                5 => ['Matemática', 'Ivana Ribodino'],                  // VIERNES
            ],
            10 => [ // M8
                1 => ['Ciud. y Participación', 'Ariel Ardiles'], // LUNES
                2 => ['Lengua y Literatura', 'Noelia Martinez Villegas'],   // MARTES
                3 => ['Matemática', 'Ivana Ribodino'],                // MIÉRCOLES
                4 => ['Dib. Técnico', 'Nadia Llarrull'],              // JUEVES
                5 => [null, null],                                    // VIERNES
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
