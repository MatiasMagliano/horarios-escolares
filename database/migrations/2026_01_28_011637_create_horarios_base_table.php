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
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('curso_materia_id')->nullable()->constrained('curso_materia')->cascadeOnDelete();
            $table->foreignId('docente_id')->constrained('docentes')->cascadeOnDelete();
            $table->foreignId('bloque_id')->constrained('bloques_horarios');
            $table->unsignedTinyInteger('dia_semana'); // 1=Lunes ... 7=Domingo
            $table->timestamps();
            $table->unique(['curso_id', 'bloque_id', 'dia_semana'], 'curso_bloque_dia_unique');
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
