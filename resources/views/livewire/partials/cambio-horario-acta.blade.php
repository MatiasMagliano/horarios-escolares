<div id="acta">
    @php
        $numeroActa = $numeroActa ?? null;
        $anioActa = $anioActa ?? null;
    @endphp

    <div class="acta-documento">
        <div class="acta-header">
            <h5 class="text-center w-100">
                ACTA ACUERDO {{ strtoupper($tipoCambio) }} DE HORARIO
            </h5>

            <p class="text-center acta-numero">
                Acta N° {{ $numeroActa && $anioActa ? $numeroActa . '/' . $anioActa : '_____/_____' }}
            </p>

            <p class="text-end acta-fecha">
                Ciudad de Monte Cristo, {{ $fechaActual }}
            </p>
        </div>
        <div class="acta-body">
            {!! $cuerpoHtml !!}
        </div>

        <table class="acta-firmas" style="width: 100%; table-layout: fixed; margin-top: 54px; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; height: 110px; padding: 42px 28px 0; text-align: center; vertical-align: bottom;">
                    <div class="firma-linea" style="border-top: 1px solid #111827; height: 10px; margin-bottom: 6px;"></div>
                    <div>Firma solicitante</div>
                </td>
                <td style="width: 50%; height: 110px; padding: 42px 28px 0; text-align: center; vertical-align: bottom;">
                    <div class="firma-linea" style="border-top: 1px solid #111827; height: 10px; margin-bottom: 6px;"></div>
                    <div>Firma Director/a</div>
                </td>
            </tr>
        </table>
    </div>
</div>
