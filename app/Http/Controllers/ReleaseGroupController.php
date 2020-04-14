<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReleaseGroup;

class ReleaseGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    INACTIVES
     ****************************************************************************************************************************/
    public function showInactiveReleaseGroup()
    {
        // Get releases from database where 'activa' attribute is 0 bits
        $data = ReleaseGroup::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of active releases
        return view('admin.Inactive.releaseGroupInactive', ['data' => $data, 'table' => 'Grupo-Altas']);
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddReleaseGroup()
    {
        // Get releases group in alfabetic order
        $data = ReleaseGroup::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with list of releases (standard name: data) and name of table in spanish (standard name: table) 
        return view('admin.Form.releaseGroupForm', ['data' => $data, 'table' => 'Grupo-Altas']);
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditReleaseGroup($id)
    {
        // Get the specific release
        $release = ReleaseGroup::find($id);
        // Redirect to the view with selected release
        return view('admin.Edit.releaseGroupEdit', compact('release'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerReleaseGroup(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'medical_discharge' => 'required|string|max:255|unique:grupo_alta,descripcion'
        ]);
        // Create a new 'object' release
        $release_group = new ReleaseGroup;
        // Set the variables to the object release
        // the variables name of object must be the same that database for save it
        // descripcion
        $release_group->descripcion = $request->medical_discharge;
        // Pass the release to database
        $release_group->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar grupo de altas', $release_group->id, $release_group->table);
        // Redirect to the view with successful status
        return redirect('registrar/grupo-altas')->with('status', 'Nueva alta creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editReleaseGroup(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the release that want to update
        $release = ReleaseGroup::find($request->id);
        // URL to redirect when process finish.
        if ($release->activa == 1) $url = "/registrar/grupo-altas/";
        else $url = "/inactivo/grupo-altas/";
        // If found it then update the data
        if ($release) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            if ($release->descripcion != $request->descripcion) {
                $check = ReleaseGroup::where('descripcion', $request->descripcion)->count();
                if ($check == 0)  $release->descripcion = $request->descripcion;
                else return redirect($url)->with('err', 'Grupo de Alta con el mismo nombre');
            }
            // Pass the new info for update
            $release->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar grupo de altas', $release->id, $release->table);
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción del alta a "' . $request->descripcion . '"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción del alta');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateReleaseGroup(Request $request)
    {
        // Get the data
        $data = ReleaseGroup::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar grupo de altas', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/grupo-altas')->with('status', 'Grupo-Altas "' . $data->descripcion . '" re-activada');
    }

    public function deletingReleaseGroup(Request $request)
    {
        // Get the data
        $data = ReleaseGroup::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar grupo de altas', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/grupo-altas')->with('status', 'Grupo-Altas "' . $data->descripcion . '" eliminada');
    }
}
