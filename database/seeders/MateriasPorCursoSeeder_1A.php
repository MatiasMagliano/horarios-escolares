<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;

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

        $materias = [
            ['nombre' => 'Geografía', 'horas_totales' => 5],
            ['nombre' => 'Lengua y Lit.', 'horas_totales' => 5],
            ['nombre' => 'Matemática', 'horas_totales' => 5],
            ['nombre' => 'Biología', 'horas_totales' => 3],
            ['nombre' => 'Ed. Tecnológica', 'horas_totales' => 4],
            ['nombre' => 'Ed. Artística', 'horas_totales' => 3],
            ['nombre' => 'Inglés', 'horas_totales' => 3],
            ['nombre' => 'Física', 'horas_totales' => 3],
            ['nombre' => 'Ciudadanía y Participación', 'horas_totales' => 3],
            ['nombre' => 'Dib. Técnico', 'horas_totales' => 2],
        ];

        foreach ($materias as $materia) {
            Materia::updateOrCreate(
                [
                    'nombre' => $materia['nombre'],
                    'curso_id' => $curso->id,
                ],
                [
                    'horas_totales' => $materia['horas_totales'],
                ]
            );
        }
    }
}
