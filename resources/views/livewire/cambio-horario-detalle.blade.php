<div>
    <div class="card">
        <div class="card-body">

            <h6>Detalles del cambio</h6>

            @if($cambio->estado !== 'borrador')
            <div class="alert alert-warning">
                Este cambio ya no se puede modificar.
            </div>
            @endif

            @if($cambio->estado === 'borrador')

            <div class="row g-2 mb-3">

                <div class="col-md-3">
                    <select wire:model="horario_base_id" class="form-select">
                        <option value="">Horario base...</option>
                        @foreach($horariosBase as $h)
                        <option value="{{ $h->id }}">
                            {{ $h->descripcion }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="number"
                        wire:model="dia_nuevo"
                        class="form-control"
                        placeholder="Día">
                </div>

                <div class="col-md-3">
                    <button wire:click="agregar"
                        class="btn btn-primary w-100">
                        Agregar detalle
                    </button>
                </div>

            </div>

            @endif

            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Horario original</th>
                        <th>Nuevo día</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cambio->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->horarioBase->descripcion }}</td>
                        <td>{{ $detalle->dia_nuevo ?? '-' }}</td>
                        <td>
                            @if($cambio->estado === 'borrador')
                            <button wire:click="eliminar({{ $detalle->id }})"
                                class="btn btn-sm btn-danger">
                                X
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>