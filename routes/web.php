<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CSVController;
use App\Http\Controllers\GraphsController;
use App\Http\Controllers\ConsultController;
use App\Http\Controllers\CareerController;
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

Route::get('/home', function () {
    return view('welcome');
});

Route::resource('csv', CSVController::class);
Route::resource('graphs', GraphsController::class);
Route::resource('consult', ConsultController::class);

Route::get('/update', [CSVController::class, 'updateDoc']);
Route::get('/load_semester', [ConsultController::class, 'loadSemester']);
Route::get('/load_career', [CareerController::class, 'loadCareer']);
Route::get('/test', [ConsultController::class, 'test'])->name('consult.test');


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/login_test', function () {
    return view('test.login');
});