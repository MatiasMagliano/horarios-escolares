<div class="row g-4">
    <div class="col-xl-4">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-1">Nuevo espacio</h5>
                <p class="text-muted small mb-0">
                    Registrá espacios físicos reutilizables para cursos y grillas.
                </p>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" wire:model="nombre" class="form-control" placeholder="Ej: Aula 3">
                    @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo</label>
                    <select wire:model="tipo" class="form-select">
                        <option value="">Seleccione un tipo...</option>
                        @foreach($tipos as $valor => $etiqueta)
                        <option value="{{ $valor }}">{{ $etiqueta }}</option>
                        @endforeach
                    </select>
                    @error('tipo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" wire:model="activo" id="espacioActivo">
                    <label class="form-check-label" for="espacioActivo">Espacio activo</label>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-end">
                <button type="button" wire:click="guardar" class="btn btn-primary">Crear espacio</button>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-1">Espacios registrados</h5>
                <p class="text-muted small mb-0">
                    Catálogo base de espacios físicos disponibles para asignar a los cursos.
                </p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Uso</th>
                                <th style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($espacios as $espacio)
                            <tr>
                                <td class="fw-semibold">{{ $espacio->nombre }}</td>
                                <td>{{ $tipos[$espacio->tipo] ?? $espacio->tipo ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $espacio->activo ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $espacio->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>{{ $espacio->curso_materias_count }}</td>
                                <td class="text-center">
                                    <button type="button" wire:click="editar({{ $espacio->id }})" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay espacios cargados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editarEspacioModal-{{ $this->getId() }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar espacio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="cancelar"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" wire:model="nombre_edicion" class="form-control" placeholder="Ej: Aula 3">
                        @error('nombre_edicion') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select wire:model="tipo_edicion" class="form-select">
                            <option value="">Seleccione un tipo...</option>
                            @foreach($tipos as $valor => $etiqueta)
                            <option value="{{ $valor }}">{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                        @error('tipo_edicion') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" wire:model="activo_edicion" id="espacioActivoEdicion-{{ $this->getId() }}">
                        <label class="form-check-label" for="espacioActivoEdicion-{{ $this->getId() }}">Espacio activo</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" wire:click="cancelar">
                        Cancelar
                    </button>
                    <button type="button" wire:click="actualizar" class="btn btn-primary">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    const editarEspacioModalEl = document.getElementById('editarEspacioModal-{{ $this->getId() }}');

    $wire.on('abrir-modal-editar-espacio', () => {
        if (!editarEspacioModalEl || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(editarEspacioModalEl).show();
    });

    $wire.on('cerrar-modal-editar-espacio', () => {
        if (!editarEspacioModalEl || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(editarEspacioModalEl).hide();
    });
</script>
@endscript
