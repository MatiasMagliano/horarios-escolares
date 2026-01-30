<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DocentesSeeder::class,
            CursosSeeder::class,
            BloquesHorariosSeeder::class,
            DocentesSeeder::class,
            MateriasPorCursoSeeder_1A::class,
            HorarioCursoSeeder_1A_TM::class,
            MateriasPorCursoSeeder_2A::class,
            HorarioCursoSeeder_2A_TM::class,
            MateriasPorCursoSeeder_3A::class,
            HorarioCursoSeeder_3A_TM::class,
            HorarioCursoSeeder_3A_CT::class,
            MateriasPorCursoSeeder_4A::class,
            HorarioCursoSeeder_4A_TM::class,
            HorarioCursoSeeder_4A_CT::class,
        ]);
    }
}
