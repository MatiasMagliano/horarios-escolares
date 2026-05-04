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
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CambioHorario extends Component
{
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
            'acta' => 'required|string|min:10',
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

        if (!$this->acta_finalizada) {
            $this->addError('acta_finalizada', 'Se debe finalizar el acta antes de guardar el borrador.');
            return;
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
            'acta' => $this->buildActaHtml(),
            'estado' => 'borrador',
            'pedido_por' => auth()->id(),
            'pedido_en' => now()->toDateString(),
        ];

        if ($this->cambio) {
            $this->cambio->update($data);
        } else {
            $this->cambio = CambioHorarioModel::create($data);
        }

        $this->modo = 'listado';
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

        $this->cursosFiltrados = Curso::query()
            ->whereHas('cursoMaterias.cmDocentes', function ($q) use ($value) {
                $q->vigente()->where('docente_id', $value);
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

        $this->materiasFiltradas = Materia::query()
            ->whereHas('cursoMaterias', function ($q) use ($value) {
                $q->where('curso_id', $value)
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

        $texto .= "<p>Sin más, se deja constancia bajo firma de los presentes.</p>";

        return $texto;
    }

    public function generarActa(): void
    {
        $rules = $this->rules();
        unset($rules['acta']);
        $this->validate($rules);

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
