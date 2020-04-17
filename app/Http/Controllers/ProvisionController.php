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
    public function showInactiveProvision()
    {
        // Get active types
        $type = Type::where('activa', 1)->get();
        // Get provisions
        $data = Provision::where('activa', 0)->get();
        // Redirect to the view with list of types
        return view('admin.Inactive.provisionInactive', ['table' => 'Glosas'], compact('type', 'data'));
    }
    public function showAddProvision()
    {
        // Get active types
        $type = Type::where('activa', 1)->get();
        // Get provisions
        $data = Provision::where('activa', 1)->get();
        // Check if are at least one type to add
        if ($type->count() == 0) {
            return redirect('/registrar/tipo')->with('err', 'Primero debe crear algun tipo (GES/PPV)');
        }
        // Else redirect to the view with list of types
        return view('admin.Form.provisionForm', ['table' => 'Glosas'], compact('type', 'data'));
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditProvision($id)
    {
        $provision = Provision::find($id);
        $tipos_prestaciones = Type::where('activa', 1)->get();
        $tipo_prestacion = Type::find($provision->tipo_id);
        return view('admin.Edit.provisionEdit', compact('provision', 'tipos_prestaciones', 'tipo_prestacion'));
    }
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
            'frecuencia' => 'required|string|max:255',
            'glosa' => 'required|string|max:255|unique:prestacion,glosaTrasadora',
            'ps_fam' => 'required|string|max:255',
            'codigo' => 'required|string|max:255|unique:prestacion,codigo',
            'lower_age' => 'required',
            'senior_age' => 'required',
            'medical_provision_type' => 'required'
        ]);

        if ($request->lower_age > $request->senior_age && $request->senior_age != 0) {
            return redirect('registrar/prestacion')->with('error', 'El rango menor es más grande que el rango mayor');
        }
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
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar glosa', $provision->id, $provision->table);
        $codigos =  Speciality::where('activa', '=', 1)->pluck('id');
        $provision->speciality()->sync($codigos);
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar especialidad para glosa' . $provision->id, $codigos, 'prestacion_posee_especialidad');
        // Redirect to the view with successful status
        return redirect('registrar/prestacion')->with('status', 'Nueva prestacion creada');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editProvision(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'glosaTrasadora' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'frecuencia' => 'required|numeric',
            'ps_fam' => 'required|string|max:255',
        ]);

        // Get the program that want to update
        $provision = Provision::find($request->id);
        // URL to redirect
        if ($provision->activa == 1) $url = "/registrar/prestacion/";
        else $url = "/inactivo/prestacion/";

        if ($request->lower_age > $request->senior_age) return redirect($url)->with('error', 'El rango menor es más grande que el rango mayor');
        if ($provision->count() != 0) {
            if ($provision->glosaTrasadora != $request->glosaTrasadora) {
                $check = Provision::where('descripcion', $request->glosaTrasadora)->count();
                if ($check == 0)  $provision->glosaTrasadora = $request->glosaTrasadora;
                else return redirect($url)->with('err', 'Glosa con el mismo nombre');
            }
            if ($provision->codigo != $request->codigo) {
                $check = Provision::where('descripcion', $request->codigo)->count();
                if ($check == 0)  $provision->codigo = $request->codigo;
                else return redirect($url)->with('err', 'Glosa con el mismo código');
            }
            $provision->rangoEdad_inferior = $request->lower_age;
            $provision->rangoEdad_superior = $request->senior_age;
            $provision->frecuencia = $request->frecuencia;
            $provision->ps_fam = $request->ps_fam;
            $provision->tipo_id = $request->tipo_prestacion;
            // Save changes
            $provision->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar glosa', $provision->id, $provision->table);
            return redirect($url)->with('status', 'Se actualizaron los datos de la prestación');
        }
        return redirect($url)->with('err', 'No se actualizaron los datos de la prestación');
    }
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
                        // Regist in logs events

                    }
                }
            }
            return redirect('asignar/especialidad-prestacion')->with('status', 'Especialidades y Prestaciones actualizadas');
        }
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateProvision(Request $request)
    {
        // Get the data
        $data = Provision::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar glosa', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/prestacion')->with('status', 'Glosa "' . $data->glosaTrasadora . '" re-activada');
    }

    public function deletingProvision(Request $request)
    {
        // Get the data
        $data = Provision::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar glosa', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/prestacion')->with('status', 'Glosa "' . $data->glosaTrasadora . '" eliminada');
    }
}
