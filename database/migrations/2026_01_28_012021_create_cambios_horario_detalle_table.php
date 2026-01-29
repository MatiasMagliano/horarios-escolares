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
        Schema::create('cambios_horario_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('c_horario_id')->constrained('cambios_horario')->cascadeOnDelete();
            $table->foreignId('h_base_id')->constrained('horarios_base')->cascadeOnDelete();
            $table->foreignId('n_doc_id')->nullable()->constrained('docentes');
            $table->foreignId('n_blq_id')->nullable()->constrained('bloques_horarios');
            $table->foreignId('n_curso_id')->nullable()->constrained('cursos');
            $table->unsignedTinyInteger('dia_nuevo')->nullable();
            $table->string('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cambios_horario_detalle');
    }
};
