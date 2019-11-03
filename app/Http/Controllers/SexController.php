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
        $data = Sex::orderBy('descripcion')->get();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.sexInactive', ['data' => $data, 'table' => 'Géneros']);
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddSex()
    {
        // Get genders in alfabetic order
        $data = Sex::orderBy('descripcion')->get();
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
            'sexuality' => 'required|string|max:255'
        ]);
        // Create the new 'object' sex
        $sex = new Sex;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $sex->descripcion = $request->sexuality;
        // Pass the gender to database
        $sex->save();
        // Redirect to the view with successful status
        return redirect('registrar/genero')->with('status', 'Nuevo Sexo / Genero creado');
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
        if($sex->activa == 1){
            $url = "/registrar/genero/";
        }else{
            $url = "/inactivo/genero/";
        }
        // If found it then update the data
        if ($sex) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $sex->descripcion = $request->descripcion;
            // Pass the new info for update
            $sex->save();
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción de la sexualidad a "'.$request->descripcion.'"');
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
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/genero')->with('status', 'Género "' . $data->descripcion . '" re-activada');
    }

    public function deletingSex(Request $request)
    {
        // Get the data
        $data = Sex::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/genero')->with('status', 'Género "' . $data->descripcion . '" eliminada');
    }
}
