<?php

namespace App\Livewire;

use App\Models\Institucion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UsuariosAdmin extends Component
{
    private const ROLES = ['admin', 'preceptor', 'solicitante', 'aprobador', 'secretario'];

    public string $busqueda = '';
    public ?int $editandoId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public bool $is_super_admin = false;
    public ?int $institucion_activa_id = null;
    public array $institucionesSeleccionadas = [];
    public array $rolesPorInstitucion = [];
    public bool $mostrarFormulario = false;

    public function boot(): void
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editandoId)],
            'password' => [$this->editandoId ? 'nullable' : 'required', 'string', 'min:8'],
            'is_super_admin' => ['boolean'],
            'institucion_activa_id' => ['nullable', 'integer', Rule::exists('datos_institucionales', 'id')],
            'institucionesSeleccionadas' => ['array'],
            'institucionesSeleccionadas.*' => ['integer', Rule::exists('datos_institucionales', 'id')],
            'rolesPorInstitucion' => ['array'],
            'rolesPorInstitucion.*' => ['nullable', 'string', Rule::in(self::ROLES)],
        ];
    }

    public function nuevo(): void
    {
        $this->resetFormulario();
        $this->mostrarFormulario = true;
    }

    public function editar(int $id): void
    {
        $user = User::query()
            ->with('instituciones')
            ->findOrFail($id);

        $this->resetValidation();
        $this->editandoId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->is_super_admin = (bool) $user->is_super_admin;
        $this->institucion_activa_id = $user->institucion_activa_id;
        $this->institucionesSeleccionadas = $user->instituciones
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
        $this->rolesPorInstitucion = $this->rolesActualesDelUsuario($user->id);
        $this->mostrarFormulario = true;
    }

    public function cancelar(): void
    {
        $this->resetFormulario();
    }

    public function updatedInstitucionesSeleccionadas(): void
    {
        $seleccionadas = collect($this->institucionesSeleccionadas)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->institucionesSeleccionadas = $seleccionadas;

        foreach ($seleccionadas as $institucionId) {
            $this->rolesPorInstitucion[$institucionId] ??= 'admin';
        }

        $this->rolesPorInstitucion = collect($this->rolesPorInstitucion)
            ->only($seleccionadas)
            ->all();

        if ($this->institucion_activa_id && ! in_array((int) $this->institucion_activa_id, $seleccionadas, true)) {
            $this->institucion_activa_id = $seleccionadas[0] ?? null;
        }
    }

    public function guardar(): void
    {
        $this->validate();

        $instituciones = collect($this->institucionesSeleccionadas)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        if (! $this->is_super_admin && $instituciones->isEmpty()) {
            $this->addError('institucionesSeleccionadas', 'Seleccioná al menos una escuela para usuarios no super-admin.');

            return;
        }

        foreach ($instituciones as $institucionId) {
            if (blank($this->rolesPorInstitucion[$institucionId] ?? null)) {
                $this->addError("rolesPorInstitucion.{$institucionId}", 'Seleccioná un rol para esta escuela.');

                return;
            }
        }

        if (
            $this->editandoId
            && ! $this->is_super_admin
            && User::query()->whereKey($this->editandoId)->where('is_super_admin', true)->exists()
            && User::query()->where('is_super_admin', true)->whereKeyNot($this->editandoId)->count() === 0
        ) {
            $this->addError('is_super_admin', 'No podés quitar el último super-admin del sistema.');

            return;
        }

        if (! $this->institucion_activa_id && $instituciones->isNotEmpty()) {
            $this->institucion_activa_id = $instituciones->first();
        }

        if (
            ! $this->is_super_admin
            && $this->institucion_activa_id
            && ! $instituciones->contains((int) $this->institucion_activa_id)
        ) {
            $this->addError('institucion_activa_id', 'La escuela activa debe estar entre las escuelas vinculadas.');

            return;
        }

        DB::transaction(function () use ($instituciones) {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'institucion_activa_id' => $this->institucion_activa_id,
                'is_super_admin' => $this->is_super_admin,
            ];

            if (filled($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            if ($this->editandoId) {
                $user = User::findOrFail($this->editandoId);
                $user->update($data);
            } else {
                $user = User::create($data);
            }

            $user->instituciones()->sync(
                $instituciones
                    ->mapWithKeys(fn ($institucionId) => [$institucionId => ['activo' => true]])
                    ->all()
            );

            $this->sincronizarRoles($user, $instituciones->all());
        });

        app(PermissionRegistrar::class)->setPermissionsTeamId(null);

        session()->flash('success', $this->editandoId ? 'Usuario actualizado correctamente.' : 'Usuario creado correctamente.');
        $this->resetFormulario();
    }

    private function sincronizarRoles(User $user, array $instituciones): void
    {
        $table = config('permission.table_names.model_has_roles');
        $rolePivotKey = config('permission.column_names.role_pivot_key') ?? 'role_id';
        $modelKey = config('permission.column_names.model_morph_key');
        $teamKey = config('permission.column_names.team_foreign_key');

        DB::table($table)
            ->where($modelKey, $user->getKey())
            ->where('model_type', $user->getMorphClass())
            ->delete();

        foreach ($instituciones as $institucionId) {
            $roleName = $this->rolesPorInstitucion[$institucionId] ?? null;

            if (! $roleName) {
                continue;
            }

            $role = Role::query()->firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
                $teamKey => $institucionId,
            ]);

            DB::table($table)->insert([
                $rolePivotKey => $role->id,
                $modelKey => $user->getKey(),
                'model_type' => $user->getMorphClass(),
                $teamKey => $institucionId,
            ]);
        }
    }

    private function rolesActualesDelUsuario(int $userId): array
    {
        $table = config('permission.table_names.model_has_roles');
        $rolePivotKey = config('permission.column_names.role_pivot_key') ?? 'role_id';
        $modelKey = config('permission.column_names.model_morph_key');
        $teamKey = config('permission.column_names.team_foreign_key');

        return DB::table($table)
            ->join('roles', "{$table}.{$rolePivotKey}", '=', 'roles.id')
            ->where("{$table}.{$modelKey}", $userId)
            ->where("{$table}.model_type", (new User())->getMorphClass())
            ->pluck('roles.name', "{$table}.{$teamKey}")
            ->mapWithKeys(fn ($role, $institucionId) => [(int) $institucionId => $role])
            ->all();
    }

    private function rolesParaUsuarios(array $userIds): array
    {
        if (empty($userIds)) {
            return [];
        }

        $table = config('permission.table_names.model_has_roles');
        $rolePivotKey = config('permission.column_names.role_pivot_key') ?? 'role_id';
        $modelKey = config('permission.column_names.model_morph_key');
        $teamKey = config('permission.column_names.team_foreign_key');
        $roles = [];

        DB::table($table)
            ->join('roles', "{$table}.{$rolePivotKey}", '=', 'roles.id')
            ->whereIn("{$table}.{$modelKey}", $userIds)
            ->where("{$table}.model_type", (new User())->getMorphClass())
            ->select("{$table}.{$modelKey} as user_id", "{$table}.{$teamKey} as institucion_id", 'roles.name')
            ->orderBy('roles.name')
            ->get()
            ->each(function ($row) use (&$roles) {
                $roles[(int) $row->user_id][(int) $row->institucion_id][] = $row->name;
            });

        return $roles;
    }

    private function resetFormulario(): void
    {
        $this->resetValidation();
        $this->reset([
            'editandoId',
            'name',
            'email',
            'password',
            'is_super_admin',
            'institucion_activa_id',
            'institucionesSeleccionadas',
            'rolesPorInstitucion',
            'mostrarFormulario',
        ]);
    }

    public function render()
    {
        $usuarios = User::query()
            ->with(['instituciones' => fn ($query) => $query->orderBy('nombre_institucion'), 'institucionActiva'])
            ->when($this->busqueda !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->busqueda}%")
                        ->orWhere('email', 'like', "%{$this->busqueda}%");
                });
            })
            ->orderByDesc('is_super_admin')
            ->orderBy('name')
            ->get();

        return view('livewire.usuarios-admin', [
            'usuarios' => $usuarios,
            'instituciones' => Institucion::query()->orderBy('nombre_institucion')->get(),
            'roles' => self::ROLES,
            'rolesUsuarios' => $this->rolesParaUsuarios($usuarios->pluck('id')->all()),
        ]);
    }
}
