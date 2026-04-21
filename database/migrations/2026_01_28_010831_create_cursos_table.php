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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institucion_id')->constrained('datos_institucionales')->cascadeOnDelete();
            $table->unsignedTinyInteger('anio');
            $table->string('division', 5);
            $table->enum('ciclo', ['CB', 'CE']);
            $table->enum('turno', ['maniana', 'tarde']);
            $table->timestamps();

            $table->unique(['institucion_id', 'anio', 'division', 'turno'], 'cursos_institucion_anio_division_turno_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
