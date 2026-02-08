<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\CursoMateria;
use App\Models\Docente;
use App\Models\BloqueHorario;
use App\Models\HorarioBase;
use Exception;

class HorarioCursoSeeder_1B_TT extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2 Curso
        $curso = Curso::where([
            'anio' => 1,
            'division' => 'B',
            'turno' => 'tarde',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => ['Lengua y Literatura', 'Marisa Morales'],                // LUNES
                2 => ['Matemática', 'Martín Andrada'],                   // MARTES
                3 => ['Lengua y Literatura', 'Marisa Morales'],                // MIÉRCOLES
                4 => ['Cs. Ns. - Física', 'Marianela Pecorari'],                   // JUEVES
                5 => ['Ciud. y Participación', 'Flavia Eberhardt'], // VIERNES
            ],
            2 => [ // M2
                1 => ['Lengua y Literatura', 'Marisa Morales'],                // LUNES
                2 => ['Matemática', 'Martín Andrada'],                   // MARTES
                3 => ['Lengua y Literatura', 'Marisa Morales'],                // MIÉRCOLES
                4 => ['Cs. Ns. - Física', 'Marianela Pecorari'],                   // JUEVES
                5 => ['Ciud. y Participación', 'Flavia Eberhardt'], // VIERNES
            ],
            3 => [ // M3
                1 => ['Lengua y Literatura', 'Marisa Morales'],                // LUNES
                2 => ['Matemática', 'Martín Andrada'],                   // MARTES
                3 => ['Ed. Tecnológica', 'Nicolás Coria'],                // MIÉRCOLES
                4 => ['Cs. Ns. - Física', 'Marianela Pecorari'],                   // JUEVES
                5 => ['Ciud. y Participación', 'Flavia Eberhardt'], // VIERNES
            ],
            5 => [ // M4
                1 => ['Ed. Art. - Art. Visuales', 'M. Elena Mansilla'],  // LUNES
                2 => ['Leng. Ext. - Inglés', 'Sandra Occhipinti'],         // MARTES
                3 => ['Ed. Tecnológica', 'Nicolás Coria'],    // MIÉRCOLES
                4 => ['Cs. Ss. - Geografía', 'Miriam Porcel'],          // JUEVES
                5 => ['Cs. Ns. - Biología', 'Yanina Lerda'],            // VIERNES
            ],
            6 => [ // M5
                1 => ['Ed. Art. - Art. Visuales', 'M. Elena Mansilla'],  // LUNES
                2 => ['Leng. Ext. - Inglés', 'Sandra Occhipinti'],         // MARTES
                3 => ['Dib. Técnico', 'Nadia Llarrull'],      // MIÉRCOLES
                4 => ['Cs. Ss. - Geografía', 'Miriam Porcel'],          // JUEVES
                5 => ['Cs. Ns. - Biología', 'Yanina Lerda'],            // VIERNES
            ],
            8 => [ // M6
                1 => ['Ed. Art. - Art. Visuales', 'M. Elena Mansilla'],  // LUNES
                2 => ['Leng. Ext. - Inglés', 'Sandra Occhipinti'],         // MARTES
                3 => ['Dib. Técnico', 'Nadia Llarrull'],      // MIÉRCOLES
                4 => ['Cs. Ss. - Geografía', 'Miriam Porcel'],          // JUEVES
                5 => ['Cs. Ns. - Biología', 'Yanina Lerda'],            // VIERNES
            ],
            9 => [ // M7
                1 => ['Matemática', 'Martín Andrada'],      // LUNES
                2 => [null, null],                          // MARTES
                3 => ['Cs. Ss. - Geografía', 'Miriam Porcel'],        // MIÉRCOLES
                4 => ['Ed. Tecnológica', 'Nicolás Coria'],  // JUEVES
                5 => [null, null],                          // VIERNES
            ],
            10 => [ // M8
                1 => ['Matemática', 'Martín Andrada'],      // LUNES
                2 => [null, null],                          // MARTES
                3 => ['Cs. Ss. - Geografía', 'Miriam Porcel'],        // MIÉRCOLES
                4 => ['Ed. Tecnológica', 'Nicolás Coria'],  // JUEVES
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

                // SELECCIÓN DE BLOQUE HORARIO
                $bloque = BloqueHorario::where([
                    'turno' => 'tarde',
                    'orden' => $orden,
                ])->firstOrFail();

                // SELECCIÓN DE MATERIA
                $cursoMateria = CursoMateria::where('curso_id', $curso->id)
                    ->whereHas('materia', fn($q) =>
                        $q->where('nombre', $materiaNombre)
                    )->firstOrFail();

                // SELECCIÓN DE DOCENTE
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
