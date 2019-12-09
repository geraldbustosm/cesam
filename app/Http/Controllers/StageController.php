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
                                                    CREATE FORM
     ****************************************************************************************************************************/
    // Create a new stage for a patient
    public function showAddStage($patient_id)
    {
        // Get diagnosis, program, release, sigges, provenance
        $diagnosis = Diagnosis::where('activa', 1)->get();
        $program = Program::where('activa', 1)->get();
        $Sigges = SiGGES::where('activa', 1)->get();
        $provenance = Provenance::all();
        // Get the functionarys with user info (personal information)
        $functionarys = Functionary::join('users', 'users.id', '=', 'funcionarios.user_id')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'funcionarios.id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->select('funcionarios.id', 'users.primer_nombre', 'users.apellido_paterno')
            ->whereRaw("lower(especialidad.descripcion) like '%medico%'")
            ->orWhereRaw("lower(especialidad.descripcion) like '%médico%'")
            ->where('funcionarios.activa', 1)
            ->get();
        // return to the view, with 'id_patient', 'functionary', 'diagnosis', 'program', 'release', 'Sigges', 'provenance'
        return view('admin.Form.stageCreateForm', ['idpatient' => $patient_id])->with(compact('functionarys', 'diagnosis', 'program', 'Sigges', 'provenance'));
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
        
        $stage->programa_id = $request->programa_id;
        //$stage->alta_id = $request->alta_id;
        $stage->sigges_id = $request->sigges_id;
        $stage->procedencia_id = $request->procedencia_id;
        $stage->funcionario_id = $request->funcionario_id;
        $stage->paciente_id = $request->idpatient;
        // Pass the new stage to database
        $stage->save();
        $stage->diagnosis()->sync($request->options);
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
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    // Check for an active stage for the patient (parameter)
    public function checkCurrStage(Request $request)
    {
        // Get the patient
        $patient = Patient::where('id', $request->DNI_stage)
            ->where('activa', 1)
            ->first();
        // Set variable with patient DNI (rut)
        $DNI = $patient->DNI;
        // Set variable with patient id (from database)
        $id_patient = $patient->id;
        // Get the active stage
        $stage = Stage::where('paciente_id', $id_patient)
            ->where('activa', 1)
            ->first();
        // If have no active stage
        if (empty($stage)) {
            // Call function 'showAddStage'
            return $this->showAddStage($id_patient);
        } else {
            // Get active functionarys
            $users = Functionary::where('activa', 1)->get();
            return view('general.attendanceForm', ['DNI' => $DNI])->with(compact('stage', 'users', 'patient'));
        }
    }
}
