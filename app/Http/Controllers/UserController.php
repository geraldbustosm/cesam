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
                                                    SHOW
    ****************************************************************************************************************************/

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
    
    /***************************************************************************************************************************
                                                    CREATE PROCESS
    ****************************************************************************************************************************/
    public function registerUser(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'nombre' => 'required|string|max:255',
            'rut' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'rol' => 'required|integer|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // Create a new 'object' user
        $user = new User;
        // Set the variables to the object user
        // the variables name of object must be the same that database
        // nombre, primer_nombre, segundo_nombre, apellido_paterno, apellido_materno, rut, email, rol, password
        $user->nombre = $request->nombre;
        $user->primer_nombre = $request->primer_nombre;
        $user->segundo_nombre = $request->segundo_nombre;
        $user->apellido_paterno = $request->apellido_paterno;
        $user->apellido_materno = $request->apellido_materno;
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
