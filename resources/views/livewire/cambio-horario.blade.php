<div>
    @if($modo === 'listado')

    <div class="d-flex justify-content-between mb-3">
        <h4>Cambios de Horario</h4>
        <button wire:click="nuevo" class="btn btn-primary">
            + Nuevo cambio
        </button>
    </div>

    @if (session()->has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <table class="table table-bordered table-sm">
        <thead>
            <tr class="text-center">
                <th>Tipo</th>
                <th>Desde</th>
                <th>Hasta</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cambios as $c)
            <tr>
                <td class="text-center align-middle">{{ ucfirst($c->tipo) }}</td>
                <td class="text-center align-middle">{{ $c->fecha_desde->format('d/m/Y') }}</td>
                <td class="text-center align-middle">{{ $c->fecha_hasta ? $c->fecha_hasta->format('d/m/Y') : '-' }}</td>
                <td class="text-center align-middle">
                    <span class="badge bg-{{ match($c->estado) {
                    'borrador' => 'secondary',
                    'autorizado' => 'info',
                    'firmado' => 'warning',
                    'activo' => 'success',
                    'finalizado' => 'dark',
                } }}">
                        {{ ucfirst($c->estado) }}
                    </span>
                </td>
                <td class="text-center align-middle">

                    <div class="btn-group" role="group">
                        <button wire:click="verDetalle({{ $c->id }})"
                            type="button"
                            class="btn btn-sm btn-outline-secondary">
                            Ver detalles
                        </button>

                        @if($c->puedeAutorizar())
                        <button wire:click="autorizar({{ $c->id }})"
                            type="button"
                            class="btn btn-sm btn-outline-info">
                            Autorizar
                        </button>
                        @endif

                        @if($c->estado === 'autorizado')
                        <button wire:click="firmar({{ $c->id }})"
                            type="button"
                            class="btn btn-sm btn-outline-warning">
                            Firmar
                        </button>
                        @endif

                        @if($c->estado === 'firmado')
                        <button wire:click="activar({{ $c->id }})"
                            type="button"
                            class="btn btn-sm btn-outline-success">
                            Activar
                        </button>
                        @endif

                        @if($c->estado === 'activo')
                        <button wire:click="finalizar({{ $c->id }})"
                            type="button"
                            class="btn btn-sm btn-outline-dark">
                            Finalizar
                        </button>
                        @endif
                    </div>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @endif

    {{-- MODO FORMULARIO --}}
    @if($modo === 'formulario')

    <div class="card">
        <div class="card-body">

            <h5>Solicitud de cambio de horario</h5>

            <form wire:submit.prevent="guardar">
                {{-- Tipo --}}
                <div class="mb-3">
                    <label>Vigencia</label>
                    <select wire:model="tipo" class="form-select">
                        <option value="temporal">Temporal</option>
                        <option value="permanente">Permanente</option>
                    </select>
                    @error('tipo') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Fecha desde --}}
                <div class="mb-3">
                    <label>Desde</label>
                    <input type="date" wire:model="fecha_desde" class="form-control">
                    @error('fecha_desde') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                {{-- Fecha hasta --}}
                @if($tipo === 'temporal')
                <div class="mb-3">
                    <label>Hasta</label>
                    <input type="date" wire:model="fecha_hasta" class="form-control">
                    @error('fecha_hasta') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                @endif

                {{-- Motivo --}}
                <div class="mb-3">
                    <label>Motivo</label>
                    <textarea wire:model="motivo"
                        class="form-control"
                        rows="4"></textarea>
                    @error('motivo') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button"
                        wire:click="$set('modo','listado')"
                        class="btn btn-secondary">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        Guardar borrador
                    </button>
                </div>

                @if($cambio)
                <hr class="my-4">
                <livewire:cambio-horario-detalles
                    :cambio="$cambio"
                    :key="'detalles-'.$cambio->id" />
                @endif
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL DETALLE CAMBIO DE HORARIO --}}
    <div wire:ignore.self class="modal fade" id="detalleCambioHorario" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del cambio de horario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Fecha de solicitud:</strong>
                            {{ $cambio ? $cambio->created_at->format('d/m/Y') : 'No se pudo cargar la fecha de solicitud.' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Solicitante:</strong>
                            {{ $cambio && $cambio->solicitante ? $cambio->solicitante->name : 'No se pudo cargar el solicitante.' }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Vigencia:</strong>
                            {{ $cambio ? ucfirst($cambio->tipo) : 'No se pudo cargar la vigencia.' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Fechas:</strong>
                            @if($cambio)
                                {{ $cambio->fecha_desde->format('d/m/Y') }}
                                @if($cambio->tipo === 'temporal')
                                - {{ $cambio->fecha_hasta ? $cambio->fecha_hasta->format('d/m/Y') : 'Sin fecha de fin' }}
                                @endif
                            @else
                                No se pudieron cargar las fechas.
                            @endif
                        </div>
                        <div class="col-md-3">
                            <strong>Estado:</strong>
                            {{ ucfirst($cambio ? $cambio->estado : 'No se pudo cargar el estado') }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <strong>Motivo:</strong> {{ $cambio ? $cambio->motivo : 'No se pudo cargar el motivo.' }}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>