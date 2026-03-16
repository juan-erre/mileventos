<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller 
{
    // Muestra el formulario de inicio de sesión
    public function formLogin() 
    {
        return view('principal');
    }

    // Realizar el inicio de sesión autenticada
    public function login(Request $request) 
    {
        // Validar los datos del formulario
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Se verifica email/password (true si ok)
        if (auth()->attempt($credentials)) {
            //Si ok--> se regenera sesión (se anota que está autenticado en la sesión).
            $request->session()->regenerate();
            //Redireccionamos a la página principal 
            return redirect(route('principal'));
        }

        // Si la autenticación falla, volver al formulario con un error
        return back()
            ->withErrors([
                'email' => 'Email o contraseña no válidos.',
            ], 'login')
            ->onlyInput('email');
    }

    // Cerrar sesión autenticada
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('principal'));
    }
}