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
});

Route::middleware(['check.role:super'])->group(function () {
    $routes = ['except' => ['show']];
    Route::resource('users', UserController::class, $routes);

    //Route::get('/users/{user}/edit', [UserController::class, 'edit']);
    //Route::put('/users/update', [UserController::class, 'update']);
    Route::get('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    //Route::delete('/users/{user}/destroy', [UserController::class, 'destroy']);
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
// Route::get('/import', [CSVController::class, 'import']);s

Route::get('/load_query', [QueryController::class, 'loadQuery']);
Route::get('/clean_query', [QueryController::class, 'cleanQuery']);

Route::get('/test', [ConsultController::class, 'test'])->name('consult.test');

Route::get('/csv', [CSVController::class, 'import'])->name('csv.import');
Route::get('/csv', [CSVController::class, 'import'])->name('csv.index');



Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


