<?php

namespace App\Http\Controllers;

use DataTables;
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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $model = User::with('roles')->get();

            return DataTables::of($model)->toJson();
        }
        // $model = User::with('roles')->get();
        // dd($model);
        return view('users.index');
    }

    /** 
     * @desc Regresa la vista de crear usuarios
    
    */ 
    public function create()
    {
        return view('users.create');
    }

    /** 
     * @desc Regresa la vista de editar usuarios con información del usuario
     * @params {User} El usuario a actualizar
    
    */ 
    public function edit(User $user)
    {
        //dd($user->roles[0]->name);
        //$rol = RoleUser::where('user_id', $user->id)->first();
        //dd($rol);
        return view('users.edit', compact('user', $user));
    }

    /** 
     * @desc Guarda la nueva información de usuario existente
     * @params {Request} Información nueva del usuario a almacenar
     * @returns Vista index de usuario
    
    */ 
    public function update(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

        $role = RoleUser::where('user_id', $id)->first();
        $role->role_id = $request->role;
        $role->save();
        return redirect()->route('users.index');
    }

    /** 
     * @desc Guarda la información de un usuario nuevo
     * @params {Request} Información del usuario a almacenar
     * @returns Vista index de usuario
    */ 
    public function store(Request $request)
    {
        $role = 0;
        $validated = $request->validate([
            'name' => 'required|max:100',
            'username' => 'required|max:100',
            'email' => 'required|email:rfc',
            'password' => 'required|min:8|alpha_num'
        ]);
        $user = new User([
            "name" => $request->name,
            "username" => $request->username,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);
        $user->save();
        if($request->role == "super")
            $role = 1;
        elseif($request->role == "admin")
            $role = 2;
        else
            $role = 3;
        $role_user = new RoleUser([
            "user_id" => $user->id,
            "role_id" => $role
        ]);
        $role_user->save();
        return view('users.index');
    }

    /** 
     * @desc Elimina un usuario
     * @params {User} Usuario a eliminar
     * @returns Vista index de usuario
    */ 
    public function destroy(User $user)
    {
        $id = $user->id;
        $role_table = RoleUser::where('user_id', $id)->first();
        $role_table->delete();
        $user_table = User::where('id', $id)->first();
        $user_table->delete();
        return redirect()->route('users.index');
    }
}
