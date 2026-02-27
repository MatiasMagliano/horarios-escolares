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
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#docentes">
            Docentes
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cambios">
            Cambios horarios
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

    <div class="tab-pane fade" id="docentes">
        <livewire:docente-index />
    </div>

    <div class="tab-pane fade" id="cambios">
        <livewire:cambio-horario />
    </div>

</div>

@endsection
