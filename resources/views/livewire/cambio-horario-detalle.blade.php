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

            @error('detalle') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

            <div class="row g-2 mb-3">

                <div class="col-md-4">
                    <select wire:model="horario_base_id" class="form-select">
                        <option value="">Horario base...</option>
                        @foreach($horariosBase as $h)
                        @php
                        $bloqueTexto = $h->bloque
                            ? $h->bloque->nombre . ' (' . $h->bloque->hora_inicio?->format('H:i') . ' - ' . $h->bloque->hora_fin?->format('H:i') . ')'
                            : 'Bloque sin datos';
                        @endphp
                        <option value="{{ $h->id }}">
                            {{ ['','Lun','Mar','Mié','Jue','Vie','Sáb','Dom'][$h->dia_semana] ?? $h->dia_semana }}
                            · {{ $bloqueTexto }}
                        </option>
                        @endforeach
                    </select>
                    @error('horario_base_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-2">
                    <select wire:model="dia_nuevo" class="form-select">
                        <option value="">Día...</option>
                        <option value="1">Lunes</option>
                        <option value="2">Martes</option>
                        <option value="3">Miércoles</option>
                        <option value="4">Jueves</option>
                        <option value="5">Viernes</option>
                    </select>
                    @error('dia_nuevo') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <select wire:model="nuevo_bloque_id" class="form-select">
                        <option value="">Bloque...</option>
                        @foreach($bloques as $bloque)
                        <option value="{{ $bloque->id }}">
                            {{ $bloque->nombre }} · {{ $bloque->hora_inicio?->format('H:i') }} - {{ $bloque->hora_fin?->format('H:i') }}
                        </option>
                        @endforeach
                    </select>
                    @error('nuevo_bloque_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <select wire:model="nuevo_curso_id" class="form-select">
                        <option value="">Curso...</option>
                        @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}">{{ $curso->nombre_completo }}</option>
                        @endforeach
                    </select>
                    @error('nuevo_curso_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <select wire:model="nuevo_docente_id" class="form-select">
                        <option value="">Docente...</option>
                        @foreach($docentes as $docente)
                        <option value="{{ $docente->id }}">{{ $docente->nombre_completo ?? $docente->nombre }}</option>
                        @endforeach
                    </select>
                    @error('nuevo_docente_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-5">
                    <input type="text"
                        wire:model="observaciones"
                        class="form-control"
                        placeholder="Observaciones">
                    @error('observaciones') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <button type="button" wire:click="agregar" class="btn btn-primary w-100">
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
                        <th>Nuevo bloque</th>
                        <th>Nuevo curso</th>
                        <th>Nuevo docente</th>
                        <th>Obs.</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cambio->detalles as $detalle)
                    <tr>
                        <td>
                            @php
                            $base = $detalle->horarioBase;
                            $baseBloque = $base?->bloque
                                ? $base->bloque->nombre . ' (' . $base->bloque->hora_inicio?->format('H:i') . ' - ' . $base->bloque->hora_fin?->format('H:i') . ')'
                                : '—';
                            @endphp
                            <div>{{ $base?->cursoMateria?->materia?->nombre ?? '—' }}</div>
                            <div class="small text-muted">
                                {{ ['','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'][$base?->dia_semana] ?? '—' }}
                                · {{ $baseBloque }}
                            </div>
                        </td>
                        <td>{{ ['','Lunes','Martes','Miércoles','Jueves','Viernes'][$detalle->dia_nuevo] ?? '—' }}</td>
                        <td>{{ $detalle->bloqueNuevo?->nombre ?? '—' }}</td>
                        <td>{{ $detalle->cursoNuevo?->nombre_completo ?? '—' }}</td>
                        <td>{{ $detalle->docenteNuevo?->nombre_completo ?? $detalle->docenteNuevo?->nombre ?? '—' }}</td>
                        <td>{{ $detalle->observaciones ?: '—' }}</td>
                        <td>
                            @if($cambio->estado === 'borrador')
                            <button type="button" wire:click="eliminar({{ $detalle->id }})"
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
