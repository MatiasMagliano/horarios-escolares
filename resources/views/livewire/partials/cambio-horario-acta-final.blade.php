<div class="card">
    <div class="card-header">
        <div class="text-center">
            <h6 class="tmb-3">
                ACTA Nº {{ $cambio ? $cambio->id : '---' }}
            </h6>
            <small class="text-muted">Se asignará número de acta luego de que se firme.</small>
        </div>
    </div>

    <div class="card-body">
        <div class="mb-4 p-3 border rounded bg-light">
            <span class="text-center d-block">ACTA ACUERDO DE {{ strtoupper($tipo_institucional) }} DE HORARIO</span>
            <p class="d-flex justify-content-end">
                Ciudad de Monte Cristo, {{ $fecha_actual }}
            </p>
            <div class="text-justify">
                {!! $texto_base_html !!}
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <p>
                Firma solicitante/s: _______________________
            </p>
            <p>
                Firma y sello director/a: _______________________
            </p>
        </div>
    </div>
</div>
