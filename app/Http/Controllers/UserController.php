<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function index()
    {
        // $users = RoleUser::join('users', 'RoleUser.role_id', '=', 'users.categoria_id')
        //     ->select('articulos.*', 'categoria_id', 'categorias.nombreCategoria')
        //     ->get();
        // $articulos = Articulos::all();
        // $categorias = Categorias::all();
        // dd($articulos);
        // return view('inventario')->with('articulos', $articulos)->with('categorias', $categorias);
        $users = User::all();
        // dd($users);
        return view('users.index')->with('users', $users);
    }

    public function guardar(Request $request)
    {
        $users = new User();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->username = $request->uername;
        $users->password = $request->password;
        $users->save();

        $log = new logUsers();
        $log->idarticulo = $users->id;
        $log->nombreN = $users->nombre;
        $log->piezasN = $users->piezas;
        $log->fechaTerminoN = $users->fechaTermino;
        $log->save();


        return redirect()->back();
    }


    use RegistersUsers;
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
