<?php

namespace App\Http\Controllers;

use App\Models\CambioHorario;
use App\Models\Curso;
use App\Models\EspacioFisico;
use App\Support\Horarios\HorarioCursoGridBuilder;
use App\Support\Horarios\UtilizacionEspaciosGridBuilder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PdfController extends Controller
{
    public function horarioCurso(Curso $curso, HorarioCursoGridBuilder $builder)
    {
        $grillas = $builder->build($curso->id);
        $advertencias = $builder->warnings($curso->id);
        $institucion = request()->user()?->institucionActiva;

        return Pdf::loadView('pdf.horario-curso', [
            'curso' => $curso,
            'grillas' => $grillas,
            'advertencias' => $advertencias,
            'institucion' => $institucion,
        ])
            ->setPaper('a4', 'portrait')
            ->download("horario-curso-{$curso->id}.pdf");
    }

    public function utilizacionEspacios(
        Request $request,
        EspacioFisico $espacio,
        UtilizacionEspaciosGridBuilder $builder
    ) {
        $cursoIdsVisibles = $this->resolveCursoIdsVisibles($request, $espacio);
        $grillas = $builder->build($espacio->id, $cursoIdsVisibles);
        $advertencias = $builder->warnings($espacio->id, $cursoIdsVisibles);
        $institucion = $request->user()?->institucionActiva;

        return Pdf::loadView('pdf.utilizacion-espacios', [
            'espacio' => $espacio,
            'grillas' => $grillas,
            'advertencias' => $advertencias,
            'institucion' => $institucion,
        ])
            ->setPaper('a4', 'landscape')
            ->download("utilizacion-espacio-{$espacio->id}.pdf");
    }

    public function cambioHorarioActa(CambioHorario $cambio)
    {
        $cambio->loadMissing(['docente', 'curso', 'materia', 'solicitante']);

        $institucion = request()->user()?->institucionActiva;
        Carbon::setLocale('es');

        return Pdf::loadView('pdf.cambio-horario-acta', [
            'cambio' => $cambio,
            'institucion' => $institucion,
            'fechaActual' => now()->translatedFormat('d \\d\\e F \\d\\e Y'),
        ])
            ->setPaper('a4', 'portrait')
            ->download("acta-cambio-horario-{$cambio->id}.pdf");
    }

    private function resolveCursoIdsVisibles(Request $request, EspacioFisico $espacio): array
    {
        $cursosAfectados = Curso::query()
            ->whereHas('horariosBase', function ($query) use ($espacio) {
                $query->vigente()
                    ->whereBetween('dia_semana', [1, 5])
                    ->whereHas('cursoMateria', function ($cursoMateriaQuery) use ($espacio) {
                        $cursoMateriaQuery->where('espacio_fisico_id', $espacio->id);
                    });
            })
            ->orderBy('anio')
            ->orderBy('division')
            ->get(['id']);

        $cursoIdsAfectados = $cursosAfectados
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $cursoIdsSolicitados = collect((array) $request->query('cursos', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->intersect($cursoIdsAfectados)
            ->values()
            ->all();

        return $cursoIdsSolicitados !== [] ? $cursoIdsSolicitados : $cursoIdsAfectados;
    }
}
