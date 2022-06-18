<?php

use App\Http\Controllers\FavoritosController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

//set la vista por default al Login
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('/buscar', [HomeController::class, 'buscar'])->name('buscar');

Route::controller(FavoritosController::class)->group(function () {
    Route::get('/favoritos/index', 'index')->name('favoritos.index');
});
