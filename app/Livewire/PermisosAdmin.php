<?php

namespace App\Livewire;

use App\Models\Institucion;
use App\Support\Permissions\PermissionCatalog;
use App\Support\Permissions\RolePermissionPresetResolver;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermisosAdmin extends Component
{
    public ?int $institucionId = null;
    public string $roleName = 'administrador';
    public array $checked = [];

    public function boot(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }

    public function mount(): void
    {
        $this->institucionId = Institucion::query()->orderBy('nombre_institucion')->value('id');
        $this->cargarPermisos();
    }

    protected function rules(): array
    {
        return [
            'institucionId' => ['required', 'integer', Rule::exists('datos_institucionales', 'id')],
            'roleName' => ['required', 'string', Rule::in(PermissionCatalog::ROLES)],
            'checked' => ['array'],
        ];
    }

    public function updatedInstitucionId(): void
    {
        $this->cargarPermisos();
    }

    public function updatedRoleName(): void
    {
        $this->cargarPermisos();
    }

    public function cargarPermisos(): void
    {
        $this->checked = [];

        if (! $this->institucionId || ! in_array($this->roleName, PermissionCatalog::ROLES, true)) {
            return;
        }

        app(PermissionRegistrar::class)->setPermissionsTeamId($this->institucionId);

        $role = Role::query()
            ->where('name', $this->roleName)
            ->where('guard_name', 'web')
            ->where(config('permission.column_names.team_foreign_key'), $this->institucionId)
            ->first();

        $permissions = $role?->permissions()->pluck('name')->all() ?? [];

        foreach ($permissions as $permission) {
            [$module, $action] = explode('.', $permission, 2);
            $this->checked[$module][$action] = true;
        }

        app(PermissionRegistrar::class)->setPermissionsTeamId(null);
    }

    public function aplicarPreset(): void
    {
        $this->checked = [];

        foreach (RolePermissionPresetResolver::permissionsForRole($this->roleName) as $permission) {
            [$module, $action] = explode('.', $permission, 2);
            $this->checked[$module][$action] = true;
        }
    }

    public function guardar(): void
    {
        $this->validate();

        $permissionsToSync = $this->selectedPermissions();

        DB::transaction(function () use ($permissionsToSync) {
            $teamKey = config('permission.column_names.team_foreign_key');

            foreach (PermissionCatalog::names() as $permissionName) {
                Permission::query()->firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
            }

            $role = Role::query()->firstOrCreate([
                'name' => $this->roleName,
                'guard_name' => 'web',
                $teamKey => $this->institucionId,
            ]);

            app(PermissionRegistrar::class)->setPermissionsTeamId($this->institucionId);
            $role->syncPermissions($permissionsToSync);
            app(PermissionRegistrar::class)->setPermissionsTeamId(null);
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        session()->flash('success', 'Permisos del rol guardados correctamente.');
        $this->cargarPermisos();
    }

    public function render()
    {
        return view('livewire.permisos-admin', [
            'instituciones' => Institucion::query()->orderBy('nombre_institucion')->get(),
            'roles' => PermissionCatalog::ROLES,
            'roleLabels' => PermissionCatalog::roleLabels(),
            'modules' => PermissionCatalog::modules(),
        ]);
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
}
