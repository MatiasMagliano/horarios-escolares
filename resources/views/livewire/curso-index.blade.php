<?php

use Livewire\Component;
use App\Models\Curso;

new class extends Component {

    public $cursoIdSeleccionado;
    public ?int $cursoAEliminar = null;

    protected $listeners = ['curso-guardado' => '$refresh', 'curso-eliminado' => '$refresh'];

    // modal de creación de curso
    public function crear()
    {
        $this->cursoIdSeleccionado = null;
        $this->dispatch('abrir-modal');
    }

    // modal de edición de curso
    public function editar($id)
    {
        $this->cursoIdSeleccionado = $id;
        $this->dispatch('abrir-modal');
    }

    // eliminar curso
    public function confirmarEliminacion($id)
    {
        $this->cursoAEliminar = $id;
        $this->dispatch('abrir-modal-eliminar');
    }

    public function eliminar()
    {
        if ($this->cursoAEliminar) {
            Curso::find($this->cursoAEliminar)?->delete();
        }

        $this->cursoAEliminar = null;

        $this->dispatch('cerrar-modal-eliminar');
    }


    // renderizar la vista
    public function render()
    {
        return view('livewire.curso-index', [
            'cursos' => Curso::orderBy('anio')
                ->orderBy('division')
                ->get(),
        ]);
    }
};
?>

<div>
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Cursos</h3>

        <button wire:click="crear" class="btn btn-success mb-3">
            + Nuevo Curso
        </button>
    </div>

    {{-- TABLA --}}
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
                    </td>

                    <td class="text-center">
                        {{ $curso->turno_designacion }}
                    </td>

                    <td class="text-center">
                        {{-- Botón de editar --}}
                        <button wire:click="editar({{ $curso->id }})" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
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
</div>

