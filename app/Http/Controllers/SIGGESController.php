<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiGGES;

class SIGGESController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    INACTIVES
     ****************************************************************************************************************************/
    public function showInactiveSiGGES()
    {
        // Get sigges from database where 'activa' attribute is 0 bits
        $data = SiGGES::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.siggesInactive', ['data' => $data, 'table' => 'SiGGES']);
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddSIGGES()
    {
        // Get sigges in alfabetic order
        $data = SiGGES::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with list of sigges (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.siggesForm', ['data' => $data, 'table' => 'SiGGES']);
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditSiGGES($id)
    {
        // Get the specific sigges
        $sigges = SiGGES::find($id);
        // Redirect to the view with selected sigges
        return view('admin.Edit.siggesEdit', compact('sigges'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerSIGGES(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'sigges' => 'required|string|max:255|unique:sigges,descripcion'
        ]);
        // Create the new 'object' sigges
        $sigges = new SiGGES;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $sigges->descripcion = $request->sigges;
        // Pass the sigges to databes
        $sigges->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar SiGGES', $sigges->id, $sigges->table);
        // Redirect to the view with successful status
        return redirect('registrar/sigges')->with('status', 'Nuevo tipo de SiGGES creado');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editSiGGES(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the gender that want to update
        $sigges = SiGGES::find($request->id);
        // URL to redirect when process finish.
        if ($sigges->activa == 1) $url = "/registrar/sigges/";
        else $url = "/inactivo/sigges/";
        // If found it then update the data
        if ($sigges) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            if ($sigges->descripcion != $request->descripcion) {
                $check = SiGGES::where('descripcion', $request->descripcion)->count();
                if ($check == 0)  $sigges->descripcion = $request->descripcion;
                else return redirect($url)->with('err', 'SiGGES con el mismo nombre');
            }
            // Pass the new info for update
            $sigges->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar SiGGES', $sigges->id, $sigges->table);
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción del tipo GES a "' . $request->descripcion . '"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción del tipo GES');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateSiGGES(Request $request)
    {
        // Get the data
        $data = SiGGES::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar SiGGES', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/sigges')->with('status', 'SiGGES "' . $data->descripcion . '" re-activado');
    }

    public function deletingSiGGES(Request $request)
    {
        // Get the data
        $data = SiGGES::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar SiGGES', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/sigges')->with('status', 'SiGGES "' . $data->descripcion . '" eliminado');
    }
}
