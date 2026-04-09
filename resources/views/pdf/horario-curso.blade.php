@extends('pdf.layout')

@section('styles')
    @php
        $totalRows = $grillas->sum(fn ($grilla) => $grilla->count());
        $warningCount = count($advertencias);
        $densityClass = $totalRows >= 30 || $warningCount >= 4
            ? 'ultra-compact'
            : ($totalRows >= 24 || $warningCount >= 2 ? 'compact' : 'regular');
    @endphp
    <style>
        @page {
            margin: 16px 16px 26px 16px;
        }

        body {
            font-size: 10px;
        }

        .schedule-sheet {
            padding-bottom: 14px;
        }

        .schedule-sheet .document-header,
        .schedule-sheet .meta {
            text-align: center;
        }

        .schedule-sheet .document-header {
            margin-bottom: 8px;
        }

        .schedule-sheet .document-title {
            font-size: 18px;
            margin-bottom: 3px;
            letter-spacing: 0.3px;
        }

        .schedule-sheet .document-subtitle {
            font-size: 10px;
        }

        .schedule-sheet .meta {
            margin-bottom: 10px;
        }

        .schedule-sheet .meta-row {
            margin-bottom: 2px;
            font-size: 11px;
        }

        .schedule-sheet .course-name {
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.2px;
        }

        .schedule-sheet .alert-warning {
            padding: 7px 9px;
            margin-bottom: 8px;
            font-size: 9px;
        }

        .schedule-sheet .alert-warning ul {
            margin: 5px 0 0 16px;
        }

        .schedule-sheet .section-title {
            font-size: 11px;
            margin: 10px 0 4px;
            text-align: center;
            padding: 4px 0;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
        }

        .schedule-sheet .grid-table {
            margin-bottom: 8px;
        }

        .schedule-sheet .grid-table th,
        .schedule-sheet .grid-table td {
            padding: 5px 4px;
            font-size: 9px;
            line-height: 1.2;
            word-break: break-word;
        }

        .schedule-sheet .grid-table thead th {
            font-size: 9px;
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .schedule-sheet .time-cell {
            width: 11%;
            font-size: 7px;
            line-height: 1.2;
            white-space: nowrap;
        }

        .schedule-sheet .time-range {
            font-size: 8px;
            font-weight: bold;
        }

        .schedule-sheet .small {
            font-size: 8px;
        }

        .schedule-sheet .subject {
            display: block;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .schedule-sheet .teacher {
            display: block;
            font-size: 8px;
        }

        .schedule-sheet.compact .grid-table th,
        .schedule-sheet.compact .grid-table td {
            padding: 4px 3px;
            font-size: 8px;
            line-height: 1.1;
        }

        .schedule-sheet.compact .time-cell,
        .schedule-sheet.compact .small,
        .schedule-sheet.compact .grid-table thead th {
            font-size: 7.2px;
        }

        .schedule-sheet.compact .time-cell {
            width: 11%;
        }

        .schedule-sheet.compact .subject {
            font-size: 8px;
        }

        .schedule-sheet.compact .teacher {
            font-size: 7px;
        }

        .schedule-sheet.ultra-compact .document-header {
            margin-bottom: 4px;
        }

        .schedule-sheet.ultra-compact .meta,
        .schedule-sheet.ultra-compact .section-title,
        .schedule-sheet.ultra-compact .grid-table {
            margin-bottom: 4px;
        }

        .schedule-sheet.ultra-compact .grid-table th,
        .schedule-sheet.ultra-compact .grid-table td {
            padding: 2px 2px;
            font-size: 6.8px;
            line-height: 1.0;
        }

        .schedule-sheet.ultra-compact .time-cell,
        .schedule-sheet.ultra-compact .small,
        .schedule-sheet.ultra-compact .grid-table thead th {
            font-size: 6.2px;
        }

        .schedule-sheet.ultra-compact .time-cell {
            width: 11%;
        }

        .schedule-sheet.ultra-compact .subject {
            font-size: 6.8px;
        }

        .schedule-sheet.ultra-compact .teacher {
            font-size: 6.1px;
        }

        .schedule-footer {
            position: fixed;
            right: 0;
            bottom: -8px;
            left: 0;
            text-align: right;
            font-size: 8px;
            font-weight: bold;
            color: #374151;
        }
    </style>
@endsection

@section('content')
    <div class="schedule-sheet {{ $densityClass }}">
        <div class="document-header">
            <div class="document-title">Horario de Curso</div>
            <div class="document-subtitle">
                {{ $institucion?->nombre_institucion ?? config('app.name') }}
            </div>
        </div>

        <div class="meta">
            <div class="meta-row">
                <strong>Curso:</strong>
                <span class="course-name">{{ $curso->nombre_completo }}</span>
            </div>
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
                            <th class="time-cell">
                                <span class="time-range">{{ $bloque->hora_inicio->format('H:i') }} a {{ $bloque->hora_fin->format('H:i') }}</span>
                            </th>
                            @for($dia = 1; $dia <= 5; $dia++)
                                @php
                                    $horario = $dias[$dia] ?? null;
                                    $cursoMateria = $horario?->cursoMateria;
                                    $materia = $cursoMateria?->materia;
                                    $docente = $horario?->docenteVigente;
                                @endphp
                                <td class="text-center">
                                    @if($materia)
                                        <span class="subject">{{ $materia->nombre }}</span>
                                        <span class="teacher muted">{{ $docente?->nombre ?? '—' }}</span>
                                    @else
                                        <span class="muted">—</span>
                                    @endif
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
    </div>
@endsection

@section('footer')
    <div class="schedule-footer">
        Emitido: {{ now()->format('d/m/Y H:i') }}
    </div>
@endsection
