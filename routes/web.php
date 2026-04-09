<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// RUTA PRINCIPAL
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin/horarios', function () {
        return view('admin.horarios');
    })->name('admin.horarios');

    Route::get('/admin/cursos', function () {
        return redirect()->route('admin.cursos.listado');
    })->name('admin.cursos');

    Route::get('/admin/cursos/listado', function () {
        return view('admin.cursos-listado');
    })->name('admin.cursos.listado');

    Route::get('/admin/cursos/materias', function () {
        return view('admin.cursos-materias');
    })->name('admin.cursos.materias');

    Route::get('/admin/docentes', function () {
        return view('admin.docentes');
    })->name('admin.docentes');

    Route::get('/admin/espacios', function () {
        return redirect()->route('admin.espacios.utilizacion');
    })->name('admin.espacios');

    Route::get('/admin/espacios/utilizacion', function () {
        return view('admin.espacios-utilizacion');
    })->name('admin.espacios.utilizacion');

    Route::get('/admin/espacios/administracion', function () {
        return view('admin.espacios-administracion');
    })->name('admin.espacios.administracion');

    Route::get('/admin/cambios-horario', function () {
        return view('admin.cambios-horario');
    })->name('admin.cambios-horario');

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
