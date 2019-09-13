<?php

namespace App\Http\Controllers;

use App\Functionary;
use App\User;
use App\Patient;
use App\Release;
use App\Attributes;
use App\Sex;
use App\Address;
use App\Prevition;
use App\Speciality;
use App\FunctionarySpeciality;
use App\Provision;
use App\Type;
use App\Diagnosis;
use App\Program;
use App\SiGGES;
use App\Provenance;
use App\Stage;
use App\Attendance;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkrole:1');
    }

    /***************************************************************************************************************************
                                                    VIEWS (GET METHOD)
     ****************************************************************************************************************************/
    // Pacientes inactivos
     public function showInactivePatients()
    {
        $patients = Patient::where('activa', 0)->get();
        $prev = Prevition::all();
        $sex = Sex::all();
        return view('admin.Views.patientInactive', compact('patients', 'prev', 'sex'));
    }
    // Funcionarios inactivos
    public function showInactiveFunctionarys()
    {
        $functionary = Functionary::where('activa', 0)->get();
        $user = User::all();
        $speciality = Speciality::all();
        $fs = FunctionarySpeciality::all();
        return view('admin.Views.funtionaryInactive', compact('functionary', 'user', 'speciality', 'fs'));
    }
    // Test
    public function showTesting()
    {
        $patient = DB::table('paciente')
            ->join('prevision', 'paciente.prevision_id', '=', 'prevision.id')
            // ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('paciente.*', 'prevision.descripcion')
            ->where('paciente.activa', '=', 1)
            ->get();
        return view('general.test', ['main' => json_encode($patient)]);
    }
    // ???
    public function data()
    {
        $data = Patient::all()->toJson();
        return $data;
    }
    /***************************************************************************************************************************
                                             VIEWS OF FORMS
     ****************************************************************************************************************************/
    // Usuario
     public function showAddUser()
    {
        return view('admin.Form.userForm');
    }
    // Alta
    public function showAddRelease()
    {
        $data = Release::orderBy('descripcion')->get();
        return view('admin.Form.releaseForm', ['data' => $data, 'table' => 'Altas']);
    }
    // Atención
    public function showAddAttendance()
    {
        $users = Functionary::where('activa', 1)->get();
        return view('general.attendanceForm', compact('users'));
    }
    // Atributo
    public function showAddAttributes()
    {
        $data = Attributes::orderBy('descripcion')->get();
        return view('admin.Form.attributesForm', ['data' => $data, 'table' => 'Atributos']);
    }    
    // Diagnóstico
    public function showAddDiagnosis()
    {
        $data = Diagnosis::orderBy('descripcion')->get();
        return view('admin.Form.diagnosisForm', ['data' => $data, 'table' => 'Diagnósticos']);
    }
    // Especialidad
    public function showAddSpeciality()
    {
        $data = Speciality::orderBy('descripcion')->get();
        return view('admin.Form.specialityForm', ['data' => $data, 'table' => 'Especialidades']);
    }
    // Etapa
    public function showAddStage()
    {
        $patient = Patient::all();
        $functionary = Functionary::all();
        $diagnosis = Diagnosis::all();
        $program = Program::all();
        $release = Release::all();
        $Sigges = SiGGES::all();
        $provenance = Provenance::all();
        return view('admin.Form.stageCreateForm', compact('patient', 'functionary', 'diagnosis', 'program', 'release', 'Sigges', 'provenance'));
    }    
    // Funcionario
    public function showAddFunctionary()
    {
        $user = User::where('activa', 1)->get();
        return view('admin.Form.functionaryForm', compact('user'));
    }
    // Paciente
    public function showAddPatient()
    {
        $sex = Sex::all();
        $previtions = Prevition::all();
        return view('admin.Form.patientForm', compact('sex', 'previtions'));
    }
    // Prestación
    public function showAddProvision()
    {
        $type = Type::where('activa', 1)->get();
        return view('admin.Form.provisionForm', compact('type'));
    }
    // Previsión
    public function showAddPrevition()
    {
        $data = Prevition::orderBy('descripcion')->get();
        return view('admin.Form.previtionForm', ['data' => $data, 'table' => 'Previsiones']);
    }
    // Procedencia
    public function showAddProvenance()
    {
        $data = Provenance::orderBy('descripcion')->get();
        return view('admin.Form.provenanceForm', ['data' => $data, 'table' => 'Procedencias']);
    }
    // Programa
    public function showAddProgram()
    {
        $speciality = Speciality::all();
        $data = Program::orderBy('descripcion')->get();
        return view('admin.Form.programForm', compact('speciality', 'data'));
    }
    // Sexo / Género
    public function showAddSex()
    {
        $data = Sex::orderBy('descripcion')->get();;
        return view('admin.Form.sexForm', ['data' => $data, 'table' => 'Géneros']);
    }
    // SiGGES
    public function showAddSIGGES()
    {
        $data = SiGGES::orderBy('descripcion')->get();;
        return view('admin.Form.siggesForm', ['data' => $data, 'table' => 'Tipo GES']);
    }
    // Tipo
    public function showAddType()
    {
        $data = Type::orderBy('descripcion')->get();;
        return view('admin.Form.typeForm', ['data' => $data, 'table' => 'Tipo prestaciones']);
    }
    // Asignar Especialidad
    public function showAsignSpeciality()
    {
        $speciality = Speciality::orderBy('descripcion')
            ->get();

        $functionary = Functionary::orderBy('profesion')
            ->get();
        $rows = [];
        $columns = [];
        $ids = [];

        foreach ($speciality as $index => $record) {
            if (!in_array($record->profesion, $columns)) {
                $columns[] = " | " . $record->descripcion . " | ";
            }
        }

        foreach ($functionary as $index => $record1) {
            $ids[0] = $record1->id;
            foreach ($speciality as $index => $record2) {
                $ids[1] = $record2->id;
                $rows[$record1->user->primer_nombre . " " . $record1->user->segundo_nombre][$record2->descripcion] = $ids;
            }
        }
        return view('admin.Asingment.specialityAsign', compact('rows', 'columns'));
    }
    // Asignar Prestación
    public function showAsignProvision()
    {
        $speciality = Speciality::orderBy('descripcion')->get();
        $provision = Provision::orderBy('glosaTrasadora')->get();

        $rows = [];
        $columns = [];
        $ids = [];

        foreach ($speciality as $index => $record) {
            if (!in_array($record->profesion, $columns)) {
                $columns[] = " | " . $record->descripcion . " | ";
            }
        }

        foreach ($provision as $index => $record1) {
            $ids[0] = $record1->id;
            foreach ($speciality as $index => $record2) {
                $ids[1] = $record2->id;
                $rows[$record1->glosaTrasadora][$record2->descripcion] = $ids;
            }
        }
        return view('admin.Asingment.provisionAsign', compact('rows', 'columns'));
    }
    /***************************************************************************************************************************
                                             VIEWS OF EDIT
     ****************************************************************************************************************************/
    // Alta
    public function showEditRelease($id)
    {
        $release = Release::find($id);
        return view('admin.Edit.releaseEdit', compact('release'));
    }
    // Atributo
    public function showEditAttribute($id)
    {
        $attribute = Attributes::find($id);
        return view('admin.Edit.attributeEdit', compact('attribute'));
    }
    // Diagnóstico
    public function showEditDiagnostic($id)
    {
        $diagnostic = Diagnosis::find($id);
        return view('admin.Edit.diagnosticEdit', compact('diagnostic'));
    }
    // Especialidad
    public function showEditSpeciality($id)
    {
        $speciality = Speciality::find($id);
        return view('admin.Edit.specialityEdit', compact('speciality'));
    }
    // Paciente
    public function showEditPatient($dni)
    {
        $patient = Patient::where('DNI', $dni)->first();
        $prev = Prevition::all();
        $sex = Sex::all();
        $patient_birthday = "";

        if ($patient) {
            // Change formate date to retrieve to the datapicker
            $patient_birthday = explode("-", $patient->fecha_nacimiento);
            $patient_birthday = join("/", array($patient_birthday[2], $patient_birthday[1], $patient_birthday[0]));
        }

        return view('admin.Edit.patientEdit', compact('patient', 'patient_birthday', 'prev', 'sex'));
    }
    // Previsión
    public function showEditPrevition($id)
    {
        $prevition = Prevition::find($id);
        return view('admin.Edit.previtionEdit', compact('prevition'));
    }
    // Procedencia
    public function showEditProvenance($id)
    {
        $provenance = Provenance::find($id);
        return view('admin.Edit.provenanceEdit', compact('provenance'));
    }
    // Sexo / género
    public function showEditSex($id)
    {
        $sex = Sex::find($id);
        return view('admin.Edit.sexEdit', compact('sex'));
    }
    // SiGGES
    public function showEditSiGGES($id)
    {
        $sigges = SiGGES::find($id);
        return view('admin.Edit.siggesEdit', compact('sigges'));
    }
    // Tipo
    public function showEditType($id)
    {
        $type = Type::find($id);
        return view('admin.Edit.typeEdit', compact('type'));
    }
    /***************************************************************************************************************************
                                                    FORMS (POST)
     ****************************************************************************************************************************/
    // Usuario
    public function registerUser(Request $request)
    {
        $validacion = $request->validate([
            'nombre' => 'required|string|max:255',
            'rut' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'rol' => 'required|integer|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User;

        $user->nombre = $request->nombre;
        $user->primer_nombre = $request->primer_nombre;
        $user->segundo_nombre = $request->segundo_nombre;
        $user->apellido_paterno = $request->apellido_paterno;
        $user->apellido_materno = $request->apellido_materno;
        $user->rut = $request->rut;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect('registrar/usuario')->with('status', 'Usuario creado');
    }
    // Alta
    public function registerRelease(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $alta = new Release;

        $alta->descripcion = $request->descripcion;

        $alta->save();

        return redirect('registrar/alta')->with('status', 'Nueva alta creada');
    }
    // Atención
    public function registerAttendance(Request $request)
    {
        $validacion = $request->validate([
            //'descripcion' => 'required|string|max:255'
        ]);
        $attendance = new Attendance;
        $functionary = Functionary::find($request->functionary);

        $attendance->funcionario_id = $request->functionary;
        $attendance->etapa_id = $request->id_stage;
        $attendance->prestacion_id = $request->get('provision');

        $var = $request->get('datepicker');
        $date = str_replace('/', '-', $var);
        $correctDate = date('Y-m-d', strtotime($date));
        $attendance->fecha = $correctDate;

        $attendance->asistencia = $request->get('selectA');
        $attendance->hora = $request->get('timeInit');
        $attendance->duracion = $request->get('duration');

        $attendance->save();

        $duration   = $request->get('duration');
        $vector     = explode(":", $duration);
        $hours      = $vector[0];
        $minutes    = $vector[1];
        $anterior   = $functionary->horasRealizadas;
        $functionary->horasRealizadas = $anterior + $hours + $minutes / 60;
        $functionary->save();

        $idPatient = $request->get('id');
        $patient = Patient::find($idPatient);
        $stage   = Stage::find($request->id_stage);
        $patientAtendances = $stage->attendance;
        $att = Attendance::all();
        return view('admin.Views.clinicalRecords', compact('patient', 'stage', 'patientAtendances'));
    }
    // Atributo
    public function registerAttributes(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $atributo = new Attributes;

        $atributo->descripcion = $request->descripcion;

        $atributo->save();

        return redirect('registrar/atributos')->with('status', 'Nuevo atributo creado');
    }
    // Diagnóstico
    public function registerDiagnosis(Request $request)
    {

        $validacion = $request->validate([
            'descripcion' => 'required|string|max:382'
        ]);

        $diagnosis = new Diagnosis;

        $diagnosis->descripcion = $request->descripcion;

        $diagnosis->save();

        return redirect('registrar/diagnostico')->with('status', 'Nuevo diagnostico creado');
    }
    // Especialidad
    public function registerSpeciality(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $speciality = new Speciality;

        $speciality->descripcion = $request->descripcion;

        $speciality->save();

        return redirect('registrar/especialidad')->with('status', 'Nueva especialidad creada');
    }
    // Etapa
    public function registerStage(Request $request)
    {

        $validation = $request->validate([]);

        echo $request->new_start;

        $stage = new Stage;

        $stage->diagnostico_id = $request->diagnostico_id;
        $stage->programa_id = $request->programa_id;
        //$stage->alta_id = $request->alta_id;
        $stage->sigges_id = $request->sigges_id;
        $stage->procedencia_id = $request->procedencia_id;
        $stage->funcionario_id = $request->funcionario_id;
        $stage->paciente_id = $request->idpatient;
        $stage->save();
        $DNI = $request->idpatient;
        $patient = Patient::where('id', $DNI)
            ->where('activa', 1)
            ->first();
        $users = Functionary::where('activa', 1)->get();
        return view('general.attendanceForm', ['patient' => 'si posee una etapa activa', 'DNI' => $DNI, 'stage_id' => $stage->id])->with(compact('stage', 'users', 'patient'));
    }
    // Funcionario
    public function registerFunctionary(Request $request)
    {
        $validacion = $request->validate([
            'profesion' => 'required|string|max:255',
            'user' => 'required|integer|max:255'
        ]);

        $functionary = new Functionary;

        $functionary->profesion = $request->profesion;
        $functionary->user_id = $request->user;
        $functionary->horasDeclaradas = $request->declared_hours;

        $functionary->save();

        return redirect('registrar/funcionario')->with('status', 'Funcionario creado');
    }
    // Paciente
    public function registerPatient(Request $request)
    {

        $validation = $request->validate([
            'id' => 'required|string',
            //'id' => 'required|int|max:255',
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'comuna' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'numero' => 'required|int',
            'patient_sex' => 'required',
            'prevition' => 'required|int',
            'numero' => 'required|int',
            'direccion' => 'string|max:255',
            'datepicker' => 'required|date_format:"d/m/Y"',
        ]);

        echo $request->new_start;

        $nombre = explode(" ", $request->nombre);
        $patient = new Patient;

        $patient->nombre1 = $nombre[0];
        $patient->nombre2 = $nombre[1];
        $patient->apellido1 = $request->apellido1;
        $patient->apellido2 = $request->apellido2;
        $patient->DNI = $request->id;
        $var = $request->get('datepicker');
        $date = str_replace('/', '-', $var);
        $correctDate = date('Y-m-d', strtotime($date));

        $patient->fecha_nacimiento = $correctDate;

        $patient->prevision_id = $request->prevition;

        $address = new Address;
        $address->region = $request->region;
        $address->comuna = $request->comuna;
        $address->calle  = $request->calle;
        $address->numero = $request->numero;

        $patient->sexo_id = $request->patient_sex;
        $address->save();

        $patient->save();
        $patient->address()->sync($address);

        return redirect('registrar/paciente')->with('status', 'Usuario creado');
    }
    // Previsión
    public function registerPrevition(Request $request)
    {

        $validacion = $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $prevition = new Prevition;

        $prevition->descripcion = $request->nombre;

        $prevition->save();

        return redirect('registrar/prevision')->with('status', 'Nueva prevision creada');
    }
    // Procedencia
    public function registerProvenance(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $provenance = new Provenance;

        $provenance->descripcion = $request->descripcion;

        $provenance->save();

        return redirect('registrar/procedencia')->with('status', 'Nueva procedencia creada');
    }
    // Programa
    public function registerProgram(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'

        ]);

        $program = new Program;

        $program->descripcion = $request->descripcion;
        $program->especialidad = $request->descripcion_espe;

        $program->save();

        return redirect('registrar/programa')->with('status', 'Nuevo programa creado');
    }
    // Prestación
    public function registerProvision(Request $request)
    {
        $validacion = $request->validate([
            'frecuencia' => 'required|int',
            'glosa' => 'required|string|max:255',
            'ps_fam' => 'required|string|max:255',
        ]);

        $provision = new Provision;

        $provision->glosaTrasadora = $request->glosa;
        $provision->frecuencia = $request->frecuencia;
        $provision->ps_fam = $request->ps_fam;
        $provision->codigo = $request->codigo;
        $provision->rangoEdad_inferior = $request->edadInf;
        $provision->rangoEdad_superior = $request->edadSup;
        $provision->tipo_id = $request->type;
        $provision->save();

        return redirect('registrar/prestacion')->with('status', 'Nueva prestacion creada');
    }
    // Sexo / género
    public function registerSex(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $sex = new Sex;

        $sex->descripcion = $request->descripcion;

        $sex->save();

        return redirect('registrar/genero')->with('status', 'Nuevo Sexo / Genero creado');
    }
    // SiGGES
    public function registerSIGGES(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $sigges = new SiGGES;

        $sigges->descripcion = $request->descripcion;

        $sigges->save();

        return redirect('registrar/sigges')->with('status', 'Nuevo tipo de SiGGES creado');
    }
    // Tipo
    public function registerType(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $type = new Type;

        $type->descripcion = $request->descripcion;

        $type->save();

        return redirect('registrar/tipo')->with('status', 'Nuevo tipo de prestación creada');
    }
    // Asignar prestación
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
    // Asignar especialidad
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
                    }
                }
            }
            return redirect('asignar/especialidad')->with('status', 'Especialidades actualizadas');
        }
    }
    /***************************************************************************************************************************
                                                    EDIT (POST)
     ****************************************************************************************************************************/
    // Alta
    public function editRelease(Request $request)
    {
        // URL to redirect when process finish.
        $url = "alta/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $release = Release::find($request->id);

        if ($release) {
            $release->descripcion = $request->descripcion;
            $release->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción del alta');
    }
    // Atributo
    public function editAttribute(Request $request)
    {

        // URL to redirect when process finish.
        $url = "atributo/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $attribute = Attributes::find($request->id);

        if ($attribute) {
            $attribute->descripcion = $request->descripcion;
            $attribute->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción del atributo');
    }
    // Diagnóstico
    public function editDiagnostic(Request $request)
    {

        // URL to redirect when process finish.
        $url = "diagnostico/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $diagnostic = Diagnosis::find($request->id);

        if ($diagnostic) {
            $diagnostic->descripcion = $request->descripcion;
            $diagnostic->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción del diagnostico');
    }
    // Especialidad
    public function editSpeciality(Request $request)
    {
        // URL to redirect when process finish.
        $url = "especialidad/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $speciality = Speciality::find($request->id);

        if ($speciality) {
            $speciality->descripcion = $request->descripcion;
            $speciality->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción de la especialidad');
    }
    // Paciente
    public function editPatient(Request $request)
    {
        // URL to redirect when process finish.
        $url = "pacientes/edit/" . $request->dni;

        // Validate form data
        $validation = $request->validate([
            'dni' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            /*'pais' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'numero' => 'required|int',
            'direccion' => 'string|max:255',
            'direccion_opcional' => 'string|max:255|nullable',*/
            'datepicker' => 'required|date_format:"d/m/Y"',
        ]);

        // Find patient to edit
        $patient = Patient::find($request->id);

        // If patient exist update his data
        if ($patient) {
            $nombre = explode(" ", $request->nombre);
            $patient->nombre1 = $nombre[0];
            $patient->nombre2 = $nombre[1];
            $patient->apellido1 = $nombre[2];
            $patient->apellido2 = $nombre[3];
            $patient->DNI = $request->dni;
            $patient->prevision_id = $request->prev;
            $patient->sexo_id = $request->sex;

            $patient->save();
        }
        return redirect($url)->with('status', 'Se actualizaron los datos del paciente');
    }
    // Previsión
    public function editPrevition(Request $request)
    {
        // URL to redirect when process finish.
        $url = "prevision/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $prevition = Prevition::find($request->id);

        if ($prevition) {
            $prevition->descripcion = $request->descripcion;
            $prevition->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción de la previsión');
    }
    // Procedencia
    public function editProvenance(Request $request)
    {

        // URL to redirect when process finish.
        $url = "procedencia/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $provenance = Provenance::find($request->id);

        if ($provenance) {
            $provenance->descripcion = $request->descripcion;
            $provenance->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción de la procedencia');
    }
    // Sexo / género
    public function editSex(Request $request)
    {
        // URL to redirect when process finish.
        $url = "sexo/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $sex = Sex::find($request->id);

        if ($sex) {
            $sex->descripcion = $request->descripcion;
            $sex->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción del atributo');
    }
    // SiGGES
    public function editSiGGES(Request $request)
    {
        // URL to redirect when process finish.
        $url = "sigges/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $sigges = Sigges::find($request->id);

        if ($sigges) {
            $sigges->descripcion = $request->descripcion;
            $sigges->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción del tipo GES');
    }
    // Tipo
    public function editType(Request $request)
    {
        // URL to redirect when process finish.
        $url = "prestacion/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $type = Type::find($request->id);

        if ($type) {
            $type->descripcion = $request->descripcion;
            $type->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción de la prestación');
    }
    /***************************************************************************************************************************
                                                    ACTIONS BUTTONS FUNCTIONS
     ****************************************************************************************************************************/
    // Activate
    public function activatePatient(Request $request)
    {
        $patient = Patient::where('DNI', $request->DNI)->get();
        $patient[0]->activa = 1;
        $patient[0]->save();
        return redirect('pacientes/inactivos')->with('status', 'Paciente ' . $request->DNI . ' reingresado');
    }
    public function activateFunctionary(Request $request)
    {
        $functionary = Functionary::where('id', $request->id)->get();
        $functionary[0]->activa = 1;
        $functionary[0]->save();

        $user = User::where('id', $functionary[0]->user_id)->get();

        return redirect('funcionarios/inactivos')->with('status', 'Funcionario ' . $user[0]->rut . ' re-incorporado');
    }
     // Deactivate
    public function deletingPatient(Request $request)
    {
        $patient = Patient::where('DNI', $request->DNI)->get();
        $patient[0]->activa = 0;
        $patient[0]->save();
        return redirect('pacientes')->with('status', 'Paciente ' . $request->DNI . ' eliminado');
    }
    public function deletingFunctionary(Request $request)
    {
        $functionary = Functionary::where('id', $request->id)->get();
        $functionary[0]->activa = 0;
        $functionary[0]->save();

        $user = User::where('id', $functionary[0]->user_id)->get();

        return redirect('funcionarios')->with('status', 'Funcionario ' . $user[0]->rut . ' eliminado');
    }
    /***************************************************************************************************************************
                                                    HELPERS AND LOGIC FUNCTIONS
     ****************************************************************************************************************************/
    // Check for a speciality linked to the functionary (parameter)
    public static function existFunctionarySpeciality($idFunct, $idSp)
    {
        $value = false;
        $doesClientHaveProduct = Speciality::where('id', $idSp)
            ->whereHas('functionary', function ($q) use ($idFunct) {
                $q->where('dbo.funcionarios.id', $idFunct);
            })
            ->count();
        if ($doesClientHaveProduct) {
            $value = true;
        }

        return $value;
    }
    // Check provision for the speciality (parameter)
    public static function existProvisionSpeciality($idprov, $idSp)
    {
        $value = false;
        $doesProvisionHaveSpeciality = Speciality::where('id', $idSp)
            ->whereHas('provision', function ($q) use ($idprov) {
                $q->where('dbo.prestacion.id', $idprov);
            })
            ->count();
        if ($doesProvisionHaveSpeciality) {
            $value = true;
        }

        return $value;
    }
    /***************************************************************************************************************************
                                                    ATTENDANCE LOGIC
     ****************************************************************************************************************************/
    // Check for an active stage for the patient (parameter)
     public function checkCurrStage(Request $request)
    {
        $DNI = $request->DNI_stage; //dni del paciente
        $patient = Patient::where('DNI', $DNI)
            ->where('activa', 1)
            ->first();
        $id_patient = $patient->id;
        $stage = Stage::where('paciente_id', $id_patient)
            ->where('activa', 1)
            ->first();
        if (empty($stage)) {
            //$functionary = Functionary::where('activa', 1)->get();
            $diagnosis = Diagnosis::where('activa', 1)->get();
            $program = Program::where('activa', 1)->get();
            $release = Release::where('activa', 1)->get();
            $Sigges = SiGGES::where('activa', 1)->get();
            $provenance = Provenance::all();
            $funcionarios = Functionary::join('users', 'users.id', '=', 'funcionarios.user_id')
                ->select('funcionarios.id', 'funcionarios.profesion', 'users.primer_nombre', 'users.apellido_paterno')
                ->where('funcionarios.activa', '=', 1)
                ->get();
            // return view('admin.stageCreateForm', compact('id_patient', 'functionary', 'diagnosis', 'program', 'release', 'Sigges', 'provenance'));
            return view('admin.Form.stageCreateForm', ['patient' => 'no tiene ninguna etapa', 'idpatient' => $id_patient])->with(compact('funcionarios', 'diagnosis', 'program', 'release', 'Sigges', 'provenance'));
        } else {
            $users = Functionary::where('activa', 1)->get();
            return view('general.attendanceForm', ['patient' => 'si posee una etapa activa', 'DNI' => $DNI])->with(compact('stage', 'users', 'patient'));
        }
    }
    // acuerdate de cambiar estas weas wn 
    public function getSpecialityPerFunctionary(Request $request)
    {
        $functionary = Functionary::find($request->functionary_id);
        $speciality = $functionary->speciality;
        return response()->json($speciality);
    }
    // y esto igual y los nombres
    public function getProvisionPerFunctionary(Request $request)
    {
        $specility = Speciality::find($request->speciality_id);
        $provision = $specility->provision;
        return response()->json($provision);
    }
}
