<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Release;

class ReleaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddRelease()
    {
        // Get releases in alfabetic order
        $data = Release::orderBy('descripcion')->get();
        // Redirect to the view with list of releases (standard name: data) and name of table in spanish (standard name: table) 
        return view('admin.Form.releaseForm', ['data' => $data, 'table' => 'Altas']);
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditRelease($id)
    {
        // Get the specific release
        $release = Release::find($id);
        // Redirect to the view with selected release
        return view('admin.Edit.releaseEdit', compact('release'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerRelease(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'medical_discharge' => 'required|string|max:255'
        ]);
        // Create a new 'object' release
        $release = new Release;
        // Set the variables to the object release
        // the variables name of object must be the same that database for save it
        // descripcion
        $release->descripcion = $request->medical_discharge;
        // Pass the release to database
        $release->save();
        // Redirect to the view with successful status
        return redirect('registrar/alta')->with('status', 'Nueva alta creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editRelease(Request $request)
    {
        // URL to redirect when process finish.
        $url = "/registrar/alta/";
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the release that want to update
        $release = Release::find($request->id);
        // If found it then update the data
        if ($release) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $release->descripcion = $request->descripcion;
            // Pass the new info for update
            $release->save();
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción del alta a "'.$request->descripcion.'"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción del alta');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
}
