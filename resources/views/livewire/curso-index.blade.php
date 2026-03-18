<?php

use Livewire\Component;
use App\Models\Curso;

new class extends Component {

    public $cursoIdSeleccionado;
    public ?int $cursoAEliminar = null;
    public ?int $cursoIdMaterias = null;

    protected $listeners = [
        'curso-guardado' => '$refresh',
        'curso-eliminado' => '$refresh',
        'curso-materias-cerrar' => 'cerrarMaterias',
    ];

    // modal de creación de curso
    public function crear()
    {
        $this->cursoIdSeleccionado = null;
        $this->dispatch('curso-abrir-modal');
    }

    // modal de edición de curso
    public function editar($id)
    {
        $this->cursoIdSeleccionado = $id;
        $this->dispatch('curso-abrir-modal');
    }

    public function administrarMaterias($id)
    {
        $this->cursoIdMaterias = (int) $id;
    }

    public function cerrarMaterias()
    {
        $this->cursoIdMaterias = null;
    }

    // eliminar curso
    public function confirmarEliminacion($id)
    {
        $this->cursoAEliminar = $id;
        $this->dispatch('curso-abrir-modal-eliminar');
    }

    public function eliminar()
    {
        if ($this->cursoAEliminar) {
            Curso::find($this->cursoAEliminar)?->delete();
        }

        $this->cursoAEliminar = null;

        $this->dispatch('curso-cerrar-modal-eliminar');
    }


    // renderizar la vista
    public function render()
    {
        $cursos = Curso::query()
            ->withCount('cursoMaterias')
            ->orderBy('anio')
            ->orderBy('division')
            ->get();

        return view('livewire.curso-index', [
            'cursos' => $cursos,
            'cursosSinMaterias' => $cursos->filter(fn ($curso) => $curso->curso_materias_count === 0),
        ]);
    }
};
?>

<div>
    @if($cursoIdMaterias)
        <livewire:curso-materias-admin
            :curso="Curso::findOrFail($cursoIdMaterias)"
            :key="'curso-materias-admin-'.$cursoIdMaterias" />
    @else
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Cursos</h3>

        <button wire:click="crear" class="btn btn-success mb-3">
            + Nuevo Curso
        </button>
    </div>

    @if($cursosSinMaterias->isNotEmpty())
    <div class="alert alert-warning d-flex justify-content-between align-items-center gap-3">
        <div>
            Hay {{ $cursosSinMaterias->count() }} curso/s sin materias cargadas.
            <span class="small text-muted">
                {{ $cursosSinMaterias->pluck('nombre_completo')->join(', ') }}
            </span>
        </div>
    </div>
    @endif

    {{-- TABLA --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle">
        <thead class="table-light">
            <tr class="text-center">
                <th style="width: 10%;">Año</th>
                <th style="width: 10%;">División</th>
                <th style="width: 15%;">Ciclo</th>
                <th style="width: 20%;">Turno</th>
                <th style="width: 25%;">Herramientas</th>
            </tr>
        </thead>

        <tbody>
            @forelse($cursos as $curso)
                <tr>
                    <td class="text-center fw-semibold">
                        {{ $curso->anio }}º
                    </td>

                    <td class="text-center">
                        {{ $curso->division }}
                    </td>

                    <td class="text-center">
                        <span class="badge {{ $curso->ciclo === 'CB' ? 'bg-secondary' : 'bg-info' }}">
                            {{ $curso->ciclo }}
                        </span>
                        @if($curso->curso_materias_count === 0)
                        <span class="badge bg-warning text-dark me-2">Sin materias</span>
                        @endif
                    </td>

                    <td class="text-center">
                        {{ $curso->turno_designacion }}
                    </td>

                    <td class="text-center">
                        
                        {{-- Botón de editar --}}
                        <button wire:click="editar({{ $curso->id }})" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button wire:click="administrarMaterias({{ $curso->id }})" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-journal-text"></i>
                        </button>

                        {{-- Botón de eliminar --}}
                        <button wire:click="confirmarEliminacion({{ $curso->id }})" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        No hay cursos cargados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- MODAL NUEVO/EDITAR --}}
    <div wire:ignore.self class="modal fade" id="cursoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $cursoIdSeleccionado ? 'Editar Curso' : 'Nuevo Curso' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <livewire:curso-form
                        :curso="$cursoIdSeleccionado ? Curso::find($cursoIdSeleccionado) : null"
                        :key="$cursoIdSeleccionado ?? 'create'"
                    />
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL ELIMINAR --}}
    <div wire:ignore.self class="modal fade" id="eliminarModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    ¿Estás seguro de que querés eliminar este curso?
                    <br>
                    <small class="text-muted">
                        Esta acción no se puede deshacer.
                    </small>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button wire:click="eliminar"
                            class="btn btn-danger">
                        Sí, eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        const cursoModalEl = document.getElementById('cursoModal');
        const eliminarModalEl = document.getElementById('eliminarModal');

        Livewire.on('curso-abrir-modal', () => {
            if (!cursoModalEl || typeof bootstrap === 'undefined') return;
            bootstrap.Modal.getOrCreateInstance(cursoModalEl).show();
        });

        Livewire.on('curso-guardado-y-cerrar', () => {
            if (!cursoModalEl || typeof bootstrap === 'undefined') return;
            bootstrap.Modal.getOrCreateInstance(cursoModalEl).hide();
        });

        Livewire.on('curso-abrir-modal-eliminar', () => {
            if (!eliminarModalEl || typeof bootstrap === 'undefined') return;
            bootstrap.Modal.getOrCreateInstance(eliminarModalEl).show();
        });

        Livewire.on('curso-cerrar-modal-eliminar', () => {
            if (!eliminarModalEl || typeof bootstrap === 'undefined') return;
            bootstrap.Modal.getOrCreateInstance(eliminarModalEl).hide();
        });
    });
</script>
