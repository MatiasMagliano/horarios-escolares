<?php

namespace App\Livewire;

use App\Models\CambioHorario as CambioHorarioModel;
use App\Models\BloqueHorario;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\HorarioBase;
use App\Support\Instituciones\InstitucionContext;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CambioHorarioDetalle extends Component
{
    public CambioHorarioModel $cambio;

    public $horario_base_id;
    public $nuevo_docente_id;
    public $nuevo_bloque_id;
    public $nuevo_curso_id;
    public $dia_nuevo;
    public $observaciones;

    public function mount(CambioHorarioModel $cambio)
    {
        $this->cambio = $cambio;
        $this->refrescarCambio();
    }

    protected function rules()
    {
        $institucionId = app(InstitucionContext::class)->id();

        return [
            'horario_base_id' => [
                'required',
                Rule::exists('horarios_base', 'id')
                    ->where('institucion_id', $institucionId)
                    ->where('curso_id', $this->cambio->curso_id),
                Rule::unique('cambio_horario_detalles', 'horario_base_id')
                    ->where('cambio_horario_id', $this->cambio->id),
            ],
            'dia_nuevo' => 'nullable|integer|min:1|max:5',
            'nuevo_docente_id' => [
                'nullable',
                Rule::exists('docentes', 'id')->where('institucion_id', $institucionId),
            ],
            'nuevo_bloque_id' => [
                'nullable',
                Rule::exists('bloques_horarios', 'id')->where('institucion_id', $institucionId),
            ],
            'nuevo_curso_id' => [
                'nullable',
                Rule::exists('cursos', 'id')->where('institucion_id', $institucionId),
            ],
            'observaciones' => 'nullable|string|max:255',
        ];
    }

    public function agregar()
    {
        if ($this->cambio->estado !== 'borrador') {
            return;
        }

        $this->validate();

        if (!$this->dia_nuevo && !$this->nuevo_docente_id && !$this->nuevo_bloque_id && !$this->nuevo_curso_id) {
            $this->addError('detalle', 'Indicá al menos un dato nuevo para el cambio.');
            return;
        }

        $this->cambio->detalles()->create([
            'horario_base_id' => $this->horario_base_id,
            'docente_nuevo_id' => $this->nuevo_docente_id,
            'bloque_nuevo_id' => $this->nuevo_bloque_id,
            'curso_nuevo_id' => $this->nuevo_curso_id,
            'dia_nuevo' => $this->dia_nuevo,
            'observaciones' => $this->observaciones,
        ]);

        $this->reset([
            'horario_base_id',
            'nuevo_docente_id',
            'nuevo_bloque_id',
            'nuevo_curso_id',
            'dia_nuevo',
            'observaciones',
        ]);

        $this->refrescarCambio();
        $this->dispatch('cambio-horario-detalles-actualizados');
    }

    public function eliminar($id)
    {
        if ($this->cambio->estado !== 'borrador') return;

        $this->cambio->detalles()->where('id', $id)->delete();
        $this->refrescarCambio();
        $this->dispatch('cambio-horario-detalles-actualizados');
    }


    public function render()
    {
        return view('livewire.cambio-horario-detalle', [
            'horariosBase' => $this->horariosBase(),
            'docentes' => Docente::query()
                ->where('activo', true)
                ->orderBy('nombre_completo')
                ->get(['id', 'nombre_completo', 'nombre']),
            'bloques' => BloqueHorario::query()
                ->where('es_editable', true)
                ->orderBy('turno')
                ->orderBy('orden')
                ->get(['id', 'nombre', 'turno', 'orden', 'hora_inicio', 'hora_fin']),
            'cursos' => Curso::query()
                ->orderBy('anio')
                ->orderBy('division')
                ->get(['id', 'anio', 'division', 'turno']),
        ]);
    }

    private function horariosBase()
    {
        return HorarioBase::query()
            ->vigente()
            ->conDocenteVigente()
            ->where('curso_id', $this->cambio->curso_id)
            ->whereHas('cursoMateria', function ($query) {
                $query->where('materia_id', $this->cambio->materia_id)
                    ->whereHas('cmDocentes', function ($docentes) {
                        $docentes->vigente()->where('docente_id', $this->cambio->docente_id);
                    });
            })
            ->orderBy('dia_semana')
            ->orderBy('bloque_id')
            ->get();
    }

    private function refrescarCambio(): void
    {
        $this->cambio->refresh();
        $this->cambio->load([
            'detalles.horarioBase.bloque',
            'detalles.horarioBase.cursoMateria.materia',
            'detalles.docenteNuevo',
            'detalles.bloqueNuevo',
            'detalles.cursoNuevo',
        ]);
    }
}
