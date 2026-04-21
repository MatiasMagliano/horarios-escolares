<?php

namespace Database\Seeders;

use App\Models\Institucion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $institucionInicial = Institucion::query()->orderBy('id')->first();

        $user = User::query()->updateOrCreate(
            ['email' => env('SEED_SUPER_ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('SEED_SUPER_ADMIN_NAME', 'Administrador General'),
                'email_verified_at' => now(),
                'password' => Hash::make(env('SEED_SUPER_ADMIN_PASSWORD', 'password')),
                'remember_token' => Str::random(10),
                'institucion_activa_id' => $institucionInicial?->id,
                'is_super_admin' => true,
            ]
        );
    }
}
