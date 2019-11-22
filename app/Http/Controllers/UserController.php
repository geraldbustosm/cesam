<?php

namespace App\Http\Controllers;

use App\Functionary;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    SHOW
     ****************************************************************************************************************************/
    public function showUsers()
    {
        // Get patients from database where 'activa' attribute is 1 bits
        $users = User::where('activa', 1)
            ->select('rut', 'primer_nombre', 'segundo_nombre', 'apellido_paterno', 'apellido_materno', 'nombre', 'email', 'activa')
            ->get();
        // Redirect to the view with list of: active patients, all previtions and all genders
        return view('admin.views.users', compact('users'));
    }

    public function showInactiveUsers()
    {
        // Get patients from database where 'activa' attribute is 0 bits
        $users = User::where('activa', 0)->get();
        // Redirect to the view with list of: inactive patients, all previtions and all genders
        return view('admin.Inactive.usersInactive', compact('users'));
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
    public function showEditData()
    {
        // Redirect to the view
        return view('general.editData');
    }

    public function showEditPassword()
    {
        // Redirect to the view
        return view('general.editPassword');
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerUser(Request $request)
    {
        // Check the format of each variable of 'request'
        $validation = $request->validate([
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
        // Separating once the nombres by space character
        $posSpace = strpos($request->name, ' ');

        if (!$posSpace) {
            $user->primer_nombre = $request->name;
            $user->segundo_nombre = "";
        } else {
            $user->primer_nombre = substr($request->name, 0, $posSpace);
            $user->segundo_nombre = substr($request->name, $posSpace + 1);
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
        if ($request->rol == 2) {
            // Get the last user added
            $id = DB::getPDO()->lastInsertId();
            $user = User::where('id', $id)->get();
            // Return to the view just with the last user
            return view('admin.Form.functionaryForm', compact('user'));
        } else {
            // Redirect to the view with successful status
            return redirect('registrar/usuario')->with('status', 'Usuario creado');
        }
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editPassword(Request $request)
    {
        $validation = $request->validate([
            'actual_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = Auth::user();
        if (Hash::check($request->actual_password, $user->password)) {

            $user->password = Hash::make($request->password);
            $user->save();
        } else {
            return redirect('password/edit')->with('status', 'Tu contraseña actual no es correcta');
        }
        Auth::logout();
        return redirect('login')->with('status', 'Se ha actualizado su contraseña');
    }
    public function editData(Request $request)
    {
        // Validate the request variables
        $validation = $request->validate([
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = Auth::user();
        if (Hash::check($request->password, $user->password)) {

            // Separating once the nombres by space character
            $posSpace = strpos($request->nombres, ' ');

            if (!$posSpace) {
                $user->primer_nombre = $request->nombres;
                $user->segundo_nombre = "";
            } else {
                $user->primer_nombre = substr($request->nombres, 0, $posSpace);
                $user->segundo_nombre = substr($request->nombres, $posSpace + 1);
            }

            $user->apellido_paterno = $request->apellido_paterno;
            $user->apellido_materno = $request->apellido_materno;
            $user->nombre = $request->nombre;
            $user->email = $request->email;
            // Pass the new info for update
            $user->save();
        } else {
            // Redirect to the URL with successful status
            return redirect('misdatos/edit')->with('wrong', 'Tu contraseña no es correcta');
        }
        // Redirect to the URL with successful status
        return redirect('misdatos/edit')->with('success', 'Se actualizaron los datos del paciente');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateUser(Request $request)
    {
        // Get the user
        $user = User::where('rut', $request->id)->first();
        // Update active to 1 bits
        $user->activa = 1;
        // Send update to database
        $user->save();
        // Get functionays
        $functionarys = Functionary::where('user_id', $user->id)->get();
        if ($functionarys->count() != 0) {
            foreach ($functionarys as $index => $record) {
                // Deactivate them
                $record->activa = 1;
                $record->save();
            }
        }
        // Redirect to the view with successful status (showing the DNI)
        return redirect('usuarios/inactivos')->with('status', 'Usuario ' . $request->id . ' reingresado');
    }
    public function deletingUser(Request $request)
    {
        // Get the user
        $user = User::where('rut', $request->id)->first();
        // Update active to 0 bits
        $user->activa = 0;
        // Send update to database
        $user->save();
        // Get functionays
        $functionarys = Functionary::where('user_id', $user->id)->get();
        if ($functionarys->count() != 0) {
            foreach ($functionarys as $index => $record) {
                // Deactivate them
                $record->activa = 0;
                $record->save();
            }
        }
        // Redirect to the view with successful status (showing the DNI)
        return redirect('usuarios')->with('status', 'Usuario ' . $request->id . ' eliminado');
    }
}
