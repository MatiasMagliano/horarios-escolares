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
        if (Schema::hasTable('datos_institucionales')) {
            return;
        }

        Schema::create('datos_institucionales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_institucion');
            $table->string('slug')->unique();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->unsignedTinyInteger('anio_maximo')->default(7);
            $table->boolean('tiene_turno_maniana')->default(true);
            $table->boolean('tiene_turno_tarde')->default(true);
            $table->boolean('tiene_contraturno_maniana')->default(false);
            $table->boolean('tiene_contraturno_tarde')->default(false);
            $table->enum('genero_director', ['masculino', 'femenino'])->nullable();
            $table->string('nombre_director')->nullable();
            $table->string('telefono_director')->nullable();
            $table->string('email_director')->nullable();
            $table->enum('genero_vicedirector', ['masculino', 'femenino'])->nullable();
            $table->string('nombre_vicedirector')->nullable();
            $table->string('telefono_vicedirector')->nullable();
            $table->string('email_vicedirector')->nullable();
            $table->boolean('activo')->default(true);
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
