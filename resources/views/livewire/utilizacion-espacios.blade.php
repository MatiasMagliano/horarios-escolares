<div>
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label" for="espacioSeleccionado">Espacio</label>
                    <select id="espacioSeleccionado" wire:model.live="espacioSeleccionado" class="form-select">
                        <option value="">Seleccione un espacio...</option>
                        @foreach($this->espacios as $espacio)
                        <option value="{{ $espacio->id }}">{{ $espacio->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-8">
                    @if($espacioSeleccionado)
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="small text-muted">
                            Cursos afectados por {{ $this->espacios->firstWhere('id', $espacioSeleccionado)?->nombre ?? 'espacio seleccionado' }}.
                            Desmarcá los cursos que quieras ocultar de la grilla.
                        </div>

                        <a
                            href="{{ route('pdf.utilizacion-espacios', ['espacio' => $espacioSeleccionado, 'cursos' => $cursoIdsVisibles]) }}"
                            class="btn btn-outline-danger btn-sm flex-shrink-0"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Descargar PDF
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            @if($espacioSeleccionado)
            <div class="mt-4">
                @if($this->cursosAfectados->isEmpty())
                <div class="alert alert-secondary mb-0">
                    No hay cursos con módulos vigentes para el espacio seleccionado.
                </div>
                @else
                <div class="row g-2">
                    @foreach($this->cursosAfectados as $curso)
                    <div class="col-md-4 col-lg-3">
                        <label class="form-check border rounded p-2 h-100">
                            <input
                                class="form-check-input me-2"
                                type="checkbox"
                                wire:model.live="cursoIdsVisibles"
                                value="{{ $curso->id }}"
                            >
                            <span class="form-check-label">
                                {{ $curso->anio }}º {{ $curso->division }}
                                <span class="d-block text-muted small">{{ $curso->turno_designacion }}</span>
                            </span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif
        </div>

        <div class="card-body">
            @if(!$espacioSeleccionado)
            <div class="alert alert-secondary mb-0">
                Seleccioná un espacio para visualizar su utilización.
            </div>
            @else
            @if($this->advertencias)
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Atención</h4>
                <hr>
                @foreach($this->advertencias as $advertencia)
                <p class="mb-0">{{ $advertencia }}</p>
                @endforeach
            </div>
            @endif

            @if($this->grillas->isEmpty())
            <div class="alert alert-secondary mb-0">
                No hay módulos vigentes para los cursos visibles en este espacio.
            </div>
            @else
            @foreach($this->grillas as $turno => $grilla)
            <h4 class="mt-4 mb-2">Turno {{ $this->designacionTurno($turno) }}</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center align-middle" style="width: 10%;">HORA/DÍA</th>
                            <th class="text-center align-middle" style="width: 18%;">Lunes</th>
                            <th class="text-center align-middle" style="width: 18%;">Martes</th>
                            <th class="text-center align-middle" style="width: 18%;">Miércoles</th>
                            <th class="text-center align-middle" style="width: 18%;">Jueves</th>
                            <th class="text-center align-middle" style="width: 18%;">Viernes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grilla as $orden => $fila)
                        @php
                            $bloque = $fila['bloque'];
                            $dias = $fila['dias'];
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
                            <th class="bg-light small text-center align-middle">
                                {{ $bloque->hora_inicio->format('H:i') }} - {{ $bloque->hora_fin->format('H:i') }}
                            </th>

                            @for($dia = 1; $dia <= 5; $dia++)
                            @php
                                $ocupaciones = $dias->get($dia, collect());
                            @endphp
                            <td class="align-top small">
                                @forelse($ocupaciones as $ocupacion)
                                <div class="border rounded p-1 mb-1 bg-light">
                                    <div class="fw-semibold">
                                        {{ $ocupacion->curso->anio }}º {{ $ocupacion->curso->division }} ({{ $ocupacion->curso->turno_designacion }})
                                    </div>
                                    <div>
                                        {{ $ocupacion->cursoMateria?->materia?->nombre ?? '—' }}
                                    </div>
                                    <div class="text-muted">
                                        {{ $ocupacion->docenteVigente?->nombre ?? '—' }}
                                    </div>
                                </div>
                                @empty
                                <span class="text-muted">—</span>
                                @endforelse
                            </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
            @endif
            @endif
        </div>
    </div>
</div>
