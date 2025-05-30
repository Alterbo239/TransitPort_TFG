<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

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

        $user = Auth::user();

        if ($user->cargo == 'gestor') {
            return redirect()->route('crearUsuario');
        } elseif ($user->cargo == 'administrativo') {
            return redirect()->route('crearOrden');
        } elseif ($user->cargo == 'operador') {
            return redirect()->route('ordenes');
        } elseif ($user->cargo == 'cliente') {
            return redirect()->route('pedirCitas');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $rol = Auth::user() -> cargo;
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($rol === 'cliente') {
            return redirect() -> route('loginCliente');
        }
        return redirect('/');
    }
}
