<?php

namespace Database\Seeders;

use App\Models\Institucion;
use App\Support\Permissions\PermissionCatalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = PermissionCatalog::names();

        foreach ($permissions as $permissionName) {
            Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $this->deleteObsoletePermissions($permissions);

        $rolePermissionMap = PermissionCatalog::rolePermissions();

        $institucionIds = Institucion::query()->pluck('id');
        $teamKey = config('permission.column_names.team_foreign_key');

        foreach ($institucionIds as $institucionId) {
            foreach (PermissionCatalog::ROLES as $roleName) {
                $role = Role::query()->firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web',
                    $teamKey => $institucionId,
                ]);

                if (! array_key_exists($role->name, $rolePermissionMap)) {
                    continue;
                }

                app(PermissionRegistrar::class)->setPermissionsTeamId($institucionId);
                $role->syncPermissions($rolePermissionMap[$role->name]);
            }
        }

        app(PermissionRegistrar::class)->setPermissionsTeamId(null);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @param array<int, string> $currentPermissions
     */
    private function deleteObsoletePermissions(array $currentPermissions): void
    {
        $obsoletePermissionIds = Permission::query()
            ->where('guard_name', 'web')
            ->whereNotIn('name', $currentPermissions)
            ->pluck('id');

        if ($obsoletePermissionIds->isEmpty()) {
            return;
        }

        $permissionPivotKey = config('permission.column_names.permission_pivot_key') ?? 'permission_id';

        DB::table(config('permission.table_names.role_has_permissions'))
            ->whereIn($permissionPivotKey, $obsoletePermissionIds)
            ->delete();

        DB::table(config('permission.table_names.model_has_permissions'))
            ->whereIn($permissionPivotKey, $obsoletePermissionIds)
            ->delete();

        Permission::query()->whereKey($obsoletePermissionIds)->delete();
    }
}
