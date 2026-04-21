<?php

namespace Database\Seeders;

use App\Models\Institucion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instituciones = Institucion::query()->pluck('id');

        foreach ($instituciones as $institucionId) {
            foreach (['admin', 'solicitante', 'aprobador', 'secretario'] as $roleName) {
                Role::query()->firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web',
                    'team_id' => $institucionId,
                ]);
            }
        }
    }
}
