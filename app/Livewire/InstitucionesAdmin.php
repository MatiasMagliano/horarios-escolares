<?php

namespace App\Livewire;

use App\Models\Institucion;
use App\Support\Horarios\BloqueHorarioTemplateManager;
use Illuminate\Validation\Rule;
use Livewire\Component;

class InstitucionesAdmin extends Component
{
    public string $nombre_institucion = '';
    public string $slug = '';
    public string $direccion = '';
    public string $telefono = '';
    public string $email = '';
    public int $anio_maximo = 6;
    public bool $tiene_turno_maniana = true;
    public bool $tiene_turno_tarde = true;
    public bool $tiene_contraturno_maniana = false;
    public bool $tiene_contraturno_tarde = false;
    public string $genero_director = 'masculino';
    public string $nombre_director = '';
    public string $telefono_director = '';
    public string $email_director = '';
    public string $genero_vicedirector = 'masculino';
    public string $nombre_vicedirector = '';
    public string $telefono_vicedirector = '';
    public string $email_vicedirector = '';
    public bool $activo = true;

    public ?int $editandoId = null;

    protected function rules(): array
    {
        return [
            'nombre_institucion' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('datos_institucionales', 'slug')->ignore($this->editandoId)],
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'anio_maximo' => 'required|integer|min:1|max:9',
            'tiene_turno_maniana' => 'boolean',
            'tiene_turno_tarde' => 'boolean',
            'tiene_contraturno_maniana' => 'boolean',
            'tiene_contraturno_tarde' => 'boolean',
            'genero_director' => 'nullable|string|in:masculino,femenino',
            'nombre_director' => 'nullable|string|max:255',
            'telefono_director' => 'nullable|string|max:50',
            'email_director' => 'nullable|email|max:255',
            'genero_vicedirector' => 'nullable|string|in:masculino,femenino',
            'nombre_vicedirector' => 'nullable|string|max:255',
            'telefono_vicedirector' => 'nullable|string|max:50',
            'email_vicedirector' => 'nullable|email|max:255',
            'activo' => 'boolean',
        ];
    }

    public function updatedNombreInstitucion($value)
    {
        if (!$this->editandoId) {
            $this->slug = \Illuminate\Support\Str::slug($value);
        }
    }

    public function guardar(): void
    {
        $this->validate();

        $data = $this->getFormData();
        if ($this->editandoId) {
            $institucion = Institucion::findOrFail($this->editandoId);
            $institucion->update($data);
        } else {
            $institucion = Institucion::create($data);
        }

        app(BloqueHorarioTemplateManager::class)->ensureForInstitucion($institucion);

        $this->cancelar();
        $this->dispatch('instituciones-actualizadas');
    }

    private function getFormData(): array
    {
        return [
            'nombre_institucion' => $this->nombre_institucion,
            'slug' => $this->slug,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'anio_maximo' => $this->anio_maximo,
            'tiene_turno_maniana' => $this->tiene_turno_maniana,
            'tiene_turno_tarde' => $this->tiene_turno_tarde,
            'tiene_contraturno_maniana' => $this->tiene_contraturno_maniana,
            'tiene_contraturno_tarde' => $this->tiene_contraturno_tarde,
            'genero_director' => $this->genero_director,
            'nombre_director' => $this->nombre_director,
            'telefono_director' => $this->telefono_director,
            'email_director' => $this->email_director,
            'genero_vicedirector' => $this->genero_vicedirector,
            'nombre_vicedirector' => $this->nombre_vicedirector,
            'telefono_vicedirector' => $this->telefono_vicedirector,
            'email_vicedirector' => $this->email_vicedirector,
            'activo' => $this->activo,
        ];
    }

    public function editar(int $id): void
    {
        $institucion = Institucion::findOrFail($id);

        $this->editandoId = $institucion->id;
        $this->nombre_institucion = $institucion->nombre_institucion;
        $this->slug = $institucion->slug;
        $this->direccion = $institucion->direccion ?? '';
        $this->telefono = $institucion->telefono ?? '';
        $this->email = $institucion->email ?? '';
        $this->anio_maximo = $institucion->anio_maximo;
        $this->tiene_turno_maniana = $institucion->tiene_turno_maniana;
        $this->tiene_turno_tarde = $institucion->tiene_turno_tarde;
        $this->tiene_contraturno_maniana = $institucion->tiene_contraturno_maniana;
        $this->tiene_contraturno_tarde = $institucion->tiene_contraturno_tarde;
        $this->genero_director = $institucion->genero_director ?? 'masculino';
        $this->nombre_director = $institucion->nombre_director ?? '';
        $this->telefono_director = $institucion->telefono_director ?? '';
        $this->email_director = $institucion->email_director ?? '';
        $this->genero_vicedirector = $institucion->genero_vicedirector ?? 'masculino';
        $this->nombre_vicedirector = $institucion->nombre_vicedirector ?? '';
        $this->telefono_vicedirector = $institucion->telefono_vicedirector ?? '';
        $this->email_vicedirector = $institucion->email_vicedirector ?? '';
        $this->activo = $institucion->activo;

        $this->dispatch('abrir-modal-institucion');
    }

    public function nuevo(): void
    {
        $this->resetFormulario();
        $this->dispatch('abrir-modal-institucion');
    }

    public function cancelar(): void
    {
        $this->resetFormulario();
        $this->dispatch('cerrar-modal-institucion');
    }

    private function resetFormulario(): void
    {
        $this->reset([
            'editandoId', 'nombre_institucion', 'slug', 'direccion', 'telefono', 'email', 
            'tiene_contraturno_maniana', 'tiene_contraturno_tarde',
            'nombre_director', 'telefono_director', 'email_director',
            'nombre_vicedirector', 'telefono_vicedirector', 'email_vicedirector'
        ]);
        $this->anio_maximo = 6;
        $this->tiene_turno_maniana = true;
        $this->tiene_turno_tarde = true;
        $this->genero_director = 'masculino';
        $this->genero_vicedirector = 'masculino';
        $this->activo = true;
    }

    public function render()
    {
        return view('livewire.instituciones-admin', [
            'instituciones' => Institucion::query()
                ->orderBy('nombre_institucion')
                ->get(),
        ]);
    }
}
