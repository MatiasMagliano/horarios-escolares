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
            $table->foreignId('autorizado_por')->nullable()->constrained('users');
            $table->date('autorizado_en')->nullable();
            $table->foreignId('firmado_por')->nullable()->constrained('users');
            $table->date('firmado_en')->nullable();
            $table->foreignId('activado_por')->nullable()->constrained('users');
            $table->date('activado_en')->nullable();
            $table->foreignId('finalizado_por')->nullable()->constrained('users');
            $table->date('finalizado_en')->nullable();
            $table->foreignId('pedido_por')->nullable()->constrained('users');
            $table->date('pedido_en')->nullable();
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
