<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\CursoMateria;

class MateriasPorCursoSeeder_3B extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curso = Curso::where([
            'anio' => 3,
            'division' => 'B',
            'turno' => 'tarde',
        ])->firstOrFail();

        $materias = [
            ['nombre' => 'Lengua y Literatura', 'horas_totales' => 5], 
            ['nombre' => 'Matemática', 'horas_totales' => 5], 
            ['nombre' => 'Física', 'horas_totales' => 3], 
            ['nombre' => 'Química', 'horas_totales' => 3], 
            ['nombre' => 'Geografía', 'horas_totales' => 4], 
            ['nombre' => 'Historia', 'horas_totales' => 4], 
            ['nombre' => 'Leng. Ext. - Inglés', 'horas_totales' => 3], 
            ['nombre' => 'Ed. Art. - Música/Teatro', 'horas_totales' => 3], 
            ['nombre' => 'Ed. Tecnológica', 'horas_totales' => 4], 
            ['nombre' => 'F.V.T', 'horas_totales' => 4],
            ['nombre' => 'Dib. Técnico', 'horas_totales' => 4],
        ];

        // PERSISTENCIA
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
