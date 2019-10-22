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
Route::get('asignar/especialidad', 'SpecialityController@showAsignSpeciality');
Route::post('asignar/especialidad', 'SpecialityController@AsignSpeciality');
// Etapa
Route::get('crearetapa', 'StageController@showAddStage');
Route::post('crearetapa', 'StageController@registerStage');
// Actividad por especialidad
Route::get('asignar/especialidad-actividad', 'ActivityController@showAsignActivity');
Route::post('asignar/especialidad-actividad', 'ActivityController@AsignActivity');
// Prestación por especialidad
Route::get('asignar/especialidad-prestacion', 'ProvisionController@showAsignProvision');
Route::post('asignar/especialidad-prestacion', 'ProvisionController@AsignProvision');
// Tipo por especialidad que abre canasata
Route::get('asignar/especialidad-tipo', 'AdminController@showAsignType');
Route::post('asignar/especialidad-tipo', 'AdminController@AsignType');
/***************************************************************************************************************************
                                                    VIEW INFO
****************************************************************************************************************************/
// Fichas
Route::post('ficha', 'AttendanceController@registerAttendance');
Route::get('ficha/{DNI}', 'GeneralController@showClinicalRecords');
// Funcionarios
Route::get('funcionarios', 'FunctionaryController@showFunctionarys');
Route::post('funcionarios', 'FunctionaryController@deletingFunctionary');

Route::get('funcionarios/inactivos', 'AdminController@showInactiveFunctionarys');
Route::post('funcionarios/inactivos', 'AdminController@activateFunctionary');
// Pacientes
Route::get('pacientes', 'PatientController@showPatients')->middleware('checkrole:1');
Route::post('pacientes', 'PatientController@deletingPatient');

Route::get('pacientes/inactivos', 'PatientController@showInactivePatients');
Route::post('pacientes/inactivos', 'PatientController@activatePatient');
/***************************************************************************************************************************
                                                    REGISTERS
****************************************************************************************************************************/
// Usuario
Route::get('registrar/usuario', 'UserController@showAddUser');
Route::post('registrar/usuario','UserController@registerUser');
// Registros simples
Route::get('registrar', 'ActivityController@showAddActivity');
// Actividades
Route::get('registrar/actividad', 'ActivityController@showAddActivity');
Route::post('registrar/actividad', 'ActivityController@registerActivity');
// Alta
Route::get('registrar/alta', 'ReleaseController@showAddRelease');
Route::post('registrar/alta', 'ReleaseController@registerRelease');
// Atributos
Route::get('registrar/atributos', 'AttributesController@showAddAttributes');
Route::post('registrar/atributos', 'AttributesController@registerAttributes');
// Diagnostico
Route::get('registrar/diagnostico', 'DiagnosisController@showAddDiagnosis');
Route::post('registrar/diagnostico', 'DiagnosisController@registerDiagnosis');
// especialidad
Route::get('registrar/especialidad', 'SpecialityController@showAddSpeciality');
Route::post('registrar/especialidad', 'SpecialityController@registerSpeciality');
// Funcionario
Route::get('registrar/funcionario', 'FunctionaryController@showAddFunctionary');
Route::post('registrar/funcionario', 'FunctionaryController@registerFunctionary');
// Paciente
Route::get('registrar/paciente', 'PatientController@showAddPatient');
Route::post('registrar/paciente', 'PatientController@registerPatient');
// Prestación
Route::get('registrar/prestacion', 'ProvisionController@showAddProvision');
Route::post('registrar/prestacion', 'ProvisionController@registerProvision');
// Previsión
Route::get('registrar/prevision', 'PrevitionController@showAddPrevition');
Route::post('registrar/prevision', 'PrevitionController@registerPrevition');
// Procedencia
Route::get('registrar/procedencia', 'ProvenanceController@showAddProvenance');
Route::post('registrar/procedencia', 'ProvenanceController@registerProvenance');
// Programa
Route::get('registrar/programa', 'ProgramController@showAddProgram');
Route::post('registrar/programa', 'ProgramController@registerProgram');
// Sexo
Route::get('registrar/genero', 'SexController@showAddSex');
Route::post('registrar/genero', 'SexController@registerSex');
// SIGGES
Route::get('registrar/sigges', 'SiGGESController@showAddSIGGES');
Route::post('registrar/sigges', 'SiGGESController@registerSIGGES');
// Tipo
Route::get('registrar/tipo', 'AdminController@showAddType');
Route::post('registrar/tipo', 'AdminController@registerType');
/***************************************************************************************************************************
                                                    EDITS 
****************************************************************************************************************************/
// Actividad
Route::get('actividad/edit/{id}', 'ActivityController@showEditActivity');
Route::put('actividad/edit', 'ActivityController@editActivity');
// Paciente
Route::get('pacientes/edit/{dni}', 'PatientController@showEditPatient');
Route::put('pacientes/edit', 'PatientController@editPatient');
// Alta
Route::get('alta/edit/{id}', 'ReleaseController@showEditRelease');
Route::put('alta/edit', 'ReleaseController@editRelease');
// Atributos
Route::get('atributo/edit/{id}', 'AttributesController@showEditAttribute');
Route::put('atributo/edit', 'AttributesController@editAttribute');
// Diagnostico
Route::get('diagnostico/edit/{id}', 'DiagnosisController@showEditDiagnostic');
Route::put('diagnostico/edit', 'DiagnosisController@editDiagnostic');
// Especialidades
Route::get('especialidad/edit/{id}', 'SpecialityController@showEditSpeciality');
Route::put('especialidad/edit', 'SpecialityController@editSpeciality');
// Sexo
Route::get('sexo/edit/{id}', 'SexController@showEditSex');
Route::put('sexo/edit', 'SexController@editSex');
// Previsiones
Route::get('prevision/edit/{id}', 'PrevitionController@showEditPrevition');
Route::put('prevision/edit', 'PrevitionController@editPrevition');
// Procedencias
Route::get('procedencia/edit/{id}', 'ProvenanceController@showEditProvenance');
Route::put('procedencia/edit', 'ProvenanceController@editProvenance');
// Tipo GES
Route::get('sigges/edit/{id}', 'SiGGESController@showEditSiGGES');
Route::put('sigges/edit', 'SiGGESController@editSiGGES');
// Tipo prestaciones
Route::get('prestacion/edit/{id}', 'AdminController@showEditType');
Route::put('prestacion/edit', 'AdminController@editType');
/***************************************************************************************************************************
                                                    TESTING SECTION
****************************************************************************************************************************/
//Route::get('registraratencion', 'AdminController@showAddAttendance');

Route::post('registrar/atencion', 'AdminController@checkCurrStage');

Route::get('lista-especialidades','AdminController@getSpecialityPerFunctionary');
Route::get('lista-prestaciones','AdminController@getProvisionPerSpeciality');
Route::get('lista-actividades','AdminController@getActivityPerSpeciality');
Route::get('age-check','AdminController@checkAge');


Route::get('testing', 'AdminController@showTesting');
Route::post('testing', 'AdminController@regTesting');

Route::get('data', 'AdminController@data')->name('data'); 