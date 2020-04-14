<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SpecialityProgram;
use App\Program;

class ProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddProgram()
    {
        // Get list of specialitys
        $speciality = SpecialityProgram::where('activa', 1)->get();
        // Get programs in alfabetic order
        $data = Program::where('activa', 1)->orderBy('descripcion')->get();
        $table = "Programas";
        // Redirect to the view with list of specialitys, programs (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.programForm', compact('speciality', 'data', 'table'));
    }
    public function showInactiveProgram()
    {
        // Get programs in alfabetic order
        $data = Program::where('activa', 0)->orderBy('descripcion')->get();
        $table = "Programas";
        // Redirect to the view with list of specialitys, programs (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Inactive.programInactive', compact('data', 'table'));
    }
    
    /***************************************************************************************************************************
                                                    EDIT FORM
    ****************************************************************************************************************************/
    
    public function showEditProgram($id){

        // Get speciality-program
        $data = SpecialityProgram::where('activa', 1)->get();
        $program = Program::find($id);
        $specialityprogram = SpecialityProgram::find($program->especialidad_programa_id);
        return view('admin.Edit.programEdit', compact('data', 'program', 'specialityprogram'));
    }

    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editProgram(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the program that want to update
        $program = Program::find($request->id);
        // URL to redirect
        if($program->activa == 1) $url = "/registrar/programa/";
        else $url = "/inactivo/programa/";
        // Set the new info
        $program->descripcion = $request->descripcion;
        $program->especialidad_programa_id = $request->speciality;
        // Save changes
        $program->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Actualizar programa', $program->id, $program->table);
        return redirect($url)->with('status', 'Se ha actualizado el programa');
    }

    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerProgram(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'program' => 'required|string|max:255',
            'speciality' => 'required|int'
        ]);
        // Create the new 'object' program
        $program = new Program;
        // Set the variable 'descripcion' and 'especialidad'
        // the variables name of object must be the same that database for save it
        $program->descripcion = $request->program;
        $program->especialidad_programa_id = $request->speciality;
        // Pass the program to database
        $program->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar programa', $program->id, $program->table);
        // Redirect to the view with successful status
        return redirect('registrar/programa')->with('status', 'Nuevo programa creado');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateProgram(Request $request)
    {
        // Get the data
        $data = Program::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar programa', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/programa')->with('status', 'Previsión "' . $data->descripcion . '" re-activada');
    }

    public function deletingProgram(Request $request)
    {
        // Get the data
        $data = Program::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar programa', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/programa')->with('status', 'Previsión "' . $data->descripcion . '" eliminada');
    }
}
