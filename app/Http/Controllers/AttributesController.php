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
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddAttributes()
    {
        // Get attributes in alfabetic order
        $data = Attributes::orderBy('descripcion')->get();
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
            'attribute' => 'required|string|max:255'
        ]);
        // Create a new 'object' attribute
        $attribute = new Attributes;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $attribute->descripcion = $request->attribute;
        // Pass the new attribute to database
        $attribute->save();
        // Redirect to the view with successful status
        return redirect('registrar/atributos')->with('status', 'Nuevo atributo creado');
    }
    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editAttribute(Request $request)
    {
        // URL to redirect when process finish.
        $url = "/registrar/atributos/";
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the attribute that want to update
        $attribute = Attributes::find($request->id);
        // If found it then update the data
        if ($attribute) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $attribute->descripcion = $request->descripcion;
            // Pass the new info for update
            $attribute->save();
            // Redirect to the URL with successful status
            return redirect($url)->with('status', 'Se actualizó la descripción del atributo a "'.$request->descripcion.'"');
        }
        // Redirect to the URL with failure status
        return redirect($url)->with('err', 'No se pudo actualizar la descripción del atributo');
    }
}
