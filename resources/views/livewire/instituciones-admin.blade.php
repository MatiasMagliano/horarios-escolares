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
                                <th style="width: 16%;">Acciones</th>
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
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Herramientas de institución">
                                        <button type="button"
                                            wire:click="editar({{ $institucion->id }})"
                                            class="btn btn-outline-primary"
                                            title="Editar escuela">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button"
                                            wire:click="abrirConfiguracionBloquesPara({{ $institucion->id }})"
                                            class="btn btn-outline-info"
                                            title="Editar grilla">
                                            <i class="bi bi-calendar3"></i>
                                        </button>
                                        <button type="button"
                                            wire:click="confirmarEliminar({{ $institucion->id }})"
                                            class="btn btn-outline-danger"
                                            title="Eliminar escuela">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
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

                        @if (!$editandoId)
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Template de bloques horarios</label>
                                <select wire:model.live="bloqueHorarioTemplate" class="form-select">
                                    @foreach ($bloqueHorarioTemplates as $valor => $label)
                                        <option value="{{ $valor }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('bloqueHorarioTemplate') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            @if ($bloqueHorarioTemplate === \App\Support\Horarios\BloqueHorarioTemplateManager::TEMPLATE_PERSONALIZADO)
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hora de inicio de la jornada</label>
                                    <input type="time" wire:model.live="personalizadoHoraInicio" class="form-control">
                                    @error('personalizadoHoraInicio') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Cantidad de bloques</label>
                                    <input type="number" wire:model.live.debounce.500ms="personalizadoCantidadBloques" class="form-control" min="1" max="12">
                                    @error('personalizadoCantidadBloques') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Cantidad de recreos</label>
                                    <input type="number" wire:model.live.debounce.500ms="personalizadoCantidadRecreos" class="form-control" min="0" max="5">
                                    @error('personalizadoCantidadRecreos') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="table-responsive border rounded">
                                        <table class="table table-sm align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 12%;">Orden</th>
                                                    <th style="width: 18%;">Nombre</th>
                                                    <th style="width: 20%;">Inicio</th>
                                                    <th style="width: 20%;">Fin</th>
                                                    <th style="width: 15%;">Tipo</th>
                                                    <th style="width: 15%;">Duración</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($personalizadoPreviewManiana as $bloque)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-secondary">{{ $bloque['orden'] }}</span>
                                                        </td>
                                                        <td class="fw-semibold">{{ $bloque['nombre'] }}</td>
                                                        <td>{{ $bloque['hora_inicio'] }}</td>
                                                        <td>{{ $bloque['hora_fin'] }}</td>
                                                        <td>
                                                            <span class="badge {{ $bloque['tipo'] === 'recreo' ? 'bg-info' : 'bg-success' }}">
                                                                {{ ucfirst($bloque['tipo']) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $bloque['duracion'] }} min</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endif

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
                    @if ($editandoId)
                        <button type="button" wire:click="abrirConfiguracionBloques" class="btn btn-info">
                            <i class="bi bi-clock-history me-1"></i> Configurar Bloques
                        </button>
                    @else
                        <button type="button" wire:click="guardarYAbrirConfiguracionBloques" class="btn btn-info">
                            <i class="bi bi-clock-history me-1"></i> Guardar y configurar bloques
                        </button>
                    @endif
                    <button type="button" wire:click="guardar" class="btn btn-primary">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Configuración de Bloques Horarios -->
    @if ($mostrarConfiguracionBloques && $editandoId)
        <div class="modal d-block" style="background-color: rgba(0, 0, 0, 0.5);" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Configuración de Bloques Horarios</h5>
                        <button type="button" class="btn-close" wire:click="cerrarConfiguracionBloques"></button>
                    </div>
                    <div class="modal-body">
                        @livewire('configuracion-bloque-horario', ['institucionId' => $editandoId], key('config-' . $editandoId))
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="cerrarConfiguracionBloques" class="btn btn-secondary">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($eliminandoId)
        <div class="modal d-block" style="background-color: rgba(0, 0, 0, 0.5);" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">Eliminar escuela</h5>
                            <p class="text-muted small mb-0">{{ $eliminandoNombre }}</p>
                        </div>
                        <button type="button" class="btn-close" wire:click="cancelarEliminacion"></button>
                    </div>

                    <div class="modal-body">
                        @if ($eliminacionBloqueada)
                            <div class="alert alert-warning mb-3">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                {{ $eliminacionBloqueada }}
                            </div>
                        @else
                            <div class="alert alert-danger mb-3">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Esta acción es irreversible. Se eliminarán los datos operativos asociados a esta escuela.
                            </div>
                        @endif

                        <div class="table-responsive border rounded mb-3">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Dato afectado</th>
                                        <th class="text-end" style="width: 20%;">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($eliminacionResumen as $label => $cantidad)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td class="text-end fw-semibold">{{ $cantidad }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <label class="form-label">
                            Escribí <span class="fw-semibold">{{ $eliminandoSlug }}</span> para confirmar
                        </label>
                        <input type="text"
                            wire:model.live="eliminacionConfirmacion"
                            class="form-control"
                            @disabled($eliminacionBloqueada)>
                        @error('eliminacionConfirmacion') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="cancelarEliminacion" class="btn btn-outline-secondary">
                            Cancelar
                        </button>
                        <button type="button"
                            wire:click="eliminarInstitucion"
                            class="btn btn-danger"
                            @disabled($eliminacionBloqueada || $eliminacionConfirmacion !== $eliminandoSlug)>
                            <i class="bi bi-trash me-1"></i> Eliminar escuela
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
