<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE cambios_horario MODIFY estado ENUM('borrador','autorizado','firmado','activo','finalizado','anulado') NOT NULL"
            );
        }

        Schema::table('cambios_horario', function (Blueprint $table) {
            $table->foreignId('anulado_por')
                ->nullable()
                ->after('finalizado_en')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('anulado_en')->nullable()->after('anulado_por');
        });
    }

    public function down(): void
    {
        DB::table('cambios_horario')
            ->where('estado', 'anulado')
            ->update(['estado' => 'borrador']);

        Schema::table('cambios_horario', function (Blueprint $table) {
            $table->dropConstrainedForeignId('anulado_por');
            $table->dropColumn('anulado_en');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE cambios_horario MODIFY estado ENUM('borrador','autorizado','firmado','activo','finalizado') NOT NULL"
            );
        }
    }
};
