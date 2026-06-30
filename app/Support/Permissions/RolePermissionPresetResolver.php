<?php

namespace App\Support\Permissions;

use App\Models\RolePermissionPreset;
use Illuminate\Support\Facades\Schema;
use Throwable;

class RolePermissionPresetResolver
{
    /**
     * @return array<int, string>
     */
    public static function permissionsForRole(string $roleName): array
    {
        if (! in_array($roleName, PermissionCatalog::ROLES, true)) {
            return [];
        }

        try {
            if (Schema::hasTable('role_permission_presets')) {
                $preset = RolePermissionPreset::query()
                    ->where('role_name', $roleName)
                    ->first();

                if ($preset) {
                    return self::validPermissions($preset->permissions ?? []);
                }
            }
        } catch (Throwable) {
            //
        }

        return PermissionCatalog::rolePermissions()[$roleName] ?? [];
    }

    /**
     * @param array<int, string> $permissions
     * @return array<int, string>
     */
    public static function validPermissions(array $permissions): array
    {
        $valid = array_flip(PermissionCatalog::names());

        return array_values(array_unique(array_filter(
            $permissions,
            fn ($permission) => is_string($permission) && isset($valid[$permission])
        )));
    }
}
