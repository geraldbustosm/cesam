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

/***************************************************************************************************************************
                                                    GENERAL
****************************************************************************************************************************/

Auth::routes();

Route::get('/', 'GeneralController@index');

/***************************************************************************************************************************
                                                    ASIGNATIONS
****************************************************************************************************************************/
// Especialidad
Route::get('asignarespecialidad', 'AdminController@showAsignSpeciality');

Route::post('asignarespecialidad', 'AdminController@AsignSpeciality');
// Etapa
Route::get('crearetapa', 'AdminController@showAddStage');

Route::post('crearetapa', 'AdminController@registerStage');
// Prestación por especialidad
Route::get('asignarespecialidadprestacion', 'AdminController@showAsignProvision');

Route::post('asignarespecialidadprestacion', 'AdminController@AsignProvision');

/***************************************************************************************************************************
                                                    VIEW INFO
****************************************************************************************************************************/
// Fichas
Route::post('fichas', 'AdminController@showClinicalRecords');
// Funcionarios
Route::get('funcionarios', 'AdminController@showFunctionarys');

Route::post('funcionarios', 'AdminController@deletingFunctionary');

Route::get('funcionarios/inactivos', 'AdminController@showInactiveFunctionarys');

Route::post('funcionarios/inactivos', 'AdminController@activateFunctionary');
//
Route::get('infopaciente', 'AdminController@showPatientInfo');
// Pacientes
Route::get('pacientes', 'AdminController@showPatients');

Route::post('pacientes', 'AdminController@deletingPatient');

Route::get('pacientes/inactivos', 'AdminController@showInactivePatients');

Route::post('pacientes/inactivos', 'AdminController@activatePatient');

/***************************************************************************************************************************
                                                    REGISTERS
****************************************************************************************************************************/
// Usuario
Route::get('registrar/usuario', 'AdminController@showAddUser');

Route::post('registrar/usuario','AdminController@registerUser');
// Registros simples
Route::get('registrar', 'AdminController@showAddRelease');
// Alta
Route::get('registrar/alta', 'AdminController@showAddRelease');

Route::post('registrar/alta', 'AdminController@registerRelease');
// Atributos
Route::get('registrar/atributos', 'AdminController@showAddAtributes');

Route::post('registrar/atributos', 'AdminController@registerAtributes');
// Diagnostico
Route::get('registrar/diagnostico', 'AdminController@showAddDiagnosis');

Route::post('registrar/diagnostico', 'AdminController@registerDiagnosis');
// especialidad
Route::get('registrar/especialidad', 'AdminController@showAddSpeciality');

Route::post('registrar/especialidad', 'AdminController@registerSpeciality');
// Funcionario
Route::get('registrarfuncionario', 'AdminController@showAddFunctionary');

Route::post('registrarfuncionario', 'AdminController@registerFunctionary');
// Paciente
Route::get('registrarpaciente', 'GeneralController@showAddPatient');

Route::post('registrarpaciente', 'GeneralController@registerPatient');
// Prestación
Route::get('registrarprestacion', 'AdminController@showAddProvision');

Route::post('registrarprestacion', 'AdminController@registerProvision');
// Previsión
Route::get('registrar/prevision', 'AdminController@showAddPrevition');

Route::post('registrar/prevision', 'AdminController@registerPrevition');
// Procedencia
Route::get('registrar/procedencia', 'AdminController@showAddProvenance');

Route::post('registrar/procedencia', 'AdminController@registerProvenance');
// Programa
Route::get('registrarprograma', 'AdminController@showAddProgram');

Route::post('registrarprograma', 'AdminController@registerProgram');
// Sexo
Route::get('registrar/genero', 'AdminController@showAddSex');

Route::post('registrar/genero', 'AdminController@registerSex');
// SIGGES
Route::get('registrar/sigges', 'AdminController@showAddSIGGES');

Route::post('registrar/sigges', 'AdminController@registerSIGGES');
// Tipo
Route::get('registrar/tipo', 'AdminController@showAddType');

Route::post('registrar/tipo', 'AdminController@registerType');
/***************************************************************************************************************************
                                                    EDITS 
****************************************************************************************************************************/
// Paciente
Route::get('pacientes/edit/{dni}', 'AdminController@showEditPatient');

Route::put('pacientes/edit', 'AdminController@editPatient');

// Alta
Route::get('alta/edit/{id}', 'AdminController@showEditRelease');
Route::put('alta/edit', 'AdminController@editRelease');

// Atributos
Route::get('atributo/edit/{id}', 'AdminController@showEditAttribute');
Route::put('atributo/edit', 'AdminController@editAttribute');

// Diagnostico
Route::get('diagnostico/edit/{id}', 'AdminController@showEditDiagnostic');
Route::put('diagnostico/edit', 'AdminController@editDiagnostic');

// Especialidades
Route::get('especialidad/edit/{id}', 'AdminController@showEditSpeciality');
Route::put('especialidad/edit', 'AdminController@editSpeciality');

// Sexo
Route::get('sexo/edit/{id}', 'AdminController@showEditSex');
Route::put('sexo/edit', 'AdminController@editSex');

// Previsiones
Route::get('prevision/edit/{id}', 'AdminController@showEditPrevition');
Route::put('prevision/edit', 'AdminController@editPrevition');

// Procedencias
Route::get('procedencia/edit/{id}', 'AdminController@showEditProvenance');
Route::put('procedencia/edit', 'AdminController@editProvenance');

// Tipo GES
Route::get('sigges/edit/{id}', 'AdminController@showEditSiGGES');
Route::put('sigges/edit', 'AdminController@editSiGGES');

// Tipo prestaciones
Route::get('prestacion/edit/{id}', 'AdminController@showEditType');
Route::put('prestacion/edit', 'AdminController@editType');

/***************************************************************************************************************************
                                                    TESTING SECTION
****************************************************************************************************************************/
//Route::get('registraratencion', 'AdminController@showAddAttendance');

Route::post('registraratencion', 'AdminController@checkCurrStage');

Route::post('registraratencionOk', 'AdminController@registerAttendance');
// cambia los nombres aca wn
Route::get('get-speciality-list','AdminController@getStateList');
Route::get('get-provision-list','AdminController@getCityList');

Route::get('testing', 'AdminController@showTesting');
Route::post('testing', 'AdminController@regTesting');

Route::get('data', 'AdminController@data')->name('data'); 