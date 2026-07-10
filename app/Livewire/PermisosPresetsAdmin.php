<?php

namespace App\Livewire;

use App\Models\RolePermissionPreset;
use App\Support\Permissions\PermissionCatalog;
use App\Support\Permissions\RolePermissionPresetResolver;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermisosPresetsAdmin extends Component
{
    public string $roleName = 'administrador';
    public array $checked = [];
    public bool $presetPersonalizado = false;

    public function boot(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }

    public function mount(): void
    {
        $this->cargarPreset();
    }

    protected function rules(): array
    {
        return [
            'roleName' => ['required', 'string', Rule::in(PermissionCatalog::ROLES)],
            'checked' => ['array'],
        ];
    }

    public function updatedRoleName(): void
    {
        $this->cargarPreset();
    }

    public function cargarPreset(): void
    {
        $preset = RolePermissionPreset::query()
            ->where('role_name', $this->roleName)
            ->first();

        $this->presetPersonalizado = (bool) $preset;
        $permissions = $preset
            ? RolePermissionPresetResolver::validPermissions($preset->permissions ?? [])
            : (PermissionCatalog::rolePermissions()[$this->roleName] ?? []);

        $this->marcarPermisos($permissions);
    }

    public function cargarPredeterminado(): void
    {
        $this->marcarPermisos(PermissionCatalog::rolePermissions()[$this->roleName] ?? []);
    }

    public function guardar(): void
    {
        $this->validate();
        $permissions = $this->selectedPermissions();

        DB::transaction(function () use ($permissions) {
            RolePermissionPreset::query()->updateOrCreate(
                ['role_name' => $this->roleName],
                ['permissions' => $permissions]
            );

            $this->sincronizarRolesExistentes($this->roleName, $permissions);
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->presetPersonalizado = true;
        session()->flash('success', 'Preset guardado y aplicado a los roles existentes.');
    }

    public function eliminar(): void
    {
        $permissions = PermissionCatalog::rolePermissions()[$this->roleName] ?? [];

        RolePermissionPreset::query()
            ->where('role_name', $this->roleName)
            ->delete();

        DB::transaction(function () use ($permissions) {
            $this->sincronizarRolesExistentes($this->roleName, $permissions);
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->presetPersonalizado = false;
        $this->cargarPredeterminado();

        session()->flash('success', 'Preset personalizado eliminado y predeterminado aplicado a los roles existentes.');
    }

    public function render()
    {
        return view('livewire.permisos-presets-admin', [
            'roles' => PermissionCatalog::ROLES,
            'roleLabels' => PermissionCatalog::roleLabels(),
            'modules' => PermissionCatalog::modules(),
        ]);
    }

    /**
     * @param array<int, string> $permissions
     */
    private function marcarPermisos(array $permissions): void
    {
        $this->checked = [];

        foreach ($permissions as $permission) {
            [$module, $action] = explode('.', $permission, 2);
            $this->checked[$module][$action] = true;
        }
    }

    /**
     * @return array<int, string>
     */
    private function selectedPermissions(): array
    {
        $allowed = array_flip(PermissionCatalog::names());
        $selected = [];

        foreach ($this->checked as $module => $actions) {
            foreach ($actions as $action => $enabled) {
                $permission = "{$module}.{$action}";

                if ($enabled && isset($allowed[$permission])) {
                    $selected[] = $permission;
                }
            }
        }

        return array_values(array_unique($selected));
    }

    /**
     * @param array<int, string> $permissions
     */
    private function sincronizarRolesExistentes(string $roleName, array $permissions): void
    {
        $teamKey = config('permission.column_names.team_foreign_key');

        foreach (PermissionCatalog::names() as $permissionName) {
            Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        try {
            Role::query()
                ->where('name', $roleName)
                ->where('guard_name', 'web')
                ->get()
                ->each(function (Role $role) use ($permissions, $teamKey) {
                    app(PermissionRegistrar::class)->setPermissionsTeamId($role->{$teamKey});
                    $role->syncPermissions($permissions);
                });
        } finally {
            app(PermissionRegistrar::class)->setPermissionsTeamId(null);
        }
    }
}
