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
            MateriasSeeder::class,
            MateriasPorCursoSeeder_1A::class,
            HorarioCursoSeeder_1A_TM::class,
            MateriasPorCursoSeeder_1B::class,
            HorarioCursoSeeder_1B_TT::class,
            MateriasPorCursoSeeder_2A::class,
            HorarioCursoSeeder_2A_TM::class,
            MateriasPorCursoSeeder_2B::class,
            HorarioCursoSeeder_2B_TT::class,
            // tercero A
            MateriasPorCursoSeeder_3A::class,
            HorarioCursoSeeder_3A_TM::class,
            HorarioCursoSeeder_3A_CT::class,
            // tercero B
            MateriasPorCursoSeeder_3B::class,
            HorarioCursoSeeder_3B_TT::class,
            HorarioCursoSeeder_3B_CT::class,
        ]);
    }
}



// MateriasPorCursoSeeder_1A::class,
            
            // HorarioCursoSeeder_1B_TT::class,
            // MateriasPorCursoSeeder_2A::class,
            // HorarioCursoSeeder_2A_TM::class,
            // MateriasPorCursoSeeder_3A::class,
            // HorarioCursoSeeder_3A_TM::class,
            // HorarioCursoSeeder_3A_CT::class,
            // MateriasPorCursoSeeder_4A::class,
            // HorarioCursoSeeder_4A_TM::class,
            // HorarioCursoSeeder_4A_CT::class,
            // MateriasPorCursoSeeder_4B::class,
            // HorarioCursoSeeder_4B_TM::class,
            // HorarioCursoSeeder_4B_CT::class,
            // MateriasPorCursoSeeder_5A::class,
            // HorarioCursoSeeder_5A_TM::class,
            // HorarioCursoSeeder_5A_CT::class,
            // MateriasPorCursoSeeder_5B::class,
            // HorarioCursoSeeder_5B_TM::class,
            // HorarioCursoSeeder_5B_CT::class,
            // MateriasPorCursoSeeder_6A::class,
            // HorarioCursoSeeder_6A_TM::class,
            // HorarioCursoSeeder_6A_CT::class,
            // MateriasPorCursoSeeder_7A::class,
            // HorarioCursoSeeder_7A_TM::class,
            // HorarioCursoSeeder_7A_CT::class,