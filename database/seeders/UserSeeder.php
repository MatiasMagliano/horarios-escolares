<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Matías Magliano',
                'email' => 'matias.magliano@mi.unc.edu.ar',
                'password' => 'mmagliano',
                'role' => 'admin',
            ],
            [
                'name' => 'Matías Molina',
                'email' => 'matias_molina@mi.unc.edu.ar',
                'password' => 'mmolina',
                'role' => 'solicitante',
            ],
            [
                'name' => 'Marcelo Núñez',
                'email' => 'm.nunez@mi.unc.edu.ar',
                'password' => 'mnunez',
                'role' => 'aprobador',
            ],
            [
                'name' => 'Melina Segura',
                'email' => 'melina.segura@dlc-laravel.ddns.net',
                'password' => 'msegura',
                'role' => 'secretario',
            ]
        ];

        foreach ($usuarios as $usuario) {

            $user = User::updateOrCreate(
                ['email' => $usuario['email']],
                [
                    'name' => $usuario['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make($usuario['password']),
                    'remember_token' => Str::random(10),
                ]
            );

            // aseguramos que el rol exista
            $role = Role::firstOrCreate(['name' => $usuario['role']]);

            // asignamos rol
            $user->syncRoles([$role->name]);
        }
    }
}
