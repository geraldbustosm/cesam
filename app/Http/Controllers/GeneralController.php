<?php

namespace App\Http\Controllers;

use App\Functionary;
use App\FunctionarySpeciality;
use App\Patient;
use App\Prevition;
use App\Release;
use App\Sex;
use App\Speciality;
use App\Stage;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    VIEWS FOR GENERAL USER
     ****************************************************************************************************************************/
    // Home
    public function index()
    {
        if(Auth::user()->rol == 1) return view('general.home');
        else return view('general.mision');
    }
    // Patients view (list)
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
    // Functionarys view (list)
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
    // Stage view
    public function showClinicalRecords($DNI)
    {
        // Get patient
        $patient = Patient::where('DNI', $DNI)->first();
        // Get the stage
        $stage = Stage::where('paciente_id', $patient->id)
            ->where('activa', 1)
            ->first();
        // If have no active stage
        if (empty($stage)) {
            return redirect(url()->previous())->with('error', 'Debe agregar una nueva etapa');
        } else {
            // Get attributes
            $attributes = "";
            foreach ($patient->attributes as $index) {
                $attributes = $index->descripcion . ", " . $attributes;
            }
            // Get diagnosis
            $diagnosis = "";
            foreach ($stage->diagnosis as $index) {
                $diagnosis = $index->descripcion . ", " . $diagnosis;
            }
            // Get array of attendance from the active stage
            $patientAttendances = $stage->attendance;
            // Identify active stage
            $activeStage = $stage;
            if ($stage->pci) $stage->PCI = Carbon::createFromDate($stage->pci)->format('d/m/Y');
            // Redirect to the view with successful status
            return view('general.clinicalRecords', compact('patient', 'stage', 'patientAttendances', 'activeStage', 'attributes', 'diagnosis'));
        }
    }
    /***************************************************************************************************************************
                                                    STAGE PROCESS
     ****************************************************************************************************************************/
    // Stage per patient
    public function stagesPerPatient(Request $request)
    {
        $id = $request->id;
        $stages = Stage::where('paciente_id', $id)->where('activa', 0)->orderBy('created_at', 'desc')->get();
        return response()->json($stages);
    }
    // Selec stage by dropbox from view
    public function selectStage(Request $request)
    {
        // Get the patient
        $patient_id = $request->id;
        $patient = Patient::find($patient_id);
        // Get the stage
        $stage_id = $request->stages;
        $stage = Stage::find($stage_id);
        $patientAttendances = $stage->attendance;
        $activeStage = Stage::where('paciente_id', $patient_id)
            ->where('activa', 1)
            ->select('id')
            ->first();
        // Get attributes
        $attributes = "";
        foreach ($patient->attributes as $index) {
            $attributes = $attributes . ", " . $index->descripcion;
        }
        // Get diagnosis
        $diagnosis = "";
        foreach ($stage->diagnosis as $index) {
            $diagnosis = $diagnosis . ", " . $index->descripcion;
        }
        if ($stage->pci) $stage->PCI = Carbon::createFromDate($stage->pci)->format('d/m/Y');
        return view('general.clinicalRecords', compact('patient', 'stage', 'patientAttendances', 'activeStage', 'attributes', 'diagnosis'));
    }
    /***************************************************************************************************************************
                                                    RELEASE PROCESS
     ****************************************************************************************************************************/
    // View for release
    public function showAddRelease($DNI)
    {
        $DNI = $DNI;
        $release = Release::where('activa', 1)->orderBy('descripcion')->get();
        return view('general.clinicalRelease', compact('DNI', 'release'));
    }
    // Release patient (deactivate Stage)
    public function addRelease(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'DNI' => 'required|string|max:255',
            'releases' => 'required|integer|max:255'
        ]);
        $DNI = $request->DNI;
        $patient = Patient::where('DNI', $DNI)->first();
        $patient_id = $patient->id;
        $stage = Stage::where('paciente_id', $patient_id)
            ->where('activa', 1)
            ->first();
        if (empty($stage)) {
            return app('App\Http\Controllers\StageController')->showAddStage($patient_id);
        } else {
            $stage->activa = 0;
            $stage->alta_id = $request->releases;
            $stage->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Dar alta', $stage->id, $stage->table);
            return redirect('/pacientes')->with('status', 'Paciente ' . $DNI . ' fue dado de alta');
        }
    }
}
