<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Functionary;
use App\Activity;
use App\Attendance;
use App\Patient;
use App\Stage;
use App\TypeSpeciality;

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
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            //'descripcion' => 'required|string|max:255'
        ]);
        // Create a new 'object' attendance
        $attendance = new Attendance;
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
        // Pass the attendance to database
        $canasta = 0;
        if (TypeSpeciality::where('especialidad_id', $request->get('speciality'))->count() > 0) {
            if (Activity::where('id', $request->get('activity'))->where('actividad_abre_canasta', 1)->count() > 0) {
                if ($request->get('selectA') == 1) {
                    $canasta = 1;
                    $attendance->abre_canasta = $canasta;
                }
            }
        }
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
        // Get the patient
        $idPatient = $request->get('id');
        $patient = Patient::find($idPatient);
        // Get the active stage
        $stage = Stage::find($request->id_stage);
        $patientAtendances = $stage->attendance;

        if ($request->register == 1) {

            // Redirect to the view with successful status
            return view('admin.Views.clinicalRecords', compact('patient', 'stage', 'patientAtendances'));
        }
        if ($request->register == 2) {
            // Get active functionarys
            $users = Functionary::where('activa', 1)->get();
            return view('general.attendanceForm', ['DNI' => $idPatient])->with(compact('users', 'patient', 'stage'));
        }
    }
}
