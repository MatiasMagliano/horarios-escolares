<?php

namespace App\Livewire;

use App\Models\BloqueHorarioConfig;
use App\Models\Institucion;
use App\Support\Horarios\BloqueHorarioTemplateManager;
use Livewire\Component;

class ConfiguracionBloqueHorario extends Component
{
    public ?int $institucionId = null;
    public ?Institucion $institucion = null;
    public string $turnoSeleccionado = 'maniana';
    public array $bloques = [];
    
    public ?int $editandoBloqueId = null;
    public bool $agregarNuevo = false;
    public string $bloqueNombre = '';
    public string $bloqueHoraInicio = '';
    public string $bloqueHoraFin = '';
    public string $bloqueTipo = 'clase';

    public function mount(?int $institucionId = null): void
    {
        if ($institucionId) {
            $this->institucionId = $institucionId;
            $this->cargarInstitucion();
        }
    }

    public function cargarInstitucion(): void
    {
        if (!$this->institucionId) {
            return;
        }

        $this->institucion = Institucion::findOrFail($this->institucionId);

        if (!$this->institucion->tieneTurno($this->turnoSeleccionado)) {
            $this->turnoSeleccionado = $this->institucion->turnosConfigurados()[0] ?? 'maniana';
        }

        $this->cargarBloquesPorTurno();
    }

    public function cargarBloquesPorTurno(): void
    {
        if (!$this->institucion) {
            return;
        }

        $this->bloques = $this->institucion
            ->getBloquesPorTurno($this->turnoSeleccionado)
            ->map(fn ($bloque) => [
                'id' => $bloque->id,
                'nombre' => $bloque->nombre,
                'turno' => $bloque->turno,
                'orden' => $bloque->orden,
                'hora_inicio' => is_string($bloque->hora_inicio) ? $bloque->hora_inicio : $bloque->hora_inicio?->format('H:i'),
                'hora_fin' => is_string($bloque->hora_fin) ? $bloque->hora_fin : $bloque->hora_fin?->format('H:i'),
                'tipo' => $bloque->tipo,
                'duracion' => $bloque->calcularDuracion(),
            ])
            ->toArray();
    }

    public function cambiarTurno(string $turno): void
    {
        $this->turnoSeleccionado = $turno;
        $this->cargarBloquesPorTurno();
        $this->cancelarEdicion();
    }

    public function editarBloque(int $bloqueId): void
    {
        $bloque = BloqueHorarioConfig::withoutGlobalScope('institucion')
            ->where('institucion_id', $this->institucionId)
            ->findOrFail($bloqueId);
        
        $this->editandoBloqueId = $bloqueId;
        $this->bloqueNombre = $bloque->nombre;
        $this->bloqueHoraInicio = is_string($bloque->hora_inicio) ? $bloque->hora_inicio : $bloque->hora_inicio?->format('H:i') ?? '';
        $this->bloqueHoraFin = is_string($bloque->hora_fin) ? $bloque->hora_fin : $bloque->hora_fin?->format('H:i') ?? '';
        $this->bloqueTipo = $bloque->tipo;
    }

    public function guardarBloque(): void
    {
        $this->validate([
            'bloqueNombre' => 'required|string|max:50',
            'bloqueHoraInicio' => 'required|date_format:H:i',
            'bloqueHoraFin' => 'required|date_format:H:i|after:bloqueHoraInicio',
            'bloqueTipo' => 'required|in:clase,recreo',
        ]);

        if ($this->editandoBloqueId) {
            $bloque = BloqueHorarioConfig::withoutGlobalScope('institucion')
                ->where('institucion_id', $this->institucionId)
                ->findOrFail($this->editandoBloqueId);

            app(BloqueHorarioTemplateManager::class)->updateBloqueConfig($bloque, [
                'nombre' => $this->bloqueNombre,
                'hora_inicio' => $this->bloqueHoraInicio,
                'hora_fin' => $this->bloqueHoraFin,
                'tipo' => $this->bloqueTipo,
            ]);

            $this->dispatch('toast', [
                'tipo' => 'success',
                'mensaje' => 'Bloque actualizado correctamente',
            ]);
        }

        $this->cargarBloquesPorTurno();
        $this->cancelarEdicion();
    }

    public function agregarBloque(): void
    {
        if (!$this->institucion) {
            return;
        }

        $this->validate([
            'bloqueNombre' => 'required|string|max:50',
            'bloqueHoraInicio' => 'required|date_format:H:i',
            'bloqueHoraFin' => 'required|date_format:H:i|after:bloqueHoraInicio',
            'bloqueTipo' => 'required|in:clase,recreo',
        ]);

        $nuevoOrden = collect($this->bloques)->max('orden') ?? 0;
        $nuevoOrden++;

        $bloque = BloqueHorarioConfig::create([
            'institucion_id' => $this->institucion->id,
            'nombre' => $this->bloqueNombre,
            'turno' => $this->turnoSeleccionado,
            'orden' => $nuevoOrden,
            'hora_inicio' => $this->bloqueHoraInicio,
            'hora_fin' => $this->bloqueHoraFin,
            'tipo' => $this->bloqueTipo,
        ]);

        app(BloqueHorarioTemplateManager::class)->syncBloqueHorarioFromConfig($bloque);

        $this->dispatch('toast', [
            'tipo' => 'success',
            'mensaje' => 'Bloque agregado correctamente',
        ]);

        $this->cargarBloquesPorTurno();
        $this->cancelarEdicion();
    }

    public function eliminarBloque(int $bloqueId): void
    {
        $bloque = BloqueHorarioConfig::withoutGlobalScope('institucion')
            ->where('institucion_id', $this->institucionId)
            ->findOrFail($bloqueId);

        app(BloqueHorarioTemplateManager::class)->deleteBloqueHorarioForConfig($bloque);

        $bloque->delete();

        $this->dispatch('toast', [
            'tipo' => 'success',
            'mensaje' => 'Bloque eliminado correctamente',
        ]);

        $this->cargarBloquesPorTurno();
    }

    public function cancelarEdicion(): void
    {
        $this->editandoBloqueId = null;
        $this->agregarNuevo = false;
        $this->bloqueNombre = '';
        $this->bloqueHoraInicio = '';
        $this->bloqueHoraFin = '';
        $this->bloqueTipo = 'clase';
    }

    public function render()
    {
        return view('livewire.configuracion-bloque-horario');
    }
}
