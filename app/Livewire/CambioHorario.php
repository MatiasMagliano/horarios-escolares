<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CambioHorario as CambioHorarioModel;
use Illuminate\Validation\Rule;

class CambioHorario extends Component
{
    public $modo = 'listado'; // listado | formulario

    public ?CambioHorarioModel $cambio = null;

    public $tipo = 'temporal';
    public $motivo = '';
    public $fecha_desde;
    public $fecha_hasta;

    public $estado = 'borrador';

    public function mount()
    {
        $this->fecha_desde = today()->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'tipo' => 'required|in:temporal,permanente',
            'motivo' => 'required|string|min:10',
            'fecha_desde' => 'required|date',
            'fecha_hasta' => [
                Rule::requiredIf($this->tipo === 'temporal'),
                'nullable',
                'date',
                'after_or_equal:fecha_desde'
            ],
        ];
    }

    public function nuevo()
    {
        $this->resetExcept('modo');
        $this->modo = 'formulario';
        $this->estado = 'borrador';
        $this->fecha_desde = today()->format('Y-m-d');
        // $this->dispatch('abrir-modal-cambio-horario');
    }

    public function guardar()
    {
        $this->validate();

        $data = [
            'tipo' => $this->tipo,
            'motivo' => $this->motivo,
            'fecha_desde' => $this->fecha_desde,
            'fecha_hasta' => $this->tipo === 'temporal'
                ? $this->fecha_hasta
                : null,
            'estado' => 'borrador',
            'pedido_por' => auth()->id()
        ];

        if ($this->cambio) {
            $this->cambio->update($data);
        } else {
            $this->cambio = CambioHorarioModel::create($data);
        }

        $this->modo = 'listado';
    }

    public function verDetalle($id)
    {
        $this->cambio = CambioHorarioModel::findOrFail($id);
        $this->dispatch('abrir-modal-detalle-cambio-horario');
    }

    // ESTADOS DE LA MÁQUINA DE ESTADOS (WORK IN PROGRESS)
    public function autorizar($id)
    {
        try {
            $cambio = CambioHorarioModel::findOrFail($id);
            $cambio->autorizar(auth()->user());

            session()->flash('success', 'Cambio autorizado correctamente.');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function firmar($id)
    {
        try {
            $cambio = CambioHorarioModel::findOrFail($id);
            $cambio->firmar(auth()->user());

            session()->flash('success', 'Cambio firmado correctamente.');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function activar($id)
    {
        try {
            $cambio = CambioHorarioModel::findOrFail($id);
            $cambio->activar(auth()->user());

            session()->flash('success', 'Cambio activado correctamente.');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function finalizar($id)
    {
        try {
            $cambio = CambioHorarioModel::findOrFail($id);
            $cambio->finalizar();

            session()->flash('success', 'Cambio finalizado correctamente.');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.cambio-horario', [
            'cambios' => CambioHorarioModel::orderByDesc('created_at')->get()
        ]);
    }
}
