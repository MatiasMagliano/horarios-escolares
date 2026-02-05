<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\HorarioBase;
use App\Models\BloqueHorario;

class HorarioCursoSeeder_4B_TM extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Curso
        $curso = Curso::where([
            'anio' => 4,
            'division' => 'B',
            'turno' => 'maniana',
        ])->firstOrFail();

        // 2 Grilla
        $grilla = [
            1 => [ // M1
                1 => [null, null], // LUNES
                2 => ['Química', 'Soledad González'], // MARTES
                3 => ['Historia', 'Erick Zaccagnini'], // MIÉRCOLES
                4 => ['Matemática', 'Martín Andrada'], // JUEVES
                5 => ['Lógica Matemática', 'Martín Andrada'], // VIERNES
            ],
            2 => [ // M2
                1 => ['Inf. Aplicada I', 'Andrea Chiappori'], // LUNES
                2 => ['Química', 'Soledad González'], // MARTES
                3 => ['Historia', 'Erick Zaccagnini'], // MIÉRCOLES
                4 => ['Matemática', 'Martín Andrada'], // JUEVES
                5 => ['Lógica Matemática', 'Martín Andrada'], // VIERNES
            ],
            3 => [ // M3
                1 => ['Inf. Aplicada I', 'Andrea Chiappori'], // LUNES
                2 => ['Química', 'Soledad González'], // MARTES
                3 => ['Historia', 'Erick Zaccagnini'], // MIÉRCOLES
                4 => ['Matemática', 'Martín Andrada'], // JUEVES
                5 => ['Lógica Matemática', 'Martín Andrada'], // VIERNES
            ],
            5 => [ // M4
                1 => ['Biología', 'Natacha Marangón'], // LUNES
                2 => ['Inglés', 'Carina Chialva'], // MARTES
                3 => ['Matemática', 'Martín Andrada'], // MIÉRCOLES
                4 => ['Biología', 'Natacha Marangón'], // JUEVES
                5 => ['Lengua y Lit.', 'Adam Luna'], // VIERNES
            ],
            6 => [ // M5
                1 => ['Biología', 'Natacha Marangón'], // LUNES
                2 => ['Inglés', 'Carina Chialva'], // MARTES
                3 => ['Matemática', 'Martín Andrada'], // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Adam Luna'], // JUEVES
                5 => ['Lengua y Lit.', 'Adam Luna'], // VIERNES
            ],
            8 => [ // M6
                1 => ['Biología', 'Natacha Marangón'], // LUNES
                2 => ['Inglés', 'Carina Chialva'], // MARTES
                3 => ['Lógica Matemática', 'Martín Andrada'], // MIÉRCOLES
                4 => ['Lengua y Lit.', 'Adam Luna'], // JUEVES
                5 => ['Geografía', 'Marcos Morales'], // VIERNES
            ],
            9 => [ // M7
                1 => ['Ed. Artística', 'Vanina Ibarra'], // LUNES
                2 => ['Física', 'Natacha Marangón'], // MARTES
                3 => ['Inf. Aplicada I', 'Andrea Chiappori'], // MIÉRCOLES
                4 => ['Física', 'Natacha Marangón'], // JUEVES
                5 => ['Geografía', 'Marcos Morales'], // VIERNES
            ],
            10 => [ // M8
                1 => ['Ed. Artística', 'Vanina Ibarra'], // LUNES
                2 => ['Física', 'Natacha Marangón'], // MARTES
                3 => ['Inf. Aplicada I', 'Andrea Chiappori'], // MIÉRCOLES
                4 => ['Física', 'Natacha Marangón'], // JUEVES
                5 => ['Geografía', 'Marcos Morales'], // VIERNES
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
