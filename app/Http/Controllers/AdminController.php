<?php

namespace App\Http\Controllers;

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
    // View admissions
    public function showAdmissions()
    {
        $data = DB::table('etapa')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->join('programa', 'programa.id', '=', 'etapa.programa_id')
            ->join('prevision', 'prevision.id', '=', 'paciente.prevision_id')
            ->join('sigges', 'sigges.id', '=', 'etapa.sigges_id')
            ->join('diagnostico', 'diagnostico.id', '=', 'etapa.diagnostico_id')
            ->join('funcionarios', 'funcionarios.id', '=', 'etapa.funcionario_id')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->leftJoin('direccion', 'direccion.idPaciente', '=', 'paciente.id')
            ->leftJoin('atencion', 'atencion.etapa_id', '=', 'etapa.id')
            ->leftJoin('prestacion', 'prestacion.id', '=', 'atencion.prestacion_id')
            ->leftJoin('tipo_prestacion', 'tipo_prestacion.id', '=', 'prestacion.tipo_id')
            ->whereMonth('etapa.created_at', Carbon::now()->month)
            ->where('etapa.activa', 1)
            ->select(
                'etapa.id as numero_ficha',
                'paciente.DNI',
                'paciente.nombre1',
                'paciente.apellido1',
                'paciente.apellido2',
                'paciente.fecha_nacimiento',
                DB::raw("DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS edad"),
                'sexo.descripcion as sexo',
                'procedencia.descripcion as procedencia',
                'programa.descripcion as programa',
                'etapa.created_at as fecha_ingreso',
                'prevision.descripcion as prevision',
                DB::raw("(CASE WHEN lower(tipo_prestacion.descripcion) like '%ges%' THEN 'SI' ELSE 'NO' END) AS ges"),
                'sigges.descripcion as sigges',
                'diagnostico.descripcion as diagnostico',
                DB::raw("(CASE WHEN direccion.departamento IS NOT NULL
                THEN lower(CONCAT(direccion.calle,' #', direccion.numero , ' depto: ', direccion.departamento, ', ', direccion.comuna))
                ELSE (CASE WHEN direccion.calle IS NOT NULL
                THEN lower(CONCAT(direccion.calle,' #', direccion.numero, ', ', direccion.comuna))
                ELSE ' ' END) END) AS direccion"),
                DB::raw("CONCAT(users.primer_nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as medico")
            )
            ->distinct('etapa.id')
            ->orderBy('etapa.created_at')
            ->get();
        // Change date format
        foreach ($data as $record) {
            $dob = Carbon::createFromDate($record->fecha_nacimiento);
            $addmission_date = Carbon::createFromDate($record->fecha_ingreso);
            $record->fecha_nacimiento = $dob->format('d/m/Y');
            $record->fecha_ingreso = $addmission_date->format('d/m/Y');
        }
        // Return to the view
        return view('general.patientAdmissions', ['main' => json_encode($data)]);
    }
    // View discharges
    public function showDischarges()
    {
        $data = DB::table('etapa')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->join('programa', 'programa.id', '=', 'etapa.programa_id')
            ->join('prevision', 'prevision.id', '=', 'paciente.prevision_id')
            ->join('sigges', 'sigges.id', '=', 'etapa.sigges_id')
            ->join('diagnostico', 'diagnostico.id', '=', 'etapa.diagnostico_id')
            ->join('funcionarios', 'funcionarios.id', '=', 'etapa.funcionario_id')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->join('alta', 'alta.id', '=', 'etapa.alta_id')
            ->leftJoin('direccion', 'direccion.idPaciente', '=', 'paciente.id')
            ->leftJoin('atencion', 'atencion.etapa_id', '=', 'etapa.id')
            ->leftJoin('prestacion', 'prestacion.id', '=', 'atencion.prestacion_id')
            ->leftJoin('tipo_prestacion', 'tipo_prestacion.id', '=', 'prestacion.tipo_id')
            ->whereMonth('etapa.created_at', Carbon::now()->month)
            ->where('etapa.activa', 0)
            ->select(
                'alta.created_at as fecha_egreso',
                'alta.descripcion as alta',
                'etapa.id as numero_ficha',
                'paciente.DNI',
                'paciente.nombre1',
                'paciente.apellido1',
                'paciente.apellido2',
                'paciente.fecha_nacimiento',
                DB::raw("DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS edad"),
                'sexo.descripcion as sexo',
                'procedencia.descripcion as procedencia',
                'programa.descripcion as programa',
                'etapa.created_at as fecha_ingreso',
                'prevision.descripcion as prevision',
                DB::raw("(CASE WHEN lower(tipo_prestacion.descripcion) like '%ges%' THEN 'SI' ELSE 'NO' END) AS ges"),
                'sigges.descripcion as sigges',
                'diagnostico.descripcion as diagnostico',
                DB::raw("(CASE WHEN direccion.departamento IS NOT NULL
                THEN lower(CONCAT(direccion.calle,' #', direccion.numero , ' depto: ', direccion.departamento, ', ', direccion.comuna))
                ELSE (CASE WHEN direccion.calle IS NOT NULL
                THEN lower(CONCAT(direccion.calle,' #', direccion.numero, ', ', direccion.comuna))
                ELSE ' ' END) END) AS direccion"),
                DB::raw("CONCAT(users.primer_nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as medico")
            )
            ->distinct('etapa.id')
            ->orderBy('etapa.created_at')
            ->get();
        // Change date format
        foreach ($data as $record) {
            $dob = Carbon::createFromDate($record->fecha_nacimiento);
            $addmission_date = Carbon::createFromDate($record->fecha_ingreso);
            $discharge_date = Carbon::createFromDate($record->fecha_egreso);
            $record->fecha_nacimiento = $dob->format('d/m/Y');
            $record->fecha_ingreso = $addmission_date->format('d/m/Y');
            $record->fecha_egreso = $discharge_date->format('d/m/Y');
        }
        // Return to the view
        return view('general.patientDischarges', ['main' => json_encode($data)]);
    }
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
        $data = DB::table('atencion')
            ->join('funcionarios', 'atencion.funcionario_id', '=', 'funcionarios.id')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->where('funcionarios.activa', 1)
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
        // Some variables
        $end = 80;
        $interval = 5;
        $list = [];
        $data = [];
        // Get total data
        $query = $this->remQuery2();
        // Get base data
        $queryOriginal = $this->remQuery1();
        // Creating usseful data
        foreach ($queryOriginal as $record1) {
            // Create necesary object
            $obj = new \stdClass();
            // Pass common data
            $obj->actividad = $record1->actividad;
            $obj->especialidad = $record1->especialidad;
            $obj->Ambos = $record1->Ambos;
            $obj->Hombres = $record1->Hombres;
            $obj->Mujeres = $record1->Mujeres;
            $iterator = 0;
            // Generate data for list
            while ($iterator < $end) {
                $str = $iterator . " - " . ($iterator + $interval - 1);
                if (!in_array($str, $list)) {
                    array_push($list, $str);
                }
                // Put come default data
                $strH = $str . " - H";
                $strM = $str . " - M";
                $obj->$strH =  0;
                $obj->$strM =  0;
                // Generate real data to use on view
                foreach ($query as $record2) {
                    // Get age of patient to number
                    $age = Carbon::parse($record2->fecha)->age;
                    // Get 
                    /*
                        Check match between queryOriginal and query
                        To sure the data is the correct to upgrade
                        And we check if is in range of age (range are in list[])
                    */
                    if (
                        $record1->actividad == $record2->actividad
                        && $record1->especialidad == $record2->especialidad
                        &&  $age >= $iterator && $age <= ($iterator + $interval - 1)
                    ) {
                        $obj->$strH = $obj->$strH + $record2->Hombres;
                        $obj->$strM = $obj->$strM + $record2->Mujeres;
                    }
                }
                $iterator = $iterator + $interval;
            }
            $str = $iterator . " - más";
            if (!in_array($str, $list)) {
                array_push($list, $str);
            }
            // More default data for last range
            $strH = $str . " - H";
            $strM = $str . " - M";
            $obj->$strH = 0;
            $obj->$strM = 0;
            // Do the same for the last range (last value in list[])
            foreach ($query as $record2) {
                // Age of patient to number
                $age = Carbon::parse($record2->fecha)->age;
                /*
                    Check match between queryOriginal and query
                    To sure the data is the correct to upgrade
                    And we check if is in range of age (range are in list[])
                */
                if (
                    $record1->actividad == $record2->actividad
                    && $record1->especialidad == $record2->especialidad
                    &&  $age >= $iterator
                ) {
                    $obj->$strH = $obj->$strH + $record2->Hombres;
                    $obj->$strM = $obj->$strM + $record2->Mujeres;
                }
            }
            // Get count of unique patient attended
            $uniques = [];
            $sename = [];
            $obj->Beneficiarios = 0;
            $obj->menoresSENAME = 0;
            foreach ($query as $record2) {
                $age = Carbon::parse($record2->fecha)->age;
                if (
                    $record1->actividad == $record2->actividad
                    && $record1->especialidad == $record2->especialidad
                ) {
                    if (!in_array($record2->DNI, $uniques)) {
                        array_push($uniques, $record2->DNI);
                    }
                    if (
                        !in_array($record2->DNI, $sename)
                        && $age < 19
                        // && $record2->sename == 'Si'
                    ) {
                        array_push($sename, $record2->DNI);
                    }
                }
            }
            $obj->Beneficiarios = count($uniques);
            $obj->menoresSENAME = count($sename);
            // Adding object to array
            array_push($data, $obj);
        }

        return view('general.recordsRem', compact('data', 'list'));
    }
    // Query with group especiality and functionary name
    public function remQuery1()
    {
        $data =  DB::table('atencion')
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
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%hombre%' THEN 1 ELSE 0 END) AS Hombres"),
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%mujer%' THEN 1 ELSE 0 END) AS Mujeres"),
                DB::raw("COUNT(atencion.asistencia) AS Ambos")
            )
            ->groupBy('actividad.descripcion', 'especialidad.descripcion')
            ->orderBy('actividad.descripcion')
            ->get();

        return $data;
    }
    // Query with group especiality, functionary name and patient birthdate (more rows)
    public function remQuery2()
    {
        $data =  DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->where('atencion.asistencia', 1)
            ->select(
                'paciente.fecha_nacimiento as fecha',
                'paciente.DNI as DNI',
                'especialidad.descripcion as especialidad',
                'actividad.descripcion as actividad',
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%hombre%' THEN 1 ELSE 0 END) AS Hombres"),
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%mujer%' THEN 1 ELSE 0 END) AS Mujeres"),
                DB::raw("COUNT(atencion.asistencia) AS Ambos")
            )
            ->groupBy('actividad.descripcion', 'especialidad.descripcion', 'paciente.fecha_nacimiento', 'paciente.DNI')
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
