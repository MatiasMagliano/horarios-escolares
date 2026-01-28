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
        Schema::create('cambios_horario', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['temporal', 'permanente']);
            $table->enum('estado', ['borrador', 'autorizado', 'firmado', 'activo', 'finalizado']);
            $table->text('motivo');
            $table->date('fecha_desde');
            $table->date('fecha_hasta')->nullable();
            $table->foreignId('autorizado_por')->nullable()->constrained('users'); // ej.: dirección
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cambios_horario');
    }
};
