<?php

namespace Database\Seeders;

use App\Models\EspacioFisico;
use Illuminate\Database\Seeder;

class EspaciosFisicosSeeder extends Seeder
{
    public function run(): void
    {
        $espacios = [
            ['nombre' => 'Aula 1', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Aula 2', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Aula 3', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Aula 4', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Aula 5', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Aula 6', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Aula 7', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Aula 8', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Aula 9', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Aula 10', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Aula 11', 'tipo' => EspacioFisico::TIPO_AULA],
            ['nombre' => 'Laboratorio de Informática', 'tipo' => EspacioFisico::TIPO_LAB_INFORMATICA],
            ['nombre' => 'Laboratorio de Electrónica', 'tipo' => EspacioFisico::TIPO_LAB_ELECTRONICA],
            ['nombre' => 'Laboratorio / Taller', 'tipo' => EspacioFisico::TIPO_LAB_TALLER],
            ['nombre' => 'Patio', 'tipo' => EspacioFisico::TIPO_PATIO],
        ];

        foreach ($espacios as $espacio) {
            EspacioFisico::updateOrCreate(
                ['nombre' => $espacio['nombre']],
                [
                    'tipo' => $espacio['tipo'],
                    'activo' => true,
                ]
            );
        }
    }
}
