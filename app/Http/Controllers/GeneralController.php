<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Address;
use App\Sex;
use App\Prevition;

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
        $previtions = Prevition::all();
        return view('admin.patientForm', compact('sex','previtions'));
    }
    public function registerPatient(Request $request){

        $validation = $request->validate([
            'id' => 'required|string',
            //'id' => 'required|int|max:255',
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'comuna' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'numero' => 'required|int',
            'patient_sex' => 'required',
            'prevition' => 'required|int',
            'numero' => 'required|int',
            'direccion' => 'string|max:255',
            'datepicker' => 'required|date_format:"d/m/Y"',
            ]);

        echo $request->new_start;

        $nombre = explode(" ", $request->nombre);
        $patient = new Patient;
    
        $patient->nombre1 = $nombre[0];
        $patient->nombre2 = $nombre[1];
        $patient->apellido1 = $request->apellido1;
        $patient->apellido2 = $request->apellido2;
        $patient->DNI = $request->id;
        $var = $request->get('datepicker');
        $date = str_replace('/', '-', $var);
        $correctDate = date('Y-m-d', strtotime($date));

        $patient->fecha_nacimiento = $correctDate;
        
        $patient->prevision_id = $request->prevition;
        
        $address = new Address;
        $address->region = $request->region;
        $address->comuna = $request->comuna;
        $address->calle  = $request->calle ;
        $address->numero = $request->numero;
        
        $patient->sexo_id = $request->patient_sex;
        $address->save();
        
        $patient->save();
        $patient->address()->sync($address);
        
        
        
        
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
