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

use Carbon\Carbon;
use Illuminate\Http\Request;

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
        // Set some variables with functionary info and inputs of view
        // the variables name of object must be the same that database for save it
        // from attendanceForm: provision, id_stage, DNI, speciality, assistance, duration of attendance, date of attendance
        // attendancte -> funcionario_id, etapa_id, prestacion_id, fecha, hora, asistencia, duracion
        $attendance->funcionario_id = $request->functionary;
        $attendance->etapa_id = $request->id_stage;
        $attendance->prestacion_id = $request->get('provision');
        $attendance->asistencia = $request->get('selectA');
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
        // Check for abre_canasta
        $patient = Patient::find($idPatient);
        $typespeciality = TypeSpeciality::where('especialidad_id', $request->get('speciality'));
        $activity = Activity::where('id', $request->get('activity'))->where('actividad_abre_canasta', 1);
        if ($typespeciality->count() > 0 && $activity->count() > 0 && $request->get('selectA') == 1) {
            $canasta = true;
            foreach ($patientAttendances as $index) {
                foreach ($typespeciality as $type) {
                    $provision = Provision::find($index->prestacion_id);
                    if ($index->abre_canasta == 1 && $provision->tipo_id == $type->tipo_id) {
                        $canasta = false;
                    }
                }
            }
            if ($canasta) {
                $attendance->abre_canasta = 1;
            }
        }
        // Pass attendance to database
        $attendance->save();
        // Update variable for functionary
        // functionary -> horasRealizadas
        $duration   = $request->get('duration');
        $vector     = explode(":", $duration);
        $hours      = $vector[0];
        $minutes    = $vector[1];
        // Get previous hours worked
        $anterior   = $functionary->horasRealizadas;
        // Add the new hours
        $functionary->horasRealizadas = $anterior + $hours + $minutes / 60;
        // Save the update
        $functionary->save();
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
        $attendance->asistencia = $request->get('selectA');
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
        // Check for abre_canasta
        $typespeciality = TypeSpeciality::where('especialidad_id', 1)->get();
        $activity = Activity::where('id', $request->get('activity'))->where('actividad_abre_canasta', 1);
        if ($typespeciality->count() > 0 && $activity->count() > 0 && $request->get('selectA') == 1) {
            $canasta = true;
            $query = Attendance::join('etapa', 'etapa.id', 'atencion.etapa_id')
                ->where('atencion.prestacion_id', $attendance->prestacion_id)
                ->where('etapa.paciente_id', $idPatient)
                ->whereMonth('atencion.fecha', Carbon::now()->month)
                ->where('atencion.activa', 1)
                ->where('etapa.activa', 1)
                ->get();
            // $query2 = Attendance::join('prestacion', 'prestacion.id', 'atencion.prestacion_id')
            //     ->join('tipo_prestacion', 'tipo_prestacion.id', 'prestacion.tipo_id')
            //     ->join('etapa', 'etapa.id', 'atencion.etapa_id')
            //     ->where('etapa.paciente_id', $idPatient)
            //     ->where('tipo_prestacion.id', $attendance->provision->type->id)
            //     ->whereMonth('atencion.fecha', Carbon::now()->month)
            //     ->where('atencion.activa', 1)
            //     ->where('etapa.activa', 1)
            //     ->get();
            if ($query->count() > 0) {
                $canasta = false;
            }
            if ($canasta) {
                $attendance->abre_canasta = 1;
            }
        }
        // Pass attendance to database
        $attendance->save();
        // Update variable for functionary
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
        $functionary->horasRealizadas = $anterior + $hours + $minutes / 60;
        // Save the update
        $functionary->save();
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
    /***************************************************************************************************************************
                                                    ATTENDANCE LOGIC
     ****************************************************************************************************************************/
    // Return a specialitys from one functionary
    public function getSpecialityPerFunctionary(Request $request)
    {
        // Get the functionary
        $functionary = Functionary::find($request->functionary_id);
        // Create a variable for send to the view
        $speciality = $functionary->speciality;
        // Return specialitys
        return response()->json($speciality);
    }
    // Return a provisions from one speciality
    public function getProvisionPerSpeciality(Request $request)
    {
        // Get the speciality
        $specility = Speciality::find($request->speciality_id);
        // Create a variable for send to the view
        $provision = $specility->provision;
        // Return provisions
        return response()->json($provision);
    }
    // Return a activitys from one speciality
    public function getActivityPerSpeciality(Request $request)
    {
        // Get the speciality
        $specility = Speciality::find($request->speciality_id);
        // Create a variable for send to the view
        $activity = $specility->activity;
        // Return activity's
        return response()->json($activity);
    }
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
}
