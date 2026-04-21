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
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institucion_id')->constrained('datos_institucionales')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('nombre_completo');
            $table->string('dni');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->date('nacimiento');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['institucion_id', 'dni'], 'docentes_institucion_dni_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
