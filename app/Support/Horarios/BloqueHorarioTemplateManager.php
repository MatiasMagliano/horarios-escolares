<?php

namespace App\Support\Horarios;

use App\Models\BloqueHorario;
use App\Models\BloqueHorarioConfig;
use App\Models\Institucion;

class BloqueHorarioTemplateManager
{
    /* NO SE TRATA DE TAMAÑO DE BLOQUE, si no de la estructura de la jornada y de la distribución de bloques horarios y recreos. Se calcula a partir de la hora de ingreso, por ejemplo:
     * TEMPLATE_1: inicio de jornada a las 7:10, con bloques de 40 min y 2 recreos de 15 min y 10 min, totalizando 8 bloques de clase y 2 recreos por turno (3 bloques, recreo, 2 bloques, recreo. 3 bloques). También define, si tiene contraturno, el mismo esquema pero empezando a las 13:30.
     * TEMPLATE_2: inicio de jornada a las 7:45, con bloques de 40 min y 3 recreos de 10 min, 10 min y 5 min, totalizando 8 bloques de clase y 3 recreos por turno (2 bloques, recreo, 2 bloques, recreo, 2 bloques, recreo, 2 bloques). También define, si tiene contraturno, el mismo esquema pero empezando a las 13:30.
     * PERSONALIZADO: define el horario de ingreso, cantidad de bloques y cantidad de recreos; con eso genera una plantilla preliminar que el usuario puede ajustar después.
     */
    public const TEMPLATE_ESTANDAR_40 = 'estandar_40';
    public const TEMPLATE_8_BLOQUES_3_RECREOS = '8_bloques_3_recreos';
    public const TEMPLATE_PERSONALIZADO = 'personalizado';

    /**
     * @return array<string, string>
     */
    public function options(): array
    {
        return [
            self::TEMPLATE_ESTANDAR_40 => '7:10 - 8 bloques, 2 recreos',
            self::TEMPLATE_8_BLOQUES_3_RECREOS => '7:45 - 8 bloques, 3 recreos',
            self::TEMPLATE_PERSONALIZADO => 'Personalizado: calcular plantilla',
        ];
    }

    /**
     * @return array<int, array<string, int|string>>
     */
    public function defaults(?string $template = null): array
    {
        return $this->template($template ?? self::TEMPLATE_ESTANDAR_40);
    }

    /**
     * @return array<int, array<string, int|string>>
     */
    public function template(string $template): array
    {
        return match ($template) {
            self::TEMPLATE_8_BLOQUES_3_RECREOS => $this->ochoBloquesTresRecreos(),
            self::TEMPLATE_PERSONALIZADO => [],
            default => $this->estandar40(),
        };
    }

    /**
     * @return array<int, array<string, int|string>>
     */
    public function personalizado(string $morningStart, int $classCount, int $breakCount, string $afternoonStart = '13:30'): array
    {
        if ($classCount < 1) {
            return [];
        }

        return $this->buildTemplate(
            morningStart: $morningStart,
            afternoonStart: $afternoonStart,
            structure: $this->buildCustomStructure($classCount, $breakCount),
        );
    }

    /**
     * @return array<int, array<string, int|string>>
     */
    private function estandar40(): array
    {
        return $this->buildTemplate(
            morningStart: '07:10',
            afternoonStart: '13:30',
            structure: [
                ['tipo' => 'clase', 'cantidad' => 3, 'duracion' => 40],
                ['tipo' => 'recreo', 'duracion' => 15],
                ['tipo' => 'clase', 'cantidad' => 2, 'duracion' => 40],
                ['tipo' => 'recreo', 'duracion' => 10],
                ['tipo' => 'clase', 'cantidad' => 3, 'duracion' => 40],
            ],
        );
    }

    /**
     * @return array<int, array<string, int|string>>
     */
    private function ochoBloquesTresRecreos(): array
    {
        return $this->buildTemplate(
            morningStart: '07:45',
            afternoonStart: '13:30',
            structure: [
                ['tipo' => 'clase', 'cantidad' => 2, 'duracion' => 40],
                ['tipo' => 'recreo', 'duracion' => 10],
                ['tipo' => 'clase', 'cantidad' => 2, 'duracion' => 40],
                ['tipo' => 'recreo', 'duracion' => 10],
                ['tipo' => 'clase', 'cantidad' => 2, 'duracion' => 40],
                ['tipo' => 'recreo', 'duracion' => 5],
                ['tipo' => 'clase', 'cantidad' => 2, 'duracion' => 40],
            ],
        );
    }

    /**
     * @param array<int, array{tipo: string, duracion: int, cantidad?: int}> $structure
     * @return array<int, array<string, int|string>>
     */
    private function buildTemplate(string $morningStart, string $afternoonStart, array $structure): array
    {
        return [
            ...$this->buildTurno('maniana', $morningStart, $structure),
            ...$this->buildTurno('contraturno_maniana', $afternoonStart, $structure),
            ...$this->buildTurno('tarde', $afternoonStart, $structure),
            ...$this->buildTurno('contraturno_tarde', $morningStart, $structure),
        ];
    }

    /**
     * @param array<int, array{tipo: string, duracion: int, cantidad?: int}> $structure
     * @return array<int, array<string, int|string>>
     */
    private function buildTurno(string $turno, string $start, array $structure): array
    {
        $bloques = [];
        $current = \DateTimeImmutable::createFromFormat('H:i', $start);

        if (!$current) {
            return [];
        }

        $orden = 1;
        $clase = 1;
        $recreo = 1;

        foreach ($structure as $segment) {
            $cantidad = $segment['tipo'] === 'clase' ? $segment['cantidad'] ?? 1 : 1;

            for ($i = 0; $i < $cantidad; $i++) {
                $fin = $current->modify('+' . $segment['duracion'] . ' minutes');
                $esRecreo = $segment['tipo'] === 'recreo';

                $bloques[] = [
                    'nombre' => ($esRecreo ? 'R' . $recreo++ : 'M' . $clase++),
                    'turno' => $turno,
                    'orden' => $orden++,
                    'hora_inicio' => $current->format('H:i'),
                    'hora_fin' => $fin->format('H:i'),
                    'duracion' => $segment['duracion'],
                    'tipo' => $esRecreo ? 'recreo' : 'clase',
                ];

                $current = $fin;
            }
        }

        return $bloques;
    }

    /**
     * @return array<int, array{tipo: string, duracion: int, cantidad?: int}>
     */
    private function buildCustomStructure(int $classCount, int $breakCount): array
    {
        $breakCount = max(0, min($breakCount, 5, $classCount - 1));
        $classGroups = $this->distributeClasses($classCount, $breakCount + 1);
        $breakDurations = $this->distributeBreakMinutes($breakCount);
        $structure = [];

        foreach ($classGroups as $index => $quantity) {
            if ($quantity > 0) {
                $structure[] = ['tipo' => 'clase', 'cantidad' => $quantity, 'duracion' => 40];
            }

            if (isset($breakDurations[$index])) {
                $structure[] = ['tipo' => 'recreo', 'duracion' => $breakDurations[$index]];
            }
        }

        return $structure;
    }

    /**
     * @return array<int, int>
     */
    private function distributeClasses(int $classCount, int $groupCount): array
    {
        $groups = array_fill(0, $groupCount, intdiv($classCount, $groupCount));
        $remaining = $classCount % $groupCount;
        $positions = [];

        for ($i = 0; $i < $groupCount; $i++) {
            $positions[] = $i;
            $opposite = $groupCount - 1 - $i;

            if ($opposite !== $i) {
                $positions[] = $opposite;
            }
        }

        foreach (array_values(array_unique($positions)) as $position) {
            if ($remaining === 0) {
                break;
            }

            $groups[$position]++;
            $remaining--;
        }

        return $groups;
    }

    /**
     * @return array<int, int>
     */
    private function distributeBreakMinutes(int $breakCount): array
    {
        if ($breakCount < 1) {
            return [];
        }

        $durations = array_fill(0, $breakCount, 5);
        $remaining = 25 - (5 * $breakCount);

        for ($i = 0; $remaining > 0; $i = ($i + 1) % $breakCount) {
            $durations[$i] += 5;
            $remaining -= 5;
        }

        return $durations;
    }

    public function ensureForInstitucion(
        Institucion $institucion,
        string $template = self::TEMPLATE_ESTANDAR_40,
        bool $replaceExisting = false
    ): void
    {
        // Primero asegurar que existan las configuraciones
        $this->saveConfigurationForInstitucion($institucion, $template, $replaceExisting);

        // Luego crear los BloqueHorario basados en las configuraciones
        $this->createBloqueHorarioFromConfigs($institucion);
    }

    /**
     * Guarda las configuraciones de bloques en la tabla bloque_horario_configs
     * Si ya existen, no las reemplaza
     */
    public function saveConfigurationForInstitucion(
        Institucion $institucion,
        string $template = self::TEMPLATE_ESTANDAR_40,
        bool $replaceExisting = false
    ): void
    {
        $this->saveBlocksForInstitucion($institucion, $this->template($template), $replaceExisting);
    }

    /**
     * @param array<int, array<string, int|string>> $blocks
     */
    public function saveBlocksForInstitucion(Institucion $institucion, array $blocks, bool $replaceExisting = false): void
    {
        $turnosDisponibles = $institucion->turnosConfigurados();

        foreach ($blocks as $bloque) {
            if (!in_array($bloque['turno'], $turnosDisponibles, true)) {
                continue;
            }

            $tipo = $bloque['tipo'] ?? (str_starts_with((string) $bloque['nombre'], 'R') ? 'recreo' : 'clase');
            $attributes = [
                'institucion_id' => $institucion->id,
                'turno' => $bloque['turno'],
                'orden' => $bloque['orden'],
            ];
            $values = [
                'nombre' => $bloque['nombre'],
                'hora_inicio' => $bloque['hora_inicio'],
                'hora_fin' => $bloque['hora_fin'],
                'tipo' => $tipo,
            ];

            $replaceExisting
                ? BloqueHorarioConfig::updateOrCreate($attributes, $values)
                : BloqueHorarioConfig::firstOrCreate($attributes, $values);
        }
    }

    /**
     * Crea los BloqueHorario basados en las configuraciones guardadas
     */
    public function createBloqueHorarioFromConfigs(Institucion $institucion): void
    {
        $configs = $institucion->getTodosBloques();

        foreach ($configs as $config) {
            BloqueHorario::withoutGlobalScopes()->updateOrCreate(
                [
                    'institucion_id' => $institucion->id,
                    'turno' => $config->turno,
                    'orden' => $config->orden,
                ],
                [
                    'nombre' => $config->nombre,
                    'hora_inicio' => $config->hora_inicio,
                    'hora_fin' => $config->hora_fin,
                    'duracion_minutos' => $config->calcularDuracion(),
                    'tipo' => $config->tipo,
                    'es_editable' => !$config->esRecreo(),
                ]
            );
        }
    }

    /**
     * Actualiza una configuración específica y regenera los BloqueHorario asociados
     */
    public function updateBloqueConfig(BloqueHorarioConfig $config, array $data): void
    {
        $config->update($data);

        $this->syncBloqueHorarioFromConfig($config->refresh());
    }

    public function syncBloqueHorarioFromConfig(BloqueHorarioConfig $config): void
    {
        BloqueHorario::withoutGlobalScopes()->updateOrCreate(
            [
                'institucion_id' => $config->institucion_id,
                'turno' => $config->turno,
                'orden' => $config->orden,
            ],
            [
                'nombre' => $config->nombre,
                'hora_inicio' => $config->hora_inicio,
                'hora_fin' => $config->hora_fin,
                'duracion_minutos' => $config->calcularDuracion(),
                'tipo' => $config->tipo,
                'es_editable' => !$config->esRecreo(),
            ]
        );
    }

    public function deleteBloqueHorarioForConfig(BloqueHorarioConfig $config): void
    {
        BloqueHorario::withoutGlobalScopes()
            ->where('institucion_id', $config->institucion_id)
            ->where('turno', $config->turno)
            ->where('orden', $config->orden)
            ->delete();
    }

    /**
     * Obtiene las configuraciones de un turno específico, ordenadas
     */
    public function getConfigsPorTurno(Institucion $institucion, string $turno)
    {
        return $institucion->getBloquesPorTurno($turno);
    }
}
