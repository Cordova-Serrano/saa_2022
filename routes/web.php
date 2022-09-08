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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('profile', 'ProfileController@index')->name('profile.index');
    Route::post('profile/{user}/update', 'ProfileController@update')->name('profile.update');

    Route::get('password/edit', 'PasswordController@edit')->name('password.edit');
    Route::post('password/update', 'PasswordController@update')->name('password.update');
});

Route::middleware(['check.role:super'])->group(function () {
    Route::resource('users', UserController::class);

    Route::post('password/{user}/reset', 'PasswordController@reset')->name('password.reset');
});


Route::get('/home', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('login');
});
//Login template
Route::get('/login_test', function () {
    return view('test.login');
});

Route::resource('csv', CSVController::class)->middleware('auth');
Route::resource('graphs', GraphsController::class)->middleware('auth');
Route::resource('consult', ConsultController::class)->middleware('auth');

Route::get('/update', [CSVController::class, 'updateDoc']);
// Route::get('/load_semester', [ConsultController::class, 'loadSemester']);
// Route::get('/load_career', [CareerController::class, 'loadCareer']);
// Route::get('/load_generation', [GenerationController::class, 'loadGeneration']);

Route::get('/load_query', [QueryController::class, 'loadQuery']);
Route::get('/clean_query', [QueryController::class, 'cleanQuery']);

Route::get('/test', [ConsultController::class, 'test'])->name('consult.test');


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//CRUD
Route::get('/eliminar/{id}', [HomeController::class, 'eliminar']);
// Route::get('/editar/{id}', [HomeController::class, 'muestraeditar']);

// Route::post('/guardar', [HomeController::class, 'guardar']);
// Route::post('/guardaedicion', [HomeController::class, 'guardarEdicion']);
