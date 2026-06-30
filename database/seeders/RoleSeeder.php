<?php

namespace Database\Seeders;

use App\Models\Institucion;
use App\Support\Permissions\PermissionCatalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $instituciones = Institucion::query()->pluck('id');
        $teamKey = config('permission.column_names.team_foreign_key');

        foreach ($instituciones as $institucionId) {
            foreach (PermissionCatalog::ROLES as $roleName) {
                Role::query()->firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web',
                    $teamKey => $institucionId,
                ]);
            }

            $obsoleteRoles = Role::query()
                ->where($teamKey, $institucionId)
                ->whereNotIn('name', PermissionCatalog::ROLES)
                ->pluck('id');

            if ($obsoleteRoles->isNotEmpty()) {
                DB::table(config('permission.table_names.model_has_roles'))
                    ->whereIn(config('permission.column_names.role_pivot_key') ?? 'role_id', $obsoleteRoles)
                    ->delete();

                DB::table(config('permission.table_names.role_has_permissions'))
                    ->whereIn(config('permission.column_names.role_pivot_key') ?? 'role_id', $obsoleteRoles)
                    ->delete();

                Role::query()->whereKey($obsoleteRoles)->delete();
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
