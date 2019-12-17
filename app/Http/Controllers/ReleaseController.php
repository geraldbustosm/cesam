<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Release;
use App\ReleaseGroup;

class ReleaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    INACTIVES
     ****************************************************************************************************************************/
    public function showInactiveRelease()
    {
        // Get releases from database where 'activa' attribute is 0 bits
        $data = Release::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of active releases
        return view('admin.Inactive.releaseInactive', ['data' => $data, 'table' => 'Altas']);
    }
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
    public function showAddRelease()
    {
        // Get releases in alfabetic order
        $data = Release::where('activa', 1)->orderBy('descripcion')->get();
        // Get releases group in alfabetic order
        $list = ReleaseGroup::where('activa', 1)->orderBy('descripcion')->get();
        if ($list->count() == 0) {
            return redirect('/registrar/grupo-altas')->with('err', 'Primero debe crear un Grupo de Altas');
        }
        // Redirect to the view with list of releases (standard name: data) and name of table in spanish (standard name: table) 
        return view('admin.Form.releaseForm', ['data' => $data, 'table' => 'Altas', 'list' => $list]);
    }
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
    public function showEditRelease($id)
    {
        // Get the specific release
        $release = Release::find($id);
        // Get releases group in alfabetic order
        $list = ReleaseGroup::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with selected release
        return view('admin.Edit.releaseEdit', compact('release', 'list'));
    }
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
        $release->grupo_id = $request->releases_group;
        // Pass the release to database
        $release->save();
        // Redirect to the view with successful status
        return redirect('registrar/alta')->with('status', 'Nueva alta creada');
    }
    public function registerReleaseGroup(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'medical_discharge' => 'required|string|max:255'
        ]);
        // Create a new 'object' release
        $release_group = new ReleaseGroup;
        // Set the variables to the object release
        // the variables name of object must be the same that database for save it
        // descripcion
        $release_group->descripcion = $request->medical_discharge;
        // Pass the release to database
        $release_group->save();
        // Redirect to the view with successful status
        return redirect('registrar/grupo-altas')->with('status', 'Nueva alta creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editRelease(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the release that want to update
        $release = Release::find($request->id);
        // URL to redirect when process finish.
        if ($release->activa == 1) {
            $url = "/registrar/alta/";
        } else {
            $url = "/inactivo/alta/";
        }
        // If found it then update the data
        if ($release) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $release->descripcion = $request->descripcion;
            $release->grupo_id = $request->grupo;
            // Pass the new info for update
            $release->save();
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción del alta a "' . $request->descripcion . '"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción del alta');
    }
    public function editReleaseGroup(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the release that want to update
        $release = ReleaseGroup::find($request->id);
        // URL to redirect when process finish.
        if ($release->activa == 1) {
            $url = "/registrar/grupo-altas/";
        } else {
            $url = "/inactivo/grupo-altas/";
        }
        // If found it then update the data
        if ($release) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $release->descripcion = $request->descripcion;
            // Pass the new info for update
            $release->save();
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción del alta a "' . $request->descripcion . '"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción del alta');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateRelease(Request $request)
    {
        // Get the data
        $data = Release::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/alta')->with('status', 'Alta "' . $data->descripcion . '" re-activada');
    }
    public function deletingRelease(Request $request)
    {
        // Get the data
        $data = Release::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/alta')->with('status', 'Alta "' . $data->descripcion . '" eliminada');
    }
    public function activateReleaseGroup(Request $request)
    {
        // Get the data
        $data = ReleaseGroup::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
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
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/grupo-altas')->with('status', 'Grupo-Altas "' . $data->descripcion . '" eliminada');
    }
}
