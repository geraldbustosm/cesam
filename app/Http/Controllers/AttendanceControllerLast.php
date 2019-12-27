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
        $attendance->asistencia = $request->get('selectA');
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
        $new_hours =  $hours + $minutes / 60;
        $functionary->horasRealizadas = $anterior +$new_hours;
        // Update specific relationship in functionary - Activiti Hours
        $activitiys = $request->get('activity');
        $functionary_id=$functionary->id;
        $registro = Hours::firstOrNew(['funcionario_id' => $functionary_id, 'actividad_id' => $activitiys ]);
        $registro->horasRealizadas = ($registro->horasRealizadas + $new_hours);
        if(is_null($registro->horasDeclaradas)){
            $registro->horasDeclaradas=0;
        }
        // Save the update
        $functionary->save();
        $registro->save();
        if ($request->register == 1) {
            // Redirect to the view with successful status
            $url = "/ficha/" . $patient->DNI;
            return redirect($url)->with('status', 'Atención agregada');
        }
        
    }
}
