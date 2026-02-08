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

class HorarioCursoSeeder_3B_TT extends Seeder
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
                1 => ['F.V.T', 'Yolanda Sucheyre'],             // LUNES
                2 => ['Historia', 'Facundo Zurita'],  // MARTES
                3 => ['Matemática', 'Vanesa Farías'],           // MIÉRCOLES
                4 => ['Química', 'Natacha Marangón'],           // JUEVES
                5 => ['F.V.T', 'Yolanda Sucheyre'],             // VIERNES
            ],
            2 => [ // M2
                1 => ['F.V.T', 'Yolanda Sucheyre'],     // LUNES
                2 => ['Historia', 'Facundo Zurita'],    // MARTES
                3 => ['Matemática', 'Vanesa Farías'],   // MIÉRCOLES
                4 => ['Química', 'Natacha Marangón'],   // JUEVES
                5 => ['F.V.T', 'Yolanda Sucheyre'],     // VIERNES
            ],
            3 => [ // M3
                1 => ['Historia', 'Facundo Zurita'],            // LUNES
                2 => ['Leng. Ext. - Inglés', 'Monica Rosso'],   // MARTES
                3 => ['Matemática', 'Vanesa Farías'],           // MIÉRCOLES
                4 => ['Química', 'Natacha Marangón'],           // JUEVES
                5 => ['Dib. Técnico', 'Florentina Arinci'],     // VIERNES
            ],
            5 => [ // M4
                1 => ['Historia', 'Facundo Zurita'],            // LUNES
                2 => ['Leng. Ext. - Inglés', 'Monica Rosso'],   // MARTES
                3 => ['Geografía', 'Marianela Gatti'],          // MIÉRCOLES
                4 => ['Dib. Técnico', 'Florentina Arinci'],     // JUEVES
                5 => ['Dib. Técnico', 'Florentina Arinci'],     // VIERNES
            ],
            6 => [ // M5
                1 => ['Ed. Tecnológica', 'Patricia Solís'],     // LUNES
                2 => ['Leng. Ext. - Inglés', 'Monica Rosso'],   // MARTES
                3 => ['Geografía', 'Marianela Gatti'],          // MIÉRCOLES
                4 => ['Dib. Técnico', 'Florentina Arinci'],     // JUEVES
                5 => ['Matemática', 'Vanesa Farías'],           // VIERNES
            ],
            8 => [ // M6
                1 => ['Ed. Tecnológica', 'Patricia Solís'],        // LUNES
                2 => ['Geografía', 'Marianela Gatti'],             // MARTES
                3 => ['Ed. Art. - Música/Teatro', 'Bruno/Morano'], // MIÉRCOLES
                4 => ['Lengua y Literatura', 'Carolina Rojas'],    // JUEVES
                5 => ['Matemática', 'Vanesa Farías'],              // VIERNES
            ],
            9 => [ // M7
                1 => ['Ed. Tecnológica', 'Patricia Solís'],        // LUNES
                2 => ['Geografía', 'Marianela Gatti'],             // MARTES
                3 => ['Ed. Art. - Música/Teatro', 'Bruno/Morano'], // MIÉRCOLES
                4 => ['Lengua y Literatura', 'Carolina Rojas'],    // JUEVES
                5 => ['Lengua y Literatura', 'Carolina Rojas'],    // VIERNES
            ],
            10 => [ // M8
                1 => ['Ed. Tecnológica', 'Patricia Solís'],        // LUNES
                2 => [null, null],                                 // MARTES
                3 => ['Ed. Art. - Música/Teatro', 'Bruno/Morano'], // MIÉRCOLES
                4 => ['Lengua y Literatura', 'Carolina Rojas'],    // JUEVES
                5 => ['Lengua y Literatura', 'Carolina Rojas'],    // VIERNES
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
