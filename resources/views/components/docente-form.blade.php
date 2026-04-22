<?php

use Livewire\Component;
use App\Models\Docente;
use App\Support\Instituciones\InstitucionContext;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

new class extends Component
{
    public ?Docente $docente = null;

    public string $dni = '';
    public string $nombre_completo = '';
    public string $nombre = '';
    public string $nacimiento = '';
    public ?string $email = null;
    public ?string $telefono = null;
    public bool $activo = true;

    public bool $editing = false;

    public function mount(?Docente $docente = null)
    {
        if ($docente && $docente->exists) {
            $this->docente = $docente;
            $this->editing = true;

            $this->nombre = $docente->nombre;
            $this->nombre_completo = $docente->nombre_completo;
            $this->dni = $docente->dni;
            $this->nacimiento = $docente->nacimiento?->format('Y-m-d') ?? '';
            $this->telefono = $docente->telefono;
            $this->email = $docente->email;
            $this->activo = $docente->activo;
        }
    }

    protected function rules()
    {
        $institucionId = app(InstitucionContext::class)->id();

        return [
            'nombre' => 'required|string|max:100',
            'nombre_completo' => 'required|string|max:150',
            'dni' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9A-Za-z]+$/',
                Rule::unique('docentes', 'dni')
                    ->where('institucion_id', $institucionId)
                    ->ignore($this->docente?->id),
            ],
            'nacimiento' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::today()->toDateString(),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('docentes', 'email')
                    ->where('institucion_id', $institucionId)
                    ->ignore($this->docente?->id),
            ],
            'telefono' => 'nullable|string|max:20',
            'activo' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'dni' => trim($this->dni),
            'nombre' => trim($this->nombre),
            'nombre_completo' => trim($this->nombre_completo),
            'nacimiento' => $this->nacimiento,
            'telefono' => filled($this->telefono) ? trim($this->telefono) : null,
            'email' => filled($this->email) ? mb_strtolower(trim($this->email)) : null,
            'activo' => $this->activo,
        ];

        if ($this->editing) {
            $this->docente->update($data);
            session()->flash('success', 'Docente actualizado correctamente.');
        } else {
            Docente::create($data);
            session()->flash('success', 'Docente creado correctamente.');
            $this->reset(['dni', 'nombre', 'nombre_completo', 'nacimiento', 'telefono', 'email', 'activo']);
            $this->activo = true;
        }

        $this->dispatch('docente-guardado');
    }

    // se pone acá, porque se dispara confirmación antes de proceder
    public function eliminar()
    {
        if (!$this->docente) return;

        if ($this->docente->tieneAsignacionesVigentes()) {
            session()->flash('error', 'No se puede eliminar el docente porque tiene materias activas asignadas.');
            return;
        }

        if ($this->docente->tieneHistorialAsignaciones()) {
            session()->flash('error', 'No se puede eliminar el docente porque tiene historial de asignaciones. Debe conservarse por trazabilidad.');
            return;
        }

        $this->docente->delete();
        session()->flash('success', 'Docente eliminado correctamente.');
        $this->dispatch('docente-eliminado');
    }
};
?>

<div class="card shadow-sm">
    <div class="card-body">
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="row">
                <div class="col-md-4">
                    {{-- DNI --}}
                    <div class="mb-3">
                        <label class="form-label">DNI</label>
                        <input wire:model="dni" type="text" class="form-control" maxlength="100" aria-describedby="documentoHelp">
                        <div id="documentoHelp" class="form-text">Sin puntos ni guiones</div>
                        @error('dni') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    {{-- DENOMINACIÓN --}}
                    <div class="mb-3">
                        <label class="form-label">Denominación</label>
                        <input wire:model="nombre" type="text" class="form-control" maxlength="100">
                        @error('nombre') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    {{-- NOMBRE COMPLETO --}}
                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input wire:model="nombre_completo" type="text" class="form-control" maxlength="100">
                        @error('nombre_completo') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    {{-- NACIMIENTO --}}
                    <div class="mb-3">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input wire:model="nacimiento" type="date" class="form-control">
                        @error('nacimiento') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    {{-- TELÉFONO --}}
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input wire:model="telefono" type="text" class="form-control" maxlength="20" aria-labelledby="telefonoHelp">
                        <div id="telefonoHelp" class="form-text">Sólo números y sin espacios</div>
                        @error('telefono') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input wire:model="email" type="email" class="form-control" maxlength="255">
                        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- ACTIVO oculto --}}
                <input wire:model="activo" type="hidden">
            </div>


            <button type="submit" class="btn btn-primary">
                {{ $editing ? 'Actualizar' : 'Crear' }}
            </button>
    </div>

    </form>

</div>
