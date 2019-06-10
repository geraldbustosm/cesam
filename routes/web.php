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
| ->name funciona para crearle un nombre a la ruta y luego poder llamarlo luego (en los action por ejemplo)
| con {{ route('nombre_de_la_ruta')}}
|
*/

Auth::routes();

Route::get('/', 'AdminController@index');

Route::get('registrar', 'AdminController@mostrarRegistrarUsuario')->middleware('checkrole:1');

Route::post('registrar','AdminController@registrarUsuario');

Route::get('pacientes', 'AdminController@pacientes');

Route::get('ingresarpaciente', 'AdminController@mostrarRegistrarPaciente')->middleware('checkrole:1');

Route::get('infopaciente', 'AdminController@infoPaciente');

Route::get('fichas', 'AdminController@fichas');