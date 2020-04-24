<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryRecordController extends Controller
{
    /**
     * Show the reports of REM
     * Use one guery for get the information
     * This one get all information for the report like prevition, attendance, activity, procedance, dex, code, ps-fam, birthdate, etc.
     */

    public function showSummaryRecords(Request $request)
    {
        if ($request->year) {
            $date = Carbon::createFromDate($request->year, $request->month, 1);
        } else {
            $date = Carbon::now();
        }
        return $this->showReport($date);
    }

    // View for month summary
    public function showReport($date)
    {
        // Get data
        $data = $this->querySummary($date);
        // Get functionaries and activities
        $functionaries = $data->unique('rut');
        $activities = $data->unique('actividad');
        $table = [];
        $list = [];
        foreach ($activities as $index1) {
            $obj = new \stdClass();
            $obj->actividad = $index1->actividad;
            foreach ($functionaries as $index2) {
                if (!in_array($index2, $list)) array_push($list, $index2);
                $strYes = $index2->rut . '-si';
                $obj->$strYes = 0;
                $strNo = $index2->rut . '-no';
                $obj->$strNo = 0;
                foreach ($data as $record) {
                    if ($record->rut == $index2->rut && $record->actividad == $index1->actividad) {
                        $obj->$strYes = (int) $record->Con_Asistencia;
                        $obj->$strNo = (int) $record->Sin_Asistencia;
                    }
                }
            }
            array_push($table, $obj);
        }
        $date = $date->format('Y-m-d');
        // Return to the view
        return view('general.recordsSummary', compact('list', 'table', 'date'));
    }
    // Query for summary info
    public function querySummary($date)
    {
        $data = DB::table('atencion')
            ->join('funcionarios', 'atencion.funcionario_id', '=', 'funcionarios.id')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->whereMonth('atencion.fecha', $date->month)
            ->whereYear('atencion.fecha', $date->year)
            ->where('funcionarios.activa', 1)
            ->where('atencion.activa', 1)
            ->select(
                'actividad.descripcion as actividad',
                'users.rut',
                DB::raw("CONCAT(users.primer_nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as nombre_funcionario"),
                DB::raw("SUM(CASE WHEN atencion.asistencia = 0 THEN 1 ELSE 0 END) AS Sin_Asistencia"),
                DB::raw("SUM(CASE WHEN atencion.asistencia = 1 THEN 1 ELSE 0 END) AS Con_Asistencia")
            )
            ->groupBy('actividad.descripcion', 'users.primer_nombre', 'users.apellido_paterno', 'users.apellido_materno', 'users.rut')
            ->get();
        return $data;
    }
}
