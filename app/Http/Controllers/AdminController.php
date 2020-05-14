<?php

namespace App\Http\Controllers;

use App\Diagnosis;
use App\Functionary;
use App\Log;
use App\Patient;
use App\Stage;
use App\Provision;
use App\Speciality;
use App\FunctionarySpeciality;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    }

    /***************************************************************************************************************************
                                                    VIEWS FOR ADMIN ROLE ONLY
     ****************************************************************************************************************************/
    public function foo()
    {
        return false;
    }

    public function showEditFunctionaryInCharge($dni){
        $patient = Patient::where('DNI', $dni)->where('activa', 1)->get()->first();
        $functionarys = Functionary::where('activa', 1)->get();
        $medicals = array();
        foreach($functionarys as $functionary){
            foreach($functionary->speciality as $specialitys){
                if($specialitys->pivot->especialidad_id == 1){
                    array_push($medicals, $functionary);
                    break;
                }
            }
        }
        
        if($patient){
            $stages = $patient->stage;
            foreach($stages as $stage){
                if($stage->activa == 1){
                    $activeStage = $stage;
                    $medicalInCharge = $activeStage->functionary;
                    return view('admin.Edit.functionaryInChargeEdit', compact('medicalInCharge', 'patient', 'activeStage', 'medicals'));
                }else{
                    return redirect('ficha/' .$dni)->with('error', 'No se encontró ficha activa');
                }
            }
        }

        return redirect('pacientes')->with('error', 'No se encontró el paciente');

    }

    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editFunctionaryInCharge(Request $request)
    {
        $etapa = Stage::find($request->id_etapa);
        $etapa->funcionario_id = $request->medical_id;
        $etapa->save();

        return redirect('cambiar-medico/'.$request->dni)->with('status', 'Se cambió el medico a cargo');
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
                                                    LOGS PROCESS
     ****************************************************************************************************************************/
    public function addLog($logAction, $logItem, $logTable)
    {
        (is_array($logItem) ? $logItem = implode( ", ", $logItem ) : false);
        $user = Auth::user();
        $logAction = 'Transacción: ' . $logAction . "\r\n" . 'Tabla: ' . $logTable . "\r\n" . 'ID tupla: ' . $logItem . "\r\n" . 'Usuario: ' . $user->rut;
        $logs = new Log();
        $logs->descripcion = $logAction;
        $logs->user_id = $user->id;
        $logs->save();
        return $logs;
    }

    public function showLogs()
    {
        $logs = Log::latest()->get();
        foreach($logs as $data) $data->fecha = $data->created_at->format('d/m/Y - H:i');
        return view('admin.Views.logs', ['data' => $logs]);
    }
}
