<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CambioHorario as CambioHorarioModel;
use Carbon\Carbon;
use App\Models\Docente;
use App\Models\Curso;
use App\Models\Materia;
use App\Models\CursoMateria;
use App\Models\BloqueHorario;
use App\Models\CambioHorarioDetalle;
use App\Models\HorarioBase;
use App\Support\Horarios\TurnoHelper;
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
    public $acta_original = '';
    public $fecha_actual;
    public $fecha_desde;
    public $fecha_hasta;
    public $acta_finalizada = false;
    public array $actasFirmadas = [];
    public array $detallesCambio = [];
    public $horario_base_id;
    public $nuevo_bloque_id;
    public $dia_nuevo;
    public $observaciones_detalle;

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
        $this->detallesCambio = [];
        $this->resetDetalleCambioForm();
        $this->acta_original = '';
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

        $actaGuardada = $this->acta_finalizada
            ? $this->acta
            : $this->cambio?->cuerpo_acta;

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

        $eraEdicion = (bool) $this->cambio;

        if ($eraEdicion) {
            $this->cambio->update($data);
            session()->flash('success', 'Borrador actualizado correctamente.');
        } else {
            $this->cambio = CambioHorarioModel::create($data);
            session()->flash('success', 'Solicitud guardada correctamente.');
        }

        Cache::forget('dashboard.cambios_horarios');
        Cache::forget('dashboard.cambios_horarios.' . app(InstitucionContext::class)->id());
        $this->cambio->refresh();
        $this->persistirDetallesCambioPendientes();
        $this->modo = 'listado';
        $this->cambio = null;
    }

    public function invalidarActaPorDetalle(): void
    {
        $teniaActa = (bool) $this->acta || $this->acta_finalizada || (bool) $this->cambio?->acta;

        if ($this->cambio) {
            $this->cambio->update(['acta' => null]);
            Cache::forget('dashboard.cambios_horarios');
            Cache::forget('dashboard.cambios_horarios.' . app(InstitucionContext::class)->id());
        }

        $this->acta = '';
        $this->acta_original = '';
        $this->acta_finalizada = false;
        $this->cambio?->refresh();
        $this->dispatch('trix-cargar-html', html: '');
        $this->dispatch('trix-set-locked', locked: false);

        if ($teniaActa) {
            session()->flash('success', 'El detalle cambió. Volvé a generar y finalizar el acta.');
        }
    }

    public function agregarDetalleCambio(): void
    {
        if ($this->tipo_cambio !== 'cambio') {
            $this->addError('detalle', 'Por ahora estamos afinando solo el cambio de horario.');
            return;
        }

        $this->validate($this->detalleCambioRules());

        if (collect($this->detallesCambio)->contains('horario_base_id', (int) $this->horario_base_id)) {
            $this->addError('horario_base_id', 'Ese horario ya fue agregado al detalle.');
            return;
        }

        $horario = $this->buscarHorarioBaseParaDetalle((int) $this->horario_base_id);

        if (!$horario) {
            $this->addError('horario_base_id', 'El horario seleccionado no corresponde a la materia, curso y docente.');
            return;
        }

        if ((int) $horario->dia_semana === (int) $this->dia_nuevo && (int) $horario->bloque_id === (int) $this->nuevo_bloque_id) {
            $this->addError('detalle', 'El nuevo día y bloque deben ser distintos del horario original.');
            return;
        }

        $detalle = $this->normalizarDetalleCambio([
            'horario_base_id' => $horario->id,
            'bloque_nuevo_id' => (int) $this->nuevo_bloque_id,
            'dia_nuevo' => (int) $this->dia_nuevo,
            'observaciones' => $this->observaciones_detalle,
        ], $horario);

        if ($this->cambio) {
            $this->cambio->detalles()->create([
                'horario_base_id' => $detalle['horario_base_id'],
                'bloque_nuevo_id' => $detalle['bloque_nuevo_id'],
                'dia_nuevo' => $detalle['dia_nuevo'],
                'observaciones' => $detalle['observaciones'],
                'docente_nuevo_id' => null,
                'curso_nuevo_id' => null,
            ]);

            $this->cargarDetallesCambioDesdeModelo();
        } else {
            $this->detallesCambio[] = $detalle;
        }

        $this->resetDetalleCambioForm();
        $this->invalidarActaPorDetalle();
    }

    public function eliminarDetalleCambio($key): void
    {
        $detalle = $this->detallesCambio[$key] ?? null;

        if (!$detalle) {
            return;
        }

        if (!empty($detalle['id']) && $this->cambio) {
            $this->cambio->detalles()->whereKey($detalle['id'])->delete();
            $this->cargarDetallesCambioDesdeModelo();
        } else {
            unset($this->detallesCambio[$key]);
            $this->detallesCambio = array_values($this->detallesCambio);
        }

        $this->invalidarActaPorDetalle();
    }

    private function detalleCambioRules(): array
    {
        $institucionId = app(InstitucionContext::class)->id();

        return [
            'docente_id' => 'required',
            'curso_id' => 'required',
            'materia_id' => 'required',
            'horario_base_id' => [
                'required',
                Rule::exists('horarios_base', 'id')
                    ->where('institucion_id', $institucionId)
                    ->where('curso_id', $this->curso_id),
            ],
            'dia_nuevo' => 'required|integer|min:1|max:5',
            'nuevo_bloque_id' => [
                'required',
                Rule::exists('bloques_horarios', 'id')->where('institucion_id', $institucionId),
            ],
            'observaciones_detalle' => 'nullable|string|max:255',
        ];
    }

    private function buscarHorarioBaseParaDetalle(int $horarioBaseId): ?HorarioBase
    {
        return $this->horariosBaseCambioQuery()
            ->whereKey($horarioBaseId)
            ->first();
    }

    private function normalizarDetalleCambio(array $detalle, ?HorarioBase $horario = null): array
    {
        $horario ??= HorarioBase::query()
            ->with(['bloque', 'curso', 'cursoMateria.materia', 'docenteVigente'])
            ->find($detalle['horario_base_id']);

        $bloqueNuevo = BloqueHorario::find($detalle['bloque_nuevo_id']);

        return [
            'id' => $detalle['id'] ?? null,
            'horario_base_id' => (int) $detalle['horario_base_id'],
            'materia' => $horario?->cursoMateria?->materia?->nombre ?? 'Materia sin datos',
            'curso' => $horario?->curso?->nombre_completo ?? 'Curso sin datos',
            'docente' => $horario?->docenteVigente?->nombre_completo
                ?? $horario?->docenteVigente?->nombre
                ?? Docente::find($this->docente_id)?->nombre_completo
                ?? 'Docente sin datos',
            'dia_original' => (int) ($horario?->dia_semana ?? 0),
            'dia_original_texto' => $this->diaSemanaTexto((int) ($horario?->dia_semana ?? 0)),
            'bloque_original_id' => $horario?->bloque_id,
            'bloque_original_texto' => $this->bloqueTextoActa($horario?->bloque),
            'dia_nuevo' => (int) $detalle['dia_nuevo'],
            'dia_nuevo_texto' => $this->diaSemanaTexto((int) $detalle['dia_nuevo']),
            'bloque_nuevo_id' => (int) $detalle['bloque_nuevo_id'],
            'bloque_nuevo_texto' => $this->bloqueTextoActa($bloqueNuevo),
            'observaciones' => $detalle['observaciones'] ?? null,
        ];
    }

    private function cargarDetallesCambioDesdeModelo(): void
    {
        if (!$this->cambio) {
            return;
        }

        $this->cambio->load([
            'detalles.horarioBase.bloque',
            'detalles.horarioBase.curso',
            'detalles.horarioBase.cursoMateria.materia',
            'detalles.horarioBase.docenteVigente',
            'detalles.bloqueNuevo',
        ]);

        $this->detallesCambio = $this->cambio->detalles
            ->map(fn (CambioHorarioDetalle $detalle) => $this->normalizarDetalleCambio([
                'id' => $detalle->id,
                'horario_base_id' => $detalle->horario_base_id,
                'bloque_nuevo_id' => $detalle->bloque_nuevo_id,
                'dia_nuevo' => $detalle->dia_nuevo,
                'observaciones' => $detalle->observaciones,
            ], $detalle->horarioBase))
            ->values()
            ->all();
    }

    private function persistirDetallesCambioPendientes(): void
    {
        if (!$this->cambio) {
            return;
        }

        foreach ($this->detallesCambio as $detalle) {
            if (!empty($detalle['id'])) {
                continue;
            }

            $creado = $this->cambio->detalles()->create([
                'horario_base_id' => $detalle['horario_base_id'],
                'bloque_nuevo_id' => $detalle['bloque_nuevo_id'],
                'dia_nuevo' => $detalle['dia_nuevo'],
                'observaciones' => $detalle['observaciones'],
                'docente_nuevo_id' => null,
                'curso_nuevo_id' => null,
            ]);

            $detalle['id'] = $creado->id;
        }

        $this->cargarDetallesCambioDesdeModelo();
    }

    private function resetDetalleCambioForm(): void
    {
        $this->horario_base_id = null;
        $this->nuevo_bloque_id = null;
        $this->dia_nuevo = null;
        $this->observaciones_detalle = null;
    }

    private function limpiarDetallesCambio(): void
    {
        if ($this->detallesCambio === []) {
            return;
        }

        if ($this->cambio) {
            $this->cambio->detalles()->delete();
        }

        $this->detallesCambio = [];
        $this->resetDetalleCambioForm();
        $this->invalidarActaPorDetalle();
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
        $this->acta = $cambio->cuerpo_acta;
        $this->acta_original = $cambio->cuerpo_acta;
        $this->acta_finalizada = (bool) $cambio->acta;

        $this->cargarCursosDelDocente($this->docente_id);
        $this->cargarMateriasDelCurso($this->curso_id);
        $this->cargarDetallesCambioDesdeModelo();

        $this->dispatch('trix-cargar-html', html: $this->acta);
        $this->dispatch('trix-set-locked', locked: false);
    }

    public function updatedActa($value): void
    {
        if ($this->acta_finalizada && $value !== $this->acta_original) {
            $this->acta_finalizada = false;
        }
    }

    public function updatedDocenteId($value): void
    {
        $this->curso_id = null;
        $this->materia_id = null;
        $this->materiasFiltradas = [];
        $this->limpiarDetallesCambio();

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
        $this->limpiarDetallesCambio();

        if (!$value || !$this->docente_id) {
            $this->materiasFiltradas = [];
            return;
        }

        $this->cargarMateriasDelCurso($value);
    }

    public function updatedMateriaId(): void
    {
        $this->limpiarDetallesCambio();
    }

    public function updatedTipoCambio($value): void
    {
        $this->limpiarDetallesCambio();
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

    public function getHorariosBaseCambioProperty()
    {
        if (!$this->docente_id || !$this->curso_id || !$this->materia_id || $this->tipo_cambio !== 'cambio') {
            return collect();
        }

        return $this->horariosBaseCambioQuery()->get();
    }

    private function horariosBaseCambioQuery()
    {
        return HorarioBase::query()
            ->vigente()
            ->conDocenteVigente()
            ->where('curso_id', $this->curso_id)
            ->whereHas('cursoMateria', function ($query) {
                $query->where('materia_id', $this->materia_id)
                    ->whereHas('cmDocentes', function ($docentes) {
                        $docentes->vigente()->where('docente_id', $this->docente_id);
                    });
            })
            ->orderBy('dia_semana')
            ->orderBy('bloque_id');
    }

    public function getBloquesCambioProperty()
    {
        if (!$this->curso_id) {
            return collect();
        }

        $curso = Curso::query()->find($this->curso_id);

        if (!$curso) {
            return collect();
        }

        $turnos = $this->institucion?->turnosVisiblesParaCurso($curso->turno)
            ?: [$curso->turno];

        $ordenFranjas = ['maniana' => 1, 'tarde' => 2];

        return BloqueHorario::query()
            ->where('es_editable', true)
            ->whereIn('turno', $turnos)
            ->orderBy('orden')
            ->get(['id', 'nombre', 'turno', 'orden', 'hora_inicio', 'hora_fin'])
            ->sortBy(fn (BloqueHorario $bloque) => [
                $ordenFranjas[TurnoHelper::franjaDeTurno($bloque->turno)] ?? 99,
                $bloque->orden,
            ])
            ->values();
    }

    public function getBloquesCambioPorFranjaProperty()
    {
        return $this->bloquesCambio
            ->groupBy(fn (BloqueHorario $bloque) => TurnoHelper::franjaDeTurno($bloque->turno));
    }

    public function designacionTurno(string $turno): string
    {
        return TurnoHelper::designacionTurno($turno);
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

        if (in_array($property, $camposQueInvalidanActa, true) && ($this->acta || $this->acta_finalizada || $this->cambio?->acta)) {
            $this->invalidarActaPorDetalle();
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
        if ($this->detallesCambio === []) {
            return '<p>No se registraron detalles específicos del cambio de horario.</p>';
        }

        $cantidadHoras = count($this->detallesCambio);
        $unidad = $cantidadHoras === 1 ? 'hora cátedra' : 'horas cátedra';
        $titulo = $this->tipo_cambio === 'permuta'
            ? "La permuta comprende {$cantidadHoras} {$unidad}, detalladas a continuación:"
            : "El cambio comprende {$cantidadHoras} {$unidad}, detalladas a continuación:";

        $items = collect($this->detallesCambio)
            ->map(fn (array $detalle) => '<li>' . $this->detalleLineaActa($detalle) . '</li>')
            ->implode('');

        return "<p>{$titulo}</p><ol>{$items}</ol>";
    }

    private function detalleLineaActa(array $detalle): string
    {
        $docenteOriginal = $detalle['docente'] ?? 'docente sin datos';
        $materia = $detalle['materia'] ?? 'materia sin datos';
        $cursoOriginal = $detalle['curso'] ?? 'curso sin datos';
        $diaOriginal = $detalle['dia_original_texto'] ?? 'día sin datos';
        $diaNuevo = $detalle['dia_nuevo_texto'] ?? 'día sin datos';
        $bloqueOriginal = $detalle['bloque_original_texto'] ?? 'bloque sin datos';
        $bloqueNuevo = $detalle['bloque_nuevo_texto'] ?? 'bloque sin datos';

        if ($this->tipo_cambio === 'permuta') {
            return e("Los docentes involucrados permutan la hora de {$materia} de {$cursoOriginal}, originalmente los {$diaOriginal} en {$bloqueOriginal}, quedando registrada para los {$diaNuevo} en {$bloqueNuevo}.");
        }

        return e("El/la docente {$docenteOriginal} modifica la hora de {$materia} de {$cursoOriginal}, originalmente los {$diaOriginal} en {$bloqueOriginal}, que pasará a dictarse los {$diaNuevo} en {$bloqueNuevo}.");
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

        if ($this->detallesCambio === []) {
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
        Gate::authorize('aprobar-cambios-horario');

        try {
            $cambio = CambioHorarioModel::findOrFail($id);
            $cambio->autorizar(auth()->user());

            session()->flash('success', 'Cambio autorizado correctamente.');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function anular($id): void
    {
        $cambio = CambioHorarioModel::findOrFail($id);
        Gate::authorize('anular-cambios-horario', $cambio);

        try {
            $cambio->anular(auth()->user());
            session()->flash('success', 'Cambio anulado correctamente.');
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
        Gate::authorize('efectivizar-cambios-horario');

        try {
            $cambio = CambioHorarioModel::findOrFail($id);
            $cambio->activar(auth()->user());

            session()->flash('success', 'Cambio efectivizado correctamente.');
        } catch (\Throwable $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function finalizar($id)
    {
        Gate::authorize('efectivizar-cambios-horario');

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
            'puedeAprobarCambios' => Gate::allows('aprobar-cambios-horario'),
            'puedeEfectivizarCambios' => Gate::allows('efectivizar-cambios-horario'),
            'puedeFirmarCambios' => Gate::allows('firmar-cambios-horario'),
        ]);
    }
}
