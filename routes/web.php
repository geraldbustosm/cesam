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

Route::get('/', 'GeneralController@index');

Route::get('registrar', 'AdminController@showAddUser');

Route::post('registrar','AdminController@registerUser');

Route::get('pacientes', 'AdminController@showPatients');

Route::get('registrarpaciente', 'AdminController@showAddPatient');

Route::post('registrarpaciente', 'AdminController@registerPatient');

Route::get('infopaciente', 'AdminController@showPatientInfo');

Route::get('fichas', 'AdminController@showClinicalRecords');

Route::get('testing', 'AdminController@showTesting');