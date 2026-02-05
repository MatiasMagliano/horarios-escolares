<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Curso;
use App\Models\BloqueHorario;
use App\Models\HorarioBase;

class HorarioCurso extends Component
{
    public $cursoId = null;
    public $turnoVista = 'maniana';

    public function mount($cursoId = null)
    {
        $this->cursoId = $cursoId;
    }

    public function updatedCursoId()
    {
        // opcional: resetear turno si hiciera falta
    }

    public function getCursosProperty()
    {
        return Curso::orderBy('anio')->orderBy('division')->get();
    }

    public function getGrillasProperty()
    {
        if (!$this->cursoId) {
            return collect();
        }

        $curso = Curso::findOrFail($this->cursoId);
        $turnoCurso = $curso->turno;
        $turnoContraturno = $this->contraturnoDe($turnoCurso);

        return collect([$turnoCurso, $turnoContraturno])
            ->mapWithKeys(function ($turno) {
                // traer todos los bloques
                $bloques = BloqueHorario::where('turno', $turno)->orderBy('orden')->get();

                // traer los horarios
                $horarios = HorarioBase::with(['materia', 'docente', 'bloque'])
                    ->where('curso_id', $this->cursoId)
                    ->whereHas('bloque', fn ($q) => $q->where('turno', $turno))
                    ->get()
                    ->groupBy(fn ($h) => $h->bloque->orden)
                    ->map(fn ($items) => $items->keyBy('dia_semana'));

                // armar la grilla
                $grilla = $bloques->mapWithKeys(function ($bloque) use ($horarios) {
                    return [
                        $bloque->orden => collect([
                            'bloque' => $bloque,
                            'dias' => $horarios[$bloque->orden] ?? collect()
                        ])
                    ];
                });

                return [$turno => $grilla];
            });
    }

    public function render()
    {
        return view('livewire.horario-curso');
    }

    protected function contraturnoDe(string $turnoCurso): string
    {
        return match ($turnoCurso) {
            'maniana' => 'contraturno_maniana',
            'tarde'   => 'contraturno_tarde',
            default   => throw new \LogicException("Turno inválido: $turnoCurso"),
        };
    }
}
