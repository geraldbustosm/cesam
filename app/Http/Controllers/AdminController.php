<?php

namespace App\Http\Controllers;

use App\Diagnosis;
use App\Functionary;
use App\Patient;
use App\Provision;
use App\Speciality;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /***************************************************************************************************************************
                                                    VIEWS FOR ADMIN ROLE ONLY
     ****************************************************************************************************************************/
    public function foo()
    {
        return false;
    }
    /***************************************************************************************************************************
                                                    HELPERS AND LOGIC FUNCTIONS
     ****************************************************************************************************************************/
    // Check for a speciality linked to the functionary (parameter)
    // Called from specialityAsign
    public static function existFunctionarySpeciality($idFunct, $idSp)
    {
        // Create boolean variable
        $value = false;
        // Query to check if speciality have a functionary
        $doesClientHaveProduct = Speciality::where('id', $idSp)
            ->whereHas('functionary', function ($q) use ($idFunct) {
                $q->where('dbo.funcionarios.id', $idFunct);
            })
            ->count();
        // If found it then change the boolean as True
        if ($doesClientHaveProduct) {
            $value = true;
        }
        // Return the boolean
        return $value;
    }
    // Check for a speciality linked to the functionary (parameter)
    // Called from specialityAsign
    public static function existTypeSpeciality($idSp, $idType)
    {
        // Create boolean variable
        $value = false;
        // Query to check if speciality have a functionary
        $doesSpecialityHaveType = Speciality::where('id', $idSp)
            ->whereHas('type', function ($q) use ($idType) {
                $q->where('dbo.tipo_prestacion.id', $idType);
            })
            ->count();
        // If found it then change the boolean as True
        if ($doesSpecialityHaveType) {
            $value = true;
        }
        // Return the boolean
        return $value;
    }
    // Check activity for the speciality (parameter)
    // Called from activityAsign
    public static function existActivitySpeciality($idprov, $idSp)
    {
        // Create boolean variable
        $value = false;
        // Query to check if speciality have a activity
        $doesActivityHaveSpeciality = Speciality::where('id', $idSp)
            ->whereHas('activity', function ($q) use ($idprov) {
                $q->where('dbo.actividad.id', $idprov);
            })
            ->count();
        // If found it then change the boolean as True
        if ($doesActivityHaveSpeciality) {
            $value = true;
        }
        // Return the boolean
        return $value;
    }
    // Check provision for the speciality (parameter)
    // Called from provisionAsign
    public static function existProvisionSpeciality($idprov, $idSp)
    {
        // Create boolean variable
        $value = false;
        // Query to check if speciality have a provision
        $doesProvisionHaveSpeciality = Speciality::where('id', $idSp)
            ->whereHas('provision', function ($q) use ($idprov) {
                $q->where('dbo.prestacion.id', $idprov);
            })
            ->count();
        // If found it then change the boolean as True
        if ($doesProvisionHaveSpeciality) {
            $value = true;
        }
        // Return the boolean
        return $value;
    }
    /***************************************************************************************************************************
                                                    ATTENDANCE LOGIC
     ****************************************************************************************************************************/
    // Return a specialitys from one functionary
    public function getSpecialityPerFunctionary(Request $request)
    {
        // Get the functionary
        $functionary = Functionary::find($request->functionary_id);
        // Create a variable for send to the view
        $speciality = $functionary->speciality;
        // Return specialitys
        return response()->json($speciality);
    }
    // Return a provisions from one speciality
    public function getProvisionPerSpeciality(Request $request)
    {
        // Get the speciality
        $specility = Speciality::find($request->speciality_id);
        // Create a variable for send to the view
        $provision = $specility->provision;
        // Return provisions
        return response()->json($provision);
    }
    // Return a activitys from one speciality
    public function getActivityPerSpeciality(Request $request)
    {
        // Get the speciality
        $specility = Speciality::find($request->speciality_id);
        // Create a variable for send to the view
        $activity = $specility->activity;
        // Return activity's
        return response()->json($activity);
    }
    // Compare age of patient and range age of provision
    public function checkAge(Request $request)
    {
        // Get the patient
        $patient = Patient::find(1);
        // Get the provision
        $provision = Provision::find($request->provision_id);
        // Get age fo patient
        $years = Carbon::parse($patient->fecha_nacimiento)->age;
        // Get ranges
        $inf = $provision->rangoEdad_inferior;
        $sup = $provision->rangoEdad_superior;
        // Default
        $response = 0;
        // Check if age is in range
        if (($inf <= $years) && ($years <= $sup)) {
            $response = 1;
        } else {
            $response = -1;
        }
        if (($inf == 0) && ($sup == 0)) {
            $response = 1;
        }
        // Return provisions
        return response()->json($response);
    }
}
