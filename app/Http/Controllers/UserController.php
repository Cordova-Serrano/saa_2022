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

    public function create()
    {
        return view('users.create');
    }

    public function edit(User $user)
    {
        //dd($user->roles[0]->name);
        //$rol = RoleUser::where('user_id', $user->id)->first();
        //dd($rol);
        return view('users.edit', compact('user', $user));
    }

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

    public function store(Request $request)
    {
        //dd($request->all());
        $role = 0;
        $validated = $request->validate([
            'name' => 'required|max:100',
            'username' => 'required|max:100',
            'email' => 'required|email:rfc',
            'password' => 'required|min:8'
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

    public function destroy(User $user)
    {
        //dd($user);
        $id = $user->id;
        $user_table = User::where('id', $id)->first();
        $user_table->delete();

        $role_table = RoleUser::where('user_id', $id)->first();
        $role_table->delete();
        return redirect()->route('users.index');
        /*if ($user->expedient()->exists()) {
            return redirect()->route('users.index')->with('error', 'El usuario no puede ser eliminado.');
        } else {
            $user->updatedBy()->associate(Auth::user());
            $user->delete();

            return redirect()->route('users.index')->with('success', 'El usuario ha sido eliminado con éxito.');
        }*/
    }

    /*public function show(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id)->first();
        //dd($user);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

        $role = RoleUser::where('user_id', $id)->first();
        $role->role_id = $request->role;
        $role->save();
        return redirect()->route('users.index');
    }*/
}
