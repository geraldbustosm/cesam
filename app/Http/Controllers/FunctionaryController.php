<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Functionary;
use App\User;
use App\Speciality;
use App\FunctionarySpeciality;

class FunctionaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /***************************************************************************************************************************
                                                    SHOW
     ****************************************************************************************************************************/
    public function showFunctionarys()
    {
        // Get functionarys from database where 'activa' attribute is 1 bits
        $functionaries = Functionary::join('users', 'users.id', '=', 'funcionarios.user_id')
            ->where('funcionarios.activa', 1)
            ->select('funcionarios.*', 'users.rut', 'users.primer_nombre', 'users.apellido_paterno', 'users.apellido_materno')
            ->get();

        foreach ($functionaries as $index) {
            $index->speciality;
            app('App\Http\Controllers\UserController')->formatRut($index);
        }
        // Total functionarys
        $cantFunctionarys = $functionaries->count();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('general.functionarys', compact('functionaries', 'cantFunctionarys'));
    }

    public function showInactiveFunctionarys()
    {
        // Get Functionarys from database where 'activa' attribute is 0 bits
        $functionaries = Functionary::join('users', 'users.id', '=', 'funcionarios.user_id')
            ->where('funcionarios.activa', 0)
            ->select('funcionarios.*', 'users.rut', 'users.primer_nombre', 'users.apellido_paterno', 'users.apellido_materno')
            ->get();

        foreach ($functionaries as $index) {
            $index->speciality;
            app('App\Http\Controllers\UserController')->formatRut($index);
        }
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.funtionaryInactive', compact('functionaries'));
    }

    public function showPatients($id)
    {
        // Get functionary
        $functionary = Functionary::where('id', $id)->where('activa', '1')->get()->first();
        
        if($functionary){
            $especialidades = $functionary->speciality;

            $esMedico = false;

            foreach($especialidades as $especialidad){
                if($especialidad->id == 1){
                    $esMedico = true;
                }
            }
            $patients = array();
            
            if($esMedico){
                $etapas = $functionary->stage;

                foreach($etapas as $etapa){
                    if($etapa->activa == '1'){
                        array_push($patients, $etapa->patient);
                    }
                }
            }
        }
        
        return view('admin.Views.functionaryPatients', compact('functionary','patients'));
    }

    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddFunctionary()
    {
        $functionarys = Functionary::pluck('user_id');
        // Get active users
        $user = User::where('activa', 1)
            ->whereNotIn('id', $functionarys)
            ->get();
        $speciality = Speciality::where('activa', 1)->get();
        // Redirect to the view with list of users
        return view('admin.Form.functionaryForm', compact('user', 'speciality'));
    }

    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditFunctionary($id)
    {
        // Get the user through dni
        $user = User::where('rut', $id)->first();
        // Get the functionary through id of that user
        $functionary = Functionary::where('user_id', $user->id)->first();
        $funcSpec = $functionary->firstSpeciality[0]->id;
        $speciality = Speciality::where('activa', 1)->get();
        // Redirect to the view with the functionary
        return view('admin.Edit.functionaryEdit', compact('functionary', 'speciality', 'funcSpec'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerFunctionary(Request $request)
    {
        // Check the format of each variable of 'request'
        $validation = $request->validate([
            'user' => 'required|integer|max:255',
            'declared_hours' => 'required'
        ]);
        // Create a new 'object' functionary
        $functionary = new Functionary;
        // Set some variables with inputs of view
        // the variables name of object must be the same that database for save it
        // speciality, user_id, horasDeclaradas
        // We need create user before the functionary
        $functionary->user_id = $request->user;
        $codigos = $request->speciality;
        $functionary->horasDeclaradas = $request->declared_hours;
        // Pass the functionary to database
        $functionary->save();
        $functionary->speciality()->sync($codigos);
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar funcionario', $functionary->id, $functionary->table);
        app('App\Http\Controllers\AdminController')->addLog('Registrar especialidad a funcionario id: ' . $functionary->id, $codigos, 'funcionario_posee_especialidad');
        // Redirect to the view with successful status
        return redirect('/funcionarios')->with('status', 'Funcionario creado!');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editFunctionary(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $url = 'funcionario/edit/' . $user->rut;

        $validation = $request->validate([
            'profesion' => 'required',
            'horasDeclaradas' => 'required|numeric',
            'horasRealizadas' => 'required|numeric',
        ]);

        $functionary = Functionary::where('user_id', $request->id)->first();
        $functionary->speciality()->sync($request->profesion);
        $functionary->horasDeclaradas = $request->horasDeclaradas;
        $functionary->horasRealizadas = $request->horasRealizadas;
        $functionary->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Actualizar funcionario', $functionary->id, $functionary->table);
        return redirect($url)->with('status', 'Se actualizaron los datos del funcionario');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateFunctionary(Request $request)
    {
        // Get the functionary
        $functionary = Functionary::find($request->id);
        // Update active to 1 bits
        $functionary->activa = 1;
        // Send update to database
        $functionary->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar funcionario', $functionary->id, $functionary->table);
        // Get the user, because have the personal info
        $user = User::where('id', $functionary->user_id)->get();
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('funcionarios/inactivos')->with('status', 'Funcionario ' . $user[0]->rut . ' re-incorporado');
    }

    public function deletingFunctionary(Request $request)
    {
        // Get the functionary
        $functionary = Functionary::find($request->id);
        // Update active to 0 bits
        $functionary->activa = 0;
        // Send update to database
        $functionary->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar funcionario', $functionary->id, $functionary->table);
        // Get the user, because have the personal info
        $user = User::where('id', $functionary->user_id)->get();
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('funcionarios')->with('status', 'Funcionario ' . $user[0]->rut . ' eliminado');
    }
}
