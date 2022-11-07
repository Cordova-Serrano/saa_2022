<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | Este controlador es responsable de manejar las confirmaciones de contraseña y
    | utiliza un rasgo simple para incluir el comportamiento. Eres libre de explorar
    | este rasgo y anula cualquier función que requiera personalización.
    |
    */

    use ConfirmsPasswords;

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
    // Consturctor donde usamos un middleware con usuario autenticado
    public function __construct()
    {
        $this->middleware('auth');
    }
}
