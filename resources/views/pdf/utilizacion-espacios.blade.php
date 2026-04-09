@extends('pdf.layout')

@section('content')
    <div class="document-header">
        <div class="document-title">Utilización de Espacios</div>
        <div class="document-subtitle">
            {{ $institucion?->nombre_institucion ?? config('app.name') }}
        </div>
    </div>

    <div class="meta">
        <div class="meta-row"><strong>Espacio:</strong> {{ $espacio->nombre }}</div>
        <div class="meta-row"><strong>Emitido:</strong> {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    @if($advertencias)
        <div class="alert-warning">
            <strong>Advertencias</strong>
            <ul>
                @foreach($advertencias as $advertencia)
                    <li>{{ $advertencia }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @forelse($grillas as $turno => $grilla)
        <div class="section-title">Turno {{ \App\Support\Horarios\TurnoHelper::designacionTurno($turno) }}</div>

        <table class="grid-table">
            <thead>
                <tr>
                    <th>Hora / Día</th>
                    <th>Lunes</th>
                    <th>Martes</th>
                    <th>Miércoles</th>
                    <th>Jueves</th>
                    <th>Viernes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grilla as $fila)
                    @php
                        $bloque = $fila['bloque'];
                        $dias = $fila['dias'];
                    @endphp

                    @if($bloque->tipo === 'recreo')
                        <tr class="recreo-row">
                            <th class="time-cell">{{ $bloque->hora_inicio->format('H:i') }} - {{ $bloque->hora_fin->format('H:i') }}</th>
                            <td colspan="5">RECREO</td>
                        </tr>
                        @continue
                    @endif

                    <tr>
                        <th class="time-cell">{{ $bloque->hora_inicio->format('H:i') }} - {{ $bloque->hora_fin->format('H:i') }}</th>
                        @for($dia = 1; $dia <= 5; $dia++)
                            @php
                                $ocupaciones = $dias->get($dia, collect());
                            @endphp
                            <td>
                                @forelse($ocupaciones as $ocupacion)
                                    <div class="cell-block">
                                        <div><strong>{{ $ocupacion->curso->anio }}º {{ $ocupacion->curso->division }}</strong></div>
                                        <div>{{ $ocupacion->cursoMateria?->materia?->nombre ?? '—' }}</div>
                                        <div class="small muted">{{ $ocupacion->docenteVigente?->nombre ?? '—' }}</div>
                                    </div>
                                @empty
                                    <span class="muted">—</span>
                                @endforelse
                            </td>
                        @endfor
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center muted">Sin horarios cargados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @empty
        <div class="muted">No hay información para exportar.</div>
    @endforelse
@endsection
