<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SpecialityProgram;
use App\Functionary;
use App\Provision;

class SpecialityProgramController extends Controller
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
        $data = SpecialityProgram::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.specialityProgramInactive', ['data' => $data, 'table' => 'Especialidad-Glosa']);
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddSpeciality()
    {
        // Get specialitys in alfabetic order
        $data = SpecialityProgram::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with list of specialitys (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.specialityProgramForm', ['data' => $data, 'table' => 'Especialidad-Glosa']);
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditSpeciality($id)
    {
        // Get the specific speciality
        $speciality = SpecialityProgram::find($id);
        // Redirect to the view with selected speciality
        return view('admin.Edit.specialityProgramEdit', compact('speciality'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerSpeciality(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'medical_speciality' => 'required|string|max:255',
            'code_speciality' => 'required|string|max:255|unique:especialidad_programa,codigo',
        ]);
        // Create a new 'object' speciality
        $speciality = new SpecialityProgram;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $speciality->descripcion = $request->medical_speciality;
        $speciality->codigo = $request->code_speciality;
        // Pass the new speciality to database
        $speciality->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar programa por especialidad', $speciality->id, $speciality->table);
        // Redirect to the view with successful status
        return redirect('registrar/especialidad-glosa')->with('status', 'Nueva especialidad creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editSpeciality(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
        ]);
        // Get the speciality that want to update
        $speciality = SpecialityProgram::find($request->id);
        // URL to redirect when process finish.
        if($speciality->activa == 1){
            $url = "/registrar/especialidad-glosa/";
        }else{
            $url = "/inactivo/especialidad-glosa/";
        }
        // If found it then update the data
        if ($speciality) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $speciality->descripcion = $request->descripcion;
            if ($speciality->codigo != $request->codigo) {
                $check = SpecialityProgram::where('descripcion', $request->descripcion)->count();
                if ($check == 0)  $speciality->codigo = $request->codigo;
                else return redirect($url)->with('err', 'Escpecialidad con el mismo nombre');
            }
            // Pass the new info for update
            $speciality->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar programa por especialidad', $speciality->id, $speciality->table);
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción de la especialidad a "'.$request->descripcion.'"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'Nose pudo actualizar la descripción de la especialidad');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateSpeciality(Request $request)
    {
        // Get the data
        $data = SpecialityProgram::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar programa por especialidad', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/especialidad-glosa')->with('status', 'Especialidad "' . $data->descripcion . '" re-activada');
    }

    public function deletingSpeciality(Request $request)
    {
        // Get the data
        $data = SpecialityProgram::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar programa por especialidad', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/especialidad-glosa')->with('status', 'Especialidad "' . $data->descripcion . '" eliminada');
    }
}
