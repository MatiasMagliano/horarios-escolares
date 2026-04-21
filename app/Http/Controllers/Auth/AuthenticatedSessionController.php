<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        $request->session()->forget('institucion_id');
        app(PermissionRegistrar::class)->setPermissionsTeamId(null);

        $user = $request->user();
        $instituciones = $user->instituciones_disponibles;

        if ($instituciones->count() === 1) {
            $institucion = $instituciones->first();
            $request->session()->put('institucion_id', $institucion->id);
            $user->activarInstitucion($institucion);
            app(PermissionRegistrar::class)->setPermissionsTeamId($institucion->id);

            return redirect()->intended(route('dashboard', absolute: false));
        }

        return redirect()->route('instituciones.select');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        app(PermissionRegistrar::class)->setPermissionsTeamId(null);

        return redirect('/');
    }
}
