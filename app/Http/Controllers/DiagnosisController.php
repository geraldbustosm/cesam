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
                                                SHOW CREATE FORM
     ****************************************************************************************************************************/
    public function showAddDiagnosis()
    {
        // Get diagnosis in alfabetic order
        $data = Diagnosis::orderBy('descripcion')->get();
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
            'diagnosis' => 'required|string|max:382'
        ]);
        // Create a new 'object' diagnosis
        $diagnosis = new Diagnosis;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $diagnosis->descripcion = $request->diagnosis;
        // Pass the new diagnosis to database
        $diagnosis->save();
        // Redirect to the view with successful status
        return redirect('registrar/diagnostico')->with('status', 'Nuevo diagnostico creado');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editDiagnostic(Request $request)
    {
        // URL to redirect when process finish.
        $url = "/registrar/diagnostico/";
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the diagnostic that want to update
        $diagnostic = Diagnosis::find($request->id);
        // If found it then update the data
        if ($diagnostic) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $diagnostic->descripcion = $request->descripcion;
            // Pass the new info for update
            $diagnostic->save();
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción del diagnóstico a "'.$request->descripcion.'"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción del diagnóstico');
    }
}
