<?php

namespace App\Livewire;

use App\Models\CambioHorario as CambioHorarioModel;
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
    }

    protected function rules()
    {
        $institucionId = app(InstitucionContext::class)->id();

        return [
            'horario_base_id' => [
                'required',
                Rule::exists('horarios_base', 'id')->where('institucion_id', $institucionId),
            ],
            'dia_nuevo' => 'nullable|integer|min:1|max:5',
        ];
    }

    public function agregar()
    {
        if ($this->cambio->estado !== 'borrador') {
            return;
        }

        $this->validate();

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
    }

    public function eliminar($id)
    {
        if ($this->cambio->estado !== 'borrador') return;

        $this->cambio->detalles()->where('id', $id)->delete();
    }


    public function render()
    {
        return view('livewire.cambio-horario-detalle');
    }
}
