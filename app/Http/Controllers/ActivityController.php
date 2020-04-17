<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Speciality;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    INACTIVES
     ****************************************************************************************************************************/
    public function showInactiveActivity()
    {
        // Get activities from database where 'activa' attribute is 0 bits
        $data = Activity::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.activityInactive', ['data' => $data, 'table' => 'Actividades']);
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddActivity()
    {
        // Get activities in alfabetic order
        $data = Activity::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with list of activitis (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.activityForm', ['data' => $data, 'table' => 'Actividades']);
    }
    /***************************************************************************************************************************
                                                    ASIGN FORM
     ****************************************************************************************************************************/
    public function showAsignActivity()
    {
        // Get specialitys in alfabetic order
        $speciality = Speciality::orderBy('descripcion')->get();
        // Get activity's in alfabetic order
        $activity = Activity::orderBy('descripcion')->get();
        // Create some variables
        $rows = [];
        $columns = [];
        $ids = [];
        // First loop (by speciality)
        foreach ($speciality as $index => $record) {
            // Get uniques profesions
            if (!in_array($record->profesion, $columns)) {
                // Add the profesion into columns
                array_push($columns, $record->descripcion);
            }
        }
        // Second loop (by activity)
        foreach ($activity as $index => $record1) {
            // Get the activity and add it into the first position of ids
            $ids[0] = $record1->id;
            // Third loop (by speciality)
            foreach ($speciality as $index => $record2) {
                // Get the speciality_id and add it into the second position of ids
                $ids[1] = $record2->id;
                // Get unique code and name of provision and add it into rows
                $rows[$record1->descripcion][$record2->descripcion] = $ids;
            }
        }
        // Redirect to the view with specialitys per each provision
        return view('admin.Asignment.activityAsign', compact('rows', 'columns'));
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditActivity($id)
    {
        // Get the specific activity
        $activity = Activity::find($id);
        // Redirect to the view with selected activity
        return view('admin.Edit.activityEdit', compact('activity'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerActivity(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'activity' => 'required|string|max:255|unique:actividad,descripcion'
        ]);
        // Create a new 'object' activity
        $activity = new Activity;
        // Set the variables to the object activity
        // the variables name of object must be the same that database for save it
        $activity->descripcion = $request->activity;
        $activity->actividad_abre_canasta = $request->input('openCanasta', 0);
        $activity->sin_asistencia = $request->input('noAssist', 0);
        // Pass the activity to database
        $activity->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Insertar', $activity->id, $activity->table);
        // Redirect to the view with successful status
        return redirect('registrar/actividad')->with('status', 'Nueva actividad creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editActivity(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the release that want to update
        $activity = Activity::find($request->id);
        // URL to redirect when process finish.
        if ($activity->activa == 1) $url = "/registrar/actividad/";
        else $url = "/inactivo/actividad/";
        // If found it then update the data
        if ($activity) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            if ($activity->descripcion != $request->descripcion) {
                $check = Activity::where('descripcion', $request->descripcion)->count();
                if ($check == 0) $activity->descripcion = $request->descripcion;
                else return redirect($url)->with('err', 'Actividad con el mismo nombre');
            }
            // Set variable openCanasta when that option was clicked
            $activity->actividad_abre_canasta = $request->input('openCanasta', 0);
            $activity->sin_asistencia = $request->input('noAssist', 0);
            // Pass the new info for update
            $activity->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar', $activity->id, $activity->table);
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la información de la actividad a "' . $request->descripcion . '"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la información de la actividad');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function AsignActivity(Request $request)
    {
        if (isset($_POST['enviar'])) {
            $activity = Activity::where('activa', 1)->get();
            foreach ($activity as $acty) {
                $acty->speciality()->sync([]);
            }
            if (isset($_POST['asignations'])) {
                if (is_array($_POST['asignations'])) {
                    foreach ($_POST['asignations'] as $key) {
                        $codigos = array();
                        foreach ($key as $key2 => $value) {
                            $str_arr = explode("|", $value);
                            $speciality = Speciality::find($str_arr[1]);
                            array_push($codigos, $speciality->id);
                            $activity = Activity::find($str_arr[0]);
                        }
                        $activity->speciality()->sync($codigos);
                        app('App\Http\Controllers\AdminController')->addLog('Asignar especialidades a actividad id: ' . $activity->id, $codigos, 'actividad_posee_especialidad');
                    }
                }
            }
            return redirect('asignar/especialidad-actividad')->with('status', 'Especialidades y Prestaciones actualizadas');
        }
    }

    public function activateActivity(Request $request)
    {
        // Get the data
        $data = Activity::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/actividad')->with('status', 'Actividad "' . $data->descripcion . '" re-activada');
    }

    public function deletingActivity(Request $request)
    {
        // Get the data
        $data = Activity::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/actividad')->with('status', 'Actividad "' . $data->descripcion . '" eliminada');
    }
}
