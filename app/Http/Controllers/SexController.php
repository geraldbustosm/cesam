<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sex;

class SexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    INACTIVES
     ****************************************************************************************************************************/
    public function showInactiveSex()
    {
        // Get genders from database where 'activa' attribute is 0 bits
        $data = Sex::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.sexInactive', ['data' => $data, 'table' => 'Géneros']);
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddSex()
    {
        // Get genders in alfabetic order
        $data = Sex::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with list of genders (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.sexForm', ['data' => $data, 'table' => 'Géneros']);
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditSex($id)
    {
        // Get the specific gender
        $sex = Sex::find($id);
        // Redirect to the view with selected gender
        return view('admin.Edit.sexEdit', compact('sex'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerSex(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'sexuality' => 'required|string|max:255|unique:sexo,descripcion'
        ]);
        // Create the new 'object' sex
        $sex = new Sex;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $sex->descripcion = $request->sexuality;
        // Pass the gender to database
        $sex->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar sexo', $sex->id, $sex->table);
        // Redirect to the view with successful status
        return redirect('registrar/genero')->with('status', 'Nuevo Sexo / Género creado');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editSex(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the gender that want to update
        $sex = Sex::find($request->id);
        // URL to redirect when process finish.
        if ($sex->activa == 1) $url = "/registrar/genero/";
        else $url = "/inactivo/genero/";
        if ($request->id == 0 || $request->id == 1) return redirect($url)->with('err', 'No se puede editar estos valores');
        // If found it then update the data
        if ($sex) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            if ($sex->descripcion != $request->descripcion) {
                $check = Sex::where('descripcion', $request->descripcion)->count();
                if ($check == 0)  $sex->descripcion = $request->descripcion;
                else return redirect($url)->with('err', 'Sexo con el mismo nombre');
            }
            // Pass the new info for update
            $sex->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar sexo', $sex->id, $sex->table);
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción de la sexualidad a "' . $request->descripcion . '"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción de la sexualidad');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateSex(Request $request)
    {
        // Get the data
        $data = Sex::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar sexo', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/genero')->with('status', 'Género "' . $data->descripcion . '" re-activada');
    }

    public function deletingSex(Request $request)
    {
        if ($request->id == 0 || $request->id == 1) return redirect(url()->previous())->with('err', 'No se puede eliminar estos valores');
        // Get the data
        $data = Sex::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar sexo', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/genero')->with('status', 'Género "' . $data->descripcion . '" eliminada');
    }
}
