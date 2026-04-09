@extends('layouts.app')

@section('title', 'Superposición de docentes')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <div class="text-uppercase text-muted small mb-1">Detalle de alerta</div>
                    <h1 class="h3 mb-1">Superposición de docentes</h1>
                    <div class="text-muted">
                        Control de horarios vigentes con asignación docente vigente.
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        Volver al inicio
                    </a>
                    <a href="{{ route('admin.horarios') }}" class="btn btn-primary">
                        Ir a horarios
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <div class="small text-muted">Resultado actual</div>
                            <div class="h5 mb-0">
                                {{ $conflictos->count() }} conflicto{{ $conflictos->count() === 1 ? '' : 's' }}
                            </div>
                        </div>
                        <span class="badge {{ $conflictos->isEmpty() ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            {{ $conflictos->isEmpty() ? 'Sin conflictos' : 'Requiere revisión' }}
                        </span>
                    </div>

                    @if($conflictos->isEmpty())
                        <div class="alert alert-success mb-0">
                            No se detectaron superposiciones docentes en los horarios vigentes.
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($conflictos as $conflicto)
                                <div class="border rounded p-3">
                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                                        <div>
                                            <div class="fw-semibold">{{ $conflicto['docente_nombre'] }}</div>
                                            <div class="small text-muted">
                                                {{ $conflicto['dia_nombre'] }}
                                                · {{ $conflicto['bloque_nombre'] }}
                                                @if($conflicto['hora_inicio'] && $conflicto['hora_fin'])
                                                    · {{ $conflicto['hora_inicio'] }} a {{ $conflicto['hora_fin'] }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="small text-muted">
                                            {{ $conflicto['total_asignaciones'] }} asignaciones simultáneas
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Curso</th>
                                                    <th>Materia</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($conflicto['asignaciones'] as $asignacion)
                                                    <tr>
                                                        <td>{{ $asignacion['curso'] }}</td>
                                                        <td>{{ $asignacion['materia'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
