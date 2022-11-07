<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | Este controlador maneja la autenticación de los usuarios para la aplicación y
    | redirigirlos a su pantalla de inicio. El controlador usa un rasgo
    | para proporcionar convenientemente su funcionalidad a sus aplicaciones.
    |
    */

    use AuthenticatesUsers;

    /**
     * Va a redirigir a los usuarios a la pantalla HOME después de iniciar sesión.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Crear nueva instancia del controlador
     *
     * @return void
     */
    // Consturctor donde usamos un middleware con invitado
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
