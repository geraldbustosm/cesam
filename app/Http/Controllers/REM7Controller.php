<?php

namespace App\Http\Controllers;

use App\Provenance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class REM7Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the reports of REM
     * Use 3 queries for get the information
     * The first one get the main information like functionary, speciality and count male, female and all
     * The second one is a helper, used for get every attention for patient made by the functionary, this is used for count male and female per range of age
     * The third one is another helper, this just count the inassistances
     * The MAIN function is ShowReports, is the one that make the union between queries
     */

     /**
      * This function pass month and year of Request to a date variable with carbon
      * because is easiest to use as Carbon Date
      * @param request (with month and year)
      * @return view (with the record of a specific date)
      */
    public function showRem7(Request $request)
    {
        if ($request->year) {
            $date = Carbon::createFromDate($request->year, $request->month, 1);
        } else {
            $date = Carbon::now();
        }
        return $this->showReport($date);
    }

    /**
     * This function is used for redirect to the view HTML using a date
     * We use 3 queries for get diferent info and ensamble into a useful array with objects
     * @param date (for extract year and month)
     * @return view (recordRem7 with a list of age-range and data array with objects)
     */
    public function showReport($date)
    {
        // Some variables
        $end = 80;
        $interval = 5;
        $list = [];
        // Get base data
        $data = $this->queryRem3($date);
        // Get helper data
        $query = $this->queryRem4($date);
        $inassit = $this->queryRem5($date);
        // Get all provenances
        $provenances = Provenance::select('id', 'descripcion')->get();
        // Creating usseful data
        foreach ($data as $record1) {
            $iterator = 0;
            // Generate data for list
            while ($iterator < $end) {
                $str = $iterator . " - " . ($iterator + $interval - 1);
                (!in_array($str, $list) ? array_push($list, $str) : false);
                // Put default data
                $record1->$str =  0;
                // Generate real data to use on view
                foreach ($query as $record2) {
                    // Check for the right functionary
                    if ($record1->id == $record2->id &&  $record2->age >= $iterator && $record2->age < ($iterator + $interval)) {
                        $record1->$str =  $record1->$str + 1;
                    }
                }
                $iterator = $iterator + $interval;
            }
            $str = $iterator . " - más";
            // Put default data
            $record1->$str =  0;
            (!in_array($str, $list) ? array_push($list, $str) : false);
            // Do the same for the last range (last value in list[])
            foreach ($query as $record2) {
                // Check for the right functionary
                if ($record1->id == $record2->id &&  $record2->age >= $iterator) {
                    $record1->$str =  $record2->Hombres + $record2->Mujeres;
                }
            }
            // Get count of uniques patients attended
            $menores = [];
            $mayores = [];
            $record1->menores = 0;
            $record1->mayores = 0;
            foreach ($query as $record2) {
                if ($record1->id == $record2->id && $record2->especialidad == $record1->especialidad && $record2->age < 15) {
                    (!in_array($record2->DNI, $menores) ? array_push($menores, $record2->DNI) : false);
                    $record1->menores = count($menores);
                } else if ($record1->id == $record2->id && $record2->especialidad == $record1->especialidad && $record2->age >= 15) {
                    (!in_array($record2->DNI, $mayores) ? array_push($mayores, $record2->DNI) : false);
                    $record1->mayores = count($mayores);
                }
            }
            // Cant per provenance
            foreach ($provenances as $index) {
                $young = $index->descripcion . "_m";
                $record1->$young = 0;
                $old = $index->descripcion . "_M";
                $record1->$old = 0;
                foreach ($query as $record2) {
                    if ($record1->id == $record2->id && $record2->especialidad == $record1->especialidad && $record2->age < 15 && $index->id == $record2->procedencia) {
                        $record1->$young = $record1->$young + 1;
                    } else if ($record1->id == $record2->id && $record2->especialidad == $record1->especialidad && $record2->age >= 15 && $index->id == $record2->procedencia) {
                        $record1->$old = $record1->$old + 1;
                    }
                }
            }
            // Cant inassist to medical consult
            $record1->repetido = 0;
            $record1->nuevo = 0;
            foreach ($inassit as $index) {
                if ($index->id == $record1->id && $index->especialidad == $record1->especialidad) {
                    ($index->repetido == 1 ? $record1->repetido = $record1->repetido + 1 : $record1->nuevo = $record1->nuevo + 1);
                }
            }
        }

        $date = $date->format('Y-m-d');
        // Return to the view
        return view('general.recordsRem7', compact('data', 'list', 'provenances', 'date'));
    }

    /**
     * Query used for get functionaries with specality
     * And count of male, female and total patient attended (group by the functionary)
     * @param date (get month and year)
     * @return data (object)
     */
    public function queryRem3($date)
    {
        $data = DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('funcionarios', 'funcionarios.id', '=', 'atencion.funcionario_id')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereRaw('lower(actividad.descripcion) like ?', ['consulta%'])
            ->whereMonth('atencion.fecha', $date->month)
            ->whereYear('atencion.fecha', $date->year)
            ->where('atencion.activa', 1)
            ->where(function ($query) {
                $query->where('atencion.asistencia', 1)
                    ->orWhere('actividad.sin_asistencia', 1);
            })
            ->select(
                'funcionarios.id as id',
                'especialidad.descripcion as especialidad',
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%hombre%' THEN 1 ELSE 0 END) AS Hombres"),
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%mujer%' THEN 1 ELSE 0 END) AS Mujeres"),
                DB::raw("COUNT(atencion.asistencia) AS Ambos"),
                DB::raw("CONCAT(users.primer_nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as nombre_funcionario")
            )
            ->groupBy(
                'especialidad.descripcion',
                'users.primer_nombre',
                'users.apellido_paterno',
                'users.apellido_materno',
                'funcionarios.id'
            )
            ->get();
        return $data;
    }

    /**
     * Query helper for REM
     * Used for get patient (with age) attended by functionary-specality
     * This is used for count patient by age-range
     * @param date (get month and year)
     * @return data (object)
     */
    public function queryRem4($date)
    {
        $data = DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            // ->whereRaw('lower(actividad.descripcion) like ?', ['consulta%'])
            ->whereMonth('atencion.fecha', $date->month)
            ->whereYear('atencion.fecha', $date->year)
            ->where('atencion.activa', 1)
            ->where(function ($query) {
                $query->where('atencion.asistencia', 1)
                    ->orWhere('actividad.sin_asistencia', 1);
            })
            ->select(
                'paciente.DNI as DNI',
                'etapa.procedencia_id as procedencia',
                'funcionario_posee_especialidad.funcionarios_id as id',
                'especialidad.descripcion as especialidad',
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%hombre%' THEN 1 ELSE 0 END) AS Hombres"),
                DB::raw("SUM(CASE WHEN lower(sexo.descripcion) like '%mujer%' THEN 1 ELSE 0 END) AS Mujeres"),
                DB::raw("COUNT(atencion.asistencia) AS Ambos"),
                DB::raw('DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS age')
            )
            ->groupBy(
                'especialidad.descripcion',
                'paciente.DNI',
                'paciente.fecha_nacimiento',
                'funcionario_posee_especialidad.funcionarios_id',
                'etapa.procedencia_id'
            )
            ->orderBy('especialidad.descripcion')
            ->get();
        return $data;
    }

    /**
     * Query helper for REM (inassist)
     * Used for get patient (with age) attended by functionary-specality
     * This is used for count patient with inassistances
     * @param date (get month and year)
     * @return data (object)
     */
    public function queryRem5($date)
    {
        $data = DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereRaw('lower(actividad.descripcion) like ?', ['consulta%'])
            ->whereMonth('atencion.fecha', $date->month)
            ->whereYear('atencion.fecha', $date->year)
            ->where('atencion.activa', 1)
            ->where('atencion.asistencia', 0)
            ->where('actividad.sin_asistencia', 0)
            ->select(
                'paciente.DNI',
                'atencion.repetido',
                'funcionario_posee_especialidad.funcionarios_id as id',
                'especialidad.descripcion as especialidad',
                'atencion.repetido'
            )
            ->orderBy('especialidad.descripcion')
            ->get();
        return $data;
    }
}
