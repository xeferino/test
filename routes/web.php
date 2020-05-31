<?php

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

Route::get('/', function () {
    return redirect('usuarios');
})->name('home');

Route::get('/usuarios', 'UsuarioController@index')->name('usuario.index');
Route::post('/usuario', 'UsuarioController@store')->name('usuario.store');
Route::put('/usuario', 'UsuarioController@update')->name('usuario.update');
Route::post('/usuario/{id}', 'UsuarioController@show')->where('id', '[0-9]+')->name('usuario.show');
Route::delete('/usuario/{id}', 'UsuarioController@delete')->where('id', '[0-9]+')->name('usuario.delete');

Route::get('/productos', 'ProductoController@index')->name('producto.index');
Route::post('/producto', 'ProductoController@store')->name('producto.store');
Route::put('/producto', 'ProductoController@update')->name('producto.update');
Route::post('/producto/{id}', 'ProductoController@show')->where('id', '[0-9]+')->name('producto.show');
Route::delete('/producto/{id}', 'ProductoController@delete')->where('id', '[0-9]+')->name('producto.delete');

Route::get('/guias', 'GuiaController@index')->name('guia.index');
Route::post('/guia', 'GuiaController@store')->name('guia.store');
Route::put('/guia', 'GuiaController@update')->name('guia.update');
Route::post('/guia/last', 'GuiaController@last')->name('guia.last');
Route::post('/guia/{id}', 'GuiaController@show')->where('id', '[0-9]+')->name('guia.show');
Route::delete('/guia/{id}', 'GuiaController@delete')->where('id', '[0-9]+')->name('guia.delete');
