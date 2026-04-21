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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('institucion_activa_id')
                ->nullable()
                ->after('remember_token')
                ->constrained('datos_institucionales')
                ->nullOnDelete();
        });

        Schema::create('institucion_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institucion_id')
                ->constrained('datos_institucionales')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['institucion_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institucion_user');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('institucion_activa_id');
        });
    }
};
