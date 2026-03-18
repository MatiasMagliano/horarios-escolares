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
        $espacios = ['aula', 'lab-informatica', 'lab-electronica', 'lab-taller', 'patio'];

        Schema::create('materias', function (Blueprint $table) use ($espacios) {
            $table->id();
            $table->string('nombre');
            $table->enum('espacio_requerido', $espacios)->default('aula');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
