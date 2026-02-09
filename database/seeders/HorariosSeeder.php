<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HorariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seeder llamador de todos los cursos
        $this->call([
            Cursos\CursoSeeder_1A::class,
            Cursos\CursoSeeder_1B::class,
            Cursos\CursoSeeder_2A::class,
            Cursos\CursoSeeder_2B::class,
            Cursos\CursoSeeder_3A::class,
            Cursos\CursoSeeder_3B::class
        ]);
    }
}
