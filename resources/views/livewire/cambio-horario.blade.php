<div>
    @if($modo === 'listado')

    <div class="d-flex justify-content-between mb-3">
        <h4>Cambios de Horario</h4>
        <button wire:click="nuevo" class="btn btn-primary">
            + Nuevo cambio
        </button>
    </div>

    <table class="table table-bordered table-sm">
        <thead>
            <tr class="text-center">
                <th>ID</th>
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
                <td>{{ $c->id }}</td>
                <td>{{ ucfirst($c->tipo) }}</td>
                <td>{{ $c->fecha_desde }}</td>
                <td>{{ $c->fecha_hasta ?? '-' }}</td>
                <td>
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
                <td class="text-center">

                    @if($c->estado === 'borrador')
                    <button wire:click="autorizar({{ $c->id }})"
                        class="btn btn-sm btn-outline-info">
                        Autorizar
                    </button>
                    @endif

                    @if($c->estado === 'autorizado')
                    <button wire:click="firmar({{ $c->id }})"
                        class="btn btn-sm btn-outline-warning">
                        Firmar
                    </button>
                    @endif

                    @if($c->estado === 'firmado')
                    <button wire:click="activar({{ $c->id }})"
                        class="btn btn-sm btn-outline-success">
                        Activar
                    </button>
                    @endif

                    @if($c->estado === 'activo')
                    <button wire:click="finalizar({{ $c->id }})"
                        class="btn btn-sm btn-outline-dark">
                        Finalizar
                    </button>
                    @endif

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

            <h5>Solicitud de cambio</h5>

            <form wire:submit.prevent="guardar">

                {{-- Tipo --}}
                <div class="mb-3">
                    <label>Tipo</label>
                    <select wire:model="tipo" class="form-select">
                        <option value="temporal">Temporal</option>
                        <option value="permanente">Permanente</option>
                    </select>
                </div>

                {{-- Fecha desde --}}
                <div class="mb-3">
                    <label>Desde</label>
                    <input type="date" wire:model="fecha_desde" class="form-control">
                </div>

                {{-- Fecha hasta --}}
                @if($tipo === 'temporal')
                <div class="mb-3">
                    <label>Hasta</label>
                    <input type="date" wire:model="fecha_hasta" class="form-control">
                </div>
                @endif

                {{-- Motivo --}}
                <div class="mb-3">
                    <label>Motivo</label>
                    <textarea wire:model="motivo"
                        class="form-control"
                        rows="4"></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button"
                        wire:click="$set('modo','listado')"
                        class="btn btn-secondary">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary">
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


</div>