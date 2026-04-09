<div>
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1">Cursos</h4>
                <div class="text-muted small">
                    Elegí una herramienta para administrar cursos o configurar las materias de un curso específico.
                </div>
                @if($this->cursoSeleccionado)
                <div class="small mt-2">
                    <span class="text-muted">Curso seleccionado:</span>
                    <span class="fw-semibold">{{ $this->cursoSeleccionado->nombre_completo }}</span>
                </div>
                @endif
            </div>

            <div class="btn-group" role="group" aria-label="Herramientas de cursos">
                <a
                    href="{{ route('admin.cursos.listado') }}"
                    class="btn {{ $vista === 'listado' ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                    Listado de cursos
                </a>
                <a
                    href="{{ route('admin.cursos.materias') }}"
                    class="btn {{ $vista === 'materias' ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                    Materias del curso
                </a>
            </div>
        </div>
    </div>

    @if($vista === 'materias')
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-6">
                        <label class="form-label" for="cursoIdSeleccionado">Curso</label>
                        <select id="cursoIdSeleccionado" wire:model.live="cursoIdSeleccionado" class="form-select">
                            <option value="">Seleccione un curso...</option>
                            @foreach($this->cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre_completo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <div class="small text-muted">
                            Seleccioná el curso que querés configurar para administrar sus materias, docentes y espacios físicos.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($this->cursoSeleccionado)
            <livewire:curso-materias-admin
                :curso="$this->cursoSeleccionado"
                :key="'curso-materias-admin-'.$this->cursoSeleccionado->id" />
        @else
            <div class="alert alert-secondary mb-0">
                Seleccioná un curso para administrar sus materias.
            </div>
        @endif
    @else
        <livewire:curso-index />
    @endif
</div>
