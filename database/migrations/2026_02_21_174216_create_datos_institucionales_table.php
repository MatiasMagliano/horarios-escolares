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
        Schema::create('datos_institucionales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_institucion');
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();

            // Datos de contacto del director
            $table->enum('genero_director', ['masculino', 'femenino'])->nullable();
            $table->string('nombre_director');
            $table->string('telefono_director')->nullable();
            $table->string('email_director')->nullable();

            // Datos de contacto del vicedirector
            $table->enum('genero_vicedirector', ['masculino', 'femenino'])->nullable();
            $table->string('nombre_vicedirector')->nullable();
            $table->string('telefono_vicedirector')->nullable();
            $table->string('email_vicedirector')->nullable();

            // vigencia
            $table->boolean('vigente')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_institucionales');
    }
};
