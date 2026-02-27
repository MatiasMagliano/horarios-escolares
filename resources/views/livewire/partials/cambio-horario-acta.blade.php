<div id="acta">
    <div class="card">
        <div class="card-header">
            <h5 class="text-center w-100">
                ACTA ACUERDO {{ strtoupper($tipoCambio) }} DE HORARIO
            </h5>

            <p class="text-end">
                Ciudad de Monte Cristo, {{ $fechaActual }}
            </p>
        </div>
        <div class="card-body" style="margin: 0 1rem; text-indent: 2rem; text-align: justify;">
            {!! $cuerpoHtml !!}
        </div>

        <div class="d-flex justify-content-between text-center mt-4 border-top pt-3">
            <p>
                Firma solicitante: _______________________
            </p>
            <p>
                Firma Director/a: _______________________
            </p>
        </div>
    </div>
</div>
