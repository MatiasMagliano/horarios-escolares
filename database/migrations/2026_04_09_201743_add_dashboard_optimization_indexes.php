<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        Schema::table('cambios_horario', function (Blueprint $table) {
            $table->index(['institucion_id', 'estado'], 'idx_cambios_horario_institucion_estado');
            $table->index(['institucion_id', 'estado', 'created_at'], 'idx_cambios_horario_institucion_estado_created');
        });

        Schema::table('horarios_base', function (Blueprint $table) {
            $table->index(['institucion_id', 'es_vigente', 'vigente_hasta'], 'idx_horarios_base_institucion_vigente');
            $table->index(['institucion_id', 'dia_semana', 'bloque_id'], 'idx_horarios_base_institucion_dia_bloque');
        });

        Schema::table('cm_docente', function (Blueprint $table) {
            $table->index(['institucion_id', 'es_vigente', 'vigente_hasta'], 'idx_cm_docente_institucion_vigente');
            $table->index(['institucion_id', 'curso_materia_id', 'es_vigente'], 'idx_cm_docente_institucion_curso_materia_vigente');
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->index(['institucion_id', 'anio', 'division'], 'idx_cursos_institucion_anio_division');
        });

        Schema::table('docentes', function (Blueprint $table) {
            $table->index(['institucion_id', 'activo'], 'idx_docentes_institucion_activo');
        });

        Schema::table('curso_materia', function (Blueprint $table) {
            $table->index(['institucion_id', 'materia_id'], 'idx_curso_materia_institucion_materia_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cambios_horario', function (Blueprint $table) {
            $table->dropIndex('idx_cambios_horario_institucion_estado');
            $table->dropIndex('idx_cambios_horario_institucion_estado_created');
        });

        Schema::table('horarios_base', function (Blueprint $table) {
            $table->dropIndex('idx_horarios_base_institucion_vigente');
            $table->dropIndex('idx_horarios_base_institucion_dia_bloque');
        });

        Schema::table('cm_docente', function (Blueprint $table) {
            $table->dropIndex('idx_cm_docente_institucion_vigente');
            $table->dropIndex('idx_cm_docente_institucion_curso_materia_vigente');
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->dropIndex('idx_cursos_institucion_anio_division');
        });

        Schema::table('docentes', function (Blueprint $table) {
            $table->dropIndex('idx_docentes_institucion_activo');
        });

        Schema::table('curso_materia', function (Blueprint $table) {
            $table->dropIndex('idx_curso_materia_institucion_materia_id');
        });
    }
};
