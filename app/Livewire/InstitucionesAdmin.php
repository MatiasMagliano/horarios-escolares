<?php

namespace App\Livewire;

use App\Models\Institucion;
use App\Support\Horarios\BloqueHorarioTemplateManager;
use Illuminate\Support\Facades\DB;
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
    public string $bloqueHorarioTemplate = BloqueHorarioTemplateManager::TEMPLATE_ESTANDAR_40;
    public string $personalizadoHoraInicio = '07:10';
    public $personalizadoCantidadBloques = 8;
    public $personalizadoCantidadRecreos = 2;

    public ?int $editandoId = null;
    public bool $mostrarConfiguracionBloques = false;
    public ?int $eliminandoId = null;
    public string $eliminandoNombre = '';
    public string $eliminandoSlug = '';
    public string $eliminacionConfirmacion = '';
    public array $eliminacionResumen = [];
    public ?string $eliminacionBloqueada = null;

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
            'bloqueHorarioTemplate' => ['required', 'string', Rule::in(array_keys(app(BloqueHorarioTemplateManager::class)->options()))],
            'personalizadoHoraInicio' => [
                Rule::requiredIf($this->bloqueHorarioTemplate === BloqueHorarioTemplateManager::TEMPLATE_PERSONALIZADO),
                'date_format:H:i',
            ],
            'personalizadoCantidadBloques' => [
                Rule::requiredIf($this->bloqueHorarioTemplate === BloqueHorarioTemplateManager::TEMPLATE_PERSONALIZADO),
                'integer',
                'min:1',
                'max:12',
            ],
            'personalizadoCantidadRecreos' => [
                Rule::requiredIf($this->bloqueHorarioTemplate === BloqueHorarioTemplateManager::TEMPLATE_PERSONALIZADO),
                'integer',
                'min:0',
                'max:5',
                'lt:personalizadoCantidadBloques',
            ],
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
        $this->persistirInstitucion();

        $this->cancelar();
        $this->dispatch('instituciones-actualizadas');
    }

    public function guardarYAbrirConfiguracionBloques(): void
    {
        $institucion = $this->persistirInstitucion();

        $this->editandoId = $institucion->id;
        $this->mostrarConfiguracionBloques = true;
        $this->dispatch('cerrar-modal-institucion');
        $this->dispatch('instituciones-actualizadas');
    }

    private function persistirInstitucion(): Institucion
    {
        $this->validate();

        $data = $this->getFormData();
        $esEdicion = (bool) $this->editandoId;

        if ($this->editandoId) {
            $institucion = Institucion::findOrFail($this->editandoId);
            $institucion->update($data);
        } else {
            $institucion = Institucion::create($data);
        }

        $manager = app(BloqueHorarioTemplateManager::class);

        if (!$esEdicion) {
            if ($this->bloqueHorarioTemplate === BloqueHorarioTemplateManager::TEMPLATE_PERSONALIZADO) {
                $manager->saveBlocksForInstitucion(
                    $institucion,
                    $this->personalizadoPreview(),
                    replaceExisting: true
                );
                $manager->createBloqueHorarioFromConfigs($institucion);
            } else {
                $manager->ensureForInstitucion(
                    $institucion,
                    $this->bloqueHorarioTemplate,
                    replaceExisting: true
                );
            }
        } else {
            $manager->ensureTurnosForInstitucion($institucion);
        }

        return $institucion;
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

    public function abrirConfiguracionBloques(): void
    {
        if (!$this->editandoId) {
            $this->guardarYAbrirConfiguracionBloques();

            return;
        }

        $this->mostrarConfiguracionBloques = true;
    }

    public function abrirConfiguracionBloquesPara(int $id): void
    {
        $institucion = Institucion::findOrFail($id);

        $this->editandoId = $institucion->id;
        $this->mostrarConfiguracionBloques = true;
    }

    public function cerrarConfiguracionBloques(): void
    {
        $this->mostrarConfiguracionBloques = false;
    }

    public function confirmarEliminar(int $id): void
    {
        $institucion = Institucion::findOrFail($id);

        $this->eliminandoId = $institucion->id;
        $this->eliminandoNombre = $institucion->nombre_institucion;
        $this->eliminandoSlug = $institucion->slug;
        $this->eliminacionConfirmacion = '';
        $this->eliminacionResumen = $this->resumenEliminacion($institucion);
        $this->eliminacionBloqueada = $this->motivoBloqueoEliminacion($institucion);
    }

    public function cancelarEliminacion(): void
    {
        $this->reset([
            'eliminandoId',
            'eliminandoNombre',
            'eliminandoSlug',
            'eliminacionConfirmacion',
            'eliminacionResumen',
            'eliminacionBloqueada',
        ]);
    }

    public function eliminarInstitucion(): void
    {
        if (!$this->eliminandoId) {
            return;
        }

        $this->validate([
            'eliminacionConfirmacion' => ['required', 'same:eliminandoSlug'],
        ], [
            'eliminacionConfirmacion.same' => 'La confirmación debe coincidir con el slug de la escuela.',
        ]);

        $institucion = Institucion::findOrFail($this->eliminandoId);
        $bloqueo = $this->motivoBloqueoEliminacion($institucion);

        if ($bloqueo) {
            $this->eliminacionBloqueada = $bloqueo;

            return;
        }

        $institucionId = $institucion->id;

        DB::transaction(function () use ($institucion, $institucionId) {
            $this->eliminarDatosDependientes($institucionId);

            $institucion->delete();

            if ((int) session('institucion_id') === (int) $institucionId) {
                session()->forget('institucion_id');
            }

            $user = auth()->user();

            if ($user && (int) $user->institucion_activa_id === (int) $institucionId) {
                $user->forceFill(['institucion_activa_id' => null])->save();
            }
        });

        if ((int) $this->editandoId === (int) $institucionId) {
            $this->editandoId = null;
            $this->mostrarConfiguracionBloques = false;
            $this->dispatch('cerrar-modal-institucion');
        }

        $this->cancelarEliminacion();
        $this->dispatch('instituciones-actualizadas');
        session()->flash('success', 'Escuela eliminada correctamente.');
    }

    private function eliminarDatosDependientes(int $institucionId): void
    {
        DB::table('cambio_horario_detalles')->where('institucion_id', $institucionId)->delete();
        DB::table('horarios_base')->where('institucion_id', $institucionId)->delete();
        DB::table('cambios_horario')->where('institucion_id', $institucionId)->delete();
        DB::table('cm_docente')->where('institucion_id', $institucionId)->delete();
        DB::table('curso_materia')->where('institucion_id', $institucionId)->delete();
        DB::table('cursos')->where('institucion_id', $institucionId)->delete();
        DB::table('docentes')->where('institucion_id', $institucionId)->delete();
        DB::table('espacios_fisicos')->where('institucion_id', $institucionId)->delete();
        DB::table('bloque_horario_configs')->where('institucion_id', $institucionId)->delete();
        DB::table('bloques_horarios')->where('institucion_id', $institucionId)->delete();
        DB::table('institucion_user')->where('institucion_id', $institucionId)->delete();
    }

    private function resumenEliminacion(Institucion $institucion): array
    {
        $id = $institucion->id;

        return [
            'Usuarios vinculados' => DB::table('institucion_user')->where('institucion_id', $id)->count(),
            'Usuarios con esta escuela activa' => DB::table('users')->where('institucion_activa_id', $id)->count(),
            'Cursos' => DB::table('cursos')->where('institucion_id', $id)->count(),
            'Docentes' => DB::table('docentes')->where('institucion_id', $id)->count(),
            'Espacios físicos' => DB::table('espacios_fisicos')->where('institucion_id', $id)->count(),
            'Bloques horarios' => DB::table('bloques_horarios')->where('institucion_id', $id)->count(),
            'Configuraciones de bloques' => DB::table('bloque_horario_configs')->where('institucion_id', $id)->count(),
            'Materias de cursos' => DB::table('curso_materia')->where('institucion_id', $id)->count(),
            'Horarios base' => DB::table('horarios_base')->where('institucion_id', $id)->count(),
            'Asignaciones docentes' => DB::table('cm_docente')->where('institucion_id', $id)->count(),
            'Cambios de horario' => DB::table('cambios_horario')->where('institucion_id', $id)->count(),
            'Detalles de cambios' => DB::table('cambio_horario_detalles')->where('institucion_id', $id)->count(),
        ];
    }

    private function motivoBloqueoEliminacion(Institucion $institucion): ?string
    {
        if (
            $institucion->activo
            && Institucion::query()->where('activo', true)->count() <= 1
        ) {
            return 'No se puede eliminar la única escuela activa del sistema. Primero activá o creá otra escuela.';
        }

        return null;
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
        $this->bloqueHorarioTemplate = BloqueHorarioTemplateManager::TEMPLATE_ESTANDAR_40;
        $this->personalizadoHoraInicio = '07:10';
        $this->personalizadoCantidadBloques = 8;
        $this->personalizadoCantidadRecreos = 2;
        $this->mostrarConfiguracionBloques = false;
    }

    public function personalizadoPreview(): array
    {
        $cantidadBloques = filter_var($this->personalizadoCantidadBloques, FILTER_VALIDATE_INT);
        $cantidadRecreos = filter_var($this->personalizadoCantidadRecreos, FILTER_VALIDATE_INT);

        if ($cantidadBloques === false || $cantidadBloques < 1) {
            return [];
        }

        if ($cantidadRecreos === false || $cantidadRecreos < 0) {
            $cantidadRecreos = 0;
        }

        return app(BloqueHorarioTemplateManager::class)->personalizado(
            $this->personalizadoHoraInicio,
            $cantidadBloques,
            $cantidadRecreos
        );
    }

    public function personalizadoPreviewManiana(): array
    {
        return collect($this->personalizadoPreview())
            ->where('turno', 'maniana')
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.instituciones-admin', [
            'instituciones' => Institucion::query()
                ->orderBy('nombre_institucion')
                ->get(),
            'bloqueHorarioTemplates' => app(BloqueHorarioTemplateManager::class)->options(),
            'personalizadoPreviewManiana' => $this->personalizadoPreviewManiana(),
        ]);
    }
}
