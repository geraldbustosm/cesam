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
// Prestación por especialidad
Route::get('asignarespecialidadprestacion', 'AdminController@showAsignProvision');

Route::post('asignarespecialidadprestacion', 'AdminController@AsignProvision');

/***************************************************************************************************************************
                                                    VIEW INFO
****************************************************************************************************************************/

Route::get('fichas', 'AdminController@showClinicalRecords');

Route::get('funcionarios', 'AdminController@showFunctionarys');

//Route::post('funcionarios', 'AdminController@deletingPatient');

Route::get('infopaciente', 'AdminController@showPatientInfo');

Route::get('pacientes', 'AdminController@showPatients');

Route::post('pacientes', 'AdminController@deletingPatient');

Route::get('pacientesinactivos', 'AdminController@showInactivePatients');

Route::post('pacientesinactivos', 'AdminController@activatePatient');

/***************************************************************************************************************************
                                                    REGISTERS
****************************************************************************************************************************/
// Usuario
Route::get('registrar', 'AdminController@showAddUser');

Route::post('registrar','AdminController@registerUser');
// Alta
Route::get('registraralta', 'AdminController@showAddRelease');

Route::post('registraralta', 'AdminController@registerRelease');
// Atributos
Route::get('registraratributos', 'AdminController@showAddAtributes');

Route::post('registraratributos', 'AdminController@registerAtributes');
// especialidad
Route::get('registrarespecialidad', 'AdminController@showAddSpeciality');

Route::post('registrarespecialidad', 'AdminController@registerSpeciality');
// procedencia
Route::get('registrarprocedencia', 'AdminController@showAddProvenance');

Route::post('registrarprocedencia', 'AdminController@registerProvenance');
// Funcionario
Route::get('registrarfuncionario', 'AdminController@showAddFunctionary');

Route::post('registrarfuncionario', 'AdminController@registerFunctionary');
// Paciente
Route::get('registrarpaciente', 'GeneralController@showAddPatient');

Route::post('registrarpaciente', 'GeneralController@registerPatient');
// Prestación
Route::get('registrarprestacion', 'AdminController@showAddProvision');

Route::post('registrarprestacion', 'AdminController@registerProvision');
// Programa
Route::get('registrarprograma', 'AdminController@showAddProgram');

Route::post('registrarprograma', 'AdminController@registerProgram');
// Previsión
Route::get('registrarprevision', 'AdminController@showAddPrevition');

Route::post('registrarprevision', 'AdminController@registerPrevition');
// Etapa
Route::get('crearetapa', 'AdminController@showAddStage');

Route::post('crearetapa', 'AdminController@registerStage');
// Diagnostico
Route::get('registrardiagnostico', 'AdminController@showAddDiagnosis');

Route::post('registrardiagnostico', 'AdminController@registerDiagnosis');
// Sexo
Route::get('registrarsexo', 'AdminController@showAddSex');

Route::post('registrarsexo', 'AdminController@registerSex');
// SIGGES
Route::get('registrarsigges', 'AdminController@showAddSIGGES');

Route::post('registrarsigges', 'AdminController@registerSIGGES');
// Tipo
Route::get('registrartipo', 'AdminController@showAddType');

Route::post('registrartipo', 'AdminController@registerType');

/***************************************************************************************************************************
                                                    EDITS 
****************************************************************************************************************************/
// Editar paciente
Route::get('pacientes/edit/{dni}', 'AdminController@showEditPatient');
Route::post('pacientes/edit', 'AdminController@editPatient');

/***************************************************************************************************************************
                                                    NEW  ATTENDANCE
****************************************************************************************************************************/
// SIGGES


/***************************************************************************************************************************
                                                    TESTING SECTION
****************************************************************************************************************************/
Route::get('registraratencion', 'AdminController@showAddAttendance');

Route::post('registraratencion', 'AdminController@checkCurrStage');

Route::get('get-speciality-list','AdminController@getStateList');
Route::get('get-provision-list','AdminController@getCityList');

Route::get('testing', 'AdminController@showTesting');