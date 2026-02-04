<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;

class MateriasPorCursoSeeder_5A extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curso = Curso::where([
            'anio' => 5,
            'division' => 'A',
            'turno' => 'maniana',
        ])->firstOrFail();

        $materias = [
            ['nombre' => 'Lengua y Lit.', 'horas_totales' => 3], 
            ['nombre' => 'Matemática', 'horas_totales' => 5], 
            ['nombre' => 'Física', 'horas_totales' => 4], 
            ['nombre' => 'Química', 'horas_totales' => 3], 
            ['nombre' => 'Psicología', 'horas_totales' => 3],
            ['nombre' => 'Geografía', 'horas_totales' => 3], 
            ['nombre' => 'Historia', 'horas_totales' => 3], 
            ['nombre' => 'Inglés', 'horas_totales' => 3], 
            ['nombre' => 'Música', 'horas_totales' => 2],
            ['nombre' => 'Elect. Digital II', 'horas_totales' => 4],
            ['nombre' => 'Elect. Analógica II', 'horas_totales' => 6],
            ['nombre' => 'Electrotecnia II', 'horas_totales' => 6],
            ['nombre' => 'Inf. Electrónica II', 'horas_totales' => 5],
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
