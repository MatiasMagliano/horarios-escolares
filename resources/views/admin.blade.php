@extends('layouts.app')

@section('title', 'Panel de administración')

@section('content')

<ul class="nav nav-tabs mb-4" id="mainTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#horarios">
            Horarios
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cursos">
            Cursos
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#materias">
            Materias
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#docentes">
            Docentes
        </button>
    </li>
</ul>

<div class="tab-content">

    <div class="tab-pane fade show active" id="horarios">
        <livewire:horario-curso />
    </div>

    <div class="tab-pane fade" id="cursos">
        <livewire:curso-index />
    </div>

    <div class="tab-pane fade" id="materias">
        <div class="alert alert-secondary">
            CRUD de materias (pendiente)
        </div>
    </div>

    <div class="tab-pane fade" id="docentes">
        <div class="alert alert-secondary">
            CRUD de docentes (pendiente)
        </div>
    </div>

</div>

<script>
    document.addEventListener('livewire:init', () => {

        // modal nuevo curso
        const modal = new bootstrap.Modal(
            document.getElementById('cursoModal')
        );
        Livewire.on('abrir-modal', () => {
            modal.show();
        });
        Livewire.on('curso-guardado', () => {
            modal.hide();
        });

        // modal eliminar curso
        const eliminarModal = new bootstrap.Modal(
        document.getElementById('eliminarModal')
        );
        Livewire.on('abrir-modal-eliminar', () => {
            eliminarModal.show();
        });
        Livewire.on('cerrar-modal-eliminar', () => {
            eliminarModal.hide();
        });

        // modal editar celda
        const modalEditarCelda = new bootstrap.Modal(
            document.getElementById('editarCelda')
        );
        Livewire.on('abrir-modal-editar-celda', () => {
            modalEditarCelda.show();
        });
        Livewire.on('cerrar-modal-editar-celda', () => {
            modalEditarCelda.hide();
        });

    });
</script>

@endsection
