<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Sex;

class GeneralController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        return view('general.home');
    }
    public function showAddPatient(){
        $sex = Sex::all();
        return view('admin.patientForm', compact('sex'));
    }
    public function registerPatient(Request $request){

        $validation = $request->validate([
            'id' => 'required|int|unique:paciente',
            //'id' => 'required|int|max:255',
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'direccion' => 'string|max:255',
            'direccion_opcional' => 'string|max:255|nullable',
            'datepicker' => 'required|date_format:"d/m/Y"',
            ]);

        echo $request->new_start;

        $nombre = explode(" ", $request->nombre);
        $patient = new Patient;
        //$patient->id = rand(1,9999);
        $patient->nombre1 = $nombre[0];
        $patient->nombre2 = $nombre[1];
        $patient->apellido1 = $nombre[2];
        $patient->apellido2 = $nombre[3];
        $patient->sexo = $request->sexo;
        $patient->fecha_nacimiento = "2019-07-19 06:19:51.029";
        $patient->prevision_id = 1;

        $patient->save();
        
        return redirect('registrarpaciente')->with('status', 'Usuario creado');

    }
    public function getPatientsAjax(Request $request){
        $data = "";
        $i = 0;
        $pacientes = Patient::where('nombre1', 'LIKE', '%'.$request->busqueda.'%')->get();

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
