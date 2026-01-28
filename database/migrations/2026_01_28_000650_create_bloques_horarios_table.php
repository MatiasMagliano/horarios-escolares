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
            $table->string('nombre');
            $table->enum('turno', ['maniana', 'tarde', 'contraturno']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->unsignedSmallInteger('duracion_minutos');
            $table->boolean('es_especial')->default(false); // EF / Taller
            $table->timestamps();
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
