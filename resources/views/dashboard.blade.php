@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                    <div>
                        <div class="text-uppercase text-muted small mb-2">Panel general</div>
                        <h1 class="h3 mb-3">Resumen del sistema</h1>
                    </div>

                    <div class="text-lg-end">
                        @can('ver-horarios')
                            <a href="{{ route('admin.horarios') }}" class="btn btn-primary">
                                Ir a horarios
                            </a>
                        @endcan
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-3">
                    @can('abm-docentes')
                        <div class="col-6 mb-4">
                            <livewire:dashboard-superposiciones-docentes />
                        </div>
                    @endcan
                    @can('ver-cambios-horario')
                        <div class="col-6 mb-4">
                            <livewire:dashboard-cambios-horarios />
                        </div>
                    @endcan
                    @can('abm-cursos')
                        <div class="col-12">
                            <livewire:dashboard-cursos-asignacion />
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
