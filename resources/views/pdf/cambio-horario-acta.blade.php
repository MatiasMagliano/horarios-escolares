@extends('pdf.layout')

@section('content')
    <div class="document-header">
        <div class="document-title">Acta de Cambio de Horario</div>
        <div class="document-subtitle">
            {{ $institucion?->nombre_institucion ?? config('app.name') }}
        </div>
    </div>

    <div class="meta">
        <div class="meta-row"><strong>Acta:</strong> {{ $cambio->numero_acta ? $cambio->numero_acta . ' / ' . $cambio->anio_acta : 'Sin numerar' }}</div>
        <div class="meta-row"><strong>Solicitud:</strong> #{{ $cambio->id }}</div>
        <div class="meta-row"><strong>Docente:</strong> {{ $cambio->docente?->nombre_completo ?? $cambio->docente?->nombre ?? '—' }}</div>
        <div class="meta-row"><strong>Curso / Materia:</strong> {{ $cambio->curso?->nombre_completo ?? '—' }} @if($cambio->materia) / {{ $cambio->materia->nombre }} @endif</div>
        <div class="meta-row"><strong>Emitido:</strong> {{ $fechaActual }}</div>
    </div>

    <div class="html-content">
        {!! $cambio->acta ?: '<p><em>No hay acta guardada para este cambio.</em></p>' !!}
    </div>
@endsection
