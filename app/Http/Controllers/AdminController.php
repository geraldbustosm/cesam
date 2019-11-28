<?php

namespace App\Http\Controllers;

use App\Activity;
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
    // View per month
    public function showMonthlyRecords()
    {
        $data = DB::table('paciente')
            ->join('prevision', 'paciente.prevision_id', '=', 'prevision.id')
            ->join('sexo', 'paciente.sexo_id', '=', 'sexo.id')
            ->join('etapa', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('atencion', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->join('funcionarios', 'funcionarios.id', '=', 'atencion.funcionario_id')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'funcionarios.id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->join('prestacion', 'prestacion.id', '=', 'atencion.prestacion_id')
            ->join('tipo_prestacion', 'tipo_prestacion.id', '=', 'prestacion.tipo_id')
            ->join('programa', 'programa.id', '=', 'etapa.programa_id')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->where('paciente.activa', '=', 1)
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->select(
                'prevision.descripcion as prevision',
                'atencion.fecha',
                'actividad.descripcion as actividad',
                'procedencia.descripcion as procedencia',
                'especialidad.descripcion as especialidad',
                'sexo.descripcion as sexo',
                'tipo_prestacion.descripcion as tipo',
                'programa.descripcion as programa',
                'prestacion.glosaTrasadora',
                'prestacion.ps_fam',
                'prestacion.codigo',
                'paciente.DNI',
                'paciente.nombre1',
                'paciente.apellido1',
                'paciente.apellido2',
                'paciente.fecha_nacimiento',
                DB::raw("(CASE WHEN atencion.abre_canasta = 1 THEN 'SI' ELSE 'NO' END) AS canasta"),
                DB::raw("(CASE WHEN atencion.asistencia = 1 THEN 'SI' ELSE 'NO' END) AS asistencia"),
                DB::raw("CONCAT(users.primer_nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as nombre_funcionario")
            )
            ->selectRaw('DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS edad')
            ->get();
        // Change date format
        foreach ($data as $record) {
            // die($record->fecha_nacimiento);
            $dob = Carbon::createFromDate($record->fecha_nacimiento);
            $date = Carbon::createFromDate($record->fecha);
            $record->fecha_nacimiento = $dob->format('d/m/Y');
            $record->fecha = $date->format('d/m/Y');
        }
        // Return to the view
        return view('general.recordsMonthly', ['main' => json_encode($data)]);
    }
    // View for month summary
    public function showSummaryRecords()
    {
        // Get data
        $data = DB::table('funcionarios')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->join('atencion', 'atencion.funcionario_id', '=', 'funcionarios.id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->select(
                'actividad.descripcion as actividad',
                DB::raw("CONCAT(users.primer_nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as nombre_funcionario"),
                DB::raw("SUM(CASE WHEN atencion.asistencia = 0 THEN 1 ELSE 0 END) AS Sin_Asistencia"),
                DB::raw("SUM(CASE WHEN atencion.asistencia = 1 THEN 1 ELSE 0 END) AS Con_Asistencia")
            )
            ->groupBy('actividad.descripcion', 'users.primer_nombre', 'users.apellido_paterno', 'users.apellido_materno')
            ->get();
        // Get functionarys and activities
        $functionarys = $data->unique('nombre_funcionario');
        $activities = $data->unique('actividad');
        $table = [];
        foreach ($activities as $index1) {
            $obj = new \stdClass();
            $obj->actividad = $index1->actividad;
            foreach ($functionarys as $index2) {
                $strYes = $index2->nombre_funcionario . '-si';
                $obj->$strYes = 0;
                $strNo = $index2->nombre_funcionario . '-no';
                $obj->$strNo = 0;
                foreach ($data as $record) {
                    if ($record->nombre_funcionario == $index2->nombre_funcionario && $record->actividad == $index1->actividad) {
                        $obj->$strYes = (int) $record->Con_Asistencia;
                        $obj->$strNo = (int) $record->Sin_Asistencia;
                    }
                }
            }
            array_push($table, $obj);
        }
        // Return to the view
        return view('general.recordsSummary', compact('functionarys', 'table'));
    }
    // View for REM
    public function showRemRecords()
    {
        // Get data of total
        $totaldata = DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->where('atencion.asistencia', 1)
            ->select(
                'especialidad.descripcion as especialidad',
                'actividad.descripcion as actividad',
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like 'hombre' THEN 1 ELSE 0 END) AS Hombres"),
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like 'mujer' THEN 1 ELSE 0 END) AS Mujeres"),
                DB::raw("COUNT(atencion.asistencia) AS Ambos")
            )
            ->groupBy('actividad.descripcion', 'especialidad.descripcion')
            ->orderBy('actividad.descripcion')
            ->get();
        $list = [];
        $data = [];
        $end = 80;
        $interval = 5;
        foreach ($totaldata as $record) {
            // Create necesary objects
            $obj = new \stdClass();
            $obj->actividad = $record->actividad;
            $obj->especialidad = $record->especialidad;
            $obj->Hombres = $record->Hombres;
            $obj->Mujeres = $record->Mujeres;
            $obj->Ambos = $record->Ambos;
            $iterator = 0;
            while ($iterator < $end) {
                $str = $iterator . " - " . ($iterator + $interval - 1);
                $strH = $str  . " - H";
                $strM = $str  . " - M";
                $obj->$strH = 0;
                $obj->$strM = 0;
                $counts = $this->count($iterator, $iterator + $interval - 1, $record->especialidad, $record->actividad);
                foreach ($counts as $record2) {
                    if ($counts->especialidad == $record->especialidad && $counts->actividad == $record->actividad) {
                        $obj->$strH = (int) $record2->Hombres;
                        $obj->$strM = (int) $record2->Mujeres;
                    }
                }
                $iterator = $iterator + $interval;
                if (!in_array($str, $list)) {
                    array_push($list, $str);
                }
            }
            $str = $iterator . " - más";
            if (!in_array($str, $list)) {
                array_push($list, $str);
            }
            $strH = $str . " - H";
            $strM = $str . " - M";
            $obj->$strH = 0;
            $obj->$strM = 0;
            $counts = $this->count($iterator, 300, $record->especialidad, $record->actividad);
            foreach ($counts as $record2) {
                if ($counts->especialidad == $record->especialidad && $counts->actividad == $record->actividad) {
                    $obj->$strH = (int) $record2->Hombres;
                    $obj->$strM = (int) $record2->Mujeres;
                }
            }
            // Adding object to array
            array_push($data, $obj);
        }

        return view('general.recordsRem', compact('totaldata', 'list', 'data'));
    }
    // count
    public function count($min, $max, $sp, $act)
    {
        $from = Carbon::now()->subYears($max - 1)->addDays(1);
        $to = Carbon::now()->subYears($min - 1)->addDays(1);
        // Get data
        $data = DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereBetween('paciente.fecha_nacimiento', [$from, $to])
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->where('atencion.asistencia', 1)
            ->where('especialidad.descripcion', $sp)
            ->where('actividad.descripcion', $act)
            ->select(
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like 'hombre' THEN 1 ELSE 0 END) AS Hombres"),
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like 'mujer' THEN 1 ELSE 0 END) AS Mujeres")
            )
            ->groupBy('actividad.descripcion', 'especialidad.descripcion')
            ->orderBy('actividad.descripcion')
            ->get();
        return $data;
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
