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
        Schema::create('espacios_fisicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institucion_id')->constrained('datos_institucionales')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('tipo', 50)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['institucion_id', 'nombre'], 'espacios_fisicos_institucion_nombre_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espacios_fisicos');
    }
};
