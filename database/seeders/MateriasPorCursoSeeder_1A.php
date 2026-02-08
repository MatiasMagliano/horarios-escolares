<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\CursoMateria;

class MateriasPorCursoSeeder_1A extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curso = Curso::where([
            'anio' => 1,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        // se asigna, de acuerdo a currícula NOMBRE.MATERIA --> CARGA.HORARIA
        $materias = [
            ['nombre' => 'Cs. Ss. - Geografía', 'horas_totales' => 5],
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 5],
            ['nombre' => 'Matemática', 'horas_totales' => 5],
            ['nombre' => 'Cs. Ns. - Biología', 'horas_totales' => 3],
            ['nombre' => 'Ed. Tecnológica', 'horas_totales' => 4],
            ['nombre' => 'Ed. Art. - Art. Visuales', 'horas_totales' => 3],
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3],
            ['nombre' => 'Cs. Ns. - Física', 'horas_totales' => 3],
            ['nombre' => 'Ciud. y Participación', 'horas_totales' => 3],
            ['nombre' => 'Dib. Técnico', 'horas_totales' => 2],
        ];

        foreach ($materias as $materia) {
            $materia_db = Materia::where('nombre', $materia['nombre'])->firstOrFail();

            CursoMateria::updateOrCreate(
                [
                    'curso_id' => $curso->id,
                    'materia_id' => $materia_db->id,
                ],
                [
                    'horas_totales' => $materia['horas_totales'],
                ]
            );
        }
    }
}
