<div>
    @if($modo === 'listado')

    <div class="d-flex justify-content-between mb-3">
        <h4>Cambios de Horario</h4>
        <button wire:click="nuevo" class="btn btn-primary">
            + Nuevo cambio
        </button>
    </div>

    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <table class="table table-bordered table-sm">
        <thead>
            <tr class="text-center">
                <th>Solicitud</th>
                <th>Duración</th>
                <th>Tipo de cambio</th>
                <th>Desde</th>
                <th>Hasta</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cambios as $c)
            <tr>
                <td class="text-center align-middle">{{ $c->pedido_en->format('d/m/Y') }}</td>
                <td class="text-center align-middle">{{ ucfirst($c->duracionBD) }}</td>
                <td class="text-center align-middle">{{ ucfirst($c->tipo_cambioBD) }}</td>
                <td class="text-center align-middle">{{ $c->fecha_desde->format('d/m/Y') }}</td>
                <td class="text-center align-middle">{{ $c->fecha_hasta ? $c->fecha_hasta->format('d/m/Y') : 'sin final' }}</td>
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

                        @if($c->acta)
                        <a
                            href="{{ route('pdf.cambio-horario-acta', ['cambio' => $c->id]) }}"
                            class="btn btn-sm btn-outline-danger"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            PDF acta
                        </a>
                        @endif

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
            <h3 class="card-title">Solicitud de cambio de horario</h3>
            <hr>
            <form wire:submit.prevent="guardar">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5">1. Tipo de cambio</span>
                    </div>

                    <div class="row mb-4">
                        <div class="col">
                            <div class="mb-3">
                                <label>Tipo de duración</label>
                                <select wire:model.live="duracion" class="form-select">
                                    <option value="temporal">Temporal</option>
                                    <option value="permanente">Permanente</option>
                                </select>
                                @error('duracion') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-3">
                                <label>Tipo de cambio</label>
                                <select wire:model="tipo_cambio" class="form-select">
                                    <option value="cambio">Cambio de horario</option>
                                    <option value="permuta">Permuta de horario</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5">2. Solicitante</span>
                    </div>
                    <div class="row mb-4">
                        <div class="col mb-3">
                            <label>Docente</label>
                            <select wire:model.live="docente_id" class="form-select">
                                <option value="">Seleccione un docente</option>
                                @foreach($docentes as $docente)
                                <option value="{{ $docente->id }}">{{ $docente->nombre_completo }}</option>
                                @endforeach
                            </select>
                            @error('docente_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="col mb-3">
                            <label>Curso</label>
                            <select wire:model.live="curso_id" class="form-select" @disabled(empty($cursosFiltrados))>
                                <option value="">Seleccione un curso</option>
                                @foreach($cursosFiltrados as $curso)
                                <option value="{{ $curso['id'] }}">
                                    {{ $curso['anio'] }}° {{ $curso['division'] }} ({{ $curso['turno_designacion'] }})
                                </option>
                                @endforeach
                            </select>
                            @error('curso_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="col mb-3">
                            <label>Materia</label>
                            <select wire:model="materia_id" class="form-select" @disabled(empty($materiasFiltradas))>
                                <option value="">Seleccione una materia</option>
                                @foreach($materiasFiltradas as $materia)
                                <option value="{{ $materia['id'] }}">{{ $materia['nombre'] }}</option>
                                @endforeach
                            </select>
                            @error('materia_id') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5">3. Fechas</span>
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            {{-- Ciclo lectivo --}}
                            <div class="mb-3">
                                <label for="ciclo_lectivo">Ciclo lectivo</label>
                                <input type="number" wire:model="ciclo_lectivo" class="form-control">
                                @error('ciclo_lectivo') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col">
                            {{-- Fecha desde --}}
                            <div class="mb-3">
                                <label>Desde</label>
                                <input type="date" wire:model="fecha_desde" class="form-control">
                                @error('fecha_desde') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        @if($duracion === 'temporal')
                        <div class="col">
                            {{-- Fecha hasta (opcional) --}}
                            <div class="mb-3">
                                <label>Hasta</label>
                                <input type="date" wire:model="fecha_hasta" class="form-control">
                                @error('fecha_hasta') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5">4. Detalle del acta</span>

                        <button type="button" class="btn btn-outline-primary btn-sm" wire:click="generarActa">
                            Generar acta
                        </button>
                    </div>
                    {{-- ACTA MODIFICABLE --}}
                    <div class="row mt-3 mb-4">
                        <div class="col">
                            <div wire:ignore>
                                <input id="acta-editor-input" type="hidden" value="{{ $acta }}">
                                <trix-editor id="acta-editor" input="acta-editor-input"
                                    class="trix-content">
                                </trix-editor>
                            </div>
                            @error('acta') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                            @error('acta_finalizada') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- ACTA FINAL --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5">5. Acta final</span>

                        <button type="button" class="btn btn-outline-success btn-sm" wire:click="finalizarActa">
                            Finalizar acta
                        </button>
                    </div>
                    @if($acta_finalizada)
                    <span class="text-success small ms-2">Acta finalizada</span>
                    @endif
                    <div class="row mt-3 mb-4">
                        <div class="col">
                            @include('livewire.partials.cambio-horario-acta', [
                                'tipoCambio' => $this->tipo_cambio,
                                'fechaActual' => $this->fechaActual,
                                'cuerpoHtml' => $acta,
                            ])
                        </div>
                    </div>

                </div>


                {{-- BOTONES --}}
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
                <livewire:cambio-horario-detalle
                    :cambio="$cambio"
                    :key="'detalles-'.$cambio->id" />
                @endif
            </form>
        </div>
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
                @if($cambio)
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Estado</div>
                            <div class="fw-semibold">{{ ucfirst($cambio->estado) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Duración / Tipo</div>
                            <div class="fw-semibold">{{ ucfirst($cambio->duracionBD) }} / {{ ucfirst($cambio->tipo_cambioBD) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Ciclo lectivo</div>
                            <div class="fw-semibold">{{ $cambio->ciclo_lectivo ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Solicitante</div>
                            <div class="fw-semibold">{{ $cambio->solicitante?->name ?? '—' }}</div>
                            <div class="text-muted small mt-2">Docente</div>
                            <div>{{ $cambio->docente?->nombre_completo ?? $cambio->docente?->nombre ?? '—' }}</div>
                            <div class="text-muted small mt-2">Curso / Materia</div>
                            <div>
                                {{ $cambio->curso?->nombre_completo ?? '—' }}
                                @if($cambio->materia)
                                / {{ $cambio->materia->nombre }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Fecha de solicitud</div>
                            <div>{{ $cambio->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
                            <div class="text-muted small mt-2">Desde / Hasta</div>
                            <div>
                                {{ $cambio->fecha_desde?->format('d/m/Y') ?? '—' }}
                                @if($cambio->duracion === 'temporal')
                                → {{ $cambio->fecha_hasta?->format('d/m/Y') ?? '—' }}
                                @endif
                            </div>
                            <div class="text-muted small mt-2">Número de acta</div>
                            @if($cambio->numero_acta && $cambio->anio_acta)
                            <div>{{ $cambio->numero_acta }} / {{ $cambio->anio_acta }}</div>
                            @else
                            <div class="fw-semibold">Asignación al momento de firmar el acta.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>

                <div>
                    <h6 class="mb-2">Detalles del borrador ({{ $cambio->detalles->count() }})</h6>
                    @if($cambio->detalles->isEmpty())
                    <div class="text-muted">Sin detalles cargados todavía.</div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Horario base</th>
                                    <th>Nuevo día</th>
                                    <th>Nuevo curso</th>
                                    <th>Nuevo bloque</th>
                                    <th>Nuevo docente</th>
                                    <th>Obs.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cambio->detalles as $detalle)
                                @php
                                $hb = $detalle->horarioBase;
                                $hbMateria = $hb?->cursoMateria?->materia?->nombre ?? '—';
                                $hbDocente = $hb?->docenteVigente?->nombre ?? '—';
                                $hbBloque = $hb?->bloque?->hora_inicio?->format('H:i') . ' - ' . $hb?->bloque?->hora_fin?->format('H:i');
                                @endphp
                                <tr>
                                    <td>
                                        <div>{{ $hbMateria }}</div>
                                        <div class="small text-muted">{{ $hbDocente }} · {{ $hbBloque }}</div>
                                    </td>
                                    <td>{{ $this->diaSemanaTexto($detalle->dia_nuevo) }}</td>
                                    <td>{{ $detalle->curso_nuevo_id ?? '—' }}</td>
                                    <td>{{ $detalle->bloque_nuevo_id ?? '—' }}</td>
                                    <td>{{ $detalle->docente_nuevo_id ?? '—' }}</td>
                                    <td>{{ $detalle->observaciones ?: '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <hr>

                <div>
                    <h6 class="mb-2">Acta guardada generada</h6>
                    <div class="border rounded p-3 bg-light">
                        {!! $cambio->acta ?: '<em>No hay acta guardada.</em>' !!}
                    </div>
                </div>

                @else
                <div class="text-muted">No se pudo cargar el cambio solicitado.</div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('abrir-modal-detalle-cambio-horario', () => {
            const modalEl = document.getElementById('detalleCambioHorario');
            if (!modalEl || typeof bootstrap === 'undefined') {
                return;
            }

            requestAnimationFrame(() => {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            });
        });

        Livewire.on('cerrar-modal-detalle-cambio-horario', () => {
            const modalEl = document.getElementById('detalleCambioHorario');
            if (!modalEl || typeof bootstrap === 'undefined') {
                return;
            }

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.hide();
        });

        document.addEventListener('trix-change', (event) => {
            if (event.target.id !== 'acta-editor') {
                return;
            }

            const input = document.getElementById('acta-editor-input');
            const host = event.target.closest('[wire\\:id]');
            if (!input || !host) {
                return;
            }

            const component = Livewire.find(host.getAttribute('wire:id'));
            if (!component) {
                return;
            }

            component.set('acta', input.value);
        });

        Livewire.on('trix-cargar-html', (payload) => {
            const editor = document.getElementById('acta-editor');
            const input = document.getElementById('acta-editor-input');
            if (!editor || !input) {
                return;
            }

            const html = payload?.html ?? '';
            input.value = html;
            editor.editor.loadHTML(html);

            const host = editor.closest('[wire\\:id]');
            if (!host) {
                return;
            }

            const component = Livewire.find(host.getAttribute('wire:id'));
            if (!component) {
                return;
            }

            component.set('acta', html);
        });

        Livewire.on('trix-set-locked', (payload) => {
            const editor = document.getElementById('acta-editor');
            if (!editor) {
                return;
            }

            const locked = Boolean(payload?.locked);
            editor.setAttribute('contenteditable', locked ? 'false' : 'true');

            if (editor.toolbarElement) {
                editor.toolbarElement.style.display = locked ? 'none' : '';
            }

            editor.style.pointerEvents = locked ? 'none' : '';
            editor.style.backgroundColor = locked ? '#f8f9fa' : '';
        });
    });
</script>
