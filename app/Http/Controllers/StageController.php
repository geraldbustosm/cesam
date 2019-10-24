<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Functionary;
use App\Diagnosis;
use App\Program;
use App\Release;
use App\SiGGES;
use App\Provenance;
use App\Stage;

class StageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerStage(Request $request)
    {
        // Check the format of each variable of 'request'
        $validation = $request->validate([]);
        // Create a new 'object' stage
        $stage = new Stage;
        // Set some variables with inputs of view
        // the variables name of object must be the same that database for save it
        // diagnostico_id, programa_id, sigges_id, procedencia_id, funcionario_id, paciente_id
        $stage->diagnostico_id = $request->diagnostico_id;
        $stage->programa_id = $request->programa_id;
        //$stage->alta_id = $request->alta_id;
        $stage->sigges_id = $request->sigges_id;
        $stage->procedencia_id = $request->procedencia_id;
        $stage->funcionario_id = $request->funcionario_id;
        $stage->paciente_id = $request->idpatient;
        // Pass the new stage to database
        $stage->save();
        // Set variable with patient_id
        $DNI = $request->idpatient;
        // Get the patient
        $patient = Patient::where('id', $DNI)
            ->where('activa', 1)
            ->first();
        // Get active functionarys
        $users = Functionary::where('activa', 1)->get();
        // Redirect to the view with stage, users (functionarys), patient, DNI (id of patient, we use DNI as standard in several views)
        // Also pass to the view the id of stage as stage_id
        return view('general.attendanceForm')->with(compact('stage', 'users', 'patient', 'DNI'));
    }
}
