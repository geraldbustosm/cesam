<?php

namespace App\Http\Controllers;
use App\Activity;
use App\Address;
use App\Attendance;
use App\Attributes;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GraphsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('checkrole:1');
    }

    /**
     * Fetch the particular company details
     * @return json response
     */
    public function chart()
    {
      $result = \DB::table('atencion')
      ->join('funcionarios', 'funcionarios.id', '=', 'atencion.funcionario_id')
      ->join('users', 'users.id', '=', 'funcionarios.user_id')
                ->select(DB::raw("count(*) as stockPrice, CONCAT(users.nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) AS stockYear"))
                ->groupBy('users.id','users.nombre','users.apellido_paterno','users.apellido_materno')
                ->get();
      return response()->json($result);
    }
    public function chart2()
    {
      $result2 = \DB::table('atencion')
        ->join('prestacion', 'prestacion.id', '=', 'atencion.prestacion_id')
        ->select(DB::raw("count(*) as numero, prestacion.glosaTrasadora AS glosa"))
        ->groupBy('prestacion.glosaTrasadora')
        ->get();
      return response()->json($result2);
    }
    public function chart3()
    {
        $result3 = \DB::table('atencion')
                ->select(
                    \DB::raw('month(fecha) as Month, count(id) AS Cantidad' ))
                ->where('abre_canasta', '=', '1')
                ->groupBy(DB::raw('month(fecha)'))
                ->get();
        
        setlocale(LC_ALL, 'es_MX', 'es', 'ES');
        Carbon::setLocale('es');
        
        foreach ($result3 as $post) {
            $fecha = Carbon::createFromDate(1, $post->Month, 1);
            
            $fecha->formatLocalized("%A %d %B %Y");
            $mes = $fecha->format("F");
            $post->Month=$mes;
        }
        
        
        return response()->json($result3);
    }
    public function chart4(Request $request)
    {
      $id = $request->functionary_id;
      
      $result4 = \DB::table('funcionario_posee_horas_actividad')
        ->where('funcionario_id',$id )
        ->join('actividad', 'actividad.id', '=', 'funcionario_posee_horas_actividad.actividad_id')
        ->select(DB::raw("horasDeclaradas as numero, actividad.descripcion AS glosa"))
        ->get();
      return response()->json($result4);
    }
}

