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
                            <p class="text-muted mb-0">
                                Este espacio queda preparado para incorporar métricas, alertas y controles globales
                                del sistema de horarios.
                            </p>
                        </div>

                        <div class="text-lg-end">
                            <a href="{{ route('admin.horarios') }}" class="btn btn-primary">
                                Ir a horarios
                            </a>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100 bg-light">
                                <div class="small text-muted mb-2">Estado</div>
                                <div class="fw-semibold">Pendiente de métricas</div>
                                <div class="small text-muted mt-2">
                                    Aquí podremos mostrar indicadores de carga, alertas y validaciones del horario.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100 bg-light">
                                <div class="small text-muted mb-2">Próximo paso</div>
                                <div class="fw-semibold">Definir widgets del tablero</div>
                                <div class="small text-muted mt-2">
                                    Cursos incompletos, superposiciones, cambios pendientes y consistencia general.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100 bg-light">
                                <div class="small text-muted mb-2">Objetivo</div>
                                <div class="fw-semibold">Portada liviana</div>
                                <div class="small text-muted mt-2">
                                    El acceso inicial ya no carga componentes pesados ni grillas innecesarias.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
