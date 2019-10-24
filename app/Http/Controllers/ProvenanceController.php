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
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddProvenance()
    {
        // Get prevenances in alfabetic order
        $data = Provenance::orderBy('descripcion')->get();
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
            'provenance' => 'required|string|max:255'
        ]);
        // Create the new 'object' provenance
        $provenance = new Provenance;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $provenance->descripcion = $request->provenance;
        // Pass the provenance to database
        $provenance->save();
        // Redirect to the view with successful status
        return redirect('registrar/procedencia')->with('status', 'Nueva procedencia creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editProvenance(Request $request)
    {
        // URL to redirect when process finish.
        $url = "procedencia/edit/" . $request->id;
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the provenance that want to update
        $provenance = Provenance::find($request->id);
        // If found it then update the data
        if ($provenance) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $provenance->descripcion = $request->descripcion;
            // Pass the new info for update
            $provenance->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción de la procedencia');
    }
}
