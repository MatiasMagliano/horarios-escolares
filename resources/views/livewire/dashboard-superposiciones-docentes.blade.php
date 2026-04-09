<div class="card shadow-sm border-0 h-100 bg-light">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
                <div class="text-uppercase text-muted small mb-1">Control de sistema</div>
                <h2 class="h5 mb-1">Superposición de docentes</h2>
                <div class="small text-muted">
                    Se encontraron la siguiente cantidad de conflictos:
                </div>
            </div>

            <span class="badge {{ $conflictos->isEmpty() ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                {{ $conflictos->count() }} conflicto{{ $conflictos->count() === 1 ? '' : 's' }}
            </span>
        </div>

        <div class="display-6 fw-semibold mt-4 mb-2">
            {{ $conflictos->count() }}
        </div>
        <p class="text-muted mb-4">
            @if($conflictos->isEmpty())
                No se detectaron conflictos activos en este momento.
            @else
                Vaya al detalle para verificarlos.
            @endif
        </p>

        <a href="{{ route('admin.alertas.superposiciones-docentes') }}" class="btn btn-outline-primary btn-sm">
            Ver detalle
        </a>
    </div>
</div>
