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
        Schema::create('bloque_horario_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institucion_id')
                ->constrained('datos_institucionales')
                ->onDelete('cascade');
            $table->string('nombre'); // M1, M2, R1, etc.
            $table->string('turno'); // maniana, tarde, contraturno_maniana, contraturno_tarde
            $table->integer('orden'); // Posición en la secuencia del turno
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('tipo', ['clase', 'recreo'])->default('clase');
            $table->timestamps();

            // Indices para búsquedas rápidas
            $table->index(['institucion_id', 'turno', 'orden']);
            $table->index(['institucion_id', 'turno']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloque_horario_configs');
    }
};
