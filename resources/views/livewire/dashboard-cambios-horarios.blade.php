<div class="card shadow-sm border-0 h-100 bg-light">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
                <div class="text-uppercase text-muted small mb-1">Gestión de cambios</div>
                <h2 class="h5 mb-1">Cambios de horarios pendientes</h2>
            </div>

            <span class="badge {{ $estadisticas['total_pendientes'] === 0 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                {{ $estadisticas['total_pendientes'] }} pendiente{{ $estadisticas['total_pendientes'] === 1 ? '' : 's' }}
            </span>
        </div>

        <div class="mt-4 mb-3">
            <div class="row g-2">
                <div class="col-6 col-sm-4">
                    <small class="text-muted d-block mb-1">Borrador</small>
                    <span class="h6 fw-semibold text-warning">{{ $estadisticas['borrador'] }}</span>
                </div>
                <div class="col-6 col-sm-4">
                    <small class="text-muted d-block mb-1">Autorizado</small>
                    <span class="h6 fw-semibold text-info">{{ $estadisticas['autorizado'] }}</span>
                </div>
                <div class="col-6 col-sm-4">
                    <small class="text-muted d-block mb-1">Firmado</small>
                    <span class="h6 fw-semibold text-info">{{ $estadisticas['firmado'] }}</span>
                </div>
                <div class="col-6 col-sm-4">
                    <small class="text-muted d-block mb-1">Activo</small>
                    <span class="h6 fw-semibold text-success">{{ $estadisticas['activo'] }}</span>
                </div>
                <div class="col-6 col-sm-4">
                    <small class="text-muted d-block mb-1">Finalizado</small>
                    <span class="h6 fw-semibold text-secondary">{{ $estadisticas['finalizado'] }}</span>
                </div>
                <div class="col-6 col-sm-4">
                    <small class="text-muted d-block mb-1">Total</small>
                    <span class="h6 fw-semibold">{{ $estadisticas['borrador'] + $estadisticas['autorizado'] + $estadisticas['firmado'] + $estadisticas['activo'] + $estadisticas['finalizado'] }}</span>
                </div>
            </div>
        </div>

        <!-- LISTADO DE TRÁMITES PENDIENTES (VER CÓDIGO EN "Desarrollo") -->

        <a href="{{ route('admin.cambios-horario') }}" class="btn btn-outline-primary btn-sm mt-3">
            Ver todos los cambios
        </a>
    </div>
</div>
