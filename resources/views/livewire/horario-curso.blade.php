<div>
    <div class="card">
        <div class="card-header">
            {{-- SELECTORES --}}
            <div class="mb-4 flex gap-4 items-end">
                <div class="form-group">
                    <label class="form-label" for="cursoId">Curso</label>
                    <select wire:model.live="cursoId" wire:key="curso-select" id="cursoId" class="form-select form-select-lg">
                        <option selected value="">Seleccione un curso...</option>
                        @foreach($this->cursos as $curso)
                            <option value="{{ $curso->id }}">
                                {{ $curso->anio }}º {{ $curso->division }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body">
            {{-- GRILLA --}}
            @if(!$cursoId)
                <div class="alert alert-secondary">
                    No hay curso seleccionado...
                </div>
            @else
                @foreach($this->grillas as $turno => $grilla)

                    <h4 class="mt-4 mb-2">
                        {{-- Este $turno es diferente y no viene del modelo, es una variable local --}}
                        Turno {{ $this->designacionTurno($turno) }}
                    </h4>

                    <table class="table table-bordered table-sm table-fixed">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center align-middle" style="width: 10%;">·</th>
                                <th class="text-center align-middle" style="width: 18%;">Lunes</th>
                                <th class="text-center align-middle" style="width: 18%;">Martes</th>
                                <th class="text-center align-middle" style="width: 18%;">Miércoles</th>
                                <th class="text-center align-middle" style="width: 18%;">Jueves</th>
                                <th class="text-center align-middle" style="width: 18%;">Viernes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grilla as $orden => $dias)
                                @php
                                    $bloque = $dias['bloque'];
                                    $dias = $dias['dias'];
                                @endphp

                                @if($bloque->tipo === 'recreo')
                                    <tr class="table-secondary">
                                        <th class="text-center small text-muted align-middle">
                                            {{ $bloque->hora_inicio->format('H:i') }} - {{ $bloque->hora_fin->format('H:i') }}
                                        </th>
                                        <td colspan="5" class="text-center fw-bold small text-muted align-middle">
                                            RECREO
                                        </td>
                                    </tr>
                                    @continue
                                @endif
                                <tr>

                                    {{-- BLOQUE DE CLASES --}}
                                    <th class="bg-light small text-center align-middle">
                                        {{ $bloque->hora_inicio->format('H:i') }} - {{ $bloque->hora_fin->format('H:i') }}
                                    </th>

                                    @for($dia = 1; $dia <= 5; $dia++)
                                        <td class="text-center align-middle">
                                            @if(isset($dias[$dia]))
                                                <div class="fw-semibold text-center">
                                                    {{ $dias[$dia]->materia->nombre }}
                                                </div>
                                                <div class="text-muted small text-center">
                                                    {{ $dias[$dia]->docente->nombre }}
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted align-middle">
                                        Sin horarios cargados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endforeach
            @endif
        </div>
    </div>
</div>