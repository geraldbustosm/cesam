<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Functionary;
use App\Speciality;
use App\Activity;
use App\User;
use App\Hours;

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
            ->select('id', 'rut', 'primer_nombre', 'segundo_nombre', 'apellido_paterno', 'apellido_materno', 'nombre', 'email', 'activa', 'rol')
            ->get();
        // Total users
        $cantUsers = $users->count();

        foreach ($users as $user) {
            $this->formatRut($user);
        }
        // Redirect to the view with list of: active patients, all previtions and all genders
        return view('admin.Views.users', compact('users', 'cantUsers'));
    }

    public function showInactiveUsers()
    {
        // Get patients from database where 'activa' attribute is 0 bits
        $users = User::where('activa', 0)
            ->select('id', 'rut', 'primer_nombre', 'segundo_nombre', 'apellido_paterno', 'apellido_materno', 'nombre', 'email', 'activa', 'rol')
            ->get();

        foreach ($users as $user) {
            $this->formatRut($user);
        }
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
            'second_last_name' => 'nullable|string|max:255',
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
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar usuario', $user->id, $user->table);
        if ($request->rol == 2) {
            // Return to the view
            return redirect('/registrar/funcionario')->with('status', 'Usuario creado');
        } else {
            // Redirect to the view with successful status
            return redirect('registrar/usuario')->with('status', 'Usuario creado');
        }
    }
    /***************************************************************************************************************************
                                                    HOURS AND PERFORMANCE PROCESS
     ****************************************************************************************************************************/
    public function editHours(Request $request)
    {
        //$functionary = $this->middleware('auth');
        $user = Auth::user();
        if ($user->rol == 2) {
            $activity = Activity::all();
            $functionary = Functionary::where('user_id', $user->id)->first();
            return view('general.reportFunctionaryHours', compact('activity', 'user', 'functionary'));
        }
    }

    public function saveHours(Request $request)
    {
        $user = Auth::user();
        if ($user->rol == 2) {
            $activitiys = $request->activityId;
            $hours = $request->hours2;
            $functionary = Functionary::where('user_id', $user->id)->first();
            $functionary_id = $functionary->id;
            for ($i = 0; $i < count($activitiys); $i++) {
                $registro = Hours::updateOrCreate(
                    ['funcionario_id' => $functionary_id, 'actividad_id' => $activitiys[$i]],
                    ['horasDeclaradas' => $hours[$i]]
                );
                if (is_null($registro->horasRealizadas)) {
                    $registro->horasRealizadas = 0;
                    $registro->save();
                }
                // Regist in logs events
                app('App\Http\Controllers\AdminController')->addLog('Actualizar horas por actividad', $registro->id, $registro->table);
            }
            return redirect('horas/edit')->with('status', 'Horas Actualizadas');
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
            'rut' => 'required|string|max:255',
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
            if ($request->email != $user->email) {
                $check = User::where('email', $request->email)->get();
                if ($check->count() > 0) {
                    return redirect(url()->previous())->with('err', 'El email ya se encuentra utilizado!');
                }
                $user->email = $request->email;
            }

            if ($request->rut != $user->rut) {
                $check = User::where('rut', $request->email)->get();
                if ($check->count() > 0) {
                    return redirect(url()->previous())->with('err', 'El rut ya se encuentra utilizado!');
                }
                $user->email = $request->email;
            }
            // Pass the new info for update
            $user->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar usuario', $user->id, $user->table);
        } else {
            // Redirect to the URL with warning status
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
        $user = User::find($request->id);
        // Update active to 1 bits
        $user->activa = 1;
        // Send update to database
        $user->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar usuario', $user->id, $user->table);
        // Get functionays
        $functionarys = Functionary::where('user_id', $user->id)->get();
        if ($functionarys->count() != 0) {
            foreach ($functionarys as $index => $record) {
                // Deactivate them
                $record->activa = 1;
                $record->save();
                // Regist in logs events
                app('App\Http\Controllers\AdminController')->addLog('Activar funcionario', $record->id, $record->table);
            }
        }
        // Redirect to the view with successful status (showing the DNI)
        return redirect('usuarios/inactivos')->with('status', 'Usuario ' . $request->id . ' reingresado');
    }
    
    public function deletingUser(Request $request)
    {
        // Get the user
        $user = User::find($request->id);
        // Update active to 0 bits
        $user->activa = 0;
        // Send update to database
        $user->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar usuario', $user->id, $user->table);
        // Get functionays
        $functionarys = Functionary::where('user_id', $user->id)->get();
        if ($functionarys->count() != 0) {
            foreach ($functionarys as $index => $record) {
                // Deactivate them
                $record->activa = 0;
                $record->save();
                // Regist in logs events
                app('App\Http\Controllers\AdminController')->addLog('Desactivar funcionario', $record->id, $record->table);
            }
        }
        // Redirect to the view with successful status (showing the DNI)
        return redirect('usuarios')->with('status', 'Usuario ' . $request->id . ' eliminado');
    }

    public function changeRolUser(Request $request)
    {
        // Get the user
        $user = User::find($request->rol);
        if ($user->rol == 1) $user->rol = 2;
        else if ($user->rol == 2) $user->rol = 1;
        $user->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Cambio de rol', $user->id, $user->table);
        // Redirect to the view with successful status (showing the DNI)
        return redirect(url()->previous())->with('status', 'Usuario ' . $request->id . ' con rol cambiado');
    }

    public function formatRut($index)
    {
        $sRut = $index->rut;
        $sRutFormateado = '';
        $digitoVerificador = substr($sRut, -1);
        if ($digitoVerificador) {
            $sDV = substr($sRut, -1);
            $sRut = substr($sRut, 0, -1);
        }
        while (strlen($sRut) > 3) {
            $sRutFormateado = "." . substr($sRut, -3) . $sRutFormateado;
            $sRut = substr($sRut, 0, strlen($sRut) - 3);
        }
        $sRutFormateado = $sRut . $sRutFormateado;
        if ($sRutFormateado != "" && $digitoVerificador) {
            $sRutFormateado = $sRutFormateado . "-" . $sDV;
        } else if ($digitoVerificador) {
            $sRutFormateado = $sRutFormateado . $sDV;
        }
        $index->run = $sRutFormateado;
        return $index;
    }
}
