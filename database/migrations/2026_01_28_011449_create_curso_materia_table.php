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
        Schema::create('curso_materia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->cascadeOnDelete();
            $table->foreignId('materia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('docente_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('horas_totales');

            // Versionado SCD2
            $table->date('vigente_desde');
            $table->date('vigente_hasta')->nullable(); // NULL = registro activo
            $table->boolean('es_vigente')->default(true)->index(); // shortcut para queries

            // Trazabilidad hacia el acta que originó el cambio
            $table->foreignId('cambio_horario_id')->nullable()->constrained('cambios_horario')->nullOnDelete();

            $table->timestamps();

            // Ya no tiene unique simple: puede haber múltiples versiones del mismo par
            $table->unique(['curso_id', 'materia_id', 'vigente_desde'], 'curso_materia_version_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_materia');
    }
};
