<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prevition;

class PrevitionController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
    ****************************************************************************************************************************/
    public function showAddPrevition()
    {
        // Get previtions in alfabetic order
        $data = Prevition::orderBy('descripcion')->get();
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
            'prevition' => 'required|string|max:255'
        ]);
        // Create the 'object' prevition
        $prevition = new Prevition;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $prevition->descripcion = $request->prevition;
        // Pass the prevition to database
        $prevition->save();
        // Redirect to the view with the successful status
        return redirect('registrar/prevision')->with('status', 'Nueva prevision creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
    ****************************************************************************************************************************/
    public function editPrevition(Request $request)
    {
        // URL to redirect when process finish.
        $url = "prevision/edit/" . $request->id;
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the prevition that want to update
        $prevition = Prevition::find($request->id);
        // If found it then update the data
        if ($prevition) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $prevition->descripcion = $request->descripcion;
            // Pass the new info for update
            $prevition->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción de la previsión');
    }
}
