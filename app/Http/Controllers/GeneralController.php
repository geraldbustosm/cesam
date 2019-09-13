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
                                                    VIEWS (GET METHOD)
     ****************************************************************************************************************************/
    // Inicio
     public function index()
    {
        return view('general.home');
    }
    // Paciente
    public function showPatients()
    {
        $patients = Patient::where('activa', 1)->get();
        $prev = Prevition::all();
        $sex = Sex::all();
        return view('general.patient', compact('patients', 'prev', 'sex'));
    }
    // Funcionario
    public function showFunctionarys()
    {
        $functionary = Functionary::where('activa', 1)->get();
        $user = User::all();
        $speciality = Speciality::all();
        $fs = FunctionarySpeciality::all();
        return view('general.functionarys', compact('functionary', 'user', 'speciality', 'fs'));
    }
}
