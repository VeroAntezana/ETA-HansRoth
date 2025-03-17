<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    public function login(){
        return view('login');
    }

    public function loginVerificar(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'password' => 'required'
        ]);


        if (Auth::attempt(['usuario' => $request->usuario, 'password' => $request->password])) {
            return redirect()->route('dashboard');
        }


        return redirect()->back()->withErrors([
            'invalid_credentials' => 'Usuario o contraseña no válidos',
        ])->withInput();
    }
    public function cerrarSesion()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'sesion cerrada correctamente');
    }
}
