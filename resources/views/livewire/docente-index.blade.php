<?php

use Livewire\Component;
use App\Models\Docente;

new class extends Component
{
    public $docenteSeleccionado;
    protected $listeners = ['docente-guardado' => '$refresh', 'docente-eliminado' => '$refresh'];

    public function crear()
    {
        $this->docenteSeleccionado = null;
        $this->dispatch('modal-crear-docente');
    }

    public function editar($id)
    {
        $this->docenteSeleccionado = $id;
        $this->dispatch('modal-crear-docente');
    }

    public function cambiarEstado($id)
    {
        $docente = Docente::findOrFail($id);
        $docente->activo = !$docente->activo;
        session()->flash('success', 'Estado del docente actualizado correctamente.');
        $docente->save();
    }

    // SOLAMENTE DISPARA EL MODAL DE CONFIRMACIÓN, LA ELIMINACIÓN SE HACE EN EL COMPONENTE docente-form.blade.php
    public function eliminar($id)
    {
        $this->docenteSeleccionado = $id;
        $this->dispatch('abrir-modal-eliminar');
    }

    public function render()
    {
        return view('livewire.docente-index', [
            'docentes' => Docente::orderBy('nombre_completo')->get(),
        ]);
    }
};
?>

<div>
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Docentes</h3>

        <button wire:click="crear" class="btn btn-success mb-3">
            + Nuevo Docente
        </button>
    </div>
    <div class="alert alert-info">
        <span>La "denominación" será el nombre con el que aparecerá en la Grilla de Horarios</span>
        <br>
        <span>El nombre completo, será el que aparecerá luego en el Acta de Cambio de Horario</span>
    </div>

    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- TABLA --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle">
            <thead class="table-light">
                <tr class="text-center">
                    <th style="width: 5%;">DNI</th>
                    <th style="width: 17%;">Denominación</th>
                    <th style="width: 27%">Nombre completo</th>
                    <th style="width: 5%;">Teléfono</th>
                    <th style="width: 15%;">E-mail</th>
                    <th style="width: 20%;">Herramientas</th>
                </tr>
            </thead>

            <tbody>
                @forelse($docentes as $docente)
                <tr>
                    <td class="text-center">
                        {{ $docente->documento }}
                    </td>

                    <td class="text-center  fw-semibold">
                        {{ $docente->nombre }}
                    </td>

                    <td class="text-center">
                        {{ $docente->nombre_completo }}
                    </td>

                    <td class="text-center">
                        {{ $docente->telefono }}
                    </td>

                    <td class="text-center">
                        {{ $docente->email }}
                    </td>

                    <td class="text-center">
                        <div class="btn-group" role="group" aria-label="">
                            {{-- Indicador de activo/inactivo --}}
                            <button type="button" wire:click="cambiarEstado({{ $docente->id }})" class="btn btn-sm btn-outline-{{ $docente->activo ? 'secondary' : 'info' }}">
                                {{ $docente->activo ? 'Activo' : 'Inactivo' }}
                            </button>

                            {{-- Botón de editar --}}
                            <button type="button" wire:click="editar({{ $docente->id }})" class="btn btn-sm btn-outline-primary">
                                Editar
                            </button>

                            {{-- Botón de eliminar --}}
                            <button type="button" wire:click="eliminar({{ $docente->id }})" class="btn btn-sm btn-outline-danger">
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        No hay docentes cargados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL CREAR/EDITAR --}}
    <div wire:ignore-self class="modal fade" id="docenteModal" tabindex="-1" aria-labelledby="docenteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="docenteModalLabel">
                        {{ $docenteSeleccionado ? 'Editar Docente' : 'Nuevo Docente' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <livewire:docente-form
                        :docente="$docenteSeleccionado ? Docente::find($docenteSeleccionado) : null"
                        :key="$docenteSeleccionado ?? 'create'" />
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }, 3000);
            }
        });
    </script>
</div>