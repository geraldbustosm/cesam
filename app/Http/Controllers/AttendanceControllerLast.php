<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Functionary;
use App\Activity;
use App\Attendance;
use App\Patient;
use App\Stage;
use App\TypeSpeciality;
use App\Hours;

class AttendanceControllerLast extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    //public function showAddAttendance(Request $request)
    public function showAddAttendance()
    {
        
        // Set variable with patient DNI (rut)
        //$DNI = $request->DNI_stage;
        // Get the patient
       //$patient = Patient::where('DNI', $DNI)
       //    ->where('activa', 1)
       //     ->first();
       $id=1;
       $patient = Patient::find($id);
        // Set variable with patient id (from database)
        $id_patient = $patient->id;
        // Get the active stage
        $stage = Stage::where('paciente_id', $id_patient)
            ->where('activa', 1)
            ->first();
        $attendance = $stage->attendance->first();
        $functionary = $attendance->functionary;
        $speciality = $functionary->speciality->first();
        $DNI = $patient->dni;
        $activity = $speciality->activity;
        
        // If have no active stage
        if (empty($stage)) {
            // Call function 'showAddStage'
            return $this->showAddStage($id_patient);
        } else {
            // Get active functionarys
            $users = Functionary::where('activa', 1)->get();
            return view('general.attendanceFormLast', ['DNI' => $DNI])->with(compact('stage', 'users', 'patient','functionary','speciality','attendance','activity'));
        }
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/

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
        $attendance->repetido = 1;
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
        app('App\Http\Controllers\AttendanceController')->checkCanasta($request, $attendance);
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Actualizar atención', $attendance->id, $attendance->table);
        // Update variable for functionary
        app('App\Http\Controllers\AttendanceController')->updateHours($request);
        // $request->register is button clicked from viewForm
        if ($request->register == 1) {
            // Redirect to the view with successful status
            $url = "/ficha/" . $patient->DNI;
            return redirect($url)->with('status', 'Atención agregada');
        }
        
    }
}
