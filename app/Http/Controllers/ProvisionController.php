<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Provision;
use App\Speciality;

class ProvisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddProvision()
    {
        // Get active types
        $type = Type::where('activa', 1)->get();
        // Get provisions
        $data = Provision::all();
        // Redirect to the view with list of types
        return view('admin.Form.provisionForm', compact('type', 'data'));
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/

    /***************************************************************************************************************************
                                                    ASIGN FORM
     ****************************************************************************************************************************/
    public function showAsignProvision()
    {
        // Get specialitys in alfabetic order
        $speciality = Speciality::orderBy('descripcion')->get();
        // Get provisions in alfabetic order
        $provision = Provision::orderBy('glosaTrasadora')->get();
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
        // Second loop (by provision)
        foreach ($provision as $index => $record1) {
            // Get the provision_id and add it into the first position of ids
            $ids[0] = $record1->id;
            // Third loop (by speciality)
            foreach ($speciality as $index => $record2) {
                // Get the speciality_id and add it into the second position of ids
                $ids[1] = $record2->id;
                // Get unique code and name of provision and add it into rows
                $rows[$record1->glosaTrasadora][$record2->descripcion] = $ids;
            }
        }
        // Redirect to the view with specialitys per each provision
        return view('admin.Asignment.provisionAsign', compact('rows', 'columns'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerProvision(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'frecuencia' => 'required|int',
            'glosa' => 'required|string|max:255',
            'ps_fam' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'lower_age' => 'required',
            'senior_age' => 'required',
            'medical_provision_type' => 'required'
        ]);
        // Create the new 'object' provision
        $provision = new Provision;
        // Set some variables with inputs of view
        // the variables name of object must be the same that database for save it
        // provision -> glosaTrasadora, frecuencia, ps_fam, codigo, rangoEdad_inferior, rangoEdad_superior, tipo_id (tipo prestación GES)
        $provision->glosaTrasadora = $request->glosa;
        $provision->frecuencia = $request->frecuencia;
        $provision->ps_fam = $request->ps_fam;
        $provision->codigo = $request->codigo;
        $provision->rangoEdad_inferior = $request->lower_age;
        $provision->rangoEdad_superior = $request->senior_age;
        $provision->tipo_id = $request->medical_provision_type;
        $provision->save();
        $codigos =  Speciality::where('activa', '=', 1)->get('id');
        $provision->speciality()->sync($codigos);
        // Redirect to the view with successful status
        return redirect('registrar/prestacion')->with('status', 'Nueva prestacion creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/

    /***************************************************************************************************************************
                                                    ASIGN PROCESS
     ****************************************************************************************************************************/
    public function AsignProvision(Request $request)
    {
        if (isset($_POST['enviar'])) {
            $provisions = Provision::where('activa', 1)->get();
            foreach ($provisions as $prov) {
                $prov->speciality()->sync([]);
            }
            if (isset($_POST['asignations'])) {
                if (is_array($_POST['asignations'])) {
                    foreach ($_POST['asignations'] as $key) {
                        $codigos = array();
                        foreach ($key as $key2 => $value) {
                            $str_arr = explode("|", $value);
                            $speciality = Speciality::find($str_arr[1]);
                            array_push($codigos, $speciality->id);
                            $provision = Provision::find($str_arr[0]);
                        }
                        $provision->speciality()->sync($codigos);
                    }
                }
            }
            return redirect('asignar/especialidad-prestacion')->with('status', 'Especialidades y Prestaciones actualizadas');
        }
    }
}
