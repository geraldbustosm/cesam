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

class GeneralController extends Controller
{
    public function __construct(){
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
        $patients = Patient::where('activa', 1)->get();
        // Get the list of previtions
        $prev = Prevition::all();
        // Get the list of genders
        $sex = Sex::all();
        // Redirect to the view with list of: active patients, all previtions and all genders
        return view('general.patient', compact('patients', 'prev', 'sex'));
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
}
