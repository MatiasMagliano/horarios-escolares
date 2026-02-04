<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;

class MateriasPorCursoSeeder_4B extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curso = Curso::where([
            'anio' => 4,
            'division' => 'B',
            'turno' => 'maniana',
        ])->firstOrFail();

        $materias = [
            ['nombre' => 'Lengua y Lit.', 'horas_totales' => 4], 
            ['nombre' => 'Matemática', 'horas_totales' => 5], 
            ['nombre' => 'Física', 'horas_totales' => 4], 
            ['nombre' => 'Química', 'horas_totales' => 3], 
            ['nombre' => 'Biología', 'horas_totales' => 4],
            ['nombre' => 'Geografía', 'horas_totales' => 3], 
            ['nombre' => 'Historia', 'horas_totales' => 3], 
            ['nombre' => 'Inglés', 'horas_totales' => 3], 
            ['nombre' => 'Ed. Artística', 'horas_totales' => 2],
            ['nombre' => 'Inf. Aplicada I', 'horas_totales' => 4],
            ['nombre' => 'Lógica Matemática', 'horas_totales' => 4],
            ['nombre' => 'Programación I', 'horas_totales' => 6],
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
