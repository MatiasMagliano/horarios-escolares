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

            // Numeración institucional
            $table->unsignedInteger('numero_acta')->nullable();
            $table->year('anio_acta')->nullable();

            // Datos formales del documento
            $table->enum('duracion', ['temporal', 'permanente']);
            $table->enum('tipo_cambio', ['cambio', 'permuta']);
            $table->foreignId('docente_id')->constrained('docentes'); // docente que solicita el cambio o permuta
            $table->foreignId('curso_id')->constrained('cursos');
            $table->foreignId('materia_id')->constrained('materias');
            $table->year('ciclo_lectivo')->nullable();
            $table->date('fecha_desde'); // fecha de inicio del cambio o permuta
            $table->date('fecha_hasta')->nullable(); // fecha de fin del cambio o permuta (solo para cambios temporales)
            $table->longText('acta')->nullable(); // ¿HTML listo para imprimir?
            $table->string('path_acta')->nullable(); // path al PDF firmado
            $table->enum('estado', ['borrador', 'autorizado', 'firmado', 'activo', 'finalizado']); // perteneciente a la máquina de estados

            // metadatos de máquina de estados
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
