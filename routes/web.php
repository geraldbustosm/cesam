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
Route::get('asignar/especialidad', 'SpecialityController@showAsignSpeciality')->middleware('checkrole:1');
Route::post('asignar/especialidad', 'SpecialityController@AsignSpeciality');
// Etapa
Route::post('crear/etapa', 'StageController@registerStage');
// Actividad por especialidad
Route::get('asignar/especialidad-actividad', 'ActivityController@showAsignActivity')->middleware('checkrole:1');
Route::post('asignar/especialidad-actividad', 'ActivityController@AsignActivity');
// Prestación por especialidad
Route::get('asignar/especialidad-prestacion', 'ProvisionController@showAsignProvision')->middleware('checkrole:1');
Route::post('asignar/especialidad-prestacion', 'ProvisionController@AsignProvision');
// Tipo por especialidad que abre canasata
Route::get('asignar/especialidad-tipo', 'TypeController@showAsignType')->middleware('checkrole:1');
Route::post('asignar/especialidad-tipo', 'TypeController@AsignType');
/***************************************************************************************************************************
                                                    VIEW INFO
****************************************************************************************************************************/
// Fichas
Route::post('ficha', 'AttendanceController@registerAttendance');
Route::get('ficha/{DNI}', 'GeneralController@showClinicalRecords');
// Funcionarios
Route::get('funcionarios', 'FunctionaryController@showFunctionarys')->middleware('checkrole:1|2|3');
Route::post('funcionarios', 'FunctionaryController@deletingFunctionary');
// Funcionarios desactivados
Route::get('funcionarios/inactivos', 'FunctionaryController@showInactiveFunctionarys')->middleware('checkrole:1');
Route::post('funcionarios/inactivos', 'FunctionaryController@activateFunctionary');
// Pacientes
Route::get('pacientes', 'PatientController@showPatients')->middleware('checkrole:1|2|3');
Route::post('pacientes', 'PatientController@deletingPatient');
// Pacientes desactivados
Route::get('pacientes/inactivos', 'PatientController@showInactivePatients')->middleware('checkrole:1');
Route::post('pacientes/inactivos', 'PatientController@activatePatient');
// Usuarios
Route::get('usuarios', 'UserController@showUsers')->middleware('checkrole:1');
Route::post('usuarios', 'UserController@deletingUser');
// Usuarios desactivados
Route::get('usuarios/inactivos', 'UserController@showInactiveUsers')->middleware('checkrole:1');
Route::post('usuarios/inactivos', 'UserController@activateUser');
/***************************************************************************************************************************
                                                    INACTIVES
****************************************************************************************************************************/
// Main
Route::get('inactivo', 'ActivityController@showInactiveActivity')->middleware('checkrole:1');
// Actividad
Route::get('inactivo/actividad', 'ActivityController@showInactiveActivity')->middleware('checkrole:1');
Route::post('activar-actividad', 'ActivityController@activateActivity');
Route::post('desactivar-actividad', 'ActivityController@deletingActivity');
// Atributo
Route::get('inactivo/atributo', 'AttributesController@showInactiveAttribute')->middleware('checkrole:1');
Route::post('activar-atributo', 'AttributesController@activateAttribute');
Route::post('desactivar-atributo', 'AttributesController@deletingAttribute');
// Alta
Route::get('inactivo/alta', 'ReleaseController@showInactiveRelease')->middleware('checkrole:1');
Route::post('activar-alta', 'ReleaseController@activateRelease');
Route::post('desactivar-alta', 'ReleaseController@deletingRelease');
// Diagnóstico
Route::get('inactivo/diagnostico', 'DiagnosisController@showInactiveDiagnosis')->middleware('checkrole:1');
Route::post('activar-diagnostico', 'DiagnosisController@activateDiagnosis');
Route::post('desactivar-diagnostico', 'DiagnosisController@deletingDiagnosis');
// Especialidad
Route::get('inactivo/especialidad', 'SpecialityController@showInactiveSpeciality')->middleware('checkrole:1');
Route::post('activar-especialidad', 'SpecialityController@activateSpeciality');
Route::post('desactivar-especialidad', 'SpecialityController@deletingSpeciality');
// Género
Route::get('inactivo/genero', 'SexController@showInactiveSex')->middleware('checkrole:1');
Route::post('activar-genero', 'SexController@activateSex');
Route::post('desactivar-genero', 'SexController@deletingSex');
// Previsión
Route::get('inactivo/prevision', 'PrevitionController@showInactivePrevition')->middleware('checkrole:1');
Route::post('activar-prevision', 'PrevitionController@activatePrevition');
Route::post('desactivar-prevision', 'PrevitionController@deletingPrevition');
// Procedencia
Route::get('inactivo/procedencia', 'ProvenanceController@showInactiveProvenance')->middleware('checkrole:1');
Route::post('activar-procedencia', 'ProvenanceController@activateProvenance');
Route::post('desactivar-procedencia', 'ProvenanceController@deletingProvenance');
// SiGGES
Route::get('inactivo/sigges', 'SiGGESController@showInactiveSiGGES')->middleware('checkrole:1');
Route::post('activar-sigges', 'SiGGESController@activateSiGGES');
Route::post('desactivar-sigges', 'SiGGESController@deletingSiGGES');
// Tipo de la prestación
Route::get('inactivo/tipo', 'TypeController@showInactiveType')->middleware('checkrole:1');
Route::post('activar-tipo', 'TypeController@activateType');
Route::post('desactivar-tipo', 'TypeController@deletingType');
/***************************************************************************************************************************
                                                    REGISTERS
****************************************************************************************************************************/
// Main
Route::get('registrar', 'ActivityController@showAddActivity')->middleware('checkrole:1');
// Actividades
Route::get('registrar/actividad', 'ActivityController@showAddActivity')->middleware('checkrole:1');
Route::post('registrar/actividad', 'ActivityController@registerActivity');
// Alta
Route::get('registrar/alta', 'ReleaseController@showAddRelease')->middleware('checkrole:1');
Route::post('registrar/alta', 'ReleaseController@registerRelease');
// Atención / Etapa
Route::post('registrar/atencion', 'StageController@checkCurrStage');
// Atributos
Route::get('registrar/atributos', 'AttributesController@showAddAttributes')->middleware('checkrole:1');
Route::post('registrar/atributos', 'AttributesController@registerAttributes');
// Diagnostico
Route::get('registrar/diagnostico', 'DiagnosisController@showAddDiagnosis')->middleware('checkrole:1');
Route::post('registrar/diagnostico', 'DiagnosisController@registerDiagnosis');
// Especialidad
Route::get('registrar/especialidad', 'SpecialityController@showAddSpeciality')->middleware('checkrole:1');
Route::post('registrar/especialidad', 'SpecialityController@registerSpeciality');
// Funcionario
Route::get('registrar/funcionario', 'FunctionaryController@showAddFunctionary')->middleware('checkrole:1');
Route::post('registrar/funcionario', 'FunctionaryController@registerFunctionary');
// Paciente
Route::get('registrar/paciente', 'PatientController@showAddPatient')->middleware('checkrole:1');
Route::post('registrar/paciente', 'PatientController@registerPatient');
// Prestación
Route::get('registrar/prestacion', 'ProvisionController@showAddProvision')->middleware('checkrole:1');
Route::post('registrar/prestacion', 'ProvisionController@registerProvision');
// Previsión
Route::get('registrar/prevision', 'PrevitionController@showAddPrevition')->middleware('checkrole:1');
Route::post('registrar/prevision', 'PrevitionController@registerPrevition');
// Procedencia
Route::get('registrar/procedencia', 'ProvenanceController@showAddProvenance')->middleware('checkrole:1');
Route::post('registrar/procedencia', 'ProvenanceController@registerProvenance');
// Programa
Route::get('registrar/programa', 'ProgramController@showAddProgram')->middleware('checkrole:1');
Route::post('registrar/programa', 'ProgramController@registerProgram');
// Sexo
Route::get('registrar/genero', 'SexController@showAddSex')->middleware('checkrole:1');
Route::post('registrar/genero', 'SexController@registerSex');
// SIGGES
Route::get('registrar/sigges', 'SIGGESController@showAddSIGGES')->middleware('checkrole:1');
Route::post('registrar/sigges', 'SIGGESController@registerSIGGES');
// Tipo
Route::get('registrar/tipo', 'TypeController@showAddType')->middleware('checkrole:1');
Route::post('registrar/tipo', 'TypeController@registerType');
// Usuario
Route::get('registrar/usuario', 'UserController@showAddUser')->middleware('checkrole:1');
Route::post('registrar/usuario','UserController@registerUser');
/***************************************************************************************************************************
                                                    EDITS 
****************************************************************************************************************************/
// Atención
Route::get('paciente/{rut}/etapa/{etapa}/atencion/{atencion}/edit', 'AttendanceController@showEditAttendance');
Route::put('paciente/etapa/atencion/edit', 'AttendanceController@editAttendance');

// Actividad
Route::get('actividades/edit/{id}', 'ActivityController@showEditActivity')->middleware('checkrole:1');
Route::put('actividades/edit', 'ActivityController@editActivity');
// Paciente
Route::get('pacientes/edit/{dni}', 'PatientController@showEditPatient')->middleware('checkrole:1');
Route::put('pacientes/edit', 'PatientController@editPatient');
// Alta
Route::get('altas/edit/{id}', 'ReleaseController@showEditRelease')->middleware('checkrole:1');
Route::put('altas/edit', 'ReleaseController@editRelease');
// Atributos
Route::get('atributos/edit/{id}', 'AttributesController@showEditAttribute')->middleware('checkrole:1');
Route::put('atributos/edit', 'AttributesController@editAttribute');
// Diagnostico
Route::get('diagnósticos/edit/{id}', 'DiagnosisController@showEditDiagnostic')->middleware('checkrole:1');
Route::put('diagnósticos/edit', 'DiagnosisController@editDiagnostic');
// Especialidades
Route::get('especialidades/edit/{id}', 'SpecialityController@showEditSpeciality')->middleware('checkrole:1');
Route::put('especialidades/edit', 'SpecialityController@editSpeciality');
// Funcionario
Route::get('funcionario/edit/{id}', 'FunctionaryController@showEditFunctionary')->middleware('checkrole:1');
Route::put('funcionario/edit', 'FunctionaryController@editFunctionary');
// Sexo
Route::get('géneros/edit/{id}', 'SexController@showEditSex')->middleware('checkrole:1');
Route::put('géneros/edit', 'SexController@editSex');
// Previsiones
Route::get('previsiones/edit/{id}', 'PrevitionController@showEditPrevition')->middleware('checkrole:1');
Route::put('previsiones/edit', 'PrevitionController@editPrevition');
// Procedencias
Route::get('procedencias/edit/{id}', 'ProvenanceController@showEditProvenance')->middleware('checkrole:1');
Route::put('procedencias/edit', 'ProvenanceController@editProvenance');
// Tipo GES
Route::get('sigges/edit/{id}', 'SiGGESController@showEditSiGGES')->middleware('checkrole:1');
Route::put('sigges/edit', 'SiGGESController@editSiGGES');
// Tipo prestaciones
Route::get('tipos/edit/{id}', 'TypeController@showEditType')->middleware('checkrole:1');
Route::put('tipos/edit', 'TypeController@editType');
// Usuario
Route::get('password/edit', 'UserController@showEditPassword');
Route::put('password/edit', 'UserController@editPassword');
Route::get('misdatos/edit', 'UserController@showEditData');
Route::put('misdatos/edit', 'UserController@editData');
/***************************************************************************************************************************
                                                    TESTING SECTION
****************************************************************************************************************************/
Route::get('lista-especialidades','AdminController@getSpecialityPerFunctionary');
Route::get('lista-prestaciones','AdminController@getProvisionPerSpeciality');
Route::get('lista-actividades','AdminController@getActivityPerSpeciality');
Route::get('age-check','AdminController@checkAge');
Route::get('charts','GraphsController@chart');
Route::get('charts2','GraphsController@chart2');
Route::get('charts3','GraphsController@chart3');
Route::get('prestaciones/mensual', 'AdminController@showMonthlyRecords');
Route::get('prestaciones/resumen', 'AdminController@showSummaryRecords');
Route::get('prestaciones/rem', 'AdminController@showRemRecords');

Route::get('alta/{DNI}', 'GeneralController@showAddRelease');
Route::post('alta', 'GeneralController@addRelease');

Route::get('etapas', 'GeneralController@stagesPerPatient');
Route::post('etapa', 'GeneralController@selectStage');

Route::get('ultima-atencion', 'AttendanceControllerLast@showAddAttendance');
Route::post('ultima-atenciontapa', 'GeneralController@selectStage');