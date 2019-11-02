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
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddSIGGES()
    {
        // Get sigges in alfabetic order
        $data = SiGGES::orderBy('descripcion')->get();
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
            'sigges' => 'required|string|max:255'
        ]);
        // Create the new 'object' sigges
        $sigges = new SiGGES;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $sigges->descripcion = $request->sigges;
        // Pass the sigges to databes
        $sigges->save();
        // Redirect to the view with successful status
        return redirect('registrar/sigges')->with('status', 'Nuevo tipo de SiGGES creado');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editSiGGES(Request $request)
    {
        // URL to redirect when process finish.
        $url = "/registrar/sigges/";
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the gender that want to update
        $sigges = SiGGES::find($request->id);
        // If found it then update the data
        if ($sigges) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $sigges->descripcion = $request->descripcion;
            // Pass the new info for update
            $sigges->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción del tipo GES');
    }
}
