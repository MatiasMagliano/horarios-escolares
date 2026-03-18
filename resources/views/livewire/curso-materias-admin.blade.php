<div>
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h3 class="mb-1">Materias del curso</h3>
        </div>

        <button type="button" wire:click="volver" class="btn btn-outline-secondary">
            Volver a cursos
        </button>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-6 col-md-3">
                    <div class="small text-uppercase text-muted">Año</div>
                    <div class="fw-semibold">{{ $curso->anio }}º</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-uppercase text-muted">División</div>
                    <div class="fw-semibold">{{ $curso->division }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-uppercase text-muted">Turno</div>
                    <div class="fw-semibold">{{ $curso->turno_designacion }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-uppercase text-muted">Ciclo</div>
                    <div class="fw-semibold">{{ $curso->ciclo }}</div>
                </div>
            </div>
        </div>
    </div>

    <livewire:curso-materias
        :curso="$curso"
        :key="'materias-admin-'.$curso->id" />
</div>
