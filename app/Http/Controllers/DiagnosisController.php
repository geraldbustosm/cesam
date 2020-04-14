<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Diagnosis;

class DiagnosisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    INACTIVES
     ****************************************************************************************************************************/
    public function showInactiveDiagnosis()
    {
        // Get diagnosis from database where 'activa' attribute is 0 bits
        $data = Diagnosis::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.diagnosisInactive', ['data' => $data, 'table' => 'Diagnósticos']);
    }
    /***************************************************************************************************************************
                                                SHOW CREATE FORM
     ****************************************************************************************************************************/
    public function showAddDiagnosis()
    {
        // Get diagnosis in alfabetic order
        $data = Diagnosis::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with list of diagnosis (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.diagnosisForm', ['data' => $data, 'table' => 'Diagnósticos']);
    }
    /***************************************************************************************************************************
                                                SHOW EDIT FORM
     ****************************************************************************************************************************/
    public function showEditDiagnostic($id)
    {
        // Get the specific diagnosis
        $diagnostic = Diagnosis::find($id);
        // Redirect to the view with selected diagnosis
        return view('admin.Edit.diagnosticEdit', compact('diagnostic'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerDiagnosis(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'diagnosis' => 'required|string|max:255|unique:diagnostico,descripcion'
        ]);
        // Create a new 'object' diagnosis
        $diagnosis = new Diagnosis;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $diagnosis->descripcion = $request->diagnosis;
        // Pass the new diagnosis to database
        $diagnosis->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar diagnóstico', $diagnosis->id, $diagnosis->table);
        // Redirect to the view with successful status
        return redirect('registrar/diagnostico')->with('status', 'Nuevo diagnostico creado');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editDiagnostic(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the diagnostic that want to update
        $diagnostic = Diagnosis::find($request->id);
        // URL to redirect when process finish.
        if($diagnostic->activa == 1) $url = "/registrar/diagnostico/";
        else $url = "/inactivo/diagnostico/";
        // If found it then update the data
        if ($diagnostic) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            if ($diagnostic->descripcion != $request->descripcion) {
                $check = Diagnosis::where('descripcion', $request->descripcion)->count();
                if ($check == 0)  $diagnostic->descripcion = $request->descripcion;
                else return redirect($url)->with('err', 'Diagnóstico con el mismo nombre');
            }
            // Pass the new info for update
            $diagnostic->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar diagnóstico', $diagnostic->id, $diagnostic->table);
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción del diagnóstico a "'.$request->descripcion.'"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción del diagnóstico');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateDiagnosis(Request $request)
    {
        // Get the data
        $data = Diagnosis::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar diagnóstico', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/diagnostico')->with('status', 'Diagnóstico "' . $data->descripcion . '" re-activado');
    }

    public function deletingDiagnosis(Request $request)
    {
        // Get the data
        $data = Diagnosis::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar diagnóstico', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/diagnostico')->with('status', 'Diagnóstico "' . $data->descripcion . '" eliminado');
    }
}
