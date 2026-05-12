<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CambioHorario as CambioHorarioModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Models\Docente;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\CursoMateria;
use App\Support\Instituciones\InstitucionContext;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class CambioHorario extends Component
{
    use WithFileUploads;

    protected $listeners = [
        'cambio-horario-detalles-actualizados' => 'invalidarActaPorDetalle',
    ];

    public ?CambioHorarioModel $cambio = null;

    public $institucion;
    public $modo = 'listado'; // listado | formulario
    public $duracion = 'temporal';
    public $tipo_cambio = 'cambio';
    public $docente_id;
    public $curso_id;
    public $materia_id;
    public $cursosFiltrados = [];
    public $materiasFiltradas = [];
    public $ciclo_lectivo;
    public $acta = '';
    public $fecha_actual;
    public $fecha_desde;
    public $fecha_hasta;
    public $acta_finalizada = false;
    public array $actasFirmadas = [];

    public $estado = 'borrador';

    public function mount()
    {
        $this->fecha_desde = today()->format('Y-m-d');
        $this->ciclo_lectivo = (int) now()->format('Y');
        $this->institucion = auth()->user()?->institucionActiva;
    }

    protected function rules()
    {
        $institucionId = app(InstitucionContext::class)->id();

        return [
            'duracion' => 'required|in:temporal,permanente',
            'tipo_cambio' => 'required|in:cambio,permuta',
            'docente_id' => [
                'required',
                Rule::exists('docentes', 'id')->where('institucion_id', $institucionId),
            ],
            'curso_id' => [
                'required',
                Rule::exists('cursos', 'id')->where('institucion_id', $institucionId),
                function ($attribute, $value, $fail) {
                    $asignado = CursoMateria::query()
                        ->where('curso_id', $value)
                        ->whereHas('cmDocentes', function ($q) {
                            $q->vigente()->where('docente_id', $this->docente_id);
                        })
                        ->exists();

                    if (!$asignado) {
                        $fail('El curso no corresponde al docente seleccionado.');
                    }
                },
            ],
            'materia_id' => [
                'required',
                Rule::exists('materias', 'id'),
                function ($attribute, $value, $fail) {
                    $asignado = CursoMateria::query()
                        ->where('curso_id', $this->curso_id)
                        ->where('materia_id', $value)
                        ->whereHas('cmDocentes', function ($q) {
                            $q->vigente()->where('docente_id', $this->docente_id);
                        })
                        ->exists();

                    if (!$asignado) {
                        $fail('La materia no corresponde al curso/docente seleccionado.');
                    }
                },
            ],
            'ciclo_lectivo' => 'required|integer|digits:4|min:2000|max:2100',
            'fecha_desde' => 'required|date',
            'fecha_hasta' => [
                Rule::requiredIf($this->duracion === 'temporal'),
                'nullable',
                'date',
                'after_or_equal:fecha_desde'
            ],
        ];
    }

    public function nuevo()
    {
        Gate::authorize('crear-cambios-horario');

        $this->resetExcept('modo');
        $this->institucion = auth()->user()?->institucionActiva;
        $this->modo = 'formulario';
        $this->duracion = 'temporal';
        $this->tipo_cambio = 'cambio';
        $this->estado = 'borrador';
        $this->cursosFiltrados = [];
        $this->materiasFiltradas = [];
        $this->fecha_desde = today()->format('Y-m-d');
        $this->ciclo_lectivo = (int) now()->format('Y');
        $this->dispatch('trix-set-locked', locked: false);
    }

    public function guardar()
    {
        Gate::authorize('crear-cambios-horario');

        $this->validate();

        if ($this->acta && !$this->acta_finalizada) {
            $this->addError('acta_finalizada', 'Finalizá el acta antes de guardar ese texto en el borrador.');
            return;
        }

        $actaGuardada = null;

        if ($this->acta_finalizada) {
            $actaGuardada = $this->cambio && $this->acta === $this->cambio->acta
                ? $this->acta
                : $this->buildActaHtml();
        } elseif ($this->cambio) {
            $actaGuardada = $this->cambio->acta;
        }

        $data = [
            'duracion' => $this->duracion,
            'tipo_cambio' => $this->tipo_cambio,
            'docente_id' => $this->docente_id,
            'curso_id' => $this->curso_id,
            'materia_id' => $this->materia_id,
            'ciclo_lectivo' => $this->ciclo_lectivo,
            'fecha_desde' => $this->fecha_desde,
            'fecha_hasta' => $this->duracion === 'temporal'
                ? $this->fecha_hasta
                : null,
            'acta' => $actaGuardada,
            'estado' => 'borrador',
            'pedido_por' => auth()->id(),
            'pedido_en' => now()->toDateString(),
        ];

        if ($this->cambio) {
            $this->cambio->update($data);
            session()->flash('success', 'Borrador actualizado correctamente.');
        } else {
            $this->cambio = CambioHorarioModel::create($data);
            session()->flash('success', 'Borrador guardado. Ahora cargá los detalles antes de generar el acta.');
        }

        Cache::forget('dashboard.cambios_horarios');
        Cache::forget('dashboard.cambios_horarios.' . app(InstitucionContext::class)->id());
        $this->modo = 'formulario';
        $this->cambio->refresh();
    }

    public function invalidarActaPorDetalle(): void
    {
        if ($this->cambio) {
            $this->cambio->update(['acta' => null]);
            Cache::forget('dashboard.cambios_horarios');
            Cache::forget('dashboard.cambios_horarios.' . app(InstitucionContext::class)->id());
        }

        $this->acta = '';
        $this->acta_finalizada = false;
        $this->cambio?->refresh();
        $this->dispatch('trix-cargar-html', html: '');
        $this->dispatch('trix-set-locked', locked: false);
        session()->flash('success', 'El detalle cambió. Volvé a generar y finalizar el acta.');
    }

    public function editar($id): void
    {
        Gate::authorize('crear-cambios-horario');

        $cambio = CambioHorarioModel::with('detalles')->findOrFail($id);

        if ($cambio->estado !== 'borrador') {
            session()->flash('error', 'Solo se pueden editar cambios en borrador.');
            return;
        }

        $this->cambio = $cambio;
        $this->modo = 'formulario';
        $this->duracion = $cambio->duracion;
        $this->tipo_cambio = $cambio->tipo_cambio;
        $this->docente_id = $cambio->docente_id;
        $this->curso_id = $cambio->curso_id;
        $this->materia_id = $cambio->materia_id;
        $this->ciclo_lectivo = $cambio->ciclo_lectivo;
        $this->fecha_desde = $cambio->fecha_desde?->format('Y-m-d');
        $this->fecha_hasta = $cambio->fecha_hasta?->format('Y-m-d');
        $this->acta = $cambio->acta ?: '';
        $this->acta_finalizada = (bool) $cambio->acta;

        $this->cargarCursosDelDocente($this->docente_id);
        $this->cargarMateriasDelCurso($this->curso_id);

        $this->dispatch('trix-cargar-html', html: $this->acta);
        $this->dispatch('trix-set-locked', locked: $this->acta_finalizada);
    }

    public function updatedDocenteId($value): void
    {
        $this->curso_id = null;
        $this->materia_id = null;
        $this->materiasFiltradas = [];

        if (!$value) {
            $this->cursosFiltrados = [];
            return;
        }

        $this->cargarCursosDelDocente($value);
    }

    private function cargarCursosDelDocente($docenteId): void
    {
        if (!$docenteId) {
            $this->cursosFiltrados = [];
            return;
        }

        $this->cursosFiltrados = Curso::query()
            ->whereHas('cursoMaterias.cmDocentes', function ($q) use ($docenteId) {
                $q->vigente()->where('docente_id', $docenteId);
            })
            ->orderBy('anio')
            ->orderBy('division')
            ->get(['id', 'anio', 'division', 'turno'])
            ->map(fn(Curso $curso) => [
                'id' => $curso->id,
                'anio' => $curso->anio,
                'division' => $curso->division,
                'turno_designacion' => $curso->turno_designacion,
            ])
            ->all();
    }

    public function updatedCursoId($value): void
    {
        $this->materia_id = null;

        if (!$value || !$this->docente_id) {
            $this->materiasFiltradas = [];
            return;
        }

        $this->cargarMateriasDelCurso($value);
    }

    private function cargarMateriasDelCurso($cursoId): void
    {
        if (!$cursoId || !$this->docente_id) {
            $this->materiasFiltradas = [];
            return;
        }

        $this->materiasFiltradas = Materia::query()
            ->whereHas('cursoMaterias', function ($q) use ($cursoId) {
                $q->where('curso_id', $cursoId)
                    ->whereHas('cmDocentes', function ($q2) {
                        $q2->vigente()->where('docente_id', $this->docente_id);
                    });
            })
            ->orderBy('nombre')
            ->get(['id', 'nombre'])
            ->map(fn(Materia $materia) => [
                'id' => $materia->id,
                'nombre' => $materia->nombre,
            ])
            ->all();
    }

    public function updatedDuracion($value): void
    {
        if ($value === 'permanente') {
            $this->fecha_hasta = null;
        }
    }

    public function updated($property): void
    {
        $camposQueInvalidanActa = [
            'duracion',
            'tipo_cambio',
            'docente_id',
            'curso_id',
            'materia_id',
            'ciclo_lectivo',
            'fecha_desde',
            'fecha_hasta',
        ];

        if (in_array($property, $camposQueInvalidanActa, true) && $this->acta_finalizada) {
            $this->acta_finalizada = false;
            $this->dispatch('trix-set-locked', locked: false);
        }
    }

    public function getTextoBaseProperty()
    {
        $docente = Docente::find($this->docente_id);
        $materia = collect($this->materiasFiltradas)->firstWhere('id', $this->materia_id);
        $curso   = collect($this->cursosFiltrados)->firstWhere('id', $this->curso_id);
        $materiaNombre = $materia['nombre'] ?? '---';
        $cursoAnio = $curso['anio'] ?? '---';
        $cursoDivision = $curso['division'] ?? '---';

        $directorTexto = match ($this->institucion?->genero_director) {
            'masculino' => 'el Sr. director',
            'femenino' => 'la Sra. directora',
            default => 'la Dirección'
        };

        $texto = "";
        $texto .= "<p>En la sede del {$this->institucion?->nombre_institucion}, ";
        $texto .= "sito en {$this->institucion?->direccion}, se reúnen {$directorTexto} ";
        $texto .= "{$this->institucion?->nombre_director} y el/la docente {$docente?->nombre_completo} ";
        $texto .= "para acordar un {$this->tipo_cambio} de horario para la materia {$materiaNombre} ";
        $texto .= "del curso {$cursoAnio}° {$cursoDivision}.</p>";

        if ($this->duracion === 'temporal') {
            $desde = $this->fecha_desde ? Carbon::parse($this->fecha_desde)->format('d/m/Y') : '---';
            $hasta = $this->fecha_hasta ? Carbon::parse($this->fecha_hasta)->format('d/m/Y') : '---';

            $texto .= "<p>La vigencia es de orden temporal y durante el ciclo lectivo {$this->ciclo_lectivo}, ";
            $texto .= "iniciando el {$desde} hasta el {$hasta}.</p>";
        } else {
            $desde = $this->fecha_desde ? Carbon::parse($this->fecha_desde)->format('d/m/Y') : '---';
            $texto .= "<p>La vigencia será permanente, iniciando a partir de {$desde} ";
            $texto .= "en el presente ciclo lectivo {$this->ciclo_lectivo}</p>";
        }

        $texto .= $this->detalleActaHtml();

        $texto .= "<p>Sin más, se deja constancia bajo firma de los presentes.</p>";

        return $texto;
    }

    private function detalleActaHtml(): string
    {
        if (!$this->cambio) {
            return '<p>El detalle del cambio se incorporará una vez cargado el borrador.</p>';
        }

        $this->cambio->load([
            'detalles.horarioBase.bloque',
            'detalles.horarioBase.curso',
            'detalles.horarioBase.cursoMateria.materia',
            'detalles.horarioBase.docenteVigente',
            'detalles.docenteNuevo',
            'detalles.bloqueNuevo',
            'detalles.cursoNuevo',
        ]);

        if ($this->cambio->detalles->isEmpty()) {
            return '<p>No se registraron detalles específicos del cambio de horario.</p>';
        }

        $cantidadHoras = $this->cambio->detalles->count();
        $unidad = $cantidadHoras === 1 ? 'hora cátedra' : 'horas cátedra';
        $titulo = $this->tipo_cambio === 'permuta'
            ? "La permuta comprende {$cantidadHoras} {$unidad}, detalladas a continuación:"
            : "El cambio comprende {$cantidadHoras} {$unidad}, detalladas a continuación:";

        $items = $this->cambio->detalles
            ->map(fn ($detalle) => '<li>' . $this->detalleLineaActa($detalle) . '</li>')
            ->implode('');

        return "<p>{$titulo}</p><ol>{$items}</ol>";
    }

    private function detalleLineaActa($detalle): string
    {
        $base = $detalle->horarioBase;
        $docenteOriginal = $base?->docenteVigente?->nombre_completo
            ?? $base?->docenteVigente?->nombre
            ?? Docente::find($this->docente_id)?->nombre_completo
            ?? 'docente sin datos';
        $docenteNuevo = $detalle->docenteNuevo?->nombre_completo
            ?? $detalle->docenteNuevo?->nombre
            ?? $docenteOriginal;
        $materiaSeleccionada = collect($this->materiasFiltradas)->firstWhere('id', $this->materia_id);
        $materia = $base?->cursoMateria?->materia?->nombre
            ?? ($materiaSeleccionada['nombre'] ?? null)
            ?? 'materia sin datos';
        $cursoOriginal = $base?->curso?->nombre_completo ?? 'curso sin datos';
        $cursoNuevo = $detalle->cursoNuevo?->nombre_completo ?? $cursoOriginal;
        $diaOriginal = $this->diaSemanaTexto($base?->dia_semana);
        $diaNuevo = $this->diaSemanaTexto($detalle->dia_nuevo ?: $base?->dia_semana);
        $bloqueOriginal = $this->bloqueTextoActa($base?->bloque);
        $bloqueNuevo = $this->bloqueTextoActa($detalle->bloqueNuevo ?: $base?->bloque);

        if ($this->tipo_cambio === 'permuta') {
            return e("Los docentes {$docenteOriginal} y {$docenteNuevo} permutan la hora de {$materia} de {$cursoOriginal}, originalmente los {$diaOriginal} en {$bloqueOriginal}, quedando registrada para los {$diaNuevo} en {$bloqueNuevo}, en {$cursoNuevo}.");
        }

        return e("El/la docente {$docenteOriginal} modifica la hora de {$materia} de {$cursoOriginal}, originalmente los {$diaOriginal} en {$bloqueOriginal}, que pasará a dictarse los {$diaNuevo} en {$bloqueNuevo}, en {$cursoNuevo}.");
    }

    private function bloqueTextoActa($bloque): string
    {
        if (!$bloque) {
            return 'bloque sin datos';
        }

        $inicio = $bloque->hora_inicio?->format('H:i');
        $fin = $bloque->hora_fin?->format('H:i');

        return trim("{$bloque->nombre} ({$inicio} - {$fin})");
    }

    public function generarActa(): void
    {
        $rules = $this->rules();
        unset($rules['acta']);
        $this->validate($rules);

        if (!$this->cambio) {
            $this->addError('acta', 'Guardá el borrador y cargá los detalles antes de generar el acta.');
            return;
        }

        if (!$this->cambio->detalles()->exists()) {
            $this->addError('acta', 'Cargá al menos un detalle del cambio antes de generar el acta.');
            return;
        }

        $this->acta = $this->textoBase;
        $this->acta_finalizada = false;
        $this->dispatch('trix-cargar-html', html: $this->acta);
        $this->dispatch('trix-set-locked', locked: false);
    }

    public function finalizarActa(): void
    {
        $this->validate([
            'acta' => 'required|string|min:10',
        ]);

        $this->acta_finalizada = true;
        $this->dispatch('trix-set-locked', locked: true);
        session()->flash('success', 'Acta finalizada. Ya podés guardar el borrador.');
    }

    public function buildActaHtml(): string
    {
        return View::make('livewire.partials.cambio-horario-acta', [
            'tipoCambio' => $this->tipo_cambio,
            'fechaActual' => $this->fechaActual,
            'cuerpoHtml' => $this->acta,
        ])->render();
    }

    public function verDetalle($id)
    {
        $this->cambio = null;

        $cambio = CambioHorarioModel::with([
            'solicitante',
            'docente',
            'curso',
            'materia',
            'detalles.horarioBase.bloque',
            'detalles.horarioBase.cursoMateria.materia',
            'detalles.horarioBase.docenteVigente',
            'detalles.docenteNuevo',
            'detalles.bloqueNuevo',
            'detalles.cursoNuevo',
        ])->find($id);

        if (!$cambio) {
            session()->flash('error', 'No se encontró el cambio solicitado.');
            return;
        }

        $this->cambio = $cambio;
        $this->dispatch('abrir-modal-detalle-cambio-horario');
    }

    public function diaSemanaTexto(?int $dia): string
    {
        return match ($dia) {
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
            default => '—',
        };
    }

    // HELPERS VARIOS
    public function getFechaActualProperty()
    {
        Carbon::setLocale('es');
        return now()->translatedFormat('d \\d\\e F \\d\\e Y');
    }

    // ESTADOS DE LA MÁQUINA DE ESTADOS (WORK IN PROGRESS)
    public function autorizar($id)
    {
        Gate::authorize('gestionar-cambios-horario');

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
        Gate::authorize('firmar-cambios-horario');

        $this->validate([
            "actasFirmadas.$id" => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            "actasFirmadas.$id.required" => 'Subí el acta firmada antes de firmar el trámite.',
            "actasFirmadas.$id.mimes" => 'El acta firmada debe ser PDF, JPG o PNG.',
            "actasFirmadas.$id.max" => 'El acta firmada no debe superar los 5 MB.',
        ]);

        try {
            $cambio = CambioHorarioModel::findOrFail($id);
            $path = $this->actasFirmadas[$id]->store('actas-cambios-horario', 'public');

            $cambio->firmar(auth()->user(), $path);
            unset($this->actasFirmadas[$id]);

            session()->flash('success', 'Cambio firmado correctamente.');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function activar($id)
    {
        Gate::authorize('gestionar-cambios-horario');

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
        Gate::authorize('gestionar-cambios-horario');

        try {
            $cambio = CambioHorarioModel::findOrFail($id);
            $cambio->finalizar(auth()->user());

            session()->flash('success', 'Cambio finalizado correctamente.');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.cambio-horario', [
            'cambios' => CambioHorarioModel::orderByDesc('created_at')->get(),
            'docentes' => Docente::query()
                ->where('activo', true)
                ->orderBy('nombre_completo')
                ->get(),
            'puedeCrearCambios' => Gate::allows('crear-cambios-horario'),
            'puedeGestionarCambios' => Gate::allows('gestionar-cambios-horario'),
            'puedeFirmarCambios' => Gate::allows('firmar-cambios-horario'),
        ]);
    }
}
