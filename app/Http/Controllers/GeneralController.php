<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paciente;

class GeneralController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        return view('general.home');
    }
    public function getPatientsAjax(Request $request){
        $data = "";
        $i = 0;
        $pacientes = Paciente::where('nombre1', 'LIKE', '%'.$request->busqueda.'%')->get();

        foreach ($pacientes as $paciente){
            $i++;
            $data = $data . '<tr><th scope="row">'. $i . '</th>
                            <td>' . $paciente->nombre1 . '</td>
                            <td>' . $paciente->nombre2 . '</td>
                            <td>' . $paciente->nombre1 . '</td>
                            <td>' . $paciente->nombre2 . '</td>
                            <td><a href="#"><i title="Ver ficha" class="material-icons">description</i></a><a href="#"><i title="Añadir prestación" class="material-icons">add</i></a><a href="#" data-toggle="modal" data-target="#exampleModal"><i title="Editar" class="material-icons">create</i></a><a href="#"><i title="Borrar" class="material-icons">delete</i></a></td></tr>';
        }
        if($request->ajax()){
            return response($data);
        }
    }
}
