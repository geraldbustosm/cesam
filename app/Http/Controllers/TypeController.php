<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Speciality;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddType()
    {
        // Get types in alfabetic order
        $data = Type::orderBy('descripcion')->get();
        // Redirect to the view with list of types (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.typeForm', ['data' => $data, 'table' => 'Tipos']);
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditType($id)
    {
        // Get the specific type
        $type = Type::find($id);
        // Redirect to the view with selected type
        return view('admin.Edit.typeEdit', compact('type'));
    }
    /***************************************************************************************************************************
                                                    ASIGN FORM
     ****************************************************************************************************************************/
    public function showAsignType()
    {
        // Get specialitys in alfabetic order
        $speciality = Speciality::orderBy('descripcion')->get();
        // Get functionarys in alfabetic order by profesions
        $type = Type::orderBy('descripcion')->get();
        // Create some variables
        $rows = [];
        $columns = [];
        $ids = [];

        // First loop (by speciality)
        foreach ($type as $index => $record) {
            // Get uniques profesions
            if (!in_array($record->descripcion, $columns)) {
                // Add the profesion into columns
                //$columns[] =  $record->descripcion ;
                array_push($columns, $record->descripcion);
            }
        }
        // Second loop (by functionary)
        foreach ($speciality as $index => $record1) {
            // Get the functionary_id and add it into the first position of ids
            $ids[0] = $record1->id;
            // Third loop (by speciality)
            foreach ($type as $index => $record2) {
                // Get the speciality_id and add it into the second position of ids
                $ids[1] = $record2->id;
                // Get full name of functionary and add it into rows
                $rows[$record1->descripcion][$record2->descripcion] = $ids;
            }
        }
        // Redirect to the view with specialitys per each functionary
        return view('admin.Asignment.typeAsign', compact('rows', 'columns'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerType(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'medical_provision_type' => 'required|string|max:255'
        ]);
        // Create the new 'object' type (of GES)
        $type = new Type;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $type->descripcion = $request->medical_provision_type;
        // Pass the type to database
        $type->save();
        // Redirect to the view with successful status
        return redirect('registrar/tipo')->with('status', 'Nuevo tipo de prestación creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editType(Request $request)
    {
        // URL to redirect when process finish.
        $url = "/registrar/tipo/";
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the type (of GES) that want to update
        $type = Type::find($request->id);
        // If found it then update the data
        if ($type) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $type->descripcion = $request->descripcion;
            // Pass the new info for update
            $type->save();
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción de la prestación a "'.$request->descripcion.'"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción de la prestación');
    }
    /***************************************************************************************************************************
                                                    ASIGN PROCESS
     ****************************************************************************************************************************/
    public function AsignType(Request $request)
    {
        if (isset($_POST['enviar'])) {
            $speciality = Speciality::where('activa', 1)->get();
            foreach ($speciality as $func) {
                $func->type()->sync([]);
            }
            if (isset($_POST['asignations'])) {
                if (is_array($_POST['asignations'])) {
                    foreach ($_POST['asignations'] as $key) {
                        $codigos = array();
                        foreach ($key as $key2 => $value) {
                            $str_arr = explode("|", $value);
                            $type = Type::find($str_arr[1]);
                            array_push($codigos, $type->id);
                            $speciality = Speciality::find($str_arr[0]);
                        }
                        $speciality->type()->sync($codigos);
                    }
                }
            }
            return redirect('asignar/especialidad-tipo')->with('status', 'Especialidades actualizadas');
        }
    }
}
