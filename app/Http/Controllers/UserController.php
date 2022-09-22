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

    public function store(Request $request)
    {
        dd($request->all());
        return view('users.index');
    }
}
