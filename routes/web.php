<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CSVController;
use App\Http\Controllers\GraphsController;
use App\Http\Controllers\ConsultController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\GenerationController;
use App\Http\Controllers\QueryController;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Career;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ruta login, es la que muestra al usuario cuando inicia el sistema
Route::get('/', function () {
    return view('login');
});

// Ruta de redireccionamiento cuando el usuario esta autenticado
Route::get('/home', function () {
    return view('welcome');
});

// Ruta de inicio del sistema cuando el usuario haya sido autenticado
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');
});

Route::middleware(['check.role:super'])->group(function () {
    $routes = ['except' => ['show']];
    Route::resource('users', UserController::class, $routes);

    //Route::get('/users/{user}/edit', [UserController::class, 'edit']);
    //Route::put('/users/update', [UserController::class, 'update']);
    Route::get('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    //Route::delete('/users/{user}/destroy', [UserController::class, 'destroy']);
});

Route::middleware(['auth'])->group(function () {
    // $routes = ['except' => ['show']];
    // Route::resource('csv', CSVController::class,$routes);
    
    Route::get('/csv/import', [CSVController::class, 'import'])->name('csv.import');
});
Route::resource('csv', CSVController::class)->middleware('auth');

//Login template
Route::get('/login_test', function () {
    return view('test.login');
});


Route::resource('graphs', GraphsController::class)->middleware('auth');
Route::resource('consult', ConsultController::class)->middleware('auth');

Route::get('/update', [CSVController::class, 'updateDoc']);

Route::get('/load_query', [QueryController::class, 'loadQuery']);
Route::get('/clean_query', [QueryController::class, 'cleanQuery']);

Route::get('/test', [ConsultController::class, 'test'])->name('consult.test');



Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


