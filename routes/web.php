<?php

use App\Http\Controllers\InstitucionSeleccionController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use App\Support\Dashboard\DocenteSuperposicionDetector;
use Illuminate\Support\Facades\Route;

// RUTA PRINCIPAL
Route::middleware(['auth'])->group(function () {
    Route::get('/instituciones/seleccionar', [InstitucionSeleccionController::class, 'index'])
        ->name('instituciones.select');

    Route::post('/instituciones/seleccionar', [InstitucionSeleccionController::class, 'store'])
        ->name('instituciones.store');

    Route::middleware('super-admin')->group(function () {
        Route::get('/admin/instituciones', function () {
            return view('admin.instituciones');
        })->name('admin.instituciones');

        Route::get('/admin/materias', function () {
            return view('admin.materias');
        })->name('admin.materias');

        Route::get('/admin/usuarios', function () {
            return view('admin.usuarios');
        })->name('admin.usuarios');

        Route::get('/admin/permisos', function () {
            return view('admin.permisos');
        })->name('admin.permisos');

        Route::get('/admin/permisos/presets', function () {
            return view('admin.permisos-presets');
        })->name('admin.permisos.presets');
    });
});

Route::middleware(['auth', 'institucion.activa'])->group(function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('can:dashboard.view')->name('dashboard');

    Route::get('/admin/horarios', function () {
        return view('admin.horarios');
    })->middleware('can:schedules.view')->name('admin.horarios');

    Route::get('/admin/cursos', function () {
        return redirect()->route('admin.cursos.listado');
    })->middleware('can:courses.view')->name('admin.cursos');

    Route::get('/admin/cursos/listado', function () {
        return view('admin.cursos-listado');
    })->middleware('can:courses.view')->name('admin.cursos.listado');

    Route::get('/admin/cursos/materias', function () {
        return view('admin.cursos-materias');
    })->middleware('can:course_subjects.view')->name('admin.cursos.materias');

    Route::get('/admin/docentes', function () {
        return view('admin.docentes');
    })->middleware('can:teachers.view')->name('admin.docentes');

    Route::get('/admin/espacios', function () {
        return redirect()->route('admin.espacios.utilizacion');
    })->middleware('can:spaces.utilization')->name('admin.espacios');

    Route::get('/admin/espacios/utilizacion', function () {
        return view('admin.espacios-utilizacion');
    })->middleware('can:spaces.utilization')->name('admin.espacios.utilizacion');

    Route::get('/admin/espacios/administracion', function () {
        return view('admin.espacios-administracion');
    })->middleware('can:spaces.view')->name('admin.espacios.administracion');

    Route::get('/admin/cambios-horario', function () {
        return view('admin.cambios-horario');
    })->middleware('can:schedule_changes.view')->name('admin.cambios-horario');

    Route::get('/admin/alertas/superposiciones-docentes', function (DocenteSuperposicionDetector $detector) {
        return view('admin.alertas-superposiciones-docentes', [
            'conflictos' => $detector->detect(),
        ]);
    })->middleware('can:alerts.superpositions')->name('admin.alertas.superposiciones-docentes');

    Route::get('/pdf/horario-curso/{curso}', [PdfController::class, 'horarioCurso'])
        ->middleware('can:schedules.export_pdf')
        ->name('pdf.horario-curso');

    Route::get('/pdf/utilizacion-espacios/{espacio}', [PdfController::class, 'utilizacionEspacios'])
        ->middleware('can:spaces.utilization')
        ->name('pdf.utilizacion-espacios');

    Route::get('/pdf/cambio-horario/{cambio}/acta', [PdfController::class, 'cambioHorarioActa'])
        ->middleware('can:schedule_changes.sign')
        ->name('pdf.cambio-horario-acta');

    Route::get('/pdf/cambio-horario/{cambio}/acta-firmada', [PdfController::class, 'cambioHorarioActaFirmada'])
        ->middleware('can:schedule_changes.view')
        ->name('pdf.cambio-horario-acta-firmada');
});

Route::middleware(['auth', 'institucion.activa'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
