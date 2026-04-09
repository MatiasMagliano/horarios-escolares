<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Optimización de índices para dashboard y consultas frecuentes
     * Impacto estimado: 50-80% mejora en tiempo de ejecución
     */
    public function up(): void
    {
        // ========================================
        // FASE 1 - Máximo Impacto (50-60% mejora)
        // ========================================

        // Agrupamiento por estado en cambios de horario
        DB::statement('ALTER TABLE cambios_horario ADD INDEX IF NOT EXISTS idx_estado (estado)');

        // Búsqueda de horarios vigentes
        DB::statement('ALTER TABLE horarios_base ADD INDEX IF NOT EXISTS idx_vigente (es_vigente, vigente_hasta)');

        // Búsqueda de docentes vigentes (crítico para relaciones)
        DB::statement('ALTER TABLE cm_docente ADD INDEX IF NOT EXISTS idx_vigente (es_vigente, vigente_hasta)');

        // ========================================
        // FASE 2 - Soporte (20-30% mejora adicional)
        // ========================================

        // Cambios ordenados por estado y fecha
        DB::statement('ALTER TABLE cambios_horario ADD INDEX IF NOT EXISTS idx_estado_created (estado, created_at DESC)');

        // Búsqueda y orden de cursos
        DB::statement('ALTER TABLE cursos ADD INDEX IF NOT EXISTS idx_anio_division (anio, division)');

        // ========================================
        // FASE 3 - Optimización Fina (10-15% mejora)
        // ========================================

        // Agrupamiento por día/bloque para búsquedas de superposiciones
        DB::statement('ALTER TABLE horarios_base ADD INDEX IF NOT EXISTS idx_dia_bloque (dia_semana, bloque_id)');

        // Búsqueda de docentes vigentes por materia
        DB::statement('ALTER TABLE cm_docente ADD INDEX IF NOT EXISTS idx_curso_materia_vigente (curso_materia_id, es_vigente)');

        // Filtrado de docentes activos
        DB::statement('ALTER TABLE docentes ADD INDEX IF NOT EXISTS idx_activo (activo)');

        // Búsqueda de materias por curso
        DB::statement('ALTER TABLE curso_materia ADD INDEX IF NOT EXISTS idx_materia_id (materia_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Limpiar índices si existen (idempotente)
        Schema::table('cambios_horario', function (Blueprint $table) {
            // Usar dropIndexIfExists pero como no está disponible en todas versiones,
            // usamos raw queries para ser seguros
            DB::statement('ALTER TABLE cambios_horario DROP INDEX IF EXISTS cambios_horario_estado_index');
            DB::statement('ALTER TABLE cambios_horario DROP INDEX IF EXISTS cambios_horario_estado_created_at_index');
        });

        Schema::table('horarios_base', function (Blueprint $table) {
            DB::statement('ALTER TABLE horarios_base DROP INDEX IF EXISTS horarios_base_es_vigente_vigente_hasta_index');
            DB::statement('ALTER TABLE horarios_base DROP INDEX IF EXISTS horarios_base_dia_semana_bloque_id_index');
        });

        Schema::table('cm_docente', function (Blueprint $table) {
            DB::statement('ALTER TABLE cm_docente DROP INDEX IF EXISTS cm_docente_es_vigente_vigente_hasta_index');
            DB::statement('ALTER TABLE cm_docente DROP INDEX IF EXISTS cm_docente_curso_materia_id_es_vigente_index');
        });

        Schema::table('cursos', function (Blueprint $table) {
            DB::statement('ALTER TABLE cursos DROP INDEX IF EXISTS cursos_anio_division_index');
        });

        Schema::table('docentes', function (Blueprint $table) {
            DB::statement('ALTER TABLE docentes DROP INDEX IF EXISTS docentes_activo_index');
        });

        Schema::table('curso_materia', function (Blueprint $table) {
            DB::statement('ALTER TABLE curso_materia DROP INDEX IF EXISTS curso_materia_materia_id_index');
        });
    }
};

