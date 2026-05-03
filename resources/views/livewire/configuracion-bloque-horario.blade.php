<div class="configuracion-bloque-horario">
    @if ($institucion)
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Configuración de Bloques Horarios - {{ $institucion->nombre_institucion }}</h6>
            </div>

            <div class="card-body">
                <!-- Selector de Turnos -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Seleccionar Turno</label>
                    <div class="btn-group w-100" role="group">
                        @foreach (['maniana' => 'Mañana', 'tarde' => 'Tarde', 'contraturno_maniana' => 'Contra. Mañana', 'contraturno_tarde' => 'Contra. Tarde'] as $valor => $label)
                            @if ($institucion->tieneTurno($valor))
                                <button type="button"
                                    wire:click="cambiarTurno('{{ $valor }}')"
                                    class="btn btn-outline-primary {{ $turnoSeleccionado === $valor ? 'active' : '' }}">
                                    {{ $label }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Tabla de Bloques -->
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 10%;">Orden</th>
                                <th style="width: 15%;">Nombre</th>
                                <th style="width: 20%;">Hora Inicio</th>
                                <th style="width: 20%;">Hora Fin</th>
                                <th style="width: 15%;">Duración</th>
                                <th style="width: 12%;">Tipo</th>
                                <th style="width: 8%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bloques as $bloque)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $bloque['orden'] }}</span>
                                    </td>
                                    <td class="fw-semibold">{{ $bloque['nombre'] }}</td>
                                    <td>{{ $bloque['hora_inicio'] }}</td>
                                    <td>{{ $bloque['hora_fin'] }}</td>
                                    <td>{{ $bloque['duracion'] }} min</td>
                                    <td>
                                        <span class="badge {{ $bloque['tipo'] === 'recreo' ? 'bg-info' : 'bg-success' }}">
                                            {{ ucfirst($bloque['tipo']) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                            wire:click="editarBloque({{ $bloque['id'] }})"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" 
                                            wire:click="eliminarBloque({{ $bloque['id'] }})"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Eliminar"
                                            onclick="return confirm('¿Está seguro?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No hay bloques configurados para este turno
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Botón Agregar -->
                @if (!$editandoBloqueId && !$agregarNuevo)
                    <button type="button" 
                        wire:click="$set('agregarNuevo', true)"
                        class="btn btn-success mb-4">
                        <i class="bi bi-plus-circle me-1"></i> Agregar Bloque
                    </button>
                @endif

                <!-- Formulario Edición/Nuevo -->
                @if ($editandoBloqueId || $agregarNuevo)
                    <div class="border-top pt-4">
                        <h6 class="mb-3">{{ $agregarNuevo ? 'Nuevo Bloque' : 'Editar Bloque' }}</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" 
                                    wire:model="bloqueNombre"
                                    class="form-control"
                                    placeholder="M1, R1, etc">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Hora Inicio</label>
                                <input type="time" 
                                    wire:model="bloqueHoraInicio"
                                    class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Hora Fin</label>
                                <input type="time" 
                                    wire:model="bloqueHoraFin"
                                    class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Tipo</label>
                                <select wire:model="bloqueTipo" class="form-select">
                                    <option value="clase">Clase</option>
                                    <option value="recreo">Recreo</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            @if ($agregarNuevo)
                                <button type="button"
                                    wire:click="agregarBloque"
                                    class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Agregar
                                </button>
                            @else
                                <button type="button"
                                    wire:click="guardarBloque"
                                    class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Guardar
                                </button>
                            @endif
                            
                            <button type="button"
                                wire:click="cancelarEdicion"
                                class="btn btn-outline-secondary">
                                Cancelar
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Debe cargar una institución para configurar los bloques horarios
        </div>
    @endif
</div>
