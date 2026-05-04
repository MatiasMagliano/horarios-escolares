<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex flex-column flex-lg-row gap-3 justify-content-between align-items-lg-center">
                <div>
                    <h5 class="mb-1">Usuarios</h5>
                    <p class="text-muted small mb-0">
                        Administración global de usuarios, escuelas vinculadas y roles por escuela.
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <input type="search"
                        wire:model.live.debounce.300ms="busqueda"
                        class="form-control"
                        placeholder="Buscar usuario...">
                    <button type="button" wire:click="nuevo" class="btn btn-primary text-nowrap">
                        <i class="bi bi-person-plus me-1"></i> Nuevo usuario
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Usuario</th>
                                <th>Escuela activa</th>
                                <th>Escuelas y roles</th>
                                <th>Alcance</th>
                                <th class="text-center" style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usuarios as $usuario)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $usuario->name }}</div>
                                        <div class="text-muted small">{{ $usuario->email }}</div>
                                    </td>
                                    <td>
                                        {{ $usuario->institucionActiva?->nombre_institucion ?? 'Sin escuela activa' }}
                                    </td>
                                    <td>
                                        @forelse ($usuario->instituciones as $institucion)
                                            <div class="mb-1">
                                                <span class="fw-semibold">{{ $institucion->nombre_institucion }}</span>
                                                @foreach (($rolesUsuarios[$usuario->id][$institucion->id] ?? []) as $role)
                                                    <span class="badge bg-secondary ms-1">{{ $role }}</span>
                                                @endforeach
                                            </div>
                                        @empty
                                            <span class="text-muted">Sin escuelas vinculadas</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        @if ($usuario->is_super_admin)
                                            <span class="badge bg-danger-subtle text-danger">Super-admin</span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary">Usuario escuela</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                            wire:click="editar({{ $usuario->id }})"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Editar usuario">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay usuarios para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($mostrarFormulario)
        @php
            $seleccionadas = collect($institucionesSeleccionadas)->map(fn ($id) => (int) $id)->all();
        @endphp

        <div class="modal d-block" style="background-color: rgba(0, 0, 0, 0.5);" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">{{ $editandoId ? 'Editar usuario' : 'Nuevo usuario' }}</h5>
                            <p class="text-muted small mb-0">Definí sus datos, escuelas vinculadas y rol en cada escuela.</p>
                        </div>
                        <button type="button" class="btn-close" wire:click="cancelar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" wire:model="name" class="form-control">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" wire:model="email" class="form-control">
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Contraseña
                                    @if ($editandoId)
                                        <span class="text-muted small">(dejar vacía para no cambiarla)</span>
                                    @endif
                                </label>
                                <input type="password" wire:model="password" class="form-control">
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Escuela activa</label>
                                <select wire:model="institucion_activa_id" class="form-select">
                                    <option value="">Sin escuela activa</option>
                                    @foreach ($instituciones as $institucion)
                                        <option value="{{ $institucion->id }}">
                                            {{ $institucion->nombre_institucion }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('institucion_activa_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="checkbox"
                                        wire:model="is_super_admin"
                                        class="form-check-input"
                                        role="switch"
                                        id="usuario-super-admin">
                                    <label class="form-check-label" for="usuario-super-admin">
                                        Super-admin
                                    </label>
                                </div>
                                @error('is_super_admin') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-12">
                                <div class="border rounded">
                                    <div class="bg-light border-bottom px-3 py-2">
                                        <h6 class="mb-0">Escuelas vinculadas</h6>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="width: 8%;">Usar</th>
                                                    <th>Escuela</th>
                                                    <th style="width: 30%;">Rol</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($instituciones as $institucion)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox"
                                                                wire:model.live="institucionesSeleccionadas"
                                                                class="form-check-input"
                                                                value="{{ $institucion->id }}"
                                                                id="institucion-usuario-{{ $institucion->id }}">
                                                        </td>
                                                        <td>
                                                            <label for="institucion-usuario-{{ $institucion->id }}" class="fw-semibold">
                                                                {{ $institucion->nombre_institucion }}
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <select wire:model="rolesPorInstitucion.{{ $institucion->id }}"
                                                                class="form-select form-select-sm"
                                                                @disabled(! in_array($institucion->id, $seleccionadas, true))>
                                                                <option value="">Seleccionar rol</option>
                                                                @foreach ($roles as $role)
                                                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error("rolesPorInstitucion.{$institucion->id}") <small class="text-danger">{{ $message }}</small> @enderror
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">No hay escuelas cargadas.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @error('institucionesSeleccionadas') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="cancelar" class="btn btn-outline-secondary">
                            Cancelar
                        </button>
                        <button type="button" wire:click="guardar" class="btn btn-primary">
                            Guardar usuario
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
