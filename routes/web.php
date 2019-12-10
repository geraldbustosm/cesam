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
Route::post('asignar/especialidad', 'SpecialityController@AsignSpeciality')->middleware('checkrole:1');
// Etapa
Route::post('crear/etapa', 'StageController@registerStage')->middleware('checkrole:1|2|3');
// Actividad por especialidad
Route::get('asignar/especialidad-actividad', 'ActivityController@showAsignActivity')->middleware('checkrole:1');
Route::post('asignar/especialidad-actividad', 'ActivityController@AsignActivity')->middleware('checkrole:1');
// Prestación por especialidad
Route::get('asignar/especialidad-prestacion', 'ProvisionController@showAsignProvision')->middleware('checkrole:1');
Route::post('asignar/especialidad-prestacion', 'ProvisionController@AsignProvision')->middleware('checkrole:1');
// Tipo por especialidad que abre canasata
Route::get('asignar/especialidad-tipo', 'TypeController@showAsignType')->middleware('checkrole:1');
Route::post('asignar/especialidad-tipo', 'TypeController@AsignType')->middleware('checkrole:1');
/***************************************************************************************************************************
                                                    VIEW INFO
 ****************************************************************************************************************************/
// Fichas
Route::post('ficha', 'AttendanceController@registerAttendance')->middleware('checkrole:1|2|3');
Route::get('ficha/{DNI}', 'GeneralController@showClinicalRecords')->middleware('checkrole:1|2|3');
// Funcionarios
Route::get('funcionarios', 'FunctionaryController@showFunctionarys')->middleware('checkrole:1|2|3');
Route::post('funcionarios', 'FunctionaryController@deletingFunctionary')->middleware('checkrole:1');
// Funcionarios desactivados
Route::get('funcionarios/inactivos', 'FunctionaryController@showInactiveFunctionarys')->middleware('checkrole:1');
Route::post('funcionarios/inactivos', 'FunctionaryController@activateFunctionary')->middleware('checkrole:1');
// Pacientes
Route::get('pacientes', 'PatientController@showPatients')->middleware('checkrole:1|2|3');
Route::post('pacientes', 'PatientController@deletingPatient')->middleware('checkrole:1');
// Pacientes desactivados
Route::get('pacientes/inactivos', 'PatientController@showInactivePatients')->middleware('checkrole:1');
Route::post('pacientes/inactivos', 'PatientController@activatePatient')->middleware('checkrole:1');
// Usuarios
Route::get('usuarios', 'UserController@showUsers')->middleware('checkrole:1');
Route::post('usuarios', 'UserController@deletingUser')->middleware('checkrole:1');
// Usuarios desactivados
Route::get('usuarios/inactivos', 'UserController@showInactiveUsers')->middleware('checkrole:1');
Route::post('usuarios/inactivos', 'UserController@activateUser')->middleware('checkrole:1');
/***************************************************************************************************************************
                                                    INACTIVES
 ****************************************************************************************************************************/
// Main
Route::get('inactivo', 'ActivityController@showInactiveActivity')->middleware('checkrole:1');
// Actividad
Route::get('inactivo/actividad', 'ActivityController@showInactiveActivity')->middleware('checkrole:1');
Route::post('activar-actividad', 'ActivityController@activateActivity')->middleware('checkrole:1');
Route::post('desactivar-actividad', 'ActivityController@deletingActivity')->middleware('checkrole:1');
// Atributo
Route::get('inactivo/atributo', 'AttributesController@showInactiveAttribute')->middleware('checkrole:1');
Route::post('activar-atributo', 'AttributesController@activateAttribute')->middleware('checkrole:1');
Route::post('desactivar-atributo', 'AttributesController@deletingAttribute')->middleware('checkrole:1');
// Alta
Route::get('inactivo/alta', 'ReleaseController@showInactiveRelease')->middleware('checkrole:1');
Route::post('activar-alta', 'ReleaseController@activateRelease')->middleware('checkrole:1');
Route::post('desactivar-alta', 'ReleaseController@deletingRelease')->middleware('checkrole:1');
// Diagnóstico
Route::get('inactivo/diagnostico', 'DiagnosisController@showInactiveDiagnosis')->middleware('checkrole:1');
Route::post('activar-diagnostico', 'DiagnosisController@activateDiagnosis')->middleware('checkrole:1');
Route::post('desactivar-diagnostico', 'DiagnosisController@deletingDiagnosis')->middleware('checkrole:1');
// Especialidad
Route::get('inactivo/especialidad', 'SpecialityController@showInactiveSpeciality')->middleware('checkrole:1');
Route::post('activar-especialidad', 'SpecialityController@activateSpeciality')->middleware('checkrole:1');
Route::post('desactivar-especialidad', 'SpecialityController@deletingSpeciality')->middleware('checkrole:1');
// Especialidad Glosa
Route::get('inactivo/especialidad-glosa', 'SpecialityProgramController@showInactiveSpeciality')->middleware('checkrole:1');
Route::post('activar-especialidad-glosa', 'SpecialityProgramController@activateSpeciality')->middleware('checkrole:1');
Route::post('desactivar-especialidad-glosa', 'SpecialityProgramController@deletingSpeciality')->middleware('checkrole:1');
// Género
Route::get('inactivo/genero', 'SexController@showInactiveSex')->middleware('checkrole:1');
Route::post('activar-genero', 'SexController@activateSex')->middleware('checkrole:1');
Route::post('desactivar-genero', 'SexController@deletingSex')->middleware('checkrole:1');
// Prestación
Route::get('inactivo/prestacion', 'ProvisionController@showInactiveProvision')->middleware('checkrole:1');
Route::post('activar-prestacion', 'ProvisionController@activateProvision')->middleware('checkrole:1');
Route::post('desactivar-prestacion', 'ProvisionController@deletingProvision')->middleware('checkrole:1');
// Previsión
Route::get('inactivo/prevision', 'PrevitionController@showInactivePrevition')->middleware('checkrole:1');
Route::post('activar-prevision', 'PrevitionController@activatePrevition')->middleware('checkrole:1');
Route::post('desactivar-prevision', 'PrevitionController@deletingPrevition')->middleware('checkrole:1');
// Procedencia
Route::get('inactivo/procedencia', 'ProvenanceController@showInactiveProvenance')->middleware('checkrole:1');
Route::post('activar-procedencia', 'ProvenanceController@activateProvenance')->middleware('checkrole:1');
Route::post('desactivar-procedencia', 'ProvenanceController@deletingProvenance')->middleware('checkrole:1');
// Programa
Route::get('inactivo/programa', 'ProgramController@showInactiveProgram')->middleware('checkrole:1');
Route::post('activar-programa', 'ProgramController@activateProgram')->middleware('checkrole:1');
Route::post('desactivar-programa', 'ProgramController@deletingProgram')->middleware('checkrole:1');
// SiGGES
Route::get('inactivo/sigges', 'SiGGESController@showInactiveSiGGES')->middleware('checkrole:1');
Route::post('activar-sigges', 'SiGGESController@activateSiGGES')->middleware('checkrole:1');
Route::post('desactivar-sigges', 'SiGGESController@deletingSiGGES')->middleware('checkrole:1');
// Tipo de la prestación
Route::get('inactivo/tipo', 'TypeController@showInactiveType')->middleware('checkrole:1');
Route::post('activar-tipo', 'TypeController@activateType')->middleware('checkrole:1');
Route::post('desactivar-tipo', 'TypeController@deletingType')->middleware('checkrole:1');
/***************************************************************************************************************************
                                                    REGISTERS
 ****************************************************************************************************************************/
// Main
Route::get('registrar', 'ActivityController@showAddActivity')->middleware('checkrole:1');
// Actividades
Route::get('registrar/actividad', 'ActivityController@showAddActivity')->middleware('checkrole:1');
Route::post('registrar/actividad', 'ActivityController@registerActivity')->middleware('checkrole:1');
// Alta
Route::get('registrar/alta', 'ReleaseController@showAddRelease')->middleware('checkrole:1');
Route::post('registrar/alta', 'ReleaseController@registerRelease')->middleware('checkrole:1');
// Dar de alta
Route::get('alta/{DNI}', 'GeneralController@showAddRelease')->middleware('checkrole:1|2|3');
Route::post('alta', 'GeneralController@addRelease')->middleware('checkrole:1|2|3');
// Atención / Etapa
Route::post('registrar/atencion', 'StageController@checkCurrStage')->middleware('checkrole:1|2|3');
// Atributos
Route::get('registrar/atributos', 'AttributesController@showAddAttributes')->middleware('checkrole:1');
Route::post('registrar/atributos', 'AttributesController@registerAttributes')->middleware('checkrole:1');
// Diagnostico
Route::get('registrar/diagnostico', 'DiagnosisController@showAddDiagnosis')->middleware('checkrole:1');
Route::post('registrar/diagnostico', 'DiagnosisController@registerDiagnosis')->middleware('checkrole:1');
// Especialidad
Route::get('registrar/especialidad', 'SpecialityController@showAddSpeciality')->middleware('checkrole:1');
Route::post('registrar/especialidad', 'SpecialityController@registerSpeciality')->middleware('checkrole:1');
// Especialidad Glosa
Route::get('registrar/especialidad-glosa', 'SpecialityProgramController@showAddSpeciality')->middleware('checkrole:1');
Route::post('registrar/especialidad-glosa', 'SpecialityProgramController@registerSpeciality')->middleware('checkrole:1');
// Funcionario
Route::get('registrar/funcionario', 'FunctionaryController@showAddFunctionary')->middleware('checkrole:1');
Route::post('registrar/funcionario', 'FunctionaryController@registerFunctionary')->middleware('checkrole:1');
// Paciente
Route::get('registrar/paciente', 'PatientController@showAddPatient')->middleware('checkrole:1');
Route::post('registrar/paciente', 'PatientController@registerPatient')->middleware('checkrole:1');
// Prestación
Route::get('registrar/prestacion', 'ProvisionController@showAddProvision')->middleware('checkrole:1');
Route::post('registrar/prestacion', 'ProvisionController@registerProvision')->middleware('checkrole:1');
// Previsión
Route::get('registrar/prevision', 'PrevitionController@showAddPrevition')->middleware('checkrole:1');
Route::post('registrar/prevision', 'PrevitionController@registerPrevition')->middleware('checkrole:1');
// Procedencia
Route::get('registrar/procedencia', 'ProvenanceController@showAddProvenance')->middleware('checkrole:1');
Route::post('registrar/procedencia', 'ProvenanceController@registerProvenance')->middleware('checkrole:1');
// Programa
Route::get('registrar/programa', 'ProgramController@showAddProgram')->middleware('checkrole:1');
Route::post('registrar/programa', 'ProgramController@registerProgram')->middleware('checkrole:1');
// Sexo
Route::get('registrar/genero', 'SexController@showAddSex')->middleware('checkrole:1');
Route::post('registrar/genero', 'SexController@registerSex')->middleware('checkrole:1');
// SIGGES
Route::get('registrar/sigges', 'SIGGESController@showAddSIGGES')->middleware('checkrole:1');
Route::post('registrar/sigges', 'SIGGESController@registerSIGGES')->middleware('checkrole:1');
// Tipo
Route::get('registrar/tipo', 'TypeController@showAddType')->middleware('checkrole:1');
Route::post('registrar/tipo', 'TypeController@registerType')->middleware('checkrole:1');
// Usuario
Route::get('registrar/usuario', 'UserController@showAddUser')->middleware('checkrole:1');
Route::post('registrar/usuario', 'UserController@registerUser')->middleware('checkrole:1');
/***************************************************************************************************************************
                                                    EDITS 
 ****************************************************************************************************************************/
// Atención
Route::get('paciente/{rut}/etapa/{etapa}/atencion/{atencion}/edit', 'AttendanceController@showEditAttendance')->middleware('checkrole:1');
Route::put('editar/atencion', 'AttendanceController@editAttendance')->middleware('checkrole:1');
// Actividad
Route::get('actividades/edit/{id}', 'ActivityController@showEditActivity')->middleware('checkrole:1');
Route::put('actividades/edit', 'ActivityController@editActivity')->middleware('checkrole:1');
// Paciente
Route::get('pacientes/edit/{dni}', 'PatientController@showEditPatient')->middleware('checkrole:1');
Route::put('pacientes/edit', 'PatientController@editPatient')->middleware('checkrole:1');
// Alta
Route::get('altas/edit/{id}', 'ReleaseController@showEditRelease')->middleware('checkrole:1');
Route::put('altas/edit', 'ReleaseController@editRelease')->middleware('checkrole:1');
// Atributos
Route::get('atributos/edit/{id}', 'AttributesController@showEditAttribute')->middleware('checkrole:1');
Route::put('atributos/edit', 'AttributesController@editAttribute')->middleware('checkrole:1');
// Diagnostico
Route::get('diagnósticos/edit/{id}', 'DiagnosisController@showEditDiagnostic')->middleware('checkrole:1');
Route::put('diagnósticos/edit', 'DiagnosisController@editDiagnostic')->middleware('checkrole:1');
// Especialidades
Route::get('especialidades/edit/{id}', 'SpecialityController@showEditSpeciality')->middleware('checkrole:1');
Route::put('especialidades/edit', 'SpecialityController@editSpeciality')->middleware('checkrole:1');
// Funcionario
Route::get('funcionario/edit/{id}', 'FunctionaryController@showEditFunctionary')->middleware('checkrole:1');
Route::put('funcionario/edit', 'FunctionaryController@editFunctionary')->middleware('checkrole:1');
// Sexo
Route::get('géneros/edit/{id}', 'SexController@showEditSex')->middleware('checkrole:1');
Route::put('géneros/edit', 'SexController@editSex')->middleware('checkrole:1');
// Previsiones
Route::get('previsiones/edit/{id}', 'PrevitionController@showEditPrevition')->middleware('checkrole:1');
Route::put('previsiones/edit', 'PrevitionController@editPrevition')->middleware('checkrole:1');
// Procedencias
Route::get('procedencias/edit/{id}', 'ProvenanceController@showEditProvenance')->middleware('checkrole:1');
Route::put('procedencias/edit', 'ProvenanceController@editProvenance')->middleware('checkrole:1');
// Tipo GES
Route::get('sigges/edit/{id}', 'SiGGESController@showEditSiGGES')->middleware('checkrole:1');
Route::put('sigges/edit', 'SiGGESController@editSiGGES')->middleware('checkrole:1');
// Tipo prestaciones
Route::get('tipos/edit/{id}', 'TypeController@showEditType')->middleware('checkrole:1');
Route::put('tipos/edit', 'TypeController@editType')->middleware('checkrole:1');
// Usuario
Route::get('password/edit', 'UserController@showEditPassword')->middleware('checkrole:1|2|3');
Route::put('password/edit', 'UserController@editPassword')->middleware('checkrole:1|2|3');
Route::get('misdatos/edit', 'UserController@showEditData')->middleware('checkrole:1|2|3');
Route::put('misdatos/edit', 'UserController@editData')->middleware('checkrole:1|2|3');
// Usuario, Manejo de horas
Route::get('horas/edit', 'UserController@editHours')->middleware('checkrole:1|2|3');
Route::post('horas/edit', 'UserController@saveHours')->middleware('checkrole:1|2|3');

/***************************************************************************************************************************
                                                    REPORTS SECTION
 ****************************************************************************************************************************/
// Mensual
Route::get('prestaciones/mensual', 'GeneralController@showMonthlyRecords')->middleware('checkrole:1|2|3');
// Resumen
Route::get('prestaciones/resumen', 'GeneralController@showSummaryRecords')->middleware('checkrole:1|2|3');
// REM
Route::get('prestaciones/rem', 'GeneralController@showRemRecords')->middleware('checkrole:1|2|3');
// Ingresos
Route::get('prestaciones/ingresos', 'GeneralController@showAdmissionDischarge')->middleware('checkrole:1|2|3');
// Ingresos resumen
Route::get('prestaciones/ingresos/info', 'GeneralController@showInfoAddmissionAndDischarge')->middleware('checkrole:1|2|3');
// Egresos
Route::get('prestaciones/egresos', 'GeneralController@showAdmissionDischarge')->middleware('checkrole:1|2|3');
// Egresos resumen
Route::get('prestaciones/egresos/info', 'GeneralController@showInfoAddmissionAndDischarge')->middleware('checkrole:1|2|3');
/***************************************************************************************************************************
                                                SUPPORT FUNCTIONS SECTION
 ****************************************************************************************************************************/
// Datos para los asignar
Route::get('lista-especialidades', 'AttendanceController@getSpecialityPerFunctionary')->middleware('checkrole:1|2|3');
Route::get('lista-prestaciones', 'AttendanceController@getProvisionPerSpeciality')->middleware('checkrole:1|2|3');
Route::get('lista-actividades', 'AttendanceController@getActivityPerSpeciality')->middleware('checkrole:1|2|3');
// Revisa si la prestación está en rango de edad
Route::get('age-check', 'AttendanceController@checkAge')->middleware('checkrole:1|2|3');
// Gráficos de inicio
Route::get('charts', 'GraphsController@chart')->middleware('checkrole:1|2|3');
Route::get('charts2', 'GraphsController@chart2')->middleware('checkrole:1|2|3');
Route::get('charts3', 'GraphsController@chart3')->middleware('checkrole:1|2|3');
Route::get('charts4', 'GraphsController@chart4')->middleware('checkrole:1|2|3');
// Selección de etapa por paciente
Route::get('etapas', 'GeneralController@stagesPerPatient')->middleware('checkrole:1|2|3');
Route::post('etapa', 'GeneralController@selectStage')->middleware('checkrole:1|2|3');
// Repetir última atención en otra fecha
Route::get('ultima-atencion', 'AttendanceControllerLast@showAddAttendance')->middleware('checkrole:1|2|3');
Route::post('ultima-atenciontapa', 'GeneralController@selectStage')->middleware('checkrole:1|2|3');
