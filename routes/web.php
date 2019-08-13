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

Route::post('pacientes', 'AdminController@deletingPatient');

Route::get('pacientesinactivos', 'AdminController@showInactivePatients');

Route::post('pacientesinactivos', 'AdminController@activatePatient');

Route::get('registrarpaciente', 'GeneralController@showAddPatient');

Route::post('registrarpaciente', 'GeneralController@registerPatient');

Route::get('registrarfuncionario', 'AdminController@showAddFunctionary');

Route::post('registrarfuncionario', 'AdminController@registerFunctionary');

Route::get('registraralta', 'AdminController@showAddRelease');

Route::post('registraralta', 'AdminController@registerRelease');

Route::get('registrarprevision', 'AdminController@showAddPrevition');

Route::post('registrarprevision', 'AdminController@registerPrevition');

Route::get('registraratributos', 'AdminController@showAddAtributes');

Route::post('registraratributos', 'AdminController@registerAtributes');

Route::get('registrarsexo', 'AdminController@showAddSex');

Route::post('registrarsexo', 'AdminController@registerSex');

Route::get('registrarespecialidad', 'AdminController@showAddSpeciality');

Route::post('registrarespecialidad', 'AdminController@registerSpeciality');

Route::get('asignarespecialidad', 'AdminController@showAsignSpeciality');

Route::post('asignarespecialidad', 'AdminController@AsignSpeciality');

Route::get('infopaciente', 'AdminController@showPatientInfo');

Route::get('fichas', 'AdminController@showClinicalRecords');

Route::get('obtenerPacientesAjax', 'GeneralController@getPatientsAjax');

Route::get('testing', 'AdminController@showTesting');