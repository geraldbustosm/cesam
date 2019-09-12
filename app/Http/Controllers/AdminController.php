<?php

namespace App\Http\Controllers;

use App\Functionary;
use App\User;
use App\Patient;
use App\Release;
use App\Atributes;
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

    public function showPatients()
    {
        $patients = Patient::where('activa', 1)->get();
        $prev = Prevition::all();
        $sex = Sex::all();
        return view('general.patient', compact('patients', 'prev', 'sex'));
    }

    public function showInactivePatients()
    {
        $patients = Patient::where('activa', 0)->get();
        $prev = Prevition::all();
        $sex = Sex::all();
        return view('admin.patientInactive', compact('patients', 'prev', 'sex'));
    }

    public function showFunctionarys()
    {
        $functionary = Functionary::where('activa', 1)->get();
        $user = User::all();
        $speciality = Speciality::all();
        $fs = FunctionarySpeciality::all();
        return view('general.functionarys', compact('functionary', 'user', 'speciality', 'fs'));
    }

    public function showInactiveFunctionarys()
    {
        $functionary = Functionary::where('activa', 0)->get();
        $user = User::all();
        $speciality = Speciality::all();
        $fs = FunctionarySpeciality::all();
        return view('admin.funtionaryInactive', compact('functionary', 'user', 'speciality', 'fs'));
    }

    public function showPatientInfo()
    {
        return view('admin.patientInfo');
    }

    public function showClinicalfunctionary()
    {
        return view('admin.clinicalfunctionary');
    }

    public function showTesting()
    {
        $main = Functionary::all();
        return view('general.test', ['main'=>json_encode($main)]);
        //return view('general.test', compact('main'));
    }
    public function data()
    {
    $data = Patient::all()->toJson();
        return $data;
    }

    public function regTesting(){

        return redirect('testing');
    }

    /***************************************************************************************************************************
                                             VIEWS (GET METHOD - SHOW ADD)
     ****************************************************************************************************************************/
    public function showAddUser()
    {
        return view('admin.userForm');
    }

    public function showAddFunctionary()
    {
        $user = User::where('activa', 1)->get();
        return view('admin.functionaryForm', compact('user'));
    }

    public function showAddRelease()
    {
        $data = Release::orderBy('descripcion')->get();
        return view('admin.releaseForm', ['data' => $data, 'table' => 'Altas']);
    }
    public function showAddProvenance()
    {
        $data = Provenance::orderBy('descripcion')->get();
        return view('admin.provenanceForm', ['data' => $data, 'table' => 'Procedencias']);
    }

    public function showAddStage()
    {
        $patient = Patient::all();
        $functionary = Functionary::all();
        $diagnosis = Diagnosis::all();
        $program = Program::all();
        $release = Release::all();
        $Sigges = SiGGES::all();
        $provenance = Provenance::all();
        return view('admin.stageCreateForm', compact('patient', 'functionary', 'diagnosis', 'program', 'release', 'Sigges', 'provenance'));
    }
    public function showAddPrevition()
    {
        $data = Prevition::orderBy('descripcion')->get();
        return view('admin.previtionForm', ['data' => $data, 'table' => 'Previsiones']);
    }
    public function showAddProgram()
    {
        $speciality = Speciality::all();
        $data = Program::orderBy('descripcion')->get();
        return view('admin.programForm', compact('speciality', 'data'));
    }
    public function showAddDiagnosis()
    {
        $data = Diagnosis::orderBy('descripcion')->get();
        return view('admin.diagnosisForm', ['data' => $data, 'table' => 'Diagnósticos']);
    }

    public function showAddAtributes()
    {
        $data = Atributes::orderBy('descripcion')->get();
        return view('admin.atributesForm', ['data' => $data, 'table' => 'Atributos']);
    }

    public function showAddSex()
    {
        $data = Sex::orderBy('descripcion')->get();;
        return view('admin.sexForm', ['data' => $data, 'table' => 'Géneros']);
    }
    public function showAddType()
    {
        $data = Type::orderBy('descripcion')->get();;
        return view('admin.typeForm', ['data' => $data, 'table' => 'Tipo prestaciones']);
    }

    public function showAddSIGGES()
    {
        $data = SiGGES::orderBy('descripcion')->get();;
        return view('admin.siggesForm', ['data' => $data, 'table' => 'Tipo GES']);
    }

    public function showAddSpeciality()
    {
        $data = Speciality::orderBy('descripcion')->get();
        return view('admin.specialityForm', ['data' => $data, 'table' => 'Especialidades']);
    }

    public function showAddProvision()
    {
        $type = Type::where('activa', 1)->get();
        return view('admin.provisionForm', compact('type'));
    }

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
        return view('admin.specialityAsign', compact('rows', 'columns'));
    }

    public function showAsignProvision()
    {
        $speciality = Speciality::orderBy('descripcion')
            ->get();

        $provision = Provision::orderBy('glosaTrasadora')
            ->get();
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
        return view('admin.provisionAsign', compact('rows', 'columns'));
    }

    /***************************************************************************************************************************
                                             VIEWS       GET METHOD SHOW EDIT
     ****************************************************************************************************************************/
    
    public function showEditPatient($dni){
        $patient = Patient::where('DNI', $dni)->first();
        $prev = Prevition::all();
        $sex = Sex::all();
        $patient_birthday = "";

        if($patient){
            // Change formate date to retrieve to the datapicker
            $patient_birthday = explode("-", $patient->fecha_nacimiento);
            $patient_birthday = join("/", array($patient_birthday[2], $patient_birthday[1], $patient_birthday[0]));
        }

        return view('admin.patientEdit', compact('patient', 'patient_birthday', 'prev', 'sex'));
    }

    public function showEditRelease($id){
        $release = Release::find($id);

        return view('admin.releaseEdit', compact('release'));
    }
    
    public function showEditAttribute($id){
        $attribute = Atributes::find($id);

        return view('admin.attributeEdit', compact('attribute'));
    }

    public function showEditDiagnostic($id){
        $diagnostic = Diagnosis::find($id);

        return view('admin.diagnosticEdit', compact('diagnostic'));
    }

    public function showEditSpeciality($id){
        $speciality = Speciality::find($id);

        return view('admin.specialityEdit', compact('speciality'));
    }
    public function showEditSex($id){
        $sex = Sex::find($id);

        return view('admin.sexEdit', compact('sex'));
    }
    public function showEditPrevition($id){
        $prevition = Prevition::find($id);

        return view('admin.previtionEdit', compact('prevition'));
    }
    public function showEditProvenance($id){
        $provenance = Provenance::find($id);

        return view('admin.provenanceEdit', compact('provenance'));
    }
    public function showEditSiGGES($id){
        $sigges = SiGGES::find($id);

        return view('admin.siggesEdit', compact('sigges'));
    }
    public function showEditType($id){
        $type = Type::find($id);

        return view('admin.typeEdit', compact('type'));
    }

    /***************************************************************************************************************************
                                                    POST METHOD (REGIST & ASIG)
     ****************************************************************************************************************************/
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
        $DNI=$request->idpatient;
        $patient = Patient::where('id',$DNI)
                ->where('activa', 1)
                ->first();
        $users = Functionary::where('activa', 1)->get();
        return view('general.attendanceForm', ['patient' => 'si posee una etapa activa', 'DNI'=>$DNI, 'stage_id'=>$stage->id])->with( compact('stage','users','patient'));
    }
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
    public function registerProgram(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'

        ]);

        $program = new Program;

        $program->descripcion = $request->descripcion;
        $program->especialidad = $request->descripcion_espe;

        $program->save();

        return redirect('registrarprograma')->with('status', 'Nuevo programa creado');
    }

    public function registerAtributes(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $atributo = new Atributes;

        $atributo->descripcion = $request->descripcion;

        $atributo->save();

        return redirect('registrar/atributos')->with('status', 'Nuevo atributo creado');
    }

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

        return redirect('registrarfuncionario')->with('status', 'Funcionario creado');
    }

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

        return redirect('registrarprestacion')->with('status', 'Nueva prestacion creada');
    }


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
            return redirect('asignarespecialidadprestacion')->with('status', 'Especialidades y Prestaciones actualizadas');
        }
    }

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
            return redirect('asignarespecialidad')->with('status', 'Especialidades actualizadas');
        }
    }

    public function editPatient(Request $request){

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
        if($patient){
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

    public function editRelease(Request $request){

        // URL to redirect when process finish.
        $url = "alta/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $release = Release::find($request->id);

        if($release){
            $release->descripcion = $request->descripcion;
            $release->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción del alta');
        
    }

    public function editAttribute(Request $request){

        // URL to redirect when process finish.
        $url = "atributo/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $attribute = Atributes::find($request->id);

        if($attribute){
            $attribute->descripcion = $request->descripcion;
            $attribute->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción del atributo');
        
    }

    public function editDiagnostic(Request $request){

        // URL to redirect when process finish.
        $url = "diagnostico/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $diagnostic = Diagnosis::find($request->id);

        if($diagnostic){
            $diagnostic->descripcion = $request->descripcion;
            $diagnostic->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción del diagnostico');
        
    }

    public function editSpeciality(Request $request){

        // URL to redirect when process finish.
        $url = "especialidad/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $speciality = Speciality::find($request->id);

        if($speciality){
            $speciality->descripcion = $request->descripcion;
            $speciality->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción de la especialidad');
        
    }

    public function editSex(Request $request){

        // URL to redirect when process finish.
        $url = "sexo/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $sex = Sex::find($request->id);

        if($sex){
            $sex->descripcion = $request->descripcion;
            $sex->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción del atributo');
        
    }

    public function editPrevition(Request $request){

        // URL to redirect when process finish.
        $url = "prevision/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $prevition = Prevition::find($request->id);

        if($prevition){
            $prevition->descripcion = $request->descripcion;
            $prevition->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción de la previsión');
        
    }

    public function editProvenance(Request $request){

        // URL to redirect when process finish.
        $url = "procedencia/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $provenance = Provenance::find($request->id);

        if($provenance){
            $provenance->descripcion = $request->descripcion;
            $provenance->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción de la procedencia');
        
    }

    public function editSiGGES(Request $request){

        // URL to redirect when process finish.
        $url = "sigges/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $sigges = Sigges::find($request->id);

        if($sigges){
            $sigges->descripcion = $request->descripcion;
            $sigges->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción del tipo GES');
        
    }

    public function editType(Request $request){

        // URL to redirect when process finish.
        $url = "prestacion/edit/" . $request->id;

        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $type = Type::find($request->id);

        if($type){
            $type->descripcion = $request->descripcion;
            $type->save();
        }

        return redirect($url)->with('status', 'Se actualizó la descripción de la prestación');
        
    }

    /***************************************************************************************************************************
                                                    ACTIONS BUTTONS FUNCTIONS
     ****************************************************************************************************************************/

    public function deletingPatient(Request $request)
    {
        $patient = Patient::where('DNI', $request->DNI)->get();
        $patient[0]->activa = 0;
        $patient[0]->save();
        return redirect('pacientes')->with('status', 'Paciente ' . $request->DNI . ' eliminado');
    }

    public function deletingFunctionary(Request $request)
    {
        $user = User::where('id', $request->DNI)->get();
        $user[0]->activa = 0;
        $user[0]->save();

        $functionary = Functionary::where('user_id', $request->DNI)->get();
        $functionary[0]->activa = 0;
        $functionary[0]->save();

        return redirect('funcionarios')->with('status', 'Funcionario ' . $user[0]->rut . ' eliminado');
    }

    public function activatePatient(Request $request)
    {
        $patient = Patient::where('DNI', $request->DNI)->get();
        $patient[0]->activa = 1;
        $patient[0]->save();
        return redirect('pacientes/inactivos')->with('status', 'Paciente ' . $request->DNI . ' reingresado');
    }

    public function activateFunctionary(Request $request)
    {
        $user = User::where('id', $request->DNI)->get();
        $user[0]->activa = 1;
        $user[0]->save();

        $functionary = Functionary::where('user_id', $request->DNI)->get();
        $functionary[0]->activa = 1;
        $functionary[0]->save();

        return redirect('funcionarios/inactivos')->with('status', 'Funcionario ' . $user[0]->rut . ' re-incorporado');
    }

    /***************************************************************************************************************************
                                                    HELPERS AND LOGIC FUNCTIONS
     ****************************************************************************************************************************/

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
    public function checkCurrStage(Request $request)
    {
        $DNI = $request->DNI_stage;//dni del paciente
        $patient = Patient::where('DNI',$DNI)
                            ->where('activa', 1)
                            ->first();
        $id_patient = $patient->id;
        $stage = Stage::where('paciente_id',$id_patient)
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
                      ->select('funcionarios.id','funcionarios.profesion','users.primer_nombre','users.apellido_paterno')
                      ->where('funcionarios.activa', '=', 1)
                      ->get();                            

           // return view('admin.stageCreateForm', compact('id_patient', 'functionary', 'diagnosis', 'program', 'release', 'Sigges', 'provenance'));
           return view('admin.stageCreateForm', ['patient' => 'no tiene ninguna etapa', 'idpatient'=>$id_patient])->with(compact('funcionarios', 'diagnosis', 'program', 'release', 'Sigges', 'provenance'));
        } else {
            $users = Functionary::where('activa', 1)->get();
            return view('general.attendanceForm', ['patient' => 'si posee una etapa activa', 'DNI'=>$DNI])->with( compact('stage','users','patient'));  
        }
    }
    public function showAddAttendance()
    {
        $users = Functionary::where('activa', 1)->get();
        return view('general.attendanceForm', compact('users'));
    }
    // acuerdate de cambiar estas weas wn 
    public function getStateList(Request $request)
    {
        $functionary = Functionary::find($request->functionary_id);
        $states = $functionary->speciality;
        return response()->json($states);
    }
    // y esto igual y los nombres
    public function getCityList(Request $request)
    {
        $specility = Speciality::find($request->speciality_id);
        $cities = $specility->provision;
        return response()->json($cities);
    }

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
        $attendance->fecha = $correctDate ;

        $attendance->asistencia = $request->get('selectA');
        $attendance->hora = $request->get('timeInit');
        $attendance->duracion = $request->get('duration');

        $attendance->save();

        $duration   = $request->get('duration');
        $vector     = explode(":",$duration);
        $hours      = $vector[0];
        $minutes    = $vector[1];
        $anterior   = $functionary->horasRealizadas;
        $functionary->horasRealizadas = $anterior+ $hours+$minutes/60;
        $functionary->save();
        
        $idPatient=$request->get('id');
        $patient = Patient::find($idPatient);
        $stage   = Stage::find($request->id_stage);
        $patientAtendances = $stage->attendance;
        $att = Attendance::all();
        return view('admin.clinicalRecords', compact('patient','stage','patientAtendances'));
        
    }
}
