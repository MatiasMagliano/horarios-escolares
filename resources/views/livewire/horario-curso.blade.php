<div>
    {{-- SELECTORES --}}
    <div class="mb-4 flex gap-4 items-end">
        <div>
            <label class="text-sm font-semibold">Curso</label>
            <select wire:model.live="cursoId" wire:key="curso-select" class="border rounded px-2 py-1">
                <option value="">Seleccione un curso...</option>
                @foreach($this->cursos as $curso)
                    <option value="{{ $curso->id }}">
                        {{ $curso->anio }}º {{ $curso->division }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- GRILLA --}}
    @if(!$cursoId)
        <div class="alert alert-secondary">
            No hay curso seleccionado...
        </div>
    @else
        @foreach($this->grillas as $turno => $grilla)

            <h4 class="mt-4 mb-2">
                Turno {{ ucfirst($turno) }}
            </h4>

            <table class="table table-bordered table-sm table-fixed">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 10%;">Bloque</th>
                        <th class="text-center" style="width: 18%;">Lunes</th>
                        <th class="text-center" style="width: 18%;">Martes</th>
                        <th class="text-center" style="width: 18%;">Miércoles</th>
                        <th class="text-center" style="width: 18%;">Jueves</th>
                        <th class="text-center" style="width: 18%;">Viernes</th>
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
                                <th class="text-center small text-muted">
                                    {{ $bloque->hora_inicio }} - {{ $bloque->hora_fin }}
                                </th>
                                <td colspan="5" class="text-center fw-bold small text-muted">
                                    RECREO
                                </td>
                            </tr>
                            @continue
                        @endif
                        <tr>

                            {{-- BLOQUE DE CLASES --}}
                            <th class="bg-light text-center">
                                {{ $bloque->hora_inicio }} - {{ $bloque->hora_fin }}
                            </th>

                            @for($dia = 1; $dia <= 5; $dia++)
                                <td class="text-center">
                                    @if(isset($dias[$dia]))
                                        <div class="fw-semibold">
                                            {{ $dias[$dia]->materia->nombre }}
                                        </div>
                                        <div class="text-muted small">
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
                            <td colspan="6" class="text-center text-muted">
                                Sin horarios cargados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endforeach
    @endif
</div>