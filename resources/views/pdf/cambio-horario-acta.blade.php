@extends('pdf.layout')

@section('styles')
    <style>
        @page {
            margin: 14mm 16mm 12mm;
        }

        body {
            font-size: 11px;
            color: #111827;
        }

        .acta-documento {
            width: 100%;
        }

        .acta-header h5,
        .text-center {
            text-align: center;
        }

        .w-100 {
            width: 100%;
        }

        .acta-header h5 {
            font-size: 14px;
            margin: 0 0 6px;
        }

        .acta-numero {
            margin: 0 0 8px;
            font-weight: bold;
        }

        .acta-fecha {
            margin: 0 0 12px;
            text-align: right;
        }

        .acta-body {
            text-align: justify;
            text-indent: 24px;
        }

        .acta-body p {
            margin: 0 0 8px;
            line-height: 1.35;
        }

        .acta-body ol {
            margin: 0 0 8px 22px;
            padding: 0;
            text-indent: 0;
        }

        .acta-body li {
            margin-bottom: 5px;
            line-height: 1.32;
            text-align: justify;
        }

        .acta-firmas {
            width: 100%;
            margin-top: 54px;
            page-break-inside: avoid;
        }

        .acta-firmas td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 42px 24px 0;
        }

        .firma-linea {
            border-top: 1px solid #111827;
            height: 10px;
            margin-bottom: 6px;
        }
    </style>
@endsection

@section('content')
    @include('livewire.partials.cambio-horario-acta', [
        'tipoCambio' => $cambio->tipo_cambio,
        'fechaActual' => $fechaActual,
        'cuerpoHtml' => $cambio->cuerpo_acta,
        'numeroActa' => $cambio->numero_acta,
        'anioActa' => $cambio->anio_acta,
    ])
@endsection
