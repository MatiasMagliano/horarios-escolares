<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('horarios_base', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institucion_id')->constrained('datos_institucionales')->cascadeOnDelete();

            // anclaje en la Grilla
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('curso_materia_id')->nullable()->constrained('curso_materia')->nullOnDelete();
            $table->foreignId('bloque_id')->constrained('bloques_horarios');
            $table->unsignedTinyInteger('dia_semana'); // 1=Lunes ... 7=Domingo

            // Versionado SCD2
            $table->date('vigente_desde');
            $table->date('vigente_hasta')->nullable();
            $table->boolean('es_vigente')->default(true)->index();

            // Trazabilidad
            $table->foreignId('cambio_horario_id')->nullable()->constrained('cambios_horario')->nullOnDelete();

            $table->timestamps();

            // El unique ahora incluye la versión temporal
            $table->unique(['institucion_id', 'curso_id', 'bloque_id', 'dia_semana', 'vigente_desde'], 'curso_bloque_dia_institucion_version_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios_base');
    }
};
