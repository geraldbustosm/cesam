<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Speciality;
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
        $speciality = Speciality::all();
        // Get programs in alfabetic order
        $data = Program::orderBy('descripcion')->get();
        // Redirect to the view with list of specialitys, programs (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.programForm', compact('speciality', 'data'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerProgram(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'program' => 'required|string|max:255',
            'especiality' => 'required'
        ]);
        // Create the new 'object' program
        $program = new Program;
        // Set the variable 'descripcion' and 'especialidad'
        // the variables name of object must be the same that database for save it
        $program->descripcion = $request->program;
        $program->especialidad = $request->especiality;
        // Pass the program to database
        $program->save();
        // Redirect to the view with successful status
        return redirect('registrar/programa')->with('status', 'Nuevo programa creado');
    }
}
