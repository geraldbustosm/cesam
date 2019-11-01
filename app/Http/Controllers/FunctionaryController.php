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
        $functionary = Functionary::where('activa', 1)->get();
        // Get the list of users
        $user = User::all();
        // Get the list of specialitys
        $speciality = Speciality::all();
        // Get the list of speciality per functionary
        $fs = FunctionarySpeciality::all();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('general.functionarys', compact('functionary', 'user', 'speciality', 'fs'));
    }

    public function showInactiveFunctionarys()
    {
        // Get Functionarys from database where 'activa' attribute is 0 bits
        $functionary = Functionary::where('activa', 0)->get();
        // Get the list of users
        $user = User::all();
        // Get the list of specialitys
        $speciality = Speciality::all();
        // Get the list of speciality per functionary
        $fs = FunctionarySpeciality::all();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Views.funtionaryInactive', compact('functionary', 'user', 'speciality', 'fs'));
    }

    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddFunctionary()
    {
        // Get active users
        $user = User::where('activa', 1)->get();
        // Redirect to the view with list of users
        return view('admin.Form.functionaryForm', compact('user'));
    }

    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditFunctionary($id){
        // Get the user through dni
        $user = User::where('rut', $id)->first();
        // Get the functionary through id of that user
        $functionary = Functionary::where('user_id',$user->id)->first();
        // Redirect to the view with the functionary
        return view('admin.Edit.functionaryEdit', compact('functionary'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerFunctionary(Request $request)
    {
        // Check the format of each variable of 'request'
        $validation = $request->validate([
            'profesion' => 'required|string|max:255',
            'user' => 'required|integer|max:255',
            'declared_hours' => 'required'
        ]);
        // Create a new 'object' functionary
        $functionary = new Functionary;
        // Set some variables with inputs of view
        // the variables name of object must be the same that database for save it
        // profesion, user_id, horasDeclaradas
        $functionary->profesion = $request->profesion;
        // We need create user before the functionary
        $functionary->user_id = $request->user;
        $functionary->horasDeclaradas = $request->declared_hours;
        // Pass the functionary to database
        $functionary->save();
        // Redirect to the view with successful status
        return redirect('asignar/especialidad');
    }

    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editFunctionary(Request $request){
        $user = User::where('id', $request->id)->first();
        $url = 'funcionario/edit/' . $user->rut;
        
        $validation = $request->validate([
            'profesion' => 'required',
            'horasDeclaradas' => 'required|numeric',
            'horasRealizadas' => 'required|numeric',
        ]);
        
        $functionary = Functionary::where('user_id', $request->id)->first();
        $functionary->profesion = $request->profesion;
        $functionary->horasDeclaradas = $request->horasDeclaradas;
        $functionary->horasRealizadas = $request->horasRealizadas;

        $functionary->save();

        return redirect($url)->with('status', 'Se actualizaron los datos del funcionario');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateFunctionary(Request $request)
    {
        // Get the functionary
        $functionary = Functionary::where('id', $request->id)->get();
        // Update active to 1 bits
        $functionary[0]->activa = 1;
        // Send update to database
        $functionary[0]->save();
        // Get the user, because have the personal info
        $user = User::where('id', $functionary[0]->user_id)->get();
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('funcionarios/inactivos')->with('status', 'Funcionario ' . $user[0]->rut . ' re-incorporado');
    }

    public function deletingFunctionary(Request $request)
    {
        // Get the functionary
        $functionary = Functionary::where('id', $request->id)->get();
        // Update active to 0 bits
        $functionary[0]->activa = 0;
        // Send update to database
        $functionary[0]->save();
        // Get the user, because have the personal info
        $user = User::where('id', $functionary[0]->user_id)->get();
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('funcionarios')->with('status', 'Funcionario ' . $user[0]->rut . ' eliminado');
    }
}
