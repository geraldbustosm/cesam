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
        // URL to redirect when process finish.
        $url = "/registrar/genero/";
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the gender that want to update
        $sex = Sex::find($request->id);
        // If found it then update the data
        if ($sex) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $sex->descripcion = $request->descripcion;
            // Pass the new info for update
            $sex->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción de la sexualidad');
    }
}
