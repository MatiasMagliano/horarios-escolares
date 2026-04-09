<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// RUTA PRINCIPAL
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('admin');
    })->name('dashboard');

    Route::get('/pdf/horario-curso/{curso}', [PdfController::class, 'horarioCurso'])
        ->name('pdf.horario-curso');

    Route::get('/pdf/utilizacion-espacios/{espacio}', [PdfController::class, 'utilizacionEspacios'])
        ->name('pdf.utilizacion-espacios');

    Route::get('/pdf/cambio-horario/{cambio}/acta', [PdfController::class, 'cambioHorarioActa'])
        ->name('pdf.cambio-horario-acta');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
