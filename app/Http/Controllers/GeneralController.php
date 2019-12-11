<?php

namespace App\Http\Controllers;

use App\Diagnosis;
use App\Functionary;
use App\FunctionarySpeciality;
use App\Patient;
use App\Prevition;
use App\Program;
use App\Provenance;
use App\Release;
use App\Sex;
use App\Speciality;
use App\Stage;
use App\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Redirect to the view
        return view('general.home');
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
            $patientAttendances = $stage->attendance;
            // Identify active stage
            $activeStage = $stage;
            // Redirect to the view with successful status
            return view('general.clinicalRecords', compact('patient', 'stage', 'patientAttendances', 'activeStage'));
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
        $patient_id = $request->id;
        $patient = Patient::find($patient_id);
        $stage_id = $request->stages;
        $stage = Stage::find($stage_id);
        $patientAttendances = $stage->attendance;
        $activeStage = Stage::where('paciente_id', $patient_id)
            ->where('activa', 1)
            ->select('id')
            ->first();
        return view('general.clinicalRecords', compact('patient', 'stage', 'patientAttendances', 'activeStage'));
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
            $url = "alta/" . $DNI;
            return redirect($url)->with('status', 'Paciente ' . $DNI . ' fue dado de alta');
        }
    }
    /***************************************************************************************************************************
                                                    MONTHLY INFO
     ****************************************************************************************************************************/
    // View per month
    public function showMonthlyRecords()
    {
        $data = $this->queryMonthly();
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
    // Query for monthly records
    public function queryMonthly()
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
        return $data;
    }
    /***************************************************************************************************************************
                                                    SUMMARY INFO
     ****************************************************************************************************************************/
    // View for month summary
    public function showSummaryRecords()
    {
        // Get data
        $data = $this->querySummary();
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
    // Query for summary info
    public function querySummary()
    {
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
        return $data;
    }
    /***************************************************************************************************************************
                                                    REM INFO
     ****************************************************************************************************************************/
    // View for REM
    public function showRemRecords()
    {
        // Some variables
        $end = 80;
        $interval = 5;
        $list = [];
        // Get base data
        $data = $this->queryRem1();
        // Get helper data
        $query = $this->queryRem2();
        // Creating usseful data
        foreach ($data as $record1) {
            $iterator = 0;
            // Generate data for list
            while ($iterator < $end) {
                $str = $iterator . " - " . ($iterator + $interval - 1);
                (!in_array($str, $list) ? array_push($list, $str) : false);
                // Put come default data
                $strH = $str . " - H";
                $strM = $str . " - M";
                $record1->$strH =  0;
                $record1->$strM =  0;
                // Generate real data to use on view
                foreach ($query as $record2) {
                    /*
                        Check match between queryOriginal and query
                        To sure the data is the correct to upgrade
                        And we check if is in range of age (range are in list[])
                    */
                    if (
                        $record1->actividad == $record2->actividad
                        && $record1->especialidad == $record2->especialidad
                        &&  $record2->age >= $iterator && $record2->age <= ($iterator + $interval - 1)
                    ) {
                        $record1->$strH = $record1->$strH + $record2->Hombres;
                        $record1->$strM = $record1->$strM + $record2->Mujeres;
                    }
                }
                $iterator = $iterator + $interval;
            }
            $str = $iterator . " - más";
            (!in_array($str, $list) ? array_push($list, $str) : false);
            // More default data for last range
            $strH = $str . " - H";
            $strM = $str . " - M";
            $record1->$strH = 0;
            $record1->$strM = 0;
            // Do the same for the last range (last value in list[])
            foreach ($query as $record2) {
                /*
                    Check match between queryOriginal and query
                    To sure the data is the correct to upgrade
                    And we check if is in range of age (range are in list[])
                */
                if (
                    $record1->actividad == $record2->actividad
                    && $record1->especialidad == $record2->especialidad
                    &&  $record2->age >= $iterator
                ) {
                    $record1->$strH = $record1->$strH + $record2->Hombres;
                    $record1->$strM = $record1->$strM + $record2->Mujeres;
                }
            }
            // Get count of unique patient attended
            $uniques = [];
            $sename = [];
            $record1->Beneficiarios = 0;
            $record1->menoresSENAME = 0;
            foreach ($query as $record2) {
                if ($record1->actividad == $record2->actividad && $record1->especialidad == $record2->especialidad) {
                    if (!in_array($record2->DNI, $sename) && $record2->age < 18) {
                        // && $record2->sename == 'Si'
                        array_push($sename, $record2->DNI);
                    }
                    (!in_array($record2->DNI, $uniques) ? array_push($uniques, $record2->DNI) : false);
                }
            }
            $record1->Beneficiarios = count($uniques);
            $record1->menoresSENAME = count($sename);
        }
        // Return to the view
        return view('general.recordsRem', compact('data', 'list'));
    }
    // Query original for REM
    public function queryRem1()
    {
        $data = DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->where(function ($query) {
                $query->where('atencion.asistencia', 1)
                    ->orWhere('actividad.sin_asistencia', 1);
            })
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
    // Query helper for REM
    public function queryRem2()
    {
        $data = DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->where(function ($query) {
                $query->where('atencion.asistencia', 1)
                    ->orWhere('actividad.sin_asistencia', 1);
            })
            ->select(
                'paciente.fecha_nacimiento as fecha',
                'paciente.DNI as DNI',
                'especialidad.descripcion as especialidad',
                'actividad.descripcion as actividad',
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%hombre%' THEN 1 ELSE 0 END) AS Hombres"),
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%mujer%' THEN 1 ELSE 0 END) AS Mujeres"),
                DB::raw("COUNT(atencion.asistencia) AS Ambos"),
                DB::raw('DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS age')
            )
            ->groupBy('actividad.descripcion', 'especialidad.descripcion', 'paciente.fecha_nacimiento', 'paciente.DNI')
            ->orderBy('actividad.descripcion')
            ->get();
        return $data;
    }
    /***************************************************************************************************************************
                                                    REPORTS
     ****************************************************************************************************************************/
    // View Admission/Discharge
    public function showAdmissionDischarge()
    {
        $url = explode("/", url()->current());
        $currUrl = strtolower($url[count($url) - 1]);
        ($currUrl == "ingresos" ? $data = $this->infoQuery1(1) : $data = $this->infoQuery1(2));
        $allDiagnosis = DB::table('etapa_posee_diagnostico')
            ->join('diagnostico', 'diagnostico.id', '=', 'etapa_posee_diagnostico.diagnostico_id')
            ->get();
        $list = [];
        // Change date format
        foreach ($data as $record) {
            $dob = Carbon::createFromDate($record->fecha_nacimiento);
            $addmission_date = Carbon::createFromDate($record->fecha_ingreso);
            $discharge_date = Carbon::createFromDate($record->fecha_egreso);
            $record->fecha_nacimiento = $dob->format('d/m/Y');
            $record->fecha_ingreso = $addmission_date->format('d/m/Y');
            $record->fecha_egreso = $discharge_date->format('d/m/Y');
            $num = 0;
            foreach ($allDiagnosis as $index) {
                if ($record->numero_ficha == $index->etapa_id) {
                    $str = "diagnostico_" . $num;
                    (!in_array($str, $list) ? array_push($list, $str) : false);
                    $record->$str = $index->descripcion;
                    $num++;
                }
            }
        }
        // Return to the view
        if ($currUrl == "ingresos") {
            return view('general.patientAdmissions', compact('data', 'list'));
        } else {
            return view('general.patientDischarges', compact('data', 'list'));
        }
    }
    // Query with with all info of admission/discharges
    public function infoQuery1($status)
    {
        $data =  DB::table('etapa')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->join('programa', 'programa.id', '=', 'etapa.programa_id')
            ->join('sigges', 'sigges.id', '=', 'etapa.sigges_id')
            ->join('funcionarios', 'funcionarios.id', '=', 'etapa.funcionario_id')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('prevision', 'prevision.id', '=', 'paciente.prevision_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->leftJoin('alta', 'alta.id', '=', 'etapa.alta_id')
            ->leftJoin('direccion', 'direccion.idPaciente', '=', 'paciente.id')
            ->leftJoin('atencion', 'atencion.etapa_id', '=', 'etapa.id')
            ->leftJoin('prestacion', 'prestacion.id', '=', 'atencion.prestacion_id')
            ->leftJoin('tipo_prestacion', 'tipo_prestacion.id', '=', 'prestacion.tipo_id')
            ->when($status, function ($query, $status) {
                if ($status == 2) {
                    return $query->whereMonth('alta.created_at', Carbon::now()->month)->where('etapa.activa', 0);
                } else if ($status == 1) {
                    return $query->whereMonth('etapa.created_at', Carbon::now()->month);
                }
            })
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
                DB::raw("(CASE WHEN direccion.departamento IS NOT NULL
                    THEN lower(CONCAT(direccion.calle,' #', direccion.numero , ' depto: ', direccion.departamento, ', ', direccion.comuna))
                    ELSE (CASE WHEN direccion.calle IS NOT NULL
                    THEN lower(CONCAT(direccion.calle,' #', direccion.numero, ', ', direccion.comuna))
                    ELSE 'Sin dirección' END) END) AS direccion"),
                DB::raw("CONCAT(users.primer_nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as medico")
            )
            ->distinct('etapa.id')
            ->orderBy('etapa.created_at')
            ->get();
        return $data;
    }
    /***************************************************************************************************************************
                                                    REPORTS INFO
     ****************************************************************************************************************************/
    // View with report of addmission/discharges
    public function showInfoAddmissionAndDischarge()
    {
        // Get url for check if is addmission or discharge
        $url = explode("/", url()->current());
        $currUrl = strtolower($url[count($url) - 2]);
        // Depends of which one is, we call the query with release/stage created at this month
        ($currUrl == "ingresos" ? $data = $this->infoQuery2(1) : $data = $this->infoQuery2(2));
        // Some variables
        // Range of age
        $end = 80;
        $interval = 5;
        // Storage data
        $list = [];
        $listData = [];
        // Get diagnosis
        $diagnosis = Diagnosis::select('descripcion')->get();
        // Creating usseful data
        foreach ($diagnosis as $index) {
            $obj = new \stdClass();
            $obj->diagnostico = $index->descripcion;            
            $obj->Ambos = 0;
            $obj->Hombres = 0;
            $obj->Mujeres = 0;
            $iterator = 0;
            // Generate data for list
            while ($iterator < $end) {
                $str = $iterator . " - " . ($iterator + $interval - 1);
                (!in_array($str, $list) ? array_push($list, $str) : false);
                // Put come default data
                $strH = $str . " - H";
                $strM = $str . " - M";
                $obj->$strH =  0;
                $obj->$strM =  0;
                foreach ($data as $record) {
                    if ($record->diagnostico == $index->descripcion) {
                        // Check if is in range of age (range are in list[])
                        // Check the sex of record
                        $sex = strtolower($record->sexo);
                        $find = strpos($sex, 'hombre');
                        if ($record->edad >= $iterator && $record->edad <= ($iterator + $interval - 1)) {
                            if ($find !== false){
                                $obj->$strH = $obj->$strH + 1;
                                $obj->Hombres = $obj->Hombres + 1;
                            } else {
                                $obj->$strM = $obj->$strM + 1;
                                $obj->Mujeres = $obj->Mujeres + 1;
                            }
                            $obj->Ambos = $obj->Ambos + 1;
                        }
                    }
                }
                $iterator = $iterator + $interval;
            }
            $str = $iterator . " - más";
            (!in_array($str, $list) ? array_push($list, $str) : false);
            // More default data for last range
            $strH = $str . " - H";
            $strM = $str . " - M";
            $obj->$strH = 0;
            $obj->$strM = 0;
            $sename = [];
            $obj->menoresSENAME = 0;
            foreach ($data as $record) {
                // Check if is in range of age (range are in list[])
                // Check the sex of record
                $sex = strtolower($record->sexo);
                $find = strpos($sex, 'hombre');
                if ($record->edad >= $iterator) {
                    ($find !== false ? $obj->$strH = $obj->$strH + 1 : $obj->$strM = $obj->$strM + 1);
                }
                if ($record->diagnostico == $index->descripcion) {
                    if (!in_array($record->numero_ficha, $sename) && $record->edad < 18) {
                        // && $record->sename == 'Si'
                        array_push($sename, $record->numero_ficha);
                    }
                }
            }
            // In process...
            if ($currUrl == "egresos") {
                $obj->abandono = 0;
                $obj->fallecimiento = 0;
                $obj->traslado = 0;
                foreach ($data as $record) {
                    if ($record->diagnostico == $index->descripcion) {
                        (strtolower($record->alta) == "abandono" ? $obj->abandono = $obj->abandono + 1 : false);
                        (strtolower($record->alta) == "fallecimiento" ? $obj->fallecimiento = $obj->fallecimiento + 1 : false);
                        (strtolower($record->alta) == "traslado" ? $obj->traslado = $obj->traslado + 1 : false);
                    }
                }
            }
            $obj->menoresSENAME = count($sename);
            array_push($listData, $obj);
        }
        // Return to the view
        if ($currUrl == "ingresos") {
            return view('general.patientAdmissionsInfo', ['main' => json_encode($listData), 'list' => json_encode($list)]);
        } else {
            return view('general.patientDischargesInfo', ['main' => json_encode($listData), 'list' => json_encode($list)]);
        }
    }
    // Query with necessary data for reports
    public function infoQuery2($status)
    {
        $data =  DB::table('etapa')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->join('etapa_posee_diagnostico', 'etapa_posee_diagnostico.etapa_id', '=', 'etapa.id')
            ->join('diagnostico', 'diagnostico.id', '=', 'etapa_posee_diagnostico.diagnostico_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->leftJoin('alta', 'alta.id', '=', 'etapa.alta_id')
            ->when($status, function ($query, $status) {
                if ($status == 2) {
                    return $query->whereMonth('alta.created_at', Carbon::now()->month)->where('etapa.activa', 0);
                } else if ($status == 1) {
                    return $query->whereMonth('etapa.created_at', Carbon::now()->month);
                }
            })
            ->select(
                'procedencia.descripcion as procedencia',
                'alta.descripcion as alta',
                'etapa.id as numero_ficha',
                'sexo.descripcion as sexo',
                'etapa.created_at as fecha_ingreso',
                'diagnostico.descripcion as diagnostico',
                DB::raw("DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS edad")
            )
            ->orderBy('etapa.created_at')
            ->get();
        return $data;
    }
    /***************************************************************************************************************************
                                                    SUMMARY OF REPORTS
     ****************************************************************************************************************************/
    // View with report of addmission/discharges
    public function showSummaryAddmissionAndDischarge()
    {
        // Get main data
        $data = $this->infoQuery3();
        // Set programs 'infanto' and 'adulto', but we can add more (remember change query3)
        $programs = ['Infanto', 'Adulto'];
        // Get all provenances
        $provenances = Provenance::select('descripcion')->get();
        // Array with new objects
        $dataList = [];
        foreach ($programs as $index) {
            // Create object
            $obj = new \stdClass();
            // Set program
            $obj->programa = $index;
            for ($i = 0; $i < count($provenances); $i++) {
                // Get provenance like string
                $provenance = $provenances[$i]->descripcion;
                // Create one array with program name and set provenance (example: Infanto: ["APS", ... ])
                $obj->$index[$i] = $provenance;
                // Create attributes to the object for storage counts
                $strYoung = $provenance . "_m";
                $strOld = $provenance . "_M";
                // Start count for young patient (< 15) with 0
                $obj->$strYoung = 0;
                // Start count for adult patient (> 15) with 0
                $obj->$strOld = 0;
                foreach ($data as $record) {
                    if ($record->edad <= 15 && $record->programa == $index && $record->procedencia == $provenance) {
                        // Increase count in 1 for young patient, if is the correct program and provenance
                        $obj->$strYoung = $obj->$strYoung + 1;
                    } else if ($record->programa == $index && $record->procedencia == $provenance) {
                        // Increase count in 1 for adult patient, if is the correct program and provenance
                        $obj->$strOld = $obj->$strOld + 1;
                    }
                }
            }
            // Storage the object on the list
            array_push($dataList, $obj);
        }
        // Return to the view
        return view('general.patientRemSummary', compact('dataList'));
    }
    // Query with data for summary report
    public function infoQuery3()
    {
        $data =  DB::table('etapa')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->join('programa', 'programa.id', '=', 'etapa.programa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->whereMonth('etapa.created_at', Carbon::now()->month)
            ->select(
                'etapa.id',
                'procedencia.descripcion as procedencia',
                DB::raw("DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 as edad"),
                DB::raw("(CASE WHEN lower(programa.descripcion) like '%infant%' THEN 'Infanto' ELSE 'Adulto' END) AS programa")
            )
            ->distinct('etapa.id')
            ->groupBy('etapa.id', 'procedencia.descripcion', 'paciente.fecha_nacimiento', 'programa.descripcion')
            ->get();
        return $data;
    }
}
