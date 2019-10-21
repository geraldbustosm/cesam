<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Address;
use App\Attendance;
use App\Attributes;
use App\Diagnosis;
use App\Functionary;
use App\FunctionarySpeciality;
use App\Patient;
use App\Prevition;
use App\Program;
use App\Provenance;
use App\Provision;
use App\Release;
use App\User;
use App\SiGGES;
use App\Sex;
use App\Speciality;
use App\Stage;
use App\Type;
use App\TypeSpeciality;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkrole:1');
    }

    public function foo($speId,$actId,$asist,$attendanceID){

        

        if(TypeSpeciality::where('especialidad_id', $speId)->count() > 0):
        if (Activity::find($actId)->where('actividad_abre_canasta',1)->count() > 0);
        if ($asist=1){};

    }
}