<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
    ****************************************************************************************************************************/
    public function showAddUser()
    {
        // Redirect to the view
        return view('admin.Form.userForm');
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
    ****************************************************************************************************************************/
    public function showEditData(){
        // Redirect to the view
        return view('general.editData');
    }

    public function showEditPassword(){
        // Redirect to the view
        return view('general.editPassword');
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
    ****************************************************************************************************************************/
    public function registerUser(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'nick' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'second_last_name' => 'required|string|max:255',
            'rut' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'rol' => 'required|integer|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // Create a new 'object' user
        $user = new User;
        // Separate the name string into array
        $nombre = explode(" ", $request->name);
        // Set some variables with inputs of view
        // the variables name of object must be the same that database for save it
        // nombre, primer_nombre, segundo_nombre, apellido_paterno, apellido_materno, rut, email, rol, password
        $user->primer_nombre = $nombre[0];
        $user->segundo_nombre = " ";
        if(count($nombre)==2){
            $user->segundo_nombre = $nombre[1];
        }
        $user->nombre = $request->nick;
        $user->apellido_paterno = $request->last_name;
        $user->apellido_materno = $request->second_last_name;
        $user->rut = $request->rut;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->password = Hash::make($request->password);
        // Pass the user to database
        $user->save();
        // Redirect to the view with successful status
        return redirect('registrar/usuario')->with('status', 'Usuario creado');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
    ****************************************************************************************************************************/
    
    /***************************************************************************************************************************
                                                    OTHER PROCESS
    ****************************************************************************************************************************/
   
}
