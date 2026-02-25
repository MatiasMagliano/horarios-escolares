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
        Schema::create('cm_docente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_materia_id')->constrained('curso_materia')->cascadeOnDelete();
            $table->foreignId('docente_id')->constrained()->cascadeOnDelete();

            // SCD2 — mismo patrón que horarios_base
            $table->date('vigente_desde');
            $table->date('vigente_hasta')->nullable();
            $table->boolean('es_vigente')->default(true)->index();
            //$table->foreignId('cambio_horario_id')->nullable()->constrained('cambios_horario')->nullOnDelete(); POR AHORA NO, PARA SIMPLIFICAR

            $table->timestamps();

            $table->unique(['curso_materia_id', 'vigente_desde'], 'cmd_version_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cm_docente');
    }
};
