<?php

namespace App\Support\Dashboard;

use App\Models\CambioHorario;
use Illuminate\Database\Eloquent\Collection;

class CambioHorarioPendientesDetector
{
    public function detectarPorEstado(): array
    {
        // Usar groupBy en la BD para contar eficientemente
        $cambios = CambioHorario::query()
            ->select('estado')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('estado')
            ->get()
            ->keyBy('estado');

        return [
            'borrador' => (int) ($cambios->get('borrador')?->total ?? 0),
            'autorizado' => (int) ($cambios->get('autorizado')?->total ?? 0),
            'firmado' => (int) ($cambios->get('firmado')?->total ?? 0),
            'activo' => (int) ($cambios->get('activo')?->total ?? 0),
            'finalizado' => (int) ($cambios->get('finalizado')?->total ?? 0),
            'total_pendientes' => ((int) ($cambios->get('borrador')?->total ?? 0) + (int) ($cambios->get('autorizado')?->total ?? 0) + (int) ($cambios->get('firmado')?->total ?? 0)),
        ];
    }

    public function obtenerCambiosPorEstado(string $estado): Collection
    {
        return CambioHorario::query()
            ->where('estado', $estado)
            ->with([
                'docente',
                'curso',
                'materia',
                'solicitante',
            ])
            ->orderByDesc('created_at')
            ->get();
    }

    public function obtenerTodos(): Collection
    {
        return CambioHorario::query()
            ->with([
                'docente',
                'curso',
                'materia',
                'solicitante',
            ])
            ->orderByDesc('created_at')
            ->get();
    }

    public function estadoTexto(string $estado): string
    {
        return match ($estado) {
            'borrador' => 'Borrador',
            'autorizado' => 'Autorizado',
            'firmado' => 'Firmado',
            'activo' => 'Activo',
            'finalizado' => 'Finalizado',
            default => 'Desconocido',
        };
    }

    public function estadoColor(string $estado): string
    {
        return match ($estado) {
            'borrador' => 'warning',
            'autorizado' => 'info',
            'firmado' => 'info',
            'activo' => 'success',
            'finalizado' => 'secondary',
            default => 'dark',
        };
    }

    public function estadoEsPendiente(string $estado): bool
    {
        return in_array($estado, ['borrador', 'autorizado', 'firmado']);
    }
}
