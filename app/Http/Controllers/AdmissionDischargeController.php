<?php

namespace App\Http\Controllers;

use App\Diagnosis;
use App\Patient;
use App\Provenance;
use App\ReleaseGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdmissionDischargeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showAdmissionDischarge(Request $request)
    {
        if ($request->year) $date = Carbon::createFromDate($request->year, $request->month, 1);
        else $date = Carbon::now();
        return $this->showReport($date);
    }

    public function showInfoAddmissionAndDischarge(Request $request)
    {
        if ($request->year) $date = Carbon::createFromDate($request->year, $request->month, 1);
        else $date = Carbon::now();
        return $this->showInfoReport($date);
    }

    public function showSummaryAddmissionAndDischarge(Request $request)
    {
        if ($request->year) $date = Carbon::createFromDate($request->year, $request->month, 1);
        else $date = Carbon::now();
        return $this->showSummaryReport($date);
    }
    /***************************************************************************************************************************
                                                    REPORTS
     ****************************************************************************************************************************/
    // View Admission/Discharge
    public function showReport($date)
    {
        // 
        $url = explode("/", url()->current());
        $currUrl = strtolower($url[count($url) - 1]);
        ($currUrl == "ingresos" ? $data = $this->infoQuery1(1, $date) : $data = $this->infoQuery1(2, $date));
        $allDiagnosis = DB::table('etapa_posee_diagnostico')
            ->join('diagnostico', 'diagnostico.id', '=', 'etapa_posee_diagnostico.diagnostico_id')
            ->get();
        $list = [];
        // Change date format
        foreach ($data as $record) {
            $dob = Carbon::createFromDate($record->fecha_nacimiento);
            $addmission_date = Carbon::createFromDate($record->fecha_ingreso);
            if ($currUrl == "egresos") $record->fecha_egreso = Carbon::createFromDate($record->fecha_egreso)->format('d/m/Y');
            $record->fecha_nacimiento = $dob->format('d/m/Y');
            $record->fecha_ingreso = $addmission_date->format('d/m/Y');
            $num = 0;
            $patient = Patient::where('DNI', $record->DNI)->first();
            $checkType = DB::table('atencion')
                ->join('prestacion', 'atencion.prestacion_id', '=', 'prestacion.id')
                ->where('atencion.etapa_id', $record->numero_ficha)
                ->where('prestacion.tipo_id', 1)
                ->select('atencion.etapa_id', 'atencion.id as atencion_id', 'atencion.prestacion_id', 'prestacion.tipo_id')
                ->count();
            ($checkType > 0 ? $record->ges = 'SI': $record->ges = 'NO');
            foreach ($allDiagnosis as $index) {
                if ($record->numero_ficha == $index->etapa_id) {
                    $str = "diagnostico_" . $num;
                    (!in_array($str, $list) ? array_push($list, $str) : false);
                    $record->$str = $index->descripcion;
                    $num++;
                }
            }
        }
        $date = $date->format('Y-m-d');
        // Return to the view
        if ($currUrl == "ingresos") return view('general.patientAdmissions', compact('data', 'list', 'date'));
        else return view('general.patientDischarges', compact('data', 'list', 'date'));
    }
    // Query with with all info of admission/discharges
    public function infoQuery1($status, $date)
    {
        $arr = [];
        $arr = [$status, $date];
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
            ->leftJoin('direccion', 'direccion.paciente_id', '=', 'paciente.id')
            ->leftJoin('atencion', 'atencion.etapa_id', '=', 'etapa.id')
            ->leftJoin('paciente_posee_atributos', 'paciente_posee_atributos.paciente_id', '=', 'paciente.id')
            ->leftJoin('atributos', 'atributos.id', '=', 'paciente_posee_atributos.atributos_id')
            ->where('atencion.activa', 1)
            ->when($arr, function ($query, $arr) {
                if ($arr[0] == 2) {
                    return $query->whereMonth('alta.created_at', $arr[1]->month)
                        ->whereYear('alta.created_at', $arr[1]->year)
                        ->where('etapa.activa', 0);
                } else if ($arr[0] == 1) {
                    return $query->whereMonth('etapa.created_at', $arr[1]->month)
                        ->whereYear('etapa.created_at', $arr[1]->year);
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
                'sexo.descripcion as sexo',
                'procedencia.descripcion as procedencia',
                'programa.descripcion as programa',
                'etapa.created_at as fecha_ingreso',
                'prevision.descripcion as prevision',
                'sigges.descripcion as sigges',
                DB::raw("DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS edad"),
                DB::raw("CONCAT(users.primer_nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as medico"),
                DB::raw("(CASE WHEN lower(atributos.descripcion) like '%sename%' THEN 'SI' ELSE 'NO' END) AS SENAME"),
                DB::raw("(CASE WHEN direccion.departamento IS NOT NULL
                    THEN lower(CONCAT(direccion.calle,' #', direccion.numero , ' depto: ', direccion.departamento, ', ', direccion.comuna))
                    ELSE (CASE WHEN direccion.calle IS NOT NULL
                    THEN lower(CONCAT(direccion.calle,' #', direccion.numero, ', ', direccion.comuna))
                    ELSE 'Sin dirección' END) END) AS direccion")
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
    public function showInfoReport($date)
    {
        // Get url for check if is addmission or discharge
        $url = explode("/", url()->current());
        $currUrl = strtolower($url[count($url) - 2]);
        // Depends of which one is, we call the query with release/stage created at this month
        ($currUrl == "ingresos" ? $data = $this->infoQuery2(1, $date) : $data = $this->infoQuery2(2, $date));
        // Some variables
        // Range of age
        $end = 80;
        $interval = 5;
        // Storage data
        $list = [];
        $listData = [];
        // Get diagnosis
        $diagnosis = Diagnosis::select('descripcion')->get();
        // Get terapeutic release
        $release = ReleaseGroup::whereRaw('lower(grupo_alta.descripcion) like ?', ['terapéutica%'])->select('id')->first();
        // Get Release-Group
        $groups = ReleaseGroup::whereRaw('lower(grupo_alta.descripcion) <> ?', ['terapéutica%'])->where('activa', 1)->get();
        // die(json_encode($groups));
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
                // Put default data
                $strH = $str . " - H";
                $strM = $str . " - M";
                $obj->$strH =  0;
                $obj->$strM =  0;
                foreach ($data as $record) {
                    if ($record->diagnostico == $index->descripcion && ($currUrl == "ingresos" || $record->grupo == $release->id)) {
                        // Check if is in range of age (range are in list[])
                        // Check the sex of record
                        $sex = strtolower($record->sexo);
                        $find = strpos($sex, 'hombre');
                        if ($record->edad >= $iterator && $record->edad <= ($iterator + $interval - 1)) {
                            if ($find !== false) {
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
            // Get count of unique patient attended
            $uniques = [];
            $sename = [];
            $obj->menoresSENAME = 0;
            $obj->Beneficiarios = 0;
            foreach ($data as $record) {
                if ($record->diagnostico == $index->descripcion && ($currUrl == "ingresos" || $record->grupo == $release->id)) {
                    // Check if is in range of age (range are in list[])
                    // Check the sex of record
                    $sex = strtolower($record->sexo);
                    $find = strpos($sex, 'hombre');
                    if ($record->edad >= $iterator) {
                        if ($find !== false) {
                            $obj->$strH = $obj->$strH + 1;
                            $obj->Hombres = $obj->Hombres + 1;
                        } else {
                            $obj->$strM = $obj->$strM + 1;
                            $obj->Mujeres = $obj->Mujeres + 1;
                        }
                        $obj->Ambos = $obj->Ambos + 1;
                    }
                }
                if ($record->diagnostico == $index->descripcion) {
                    if (!in_array($record->numero_ficha, $sename) && $record->edad < 18) {
                        // && $record->sename == 'Si'
                        array_push($sename, $record->numero_ficha);
                    }
                    (!in_array($record->DNI, $uniques) ? array_push($uniques, $record->DNI) : false);
                }
            }
            // Get counts of releases distinct of terapeutic
            if ($currUrl == "egresos") {
                foreach ($groups as $group) {
                    $str = $group->descripcion;
                    $obj->$str = 0;
                }
                foreach ($data as $record) {
                    if ($record->diagnostico == $index->descripcion) {
                        foreach ($groups as $group) {
                            if ($record->grupo == $group->id) {
                                $str = $group->descripcion;
                                $obj->$str = $obj->$str + 1;
                            }
                        }
                    }
                }
            }
            $obj->menoresSENAME = count($sename);
            $obj->Beneficiarios = count($uniques);
            array_push($listData, $obj);
        }

        $date = $date->format('Y-m-d');
        // Return to the view
        if ($currUrl == "ingresos") {
            return view('general.patientAdmissionsInfo', ['main' => json_encode($listData), 'list' => json_encode($list), 'diagnosis' => json_encode($diagnosis), 'date' => json_encode($date)]);
        } else {
            return view('general.patientDischargesInfo', ['main' => json_encode($listData), 'list' => json_encode($list), 'diagnosis' => json_encode($diagnosis), 'date' => json_encode($date)]);
        }
    }

    // Query with necessary data for reports
    public function infoQuery2($status, $date)
    {
        $arr = [$status, $date];
        $data =  DB::table('etapa')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->join('etapa_posee_diagnostico', 'etapa_posee_diagnostico.etapa_id', '=', 'etapa.id')
            ->join('diagnostico', 'diagnostico.id', '=', 'etapa_posee_diagnostico.diagnostico_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->leftJoin('alta', 'alta.id', '=', 'etapa.alta_id')
            ->leftJoin('grupo_alta', 'grupo_alta.id', '=', 'alta.grupo_id')
            ->when($arr, function ($query, $arr) {
                if ($arr[0] == 2) {
                    return $query->whereMonth('alta.created_at', $arr[1]->month)
                        ->whereYear('alta.created_at', $arr[1]->year)
                        ->where('etapa.activa', 0);
                } else if ($arr[0] == 1) {
                    return $query->whereMonth('etapa.created_at', $arr[1]->month)
                        ->whereYear('etapa.created_at', $arr[1]->year);
                }
            })
            ->select(
                'procedencia.descripcion as procedencia',
                'paciente.DNI',
                'alta.descripcion as alta',
                'alta.grupo_id as grupo',
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
    public function showSummaryReport($date)
    {
        // Get main data
        $data = $this->infoQuery3($date);
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
                    if ($record->edad < 15 && $record->programa == $index && $record->procedencia == $provenance) {
                        // Increase count in 1 for young patient, if is the correct program and provenance
                        $obj->$strYoung = $obj->$strYoung + 1;
                    } else if ($record->edad >= 15 && $record->programa == $index && $record->procedencia == $provenance) {
                        // Increase count in 1 for adult patient, if is the correct program and provenance
                        $obj->$strOld = $obj->$strOld + 1;
                    }
                }
            }
            // Storage the object on the list
            array_push($dataList, $obj);
        }
        $date = $date->format('Y-m-d');
        // Return to the view
        return view('general.patientRemSummary', compact('dataList', 'date'));
    }

    // Query with data for summary report
    public function infoQuery3($date)
    {
        $data =  DB::table('etapa')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->join('programa', 'programa.id', '=', 'etapa.programa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->whereMonth('etapa.created_at', $date->month)
            ->whereYear('etapa.created_at', $date->year)
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
