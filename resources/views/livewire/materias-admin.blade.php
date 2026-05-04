<div class="row g-4">
    <div class="col-xl-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1">Materias</h5>
                    <p class="text-muted small mb-0">
                        Catálogo global de materias para todas las escuelas.
                    </p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <input type="text" wire:model.live.debounce.300ms="busqueda" class="form-control form-control-sm" placeholder="Buscar materia...">
                    <button type="button" wire:click="nuevo" class="btn btn-primary btn-sm text-nowrap">
                        <i class="bi bi-plus-circle me-1"></i> Nueva Materia
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nombre de la Materia</th>
                                <th>Uso en Cursos</th>
                                <th style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materias as $materia)
                            <tr>
                                <td class="text-muted">{{ $materia->id }}</td>
                                <td class="fw-semibold">{{ $materia->nombre }}</td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill">
                                        {{ $materia->curso_materias_count }} asignaciones
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" wire:click="editar({{ $materia->id }})" class="btn btn-sm btn-outline-primary" title="Editar materia">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    @if($busqueda)
                                        No se encontraron materias que coincidan con "{{ $busqueda }}".
                                    @else
                                        No hay materias cargadas en el sistema.
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($materias->hasPages())
            <div class="card-footer bg-white border-top-0 pt-3">
                {{ $materias->setPath('/admin/materias')->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="materiaModal-{{ $this->getId() }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit.prevent="guardar">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editandoId ? 'Editar Materia' : 'Nueva Materia' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="cancelar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre de la Materia <span class="text-danger">*</span></label>
                            <input type="text" wire:model="nombre" class="form-control" placeholder="Ej: MATEMÁTICA I" required autofocus>
                            @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                            <div class="form-text">El nombre se guardará automáticamente en mayúsculas para mantener consistencia.</div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" wire:click="cancelar">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@script
<script>
    const modalEl = document.getElementById('materiaModal-{{ $this->getId() }}');

    $wire.on('abrir-modal-materia', () => {
        if (!modalEl || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    });

    $wire.on('cerrar-modal-materia', () => {
        if (!modalEl || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
    });
</script>
@endscript
