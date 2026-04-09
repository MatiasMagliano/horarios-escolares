<div class="card shadow-sm border-0 h-100 bg-light">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
                <div class="text-uppercase text-muted small mb-1">Configuración de cursos</div>
                <h2 class="h5 mb-1">Asignación de materias y horarios</h2>
                <div class="small text-muted">
                    Estado actual de configuración:
                </div>
            </div>

            <span class="badge {{ $estadisticas['total_conflictos'] === 0 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                {{ $estadisticas['total_conflictos'] }} pendiente{{ $estadisticas['total_conflictos'] === 1 ? '' : 's' }}
            </span>
        </div>

        <div class="mt-4 mb-3">
            <div class="row g-2 text-center">
                <div class="col-6 col-sm-3">
                    <small class="text-muted d-block mb-1">Total</small>
                    <span class="h6 fw-semibold">{{ $estadisticas['total_cursos'] }}</span>
                </div>
                <div class="col-6 col-sm-3">
                    <small class="text-muted d-block mb-1">Sin materias</small>
                    <span class="h6 fw-semibold text-warning">{{ $estadisticas['cursos_sin_materias'] }}</span>
                </div>
                <div class="col-6 col-sm-3">
                    <small class="text-muted d-block mb-1">Sin horarios</small>
                    <span class="h6 fw-semibold text-info">{{ $estadisticas['cursos_sin_horarios'] }}</span>
                </div>
                <div class="col-6 col-sm-3">
                    <small class="text-muted d-block mb-1">Completos</small>
                    <span class="h6 fw-semibold text-success">{{ $estadisticas['cursos_completos'] }}</span>
                </div>
            </div>
        </div>

        @if(count($cursosPendientes) > 0)
            <div class="mt-4 pt-3 border-top">
                <small class="text-muted d-block mb-2">Pendientes de completar:</small>
                <div class="list-group list-group-sm">
                    @foreach($cursosPendientes as $curso)
                        @php
                            $estadoColor = match($curso['estado']) {
                                'sin_materias' => 'warning',
                                'sin_horarios' => 'info',
                                default => 'secondary'
                            };
                            $estadoTexto = match($curso['estado']) {
                                'sin_materias' => 'Sin materias',
                                'sin_horarios' => 'Sin horarios',
                                default => 'Desconocido'
                            };
                            $icono = match($curso['estado']) {
                                'sin_materias' => 'exclamation-circle',
                                'sin_horarios' => 'clock-history',
                                default => 'question-circle'
                            };
                            $ruta = $curso['estado'] === 'sin_materias' 
                                ? route('admin.cursos.materias', ['curso' => $curso['id']])
                                : route('admin.horarios');
                        @endphp
                        <a href="{{ $ruta }}" class="list-group-item list-group-item-action py-2 px-0 border-0 small">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <div class="flex-grow-1">
                                    <div class="fw-500">{{ $curso['nombre_completo'] }}</div>
                                    <div class="text-muted small">
                                        {{ $curso['materias_count'] }} materia{{ $curso['materias_count'] !== 1 ? 's' : '' }} • 
                                        {{ $curso['horarios_count'] }} bloque{{ $curso['horarios_count'] !== 1 ? 's' : '' }}
                                    </div>
                                </div>
                                <span class="badge badge-{{ $estadoColor }}">
                                    <i class="bi bi-{{ $icono }} me-1"></i>{{ $estadoTexto }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-muted small mt-4 mb-0">
                Todos los cursos están completamente configurados ✓
            </p>
        @endif

        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('admin.cursos') }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                <i class="bi bi-book"></i> Cursos
            </a>
            <a href="{{ route('admin.horarios') }}" class="btn btn-outline-secondary btn-sm flex-grow-1">
                <i class="bi bi-grid-3x2"></i> Horarios
            </a>
        </div>
    </div>
</div>
