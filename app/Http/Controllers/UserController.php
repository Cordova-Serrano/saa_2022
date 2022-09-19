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
        return view('users.index');
    }

    // public function guardar(Request $request)
    // {
    //     $users = new User();
    //     $users->name = $request->name;
    //     $users->email = $request->email;
    //     $users->username = $request->uername;
    //     $users->password = $request->password;
    //     $users->save();

    //     $log = new logUsers();
    //     $log->idarticulo = $users->id;
    //     $log->nombreN = $users->nombre;
    //     $log->piezasN = $users->piezas;
    //     $log->fechaTerminoN = $users->fechaTermino;
    //     $log->save();


    //     return redirect()->back();
    // }
}
