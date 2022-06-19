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
    Route::post('/favoritos/add', 'add')->name('favoritos.add');
    Route::put('/favoritos/modify', 'modify')->name('favoritos.modify');
    Route::delete('/favoritos/remove', 'remove')->name('favoritos.remove');
});
