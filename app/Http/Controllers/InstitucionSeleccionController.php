<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\PermissionRegistrar;

class InstitucionSeleccionController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $instituciones = $user->instituciones_disponibles;

        if ($instituciones->count() === 1) {
            $request->session()->flash('info', 'Actualmente cuentas con una sola escuela asignada, por lo que fue seleccionada automáticamente.');
            return $this->activate($request, $instituciones->first());
        }

        return view('auth.select-institucion', [
            'instituciones' => $instituciones,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'institucion_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $institucion = Institucion::query()
            ->whereKey($data['institucion_id'])
            ->where('activo', true)
            ->firstOrFail();

        abort_unless($user->puedeAccederInstitucion($institucion->id), 403);

        return $this->activate($request, $institucion);
    }

    private function activate(Request $request, Institucion $institucion): RedirectResponse
    {
        $request->session()->put('institucion_id', $institucion->id);
        $request->user()->activarInstitucion($institucion);
        app(PermissionRegistrar::class)->setPermissionsTeamId($institucion->id);

        return redirect()->route('dashboard');
    }
}
