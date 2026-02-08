<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\CursoMateria;
use App\Models\Materia;

class MateriasPorCursoSeeder_2B extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curso = Curso::where([
            'anio' => 2,
            'division' => 'B',
            'turno' => 'tarde',
        ])->firstOrFail();

        // se asigna, de acuerdo a currícula NOMBRE.MATERIA --> CARGA.HORARIA
        $materias = [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 5],
            ['nombre' => 'Matemática', 'horas_totales' => 5],
            ['nombre' => 'Biología', 'horas_totales' => 3],
            ['nombre' => 'Cs. Ns. - Química', 'horas_totales' => 3],
            ['nombre' => 'Cs. Ss. - Historia', 'horas_totales' => 5],
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3],
            ['nombre' => 'Ed. Art. - Música', 'horas_totales' => 3],
            ['nombre' => 'Ed. Tecnológica', 'horas_totales' => 4],
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
