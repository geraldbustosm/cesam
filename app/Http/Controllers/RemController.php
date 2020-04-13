<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the reports of REM
     * Use 2 queries for get the information
     * The first one get the main information like speciality, activity and count male, female and all
     * The second one is a helper, used for get every attention for patient, this is used for count male and female per range of age
     * The MAIN function is ShowReports, is the one that make the union between both queries
     */

    public function showRemRecords(Request $request)
    {
        if ($request->year) {
            $date = Carbon::createFromDate($request->year, $request->month, 1);
        } else {
            $date = Carbon::now();
        }
        return $this->showReport($date);
    }

    // View for REM
    public function showReport($date)
    {
        // Some variables
        $end = 80;
        $interval = 5;
        $list = [];
        // Get base data
        $data = $this->queryRem1($date);
        // Get helper data
        $query = $this->queryRem2($date);
        // Creating usseful data
        foreach ($data as $record1) {
            $iterator = 0;
            // Generate data for list
            while ($iterator < $end) {
                $str = $iterator . " - " . ($iterator + $interval - 1);
                (!in_array($str, $list) ? array_push($list, $str) : false);
                // Put default data
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
                        ($record2->sexo == 1 ? $record1->$strH = $record1->$strH + 1 : $record1->$strM = $record1->$strM + 1);
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
                    ($record2->sexo == 1 ? $record1->$strH = $record1->$strH + 1 : $record1->$strM = $record1->$strM + 1);
                }
            }
            // Get count of unique patient attended
            $uniques = [];
            $sename = [];
            $record1->Beneficiarios = 0;
            $record1->menoresSENAME = 0;
            foreach ($query as $record2) {
                if ($record1->actividad == $record2->actividad && $record1->especialidad == $record2->especialidad) {
                    if (!in_array($record2->DNI, $sename) && $record2->age < 18 && $record2->SENAME > 0) {
                        array_push($sename, $record2->DNI);
                    }
                    (!in_array($record2->DNI, $uniques) ? array_push($uniques, $record2->DNI) : false);
                }
            }
            $record1->Beneficiarios = count($uniques);
            $record1->menoresSENAME = count($sename);
        }
        $date = $date->format('Y-m-d');
        // Return to the view
        return view('general.recordsRem', compact('data', 'list', 'date'));
    }

    // Query original for REM
    public function queryRem1($date)
    {
        $data = DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereMonth('atencion.fecha', $date->month)
            ->whereYear('atencion.fecha', $date->year)
            ->where('atencion.activa', 1)
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
    public function queryRem2($date)
    {
        $data = DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->leftJoin('paciente_posee_atributos', 'paciente_posee_atributos.paciente_id', '=', 'paciente.id')
            ->leftJoin('atributos', 'atributos.id', '=', 'paciente_posee_atributos.atributos_id')
            ->whereMonth('atencion.fecha', $date->month)
            ->whereYear('atencion.fecha', $date->year)
            ->where('atencion.activa', 1)
            ->where(function ($query) {
                $query->where('atencion.asistencia', 1)
                    ->orWhere('actividad.sin_asistencia', 1);
            })
            ->select(
                'paciente.fecha_nacimiento as fecha',
                'paciente.DNI as DNI',
                'paciente.sexo_id as sexo',
                'especialidad.descripcion as especialidad',
                'actividad.descripcion as actividad',
                DB::raw("SUM(CASE WHEN lower(atributos.descripcion) like '%sename%' THEN 1 ELSE 0 END) AS SENAME"),
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%hombre%' THEN 1 ELSE 0 END) AS Hombres"),
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%mujer%' THEN 1 ELSE 0 END) AS Mujeres"),
                DB::raw("COUNT(atencion.asistencia) AS Ambos"),
                DB::raw('DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS age')
            )
            ->groupBy('actividad.descripcion', 'especialidad.descripcion', 'paciente.fecha_nacimiento', 'paciente.DNI', 'paciente.sexo_id')
            ->orderBy('actividad.descripcion')
            ->get();
        return $data;
    }
}
