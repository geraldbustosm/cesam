<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Speciality;
use App\Functionary;
use App\Provision;

class SpecialityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    INACTIVES
     ****************************************************************************************************************************/
    public function showInactiveSpeciality()
    {
        // Get specialitys from database where 'activa' attribute is 0 bits
        $data = Speciality::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.specialityInactive', ['data' => $data, 'table' => 'Especialidades']);
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddSpeciality()
    {
        // Get specialitys in alfabetic order
        $data = Speciality::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with list of specialitys (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.specialityForm', ['data' => $data, 'table' => 'Especialidades']);
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditSpeciality($id)
    {
        // Get the specific speciality
        $speciality = Speciality::find($id);
        // Redirect to the view with selected speciality
        return view('admin.Edit.specialityEdit', compact('speciality'));
    }
    /***************************************************************************************************************************
                                                    ASIGN
     ****************************************************************************************************************************/
    public function showAsignSpeciality()
    {
        // Get specialitys in alfabetic order
        $speciality = Speciality::orderBy('descripcion')->get();
        // Get functionarys
        $functionary = Functionary::where('activa', 1)->get();
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
        // Second loop (by functionary)
        foreach ($functionary as $index => $record1) {
            // Get the functionary_id and add it into the first position of ids
            $ids[0] = $record1->id;
            // Third loop (by speciality)
            foreach ($speciality as $index => $record2) {
                // Get the speciality_id and add it into the second position of ids
                $ids[1] = $record2->id;
                // Get full name of functionary and add it into rows
                $rows[$record1->user->primer_nombre . " " . $record1->user->apellido_paterno][$record2->descripcion] = $ids;
            }
        }
        // Redirect to the view with specialitys per each functionary
        return view('admin.Asignment.specialityAsign', compact('rows', 'columns'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerSpeciality(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'medical_speciality' => 'required|string|max:255|unique:especialidad,descripcion'
        ]);
        // Create a new 'object' speciality
        $speciality = new Speciality;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $speciality->descripcion = $request->medical_speciality;
        // Pass the new speciality to database
        $speciality->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar especialidad', $speciality->id, $speciality->table);
        $codigos = Provision::where('activa', '=', 1)->get('id');
        $speciality->provision()->sync($codigos);
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar glosas para especialidad id: ' . $speciality->id, $codigos, 'prestacion_posee_especialidad');
        // Redirect to the view with successful status
        return redirect('registrar/especialidad')->with('status', 'Nueva especialidad creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editSpeciality(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the speciality that want to update
        $speciality = Speciality::find($request->id);
        // URL to redirect when process finish.
        if ($speciality->activa == 1) $url = "/registrar/especialidad/";
        else $url = "/inactivo/especialidad/";
        // If found it then update the data
        if ($speciality) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            if ($speciality->descripcion != $request->descripcion) {
                $check = Speciality::where('descripcion', $request->descripcion)->count();
                if ($check == 0)  $speciality->descripcion = $request->descripcion;
                else return redirect($url)->with('err', 'Escpecialidad con el mismo nombre');
            }
            // Pass the new info for update
            $speciality->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar especialidad', $speciality->id, $speciality->table);
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción de la especialidad a "' . $request->descripcion . '"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'Nose pudo actualizar la descripción de la especialidad');
    }
    /***************************************************************************************************************************
                                                    ASIGN PROCESS
     ****************************************************************************************************************************/
    public function AsignSpeciality(Request $request)
    {
        if (isset($_POST['enviar'])) {
            $functionarys = Functionary::where('activa', 1)->get();
            foreach ($functionarys as $func) {
                $func->speciality()->sync([]);
            }
            if (isset($_POST['asignations'])) {
                if (is_array($_POST['asignations'])) {
                    foreach ($_POST['asignations'] as $key) {
                        $codigos = array();
                        foreach ($key as $key2 => $value) {
                            $str_arr = explode("|", $value);
                            $speciality = Speciality::find($str_arr[1]);
                            array_push($codigos, $speciality->id);
                            $functionary = Functionary::find($str_arr[0]);
                        }
                        $functionary->speciality()->sync($codigos);
                        // Regist in logs events
                    }
                }
            }
            return redirect('asignar/especialidad')->with('status', 'Especialidades actualizadas');
        }
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateSpeciality(Request $request)
    {
        // Get the data
        $data = Speciality::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar especialidad', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/especialidad')->with('status', 'Especialidad "' . $data->descripcion . '" re-activada');
    }

    public function deletingSpeciality(Request $request)
    {
        // Get the data
        $data = Speciality::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar especialidad', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/especialidad')->with('status', 'Especialidad "' . $data->descripcion . '" eliminada');
    }
}
