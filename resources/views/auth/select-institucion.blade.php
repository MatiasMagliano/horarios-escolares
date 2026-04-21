@extends('layouts.app')

@section('title', 'Seleccionar escuela')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body p-4 p-lg-5">
                <div class="mb-4">
                    <div class="text-uppercase text-muted small mb-2">Acceso institucional</div>
                    <h1 class="h3 mb-2">Seleccioná una escuela</h1>
                    <p class="text-muted mb-0">
                        Tu sesión ya está iniciada. Elegí la institución con la que querés trabajar en esta sesión.
                    </p>
                </div>

                @if ($instituciones->isEmpty())
                    <div class="alert alert-warning mb-0">
                        Tu usuario no tiene escuelas activas asociadas. Contactá a la persona administradora del sistema.
                    </div>
                @else
                    <div class="list-group">
                        @foreach ($instituciones as $institucion)
                            <form method="POST" action="{{ route('instituciones.store') }}">
                                @csrf
                                <input type="hidden" name="institucion_id" value="{{ $institucion->id }}">

                                <button type="submit" class="list-group-item list-group-item-action py-3">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div class="text-start">
                                            <div class="fw-semibold">{{ $institucion->nombre_institucion }}</div>
                                            <div class="small text-muted">
                                                {{ $institucion->slug }}
                                                @if ($institucion->direccion)
                                                    · {{ $institucion->direccion }}
                                                @endif
                                            </div>
                                        </div>

                                        <span class="badge text-bg-secondary">
                                            Hasta {{ $institucion->anio_maximo }}º
                                        </span>
                                    </div>
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
