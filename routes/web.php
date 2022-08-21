<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CSVController;
use App\Http\Controllers\GraphsController;
use App\Http\Controllers\ConsultController;
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
Route::get('/test', function () {
    return view('test.test');
});