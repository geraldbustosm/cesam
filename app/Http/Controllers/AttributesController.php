<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attributes;

class AttributesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    INACTIVES
     ****************************************************************************************************************************/
    public function showInactiveAttribute()
    {
        // Get attributes from database where 'activa' attribute is 0 bits
        $data = Attributes::where('activa', 0)->orderBy('descripcion')->get();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Inactive.attributesInactive', ['data' => $data, 'table' => 'Atributos']);
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddAttributes()
    {
        // Get attributes in alfabetic order
        $data = Attributes::where('activa', 1)->orderBy('descripcion')->get();
        // Redirect to the view with list of attriutes (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.attributesForm', ['data' => $data, 'table' => 'Atributos']);
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditAttribute($id)
    {
        // Get the specific attribute
        $attribute = Attributes::find($id);
        // Redirect to the view with selected attribute
        return view('admin.Edit.attributeEdit', compact('attribute'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerAttributes(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'attribute' => 'required|string|max:255|unique:atributos,descripcion'
        ]);
        // Create a new 'object' attribute
        $attribute = new Attributes;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $attribute->descripcion = $request->attribute;
        // Pass the new attribute to database
        $attribute->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar atributo', $attribute->id, $attribute->table);
        // Redirect to the view with successful status
        return redirect('registrar/atributos')->with('status', 'Nuevo atributo creado');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editAttribute(Request $request)
    {
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the attribute that want to update
        $attribute = Attributes::find($request->id);
        // URL to redirect when process finish.
        if ($attribute->activa == 1) $url = "/registrar/atributos/";
        else $url = "/inactivo/atributo/";
        // If found it then update the data
        if ($attribute) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            if ($attribute->descripcion != $request->descripcion) {
                $check = Attributes::where('descripcion', $request->descripcion)->count();
                if ($check == 0)  $attribute->descripcion = $request->descripcion;
                else return redirect($url)->with('err', 'Atributo con el mismo nombre');
            }
            // Pass the new info for update
            $attribute->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar atributo', $attribute->id, $attribute->table);
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción del atributo a "' . $request->descripcion . '"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción del atributo');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activateAttribute(Request $request)
    {
        // Get the data
        $data = Attributes::find($request->id);
        // Update active to 1 bits
        $data->activa = 1;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar atributo', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('inactivo/atributo')->with('status', 'Atributo "' . $data->descripcion . '" re-activado');
    }

    public function deletingAttribute(Request $request)
    {
        // Get the data
        $data = Attributes::find($request->id);
        // Update active to 0 bits
        $data->activa = 0;
        // Send update to database
        $data->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar atributo', $data->id, $data->table);
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('registrar/atributos')->with('status', 'Atributo "' . $data->descripcion . '" eliminado');
    }
}
