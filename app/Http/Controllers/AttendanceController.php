<?php

namespace App\Http\Controllers;

use App\Functionary;
use App\Activity;
use App\Attendance;
use App\Patient;
use App\Speciality;
use App\Stage;
use App\TypeSpeciality;
use App\Provision;
use App\Hours;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddAttendance()
    {
        // Get active functionarys
        $users = Functionary::where('activa', 1)->get();
        // Redirect to the view with list of functionarys
        return view('general.attendanceForm', compact('users'));
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditAttendance($dni, $etapa, $atencion)
    {
        // URL to redirect
        $url = 'ficha/' . $dni;

        $functionarys = Functionary::where('activa', 1)->get();
        $patient = Patient::where('dni', $dni)->first();

        if ($patient) {
            $stages = $patient->stage;
            if ($stages) {
                foreach ($stages as $stage) {
                    if ($stage->id == $etapa) {
                        $attendances = $stage->attendance;
                        if ($attendances) {
                            foreach ($attendances as $attendance) {
                                if ($attendance->id == $atencion) {
                                    // Formatting date for the view
                                    $fecha = explode("-", $attendance->fecha);
                                    $fecha = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];

                                    // Formatting hour for the view
                                    $hora = $attendance->hora;
                                    $hora = substr($hora, 0, 5);
                                    return view('admin.Edit.attendanceEdit', compact('patient', 'stage', 'attendance', 'functionarys', 'fecha', 'hora'));
                                }
                            }
                            return redirect($url)->with('error', 'No se encontró la atención para la etapa del paciente');
                        } else {
                            return redirect($url)->with('error', 'No se encontraron atenciones para la etapa del paciente');
                        }
                    }
                }
                return redirect($url)->with('error', 'No se encontró la etapa del paciente');
            } else {
                return redirect($url)->with('error', 'No se encontaron etapas para el paciente');
            }
        } else {
            return redirect('pacientes')->with('error', 'No se encontró el paciente');
        }
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editAttendance(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            //'descripcion' => 'required|string|max:255'
        ]);
        // Create a new 'object' attendance
        $attendance = Attendance::find($request->attendance_id);
        // Get the specific functionary
        $functionary = Functionary::find($request->functionary);
        // Update variable for functionary
        if ($attendance->actividad_id != $request->get('activity') || $attendance->duracion != $request->get('duration')) {
            $this->eraseHours($attendance);
            $updatedHours = $this->updateHours($request);
        }
        // Set some variables with functionary info and inputs of view
        // the variables name of object must be the same that database for save it
        // from attendanceForm: provision, id_stage, DNI, speciality, assistance, duration of attendance, date of attendance
        // attendancte -> funcionario_id, etapa_id, prestacion_id, fecha, hora, asistencia, duracion
        ($request->functionary ? $attendance->funcionario_id = $request->functionary : false);
        $attendance->etapa_id = $request->id_stage;
        ($request->get('provision') ? $attendance->prestacion_id = $request->get('provision') : false);
        $attendance->asistencia = $request->get('selectAssist');
        $attendance->hora = $request->get('timeInit');
        ($request->get('activity') ? $attendance->actividad_id = $request->get('activity') : false);
        ($request->get('duration') ? $attendance->duracion = $request->get('duration') : false);
        // Re-format database date to datepicker type
        $var = $request->get('datepicker');
        $date = str_replace('/', '-', $var);
        $correctDate = date('Y-m-d', strtotime($date));
        $attendance->fecha = $correctDate;
        // Set abre_canasta with 0;
        $attendance->abre_canasta = 0;
        // Get the active stage
        $stage = Stage::find($request->id_stage);
        $patientAttendances = $stage->attendance;
        // Get the patient
        $idPatient = $request->get('id');
        // Check for abre_canasta
        $patient = Patient::find($idPatient);
        // Check if canasta = true
        $attendance = $this->checkCanasta($request, $attendance, $idPatient);
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Actualizar atención', $attendance->id, $attendance->table);
        if ($request->register == 1) {
            $activeStage = Stage::where('paciente_id', $idPatient)
                ->where('activa', 1)
                ->select('id')
                ->first();
            // Redirect to the view with successful status
            $url = 'ficha/' . $patient->DNI;
            return redirect($url)->with('status', 'Se actualizó la atención');
        }
        if ($request->register == 2) {
            // Get active functionarys
            $users = Functionary::where('activa', 1)->get();
            return view('general.attendanceForm', ['DNI' => $idPatient])->with(compact('users', 'patient', 'stage'));
        }
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerAttendance(Request $request)
    {
        // Create a new 'object' attendance
        $attendance = new Attendance;
        // Set some variables with functionary info and inputs of view
        // the variables name of object must be the same that database for save it
        // from attendanceForm: provision, id_stage, DNI, speciality, assistance, duration of attendance, date of attendance
        // attendancte -> funcionario_id, etapa_id, prestacion_id, fecha, hora, asistencia, duracion
        $attendance->funcionario_id = $request->functionary;
        $attendance->etapa_id = $request->id_stage;
        $attendance->prestacion_id = $request->get('provision');
        $attendance->asistencia = $request->get('selectAssist');
        $attendance->repetido = $request->get('selectType');
        $attendance->hora = $request->get('timeInit');
        $attendance->actividad_id = $request->get('activity');
        $attendance->duracion = $request->get('duration');
        // Re-format database date to datepicker type
        $var = $request->get('datepicker');
        $date = str_replace('/', '-', $var);
        $correctDate = date('Y-m-d', strtotime($date));
        $attendance->fecha = $correctDate;
        // Set abre_canasta with 0;
        $attendance->abre_canasta = 0;
        // Get the active stage
        $stage = Stage::find($request->id_stage);
        $patientAttendances = $stage->attendance;
        // Get the patient
        $idPatient = $request->get('id');
        $patient = Patient::find($idPatient);
        // Check if canasta = true
        $this->checkCanasta($request, $attendance, $idPatient);
        // Update variable for functionary
        $this->updateHours($request);
        if ($request->register == 1) {
            // Redirect to the view with successful status
            $url = "/ficha/" . $patient->DNI;
            return redirect($url)->with('status', 'Atención agregada');
        }
        if ($request->register == 2) {
            // Get active functionarys
            $users = Functionary::where('activa', 1)->get();
            return view('general.attendanceForm', ['DNI' => $idPatient])->with(compact('users', 'patient', 'stage'));
        }
    }

    /**
     * Check if the attention open benefits ('canasta')
     * Logic: if is one attention with a certain functionary (asigned to GES or PPV) and the provision match ('glosa' -> GES or PPV)
     *        and is the first that match, so open benefits
     * @param request (from post)
     * @param attendance
     * @param patient_id
     * @return void
     */
    public function checkCanasta(Request $request, $attendance, $idPatient)
    {
        // Check for abre_canasta
        $typespeciality = TypeSpeciality::where('especialidad_id', $request->speciality)->get();
        $activity = Activity::where('id', $request->get('activity'))->where('actividad_abre_canasta', 1)->get();
        if ($typespeciality->count() > 0 && $activity->count() > 0 && $request->get('selectAssist') == 1) {
            $canasta = true;
            $query = Attendance::join('etapa', 'etapa.id', 'atencion.etapa_id')
                ->where('atencion.prestacion_id', $attendance->prestacion_id)
                ->where('etapa.paciente_id', $idPatient)
                ->whereMonth('atencion.fecha', Carbon::now()->month)
                ->where('atencion.activa', 1)
                ->where('etapa.activa', 1)
                ->get();
            if ($query->count() > 0) $canasta = false;
            if ($canasta) $attendance->abre_canasta = 1;
        }
        // Pass attendance to database
        $attendance->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar atención', $attendance->id, $attendance->table);
        return;
    }

    /**
     * Add attendance hours to the functionary and per speciality
     * @param request
     * @return void
     */
    public function updateHours(Request $request)
    {
        // functionary -> horasRealizadas
        $duration   = $request->get('duration');
        $vector     = explode(":", $duration);
        $hours      = $vector[0];
        $minutes    = $vector[1];
        // Get the specific functionary
        $functionary = Functionary::find($request->functionary);
        // Get previous hours worked
        $anterior   = $functionary->horasRealizadas;
        // Add the new hours
        $new_hours =  $hours + $minutes / 60;
        $functionary->horasRealizadas = $anterior + $new_hours;
        // Update specific relationship in functionary - Activity Hours
        $registro = Hours::updateOrCreate(['funcionario_id' => $functionary->id, 'actividad_id' => $request->activity, 'horasRealizadas' => $new_hours]);
        // Save the update
        $functionary->save();
        $registro->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Actualizar horas', $functionary->id, $functionary->table);
        app('App\Http\Controllers\AdminController')->addLog('Registrar horas por actividad', $registro->id, $registro->table);
        return;
    }

    /**
     * Clean the hours added before the update
     * @param attendance
     * @return void
     */
    public function eraseHours($attendance)
    {
        // functionary -> horasRealizadas
        $vector     = explode(":", $attendance->duracion);
        $hours      = $vector[0];
        $minutes    = $vector[1];
        // Get the specific functionary
        $functionary = Functionary::find($attendance->funcionario_id);
        // Get previous hours worked
        $anterior   = $functionary->horasRealizadas;
        // Add the new hours
        $new_hours =  $hours + $minutes / 60;
        $functionary->horasRealizadas = $anterior - $new_hours;
        // Delete specific relationship in functionary - Activity Hours
        $registro = Hours::updateOrCreate(['funcionario_id' => $functionary->id, 'actividad_id' => $attendance->actividad_id, 'horasRealizadas' => $new_hours * -1]);
        // Save the update
        $functionary->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Actualizar horas', $functionary->id, $functionary->table);
        app('App\Http\Controllers\AdminController')->addLog('Eliminar horas por actividad', $registro->id, $registro->table);
        return;
    }

    /***************************************************************************************************************************
                                                    ATTENDANCE LOGIC
     ****************************************************************************************************************************/
    /**
     * Function for get the specialities from one functionary
     * @param request (with the functionary ID)
     * @return speciality_array
    */
    public function getSpecialityPerFunctionary(Request $request)
    {
        // Get the functionary
        $functionary = Functionary::find($request->functionary_id);
        // Create a variable for send to the view
        $speciality = $functionary->speciality;
        // Return specialitys
        return response()->json($speciality);
    }
    /**
     * Function for get the provisions for a speciality
     * @param request (with the speciality ID)
     * @return provision_array
    */
    public function getProvisionPerSpeciality(Request $request)
    {
        // Get the speciality
        $specility = Speciality::find($request->speciality_id);
        // Create a variable for send to the view
        $provision = $specility->provision;
        // Return provisions
        return response()->json($provision);
    }
    /**
     * Function for get the activities for a speciality
     * @param request (with the speciality ID)
     * @return activity_array
    */
    public function getActivityPerSpeciality(Request $request)
    {
        // Get the speciality
        $specility = Speciality::find($request->speciality_id);
        // Create a variable for send to the view
        $activity = $specility->activity;
        // Return activity's
        return response()->json($activity);
    }
    /**
     * Function for check the patient age with respect to age range of provision
     * @param request (with the patient_id and provision_id)
     * @return integer (true if is in range)
    */
    // Compare age of patient and range age of provision
    public function checkAge(Request $request)
    {
        // Get the patient
        $patient = Patient::find($request->patient_id);
        // Get the provision
        $provision = Provision::find($request->provision_id);
        // Get age fo patient
        $years = Carbon::parse($patient->fecha_nacimiento)->age;
        // Get ranges
        $inf = $provision->rangoEdad_inferior;
        $sup = $provision->rangoEdad_superior;
        // Default
        $response = 0;
        // Check if age is in range
        if ((($inf <= $years) || $inf == 0) && (($years <= $sup) || $sup == 0)) {
            $response = 1;
        } else {
            $response = -1;
        }
        // Return provisions
        return response()->json($response);
    }
    /***************************************************************************************************************************
                                                    DELETE LOGIC
     ****************************************************************************************************************************/
    /**
     * Function that deactivate the attendance
     * @param request (with attendance_id)
     * @return redirect (to stage view)
     */
    public function deleteAttendance(Request $request)
    {
        $attendance = Attendance::find($request->id_attendance);
        $attendance->activa = 0;
        $attendance->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Eliminar', $attendance->id, $attendance->table);
        return redirect(url()->previous())->with('status', 'La prestación ha sido eliminada');
    }
}
