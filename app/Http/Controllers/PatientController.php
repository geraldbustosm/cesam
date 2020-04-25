<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Prevition;
use App\Address;
use App\Sex;
use App\Attributes;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /***************************************************************************************************************************
                                                    SHOW
     ****************************************************************************************************************************/
    public function showPatients()
    {
        // Get patients from database where 'activa' attribute is TRUE (1 bit)
        $patients = $this->getPatients(1);
        // Count patients
        $cantPatients = $patients->count();
        // Redirect to the view with list of: active patients, all previtions and all genders
        return view('general.patient', compact('patients', 'prev', 'sex', 'cantPatients'));
    }

    public function showInactivePatients()
    {
        // Get patients from database where 'activa' attribute is FALSE (0 bits)
        $patients = $this->getPatients(0);
        // Count patients
        $cantPatients = $patients->count();
        // Redirect to the view with list of: inactive patients, all previtions and all genders
        return view('admin.Inactive.patientInactive', compact('patients', 'prev', 'sex'));
    }

    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
    public function showAddPatient()
    {
        // Get list of genders
        $sex = Sex::where('activa', 1)->get();
        // Get list of attributes
        $attributes = Attributes::where('activa', 1)->get();
        // Get list of previtions
        $previtions = Prevition::where('activa', 1)->get();
        // Redirect to view with list of genders and previtions
        return view('admin.Form.patientForm', compact('sex', 'previtions', 'attributes'));
    }

    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditPatient($dni)
    {
        // Get the first patient that match with DNI
        $patient = Patient::where('DNI', $dni)->first();
        // Get previtions
        $prev = Prevition::where('activa', 1)->get();
        // Get genders
        $sex = Sex::where('activa', 1)->get();
        // Get list of attributes
        $attributes = Attributes::where('activa', 1)->get();
        // Create variable for date
        $patient_birthdate = "";
        // Create variable for patient's address
        $address = "";
        // If patient exist, then change formate date to retrieve to the datepicker and retrieve his address
        if ($patient) {
            // Separate birthdate (dd-mm-yyyy) into array
            $patient_birthdate = explode("-", $patient->fecha_nacimiento);
            // Set date array as new date format (yyyy/mm/dd)
            $patient_birthdate = join("/", array($patient_birthdate[2], $patient_birthdate[1], $patient_birthdate[0]));
            // Get patient's address
            $address = Address::where('paciente_id', $patient->id)->first();
            (strpos(strtolower($address->pais), 'chile') !== false ? $this->formatRut($patient) : false);
        }
        // Redirect to the view with list of prevition and gender, also return the patient and birthdate
        return view('admin.Edit.patientEdit', compact('patient', 'address', 'patient_birthdate', 'prev', 'sex', 'attributes'));
    }

    public function showEditPatientAttributes($dni)
    {
        $patient = Patient::where('dni', $dni)->first();
        $attributes = Attributes::where('activa', 1)->get();
        return view('admin.Edit.patientAttributesEdit', compact('patient', 'attributes'));
    }

    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/

    public function registerPatient(Request $request)
    {
        // Check the format of each variable of 'request'
        $validation = $request->validate([
            'dni' => 'required|string|max:255|unique:paciente,DNI',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'pais' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'comuna' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'numero' => 'required|string|max:255',
            'depto' => 'nullable|string|max:255',
            'patient_sex' => 'required',
            'prevition' => 'required|int',
            'datepicker' => 'required|date_format:"d/m/Y"',
        ]);

        // Create patient
        $patient = new Patient;
        // Set some variables with inputs of view
        // the variables name of object must be the same that database for save it
        // patient -> DNI, nombre1, nombre2, apellido1, apellido2, sexo_id, fecha_nacimiento, prevision_id
        $posSpace = strpos($request->name, ' ');

        if (!$posSpace) {
            $patient->nombre1 = $request->name;
            $patient->nombre2 = "";
        } else {
            $patient->nombre1 = substr($request->name, 0, $posSpace);
            $patient->nombre2 = substr($request->name, $posSpace + 1);
        }
        $patient->apellido1 = $request->last_name;
        $patient->apellido2 = $request->second_last_name;
        $patient->DNI = $request->dni;
        $patient->prevision_id = $request->prevition;
        $patient->sexo_id = $request->patient_sex;
        // Change datepicker format to database format
        $var = $request->get('datepicker');
        $date = str_replace('/', '-', $var);
        $correctDate = date('Y-m-d', strtotime($date));
        $patient->fecha_nacimiento = $correctDate;
        $patient->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar paciente', $patient->id, $patient->table);
        // Create address
        $address = new Address;
        $address->pais = $request->pais;
        $address->region = $request->region;
        $address->comuna = $request->comuna;
        $address->calle  = $request->calle;
        $address->numero = $request->numero;
        $address->departamento = $request->depto;
        $patient_id = Patient::where('DNI', $request->dni)->first()->id;
        $address->paciente_id = $patient_id;
        $address->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar paciente', $patient->id, $patient->table);
        app('App\Http\Controllers\AdminController')->addLog('Registrar dirección', $address->id, $address->table);
        // Add attributes for this patient
        $patient->attributes()->sync($request->options);
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar atributos a paciente id: ' . $patient->id, $request->options, 'paciente_posee_atributos');
        // Redirect to the view with successful status
        return redirect('registrar/paciente')->with('status', 'Usuario creado');
    }

    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/

    public function editPatient(Request $request)
    {
        // URL to redirect when process finish.
        $url = "pacientes/edit/" . $request->dni;
        // Validate the request variables
        $validation = $request->validate([
            'dni' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'pais' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'comuna' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'numero' => 'required|string|max:255',
            'depto' => 'nullable|string|max:255',
            'patient_sex' => 'required',
            'prevition' => 'required|int',
            'datepicker' => 'required|date_format:"d/m/Y"',
        ]);
        // Get the patient that want to update
        $patient = Patient::find($request->id);
        // If found it then update the data
        if ($patient) {
            // Set some variables with inputs of view
            // patient -> DNI, nombre1, nombre2, apellido1, apellido2, sexo_id, prevision_id
            $posSpace = strpos($request->name, ' ');

            if (!$posSpace) {
                $patient->nombre1 = $request->name;
                $patient->nombre2 = "";
            } else {
                $patient->nombre1 = substr($request->name, 0, $posSpace);
                $patient->nombre2 = substr($request->name, $posSpace + 1);
            }
            $patient->apellido1 = $request->last_name;
            $patient->apellido2 = $request->second_last_name;
            if ($request->dni != $patient->DNI) {
                $check = Patient::where('DNI', $request->dni)->get();
                if ($check->count() > 0) {
                    return redirect($url)->with('err', 'El rut ya se encuentra utilizado!');
                }
                $patient->DNI = $request->dni;
            }
            $patient->prevision_id = $request->prevition;
            $patient->sexo_id = $request->patient_sex;
            // Change datepicker format to database format
            $var = $request->get('datepicker');
            $date = str_replace('/', '-', $var);
            $correctDate = date('Y-m-d', strtotime($date));
            $patient->fecha_nacimiento = $correctDate;

            // Edit address
            $address = Address::where('paciente_id', $patient->id)->first();
            $address->pais = $request->pais;
            $address->region = $request->region;
            $address->comuna = $request->comuna;
            $address->calle  = $request->calle;
            $address->numero = $request->numero;
            $address->departamento = $request->depto;

            // Pass the new info for update
            $patient->save();
            $address->save();
            // Regist in logs events
            app('App\Http\Controllers\AdminController')->addLog('Actualizar paciente', $patient->id, $patient->table);
            app('App\Http\Controllers\AdminController')->addLog('Actualizar dirección', $patient->id, $patient->table);
        }
        $patient->attributes()->sync($request->options);
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar atributos a paciente id: ' . $patient->id, $request->options, 'paciente_posee_atributos');
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizaron los datos del paciente');
    }

    public function editPatientAttributes(Request $request)
    {
        $patient = Patient::where('dni', $request->dni)->first();
        $patient->attributes()->sync($request->options);
        $patient->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Actualizar atributos de paciente id: ' . $patient, $request->options, 'paciente_posee_atributos');
        // URL to redirect
        $url = 'paciente-atributos/' . $request->dni;
        return redirect($url)->with('status', 'Se actualizaron los atributos del paciente');
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    public function activatePatient(Request $request)
    {
        // Get the patient
        $patient = Patient::find($request->id);
        // Update active to 1 bits
        $patient->activa = 1;
        // Send update to database
        $patient->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Activar paciente', $patient->id, $patient->table);
        // Redirect to the view with successful status (showing the DNI)
        return redirect('/pacientes/inactivos')->with('status', 'Paciente ' . $patient->DNI . ' reingresado');
    }
    public function deletingPatient(Request $request)
    {
        // Get the patient
        $patient = Patient::find($request->id);
        // Update active to 0 bits
        $patient->activa = 0;
        // Send update to database
        $patient->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Desactivar paciente', $patient->id, $patient->table);
        // Redirect to the view with successful status (showing the DNI)
        return redirect('/pacientes')->with('status', 'Paciente ' . $patient->DNI . ' eliminado');
    }

    public function formatRut($index)
    {
        $sRut = $index->DNI;
        $sRutFormateado = '';
        $digitoVerificador = substr($sRut, -1);
        if ($digitoVerificador) {
            $sDV = substr($sRut, -1);
            $sRut = substr($sRut, 0, -1);
        }
        while (strlen($sRut) > 3) {
            $sRutFormateado = "." . substr($sRut, -3) . $sRutFormateado;
            $sRut = substr($sRut, 0, strlen($sRut) - 3);
        }
        $sRutFormateado = $sRut . $sRutFormateado;
        if ($sRutFormateado != "" && $digitoVerificador) {
            $sRutFormateado = $sRutFormateado . "-" . $sDV;
        } else if ($digitoVerificador) {
            $sRutFormateado = $sRutFormateado . $sDV;
        }
        $index->dni = $sRutFormateado;
        return $index;
    }

    public function getPatients($active)
    {
        $patients = Patient::join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->join('prevision', 'prevision.id', '=', 'paciente.prevision_id')
            ->where('paciente.activa', $active)
            ->select('paciente.id', 'DNI', 'nombre1', 'nombre2', 'apellido1', 'apellido2', 'fecha_nacimiento', 'prevision.descripcion as prevision', 'sexo.descripcion as sexo', 'paciente.activa')
            ->get();
        foreach ($patients as $patient) {
            // Get patient's address
            $address = Address::where('paciente_id', $patient->id)->first();
            (strpos(strtolower($address->pais), 'chile') !== false ? $this->formatRut($patient) : false);
            // Get age
            $age = Carbon::createFromDate($patient->fecha_nacimiento);
            $patient->fecha = $age->format('d/m/Y');
            if ($age->age != 0) {
                $patient->edad = $age->diff(Carbon::now())->format('%y años');
            } else {
                $patient->edad = $age->diff(Carbon::now())->format('%m meses y %d días');
            }
        }
        return $patients;
    }
}
