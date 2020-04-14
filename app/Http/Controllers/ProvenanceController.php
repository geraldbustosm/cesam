<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Provenance;

class ProvenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    INACTIVES
     ****************************************************************************************************************************/
    public function showInactiveProvenance()
    {
        // Get prevenances from database where 'activa' attribute is 0 bits
        $data = Provenance::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.provenanceInactive', ['data' => $data, 'table' => 'Procedencias']);
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddProvenance()
    {
        // Get prevenances in alfabetic order
        $data = Provenance::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with list of prevenances (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.provenanceForm', ['data' => $data, 'table' => 'Procedencias']);
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditProvenance($id)
    {
        // Get the specific provenance
        $provenance = Provenance::find($id);
        // Redirect to the view with selected provenance
        return view('admin.Edit.provenanceEdit', compact('provenance'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerProvenance(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'provenance' => 'required|string|max:255|unique:procedencia,descripcion'
        ]);
        // Create the new 'object' provenance
        $provenance = new Provenance;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $provenance->descripcion = $request->provenance;
        // Pass the provenance to database
        $provenance->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar procedencia', $provenance->id, $provenance->table);
        // Redirect to the view with successful status
        return redirect('registrar/procedencia')->with('status', 'Nueva procedencia creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editProvenance(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);
        // Get the provenance that want to update
        $provenance = Provenance::find($request->id);
        // URL to redirect when process finish.
        if ($provenance->activa == 1) $url = "/registrar/procedencia/";
        else $url = "/inactivo/procedencia/";
        // If found it then update the data
        if ($provenance) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            if ($provenance->descripcion != $request->descripcion) {
                $check = Provenance::where('descripcion', $request->descripcion)->count();
                if ($check == 0)  $provenance->descripcion = $request->descripcion;
                else return redirect($url)->with('err', 'Procendencia con el mismo nombre');
            }
            // Pass the new info for update
            $provenance->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar procedencia', $provenance->id, $provenance->table);
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción de la procedencia a "' . $request->descripcion . '"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción de la procedencia');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateProvenance(Request $request)
    {
        // Get the data
        $data = Provenance::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar procedencia', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/procedencia')->with('status', 'Procedencia "' . $data->descripcion . '" re-activada');
    }

    public function deletingProvenance(Request $request)
    {
        // Get the data
        $data = Provenance::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar procedencia', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/procedencia')->with('status', 'Procedencia "' . $data->descripcion . '" eliminada');
    }
}
