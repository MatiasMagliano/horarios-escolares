<?php

namespace Tests\Unit;

use App\Support\Permissions\PermissionCatalog;
use PHPUnit\Framework\TestCase;

class PermissionCatalogTest extends TestCase
{
    public function test_roles_are_the_institutional_roles(): void
    {
        $this->assertSame([
            'administrador',
            'preceptor',
            'aprobador',
            'secretario',
        ], PermissionCatalog::ROLES);
    }

    public function test_permission_names_are_normalized_in_english(): void
    {
        $permissions = PermissionCatalog::names();

        $this->assertContains('schedules.view', $permissions);
        $this->assertContains('schedule_changes.approve', $permissions);
        $this->assertContains('teachers.activate', $permissions);

        $this->assertNotContains('horarios.ver', $permissions);
        $this->assertNotContains('cambios_horarios.edit', $permissions);
        $this->assertNotContains('abm-cursos', $permissions);
    }

    public function test_role_presets_only_reference_catalog_permissions(): void
    {
        $validPermissions = PermissionCatalog::names();

        foreach (PermissionCatalog::rolePermissions() as $role => $permissions) {
            $this->assertContains($role, PermissionCatalog::ROLES);
            $this->assertEmpty(array_diff($permissions, $validPermissions));
        }
    }
}
