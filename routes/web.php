<?php

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
    return view('cliente.inicio');
});
Route::get('/cadastrar/cliente/csv', function () {
    return view('cliente.cadastrar');
});

Route::post('/cadastrar/cliente/csv','cliente\ClienteController@cadastrar');
Route::get('/clientes','cliente\ClienteController@listar');

Route::post('/cliente/exportar/csv','cliente\ClienteController@exportar');
Route::post('/cliente/exportar/xls','cliente\ClienteController@exportarXLS');
