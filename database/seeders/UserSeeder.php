<?php

namespace Database\Seeders;

use App\Models\Institucion;
use App\Models\User;
use App\Support\Permissions\PermissionCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $institucionInicial = Institucion::query()->orderBy('id')->first();

        if (! $institucionInicial) {
            return;
        }

        $superAdmin = User::query()->updateOrCreate(
            ['email' => env('SEED_SUPER_ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => env('SEED_SUPER_ADMIN_NAME', 'Administrador General'),
                'email_verified_at' => now(),
                'password' => Hash::make(env('SEED_SUPER_ADMIN_PASSWORD', 'password')),
                'remember_token' => Str::random(10),
                'institucion_activa_id' => $institucionInicial->id,
                'is_super_admin' => true,
            ]
        );

        $superAdmin->instituciones()->syncWithoutDetaching([
            $institucionInicial->id => ['activo' => true],
        ]);

        $usuarios = [
            [
                'name' => 'Administrador Institucional',
                'email' => 'admin.institucion@example.com',
                'role' => 'administrador',
            ],
            [
                'name' => 'Preceptor Demo',
                'email' => 'preceptor@example.com',
                'role' => 'preceptor',
            ],
            [
                'name' => 'Aprobador Demo',
                'email' => 'aprobador@example.com',
                'role' => 'aprobador',
            ],
            [
                'name' => 'Secretario Demo',
                'email' => 'secretario@example.com',
                'role' => 'secretario',
            ],
        ];

        app(PermissionRegistrar::class)->setPermissionsTeamId($institucionInicial->id);

        foreach ($usuarios as $datos) {
            if (! in_array($datos['role'], PermissionCatalog::ROLES, true)) {
                continue;
            }

            $user = User::query()->updateOrCreate(
                ['email' => $datos['email']],
                [
                    'name' => $datos['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                    'institucion_activa_id' => $institucionInicial->id,
                    'is_super_admin' => false,
                ]
            );

            $user->instituciones()->syncWithoutDetaching([
                $institucionInicial->id => ['activo' => true],
            ]);

            $user->syncRoles([$datos['role']]);
        }

        $solicitante = User::query()->where('email', 'solicitante@example.com')->first();
        $solicitante?->delete();

        app(PermissionRegistrar::class)->setPermissionsTeamId(null);
    }
}
