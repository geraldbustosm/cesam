<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Functionary;
use App\FunctionarySpeciality;
use App\Patient;
use App\Prevition;
use App\Sex;
use App\Speciality;
use App\Stage;
use App\User;

class GeneralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    VIEWS FOR GENERAL USER
     ****************************************************************************************************************************/
    // Inicio
    public function index()
    {
        // Redirect to the view
        return view('general.home');
    }
    // Paciente
    public function showPatients()
    {
        // Get patients from database where 'activa' attribute is 1 bits
        $patients = Patient::where('activa', 1)
            ->select('DNI', 'nombre1', 'nombre2', 'apellido1', 'apellido2', 'fecha_nacimiento', 'prevision_id', 'sexo_id', 'activa')
            ->get();
        // Count patients
        $cantPatients = $patients->count();
        // Get the list of previtions
        $prev = Prevition::all();
        // Get the list of genders
        $sex = Sex::all();
        // Redirect to the view with list of: active patients, all previtions and all genders
        return view('general.patient', compact('patients', 'prev', 'sex', 'cantPatients'));
    }
    // Funcionario
    public function showFunctionarys()
    {
        // Get functionarys from database where 'activa' attribute is 1 bits
        $functionary = Functionary::where('activa', 1)->get();
        // Get the list of users
        $user = User::all();
        // Get the list of specialitys
        $speciality = Speciality::all();
        // Get the list of speciality per functionary
        $fs = FunctionarySpeciality::all();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('general.functionarys', compact('functionary', 'user', 'speciality', 'fs'));
    }
    // Ficha
    public function showClinicalRecords($DNI)
    {
        // Get patient
        $patient = Patient::where('DNI', $DNI)->first();
        // Get patient id
        $patient_id = $patient->id;
        // Get the stage
        $stage = Stage::where('paciente_id', $patient_id)
                    ->where('activa', 1)
                    ->first();
        // If have no active stage
        if (empty($stage)) {
            return redirect(url()->previous())->with('error', 'Debe agregar una nueva etapa');
        } else {
            // Get array of attendance from the active stage
            $patientAtendances = $stage->attendance;
            // Identify active stage
            $activeStage = $stage->id;
            // Redirect to the view with successful status
            return view('General.clinicalRecords', compact('patient', 'stage', 'patientAtendances', 'activeStage'));
        }
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function stagesPerPatient(Request $request){
        $id = $request->id;
        // $stages = Stage::where('paciente_id', $id)->where('activa', 0)->select('id');
        $stages = Stage::all();
        return response()->json($stages);  
    }

    public function selectStage(Request $request){
        $patient_id = $request->id;
        $patient = Patient::find($patient_id);
        $stage_id = $request->stages;
        $stage = Stage::find($stage_id);
        $patientAtendances = $stage->attendance;
        $activeStage = Stage::where('paciente_id', $patient_id)
                            ->where('activa', 1)
                            ->select('id')
                            ->first();
        return view('General.clinicalRecords', compact('patient', 'stage', 'patientAtendances', 'activeStage'));
    }
}
