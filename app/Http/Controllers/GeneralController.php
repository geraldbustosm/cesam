<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Functionary;
use App\Speciality;
use App\FunctionarySpeciality;
use App\User;
use App\Sex;
use App\Prevition;

use App\Attendance;
use App\Stage;

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
                            ->select('DNI','nombre1','nombre2','apellido1','apellido2','fecha_nacimiento','prevision_id','sexo_id','activa')
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
        $patient = Patient::where('DNI', $DNI)->get();
        // Set patient out of array (like object)
        $patient = $patient[0];
        // Get patient id
        $patient_id = $patient->id;
        // Get the stage
        $stage = Stage::where('paciente_id', $patient_id)
            ->where('activa', 1)
            ->get();
        // Set stage like object
        $stage = $stage[0];
        // Get array of attendance from the active stage
        $patientAtendances = $stage->attendance;
        // Redirect to the view with successful status
        return view('admin.Views.clinicalRecords', compact('patient', 'stage', 'patientAtendances'));
    }
}
