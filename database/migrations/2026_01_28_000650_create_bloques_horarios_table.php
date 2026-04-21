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
        Schema::create('bloques_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institucion_id')->constrained('datos_institucionales')->cascadeOnDelete();
            $table->string('nombre');
            $table->enum('turno', ['maniana', 'tarde', 'contraturno_maniana', 'contraturno_tarde']);
            $table->unsignedTinyInteger('orden');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->unsignedSmallInteger('duracion_minutos');
            $table->string('tipo', 30)->default('clase');
            $table->boolean('es_editable')->default(true);
            $table->timestamps();
            $table->unique(['institucion_id', 'turno', 'orden'], 'bloques_horarios_institucion_turno_orden_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloques_horarios');
    }
};
