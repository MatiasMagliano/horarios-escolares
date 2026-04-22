<div class="row g-4">
    <div class="col-xl-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Instituciones</h5>
                    <p class="text-muted small mb-0">
                        Administración de las instituciones del sistema.
                    </p>
                </div>
                <button type="button" wire:click="nuevo" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Institución
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Slug</th>
                                <th>Año Máximo</th>
                                <th>Turnos</th>
                                <th>Estado</th>
                                <th style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($instituciones as $institucion)
                            <tr>
                                <td class="fw-semibold">{{ $institucion->nombre_institucion }}</td>
                                <td>{{ $institucion->slug }}</td>
                                <td>{{ $institucion->anio_maximo }}</td>
                                <td>
                                    <span class="badge {{ $institucion->tiene_turno_maniana ? 'bg-success' : 'bg-secondary' }}">M</span>
                                    <span class="badge {{ $institucion->tiene_turno_tarde ? 'bg-success' : 'bg-secondary' }}">T</span>
                                    <span class="badge {{ $institucion->tiene_contraturno_maniana ? 'bg-success' : 'bg-secondary' }}">CM</span>
                                    <span class="badge {{ $institucion->tiene_contraturno_tarde ? 'bg-success' : 'bg-secondary' }}">CT</span>
                                </td>
                                <td>
                                    <span class="badge {{ $institucion->activo ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $institucion->activo ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" wire:click="editar({{ $institucion->id }})" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay instituciones cargadas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="institucionModal-{{ $this->getId() }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editandoId ? 'Editar Institución' : 'Nueva Institución' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="cancelar"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" wire:model.live="nombre_institucion" class="form-control">
                            @error('nombre_institucion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" wire:model="slug" class="form-control">
                            @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" wire:model="direccion" class="form-control">
                            @error('direccion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" wire:model="telefono" class="form-control">
                            @error('telefono') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" wire:model="email" class="form-control">
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Año Máximo</label>
                            <input type="number" wire:model="anio_maximo" class="form-control" min="1" max="9">
                            @error('anio_maximo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Turnos Disponibles</label>
                            <div class="d-flex gap-3 mt-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" wire:model="tiene_turno_maniana" id="tm">
                                    <label class="form-check-label" for="tm">Mañana</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" wire:model="tiene_turno_tarde" id="tt">
                                    <label class="form-check-label" for="tt">Tarde</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" wire:model="tiene_contraturno_maniana" id="ctm">
                                    <label class="form-check-label" for="ctm">Contra. Mañana</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" wire:model="tiene_contraturno_tarde" id="ctt">
                                    <label class="form-check-label" for="ctt">Contra. Tarde</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2 mb-2">
                            <h6 class="border-bottom pb-2">Director/a</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Género</label>
                            <select wire:model="genero_director" class="form-select">
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" wire:model="nombre_director" class="form-control">
                        </div>

                        <div class="col-12 mt-2 mb-2">
                            <h6 class="border-bottom pb-2">Vicedirector/a</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Género</label>
                            <select wire:model="genero_vicedirector" class="form-select">
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" wire:model="nombre_vicedirector" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3 mt-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" wire:model="activo" id="institucionActivo">
                                <label class="form-check-label" for="institucionActivo">Institución activa</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" wire:click="cancelar">
                        Cancelar
                    </button>
                    <button type="button" wire:click="guardar" class="btn btn-primary">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    const modalEl = document.getElementById('institucionModal-{{ $this->getId() }}');

    $wire.on('abrir-modal-institucion', () => {
        if (!modalEl || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    });

    $wire.on('cerrar-modal-institucion', () => {
        if (!modalEl || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
    });
</script>
@endscript
