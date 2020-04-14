<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthlyRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the reports of REM
     * Use one guery for get the information
     * This one get all information for the report like prevition, attendance, activity, procedance, dex, code, ps-fam, birthdate, etc.
     */

    public function showMonthlyRecords(Request $request)
    {
        if ($request->year) $date = Carbon::createFromDate($request->year, $request->month, 1);
        else $date = Carbon::now();
        return $this->showReport($date);
    }

    // View per month
    public function showReport($date)
    {
        $data = $this->queryMonthly($date);
        // Change date format
        foreach ($data as $record) {
            // die($record->fecha_nacimiento);
            $dob = Carbon::createFromDate($record->fecha_nacimiento);
            $date = Carbon::createFromDate($record->fecha);
            $record->fecha_nacimiento = $dob->format('d/m/Y');
            $record->fecha = $date->format('d/m/Y');
        }
        $date = $date->format('Y-m-d');
        // Return to the view
        return view('general.recordsMonthly', compact('data', 'date'));
    }
    // Query for monthly records
    public function queryMonthly($date)
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
            ->join('especialidad_programa', 'especialidad_programa.id', '=', 'programa.especialidad_programa_id')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->where('paciente.activa', 1)
            ->where('atencion.activa', 1)
            ->whereMonth('atencion.fecha', $date->month)
            ->whereYear('atencion.fecha', $date->year)
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
                'especialidad_programa.descripcion as especialidad_programa',
                DB::raw("(CASE WHEN atencion.repetido = 1 THEN 'REPETIDO' ELSE 'NUEVO' END) AS tipo_paciente"),
                DB::raw("(CASE WHEN atencion.abre_canasta = 1 THEN 'SI' ELSE 'NO' END) AS canasta"),
                DB::raw("(CASE WHEN atencion.asistencia = 1 THEN 'SI' ELSE 'NO' END) AS asistencia"),
                DB::raw("CONCAT(users.primer_nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as nombre_funcionario")
            )
            ->selectRaw('DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS edad')
            ->get();
        return $data;
    }
}
