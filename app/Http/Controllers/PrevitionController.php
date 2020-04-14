<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prevition;

class PrevitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    INACTIVES
     ****************************************************************************************************************************/
    public function showInactivePrevition()
    {
        // Get previtions from database where 'activa' attribute is 0 bits
        $data = Prevition::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.previtionInactive', ['data' => $data, 'table' => 'Previsiones']);
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddPrevition()
    {
        // Get previtions in alfabetic order
        $data = Prevition::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with list of previtions (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.previtionForm', ['data' => $data, 'table' => 'Previsiones']);
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditPrevition($id)
    {
        // Get the specific prevition
        $prevition = Prevition::find($id);
        // Redirect to the view with selected prevition
        return view('admin.Edit.previtionEdit', compact('prevition'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerPrevition(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'prevition' => 'required|string|max:255|unique:prevision,descripcion'
        ]);
        // Create the 'object' prevition
        $prevition = new Prevition;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $prevition->descripcion = $request->prevition;
        // Pass the prevition to database
        $prevition->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar previsión', $prevition->id, $prevition->table);
        // Redirect to the view with the successful status
        return redirect('registrar/prevision')->with('status', 'Nueva prevision creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editPrevition(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255|unique:prevision,descripcion',
        ]);
        // Get the prevition that want to update
        $prevition = Prevition::find($request->id);
        // URL to redirect when process finish.
        if ($prevition->activa == 1) $url = "/registrar/prevision/";
        else $url = "/inactivo/prevision/";
        // If found it then update the data
        if ($prevition) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            if ($prevition->descripcion != $request->descripcion) {
                $check = Prevition::where('descripcion', $request->descripcion)->count();
                if ($check == 0)  $prevition->descripcion = $request->descripcion;
                else return redirect($url)->with('err', 'Previsión con el mismo nombre');
            }
            // Pass the new info for update
            $prevition->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar previsión', $prevition->id, $prevition->table);
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción de la previsión a "' . $request->descripcion . '"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción de la previsión');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activatePrevition(Request $request)
    {
        // Get the data
        $data = Prevition::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar previsión', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/prevision')->with('status', 'Previsión "' . $data->descripcion . '" re-activada');
    }

    public function deletingPrevition(Request $request)
    {
        // Get the data
        $data = Prevition::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar previsión', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/prevision')->with('status', 'Previsión "' . $data->descripcion . '" eliminada');
    }
}
