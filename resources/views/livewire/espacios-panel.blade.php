<div>
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1">Espacios</h4>
                <div class="text-muted small">
                    Elegí una herramienta para administrar espacios físicos o visualizar su utilización.
                </div>
            </div>

            <div class="btn-group" role="group" aria-label="Herramientas de espacios">
                <button
                    type="button"
                    wire:click="mostrarUtilizacion"
                    class="btn {{ $vista === 'utilizacion' ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                    Utilización
                </button>
                <button
                    type="button"
                    wire:click="mostrarAdministracion"
                    class="btn {{ $vista === 'administracion' ? 'btn-primary' : 'btn-outline-primary' }}"
                >
                    Administrar espacios
                </button>
            </div>
        </div>
    </div>

    @if($vista === 'administracion')
        <livewire:espacios-fisicos-admin />
    @else
        <livewire:utilizacion-espacios />
    @endif
</div>
