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
        return view('general.patient', ['patients' => $patients, 'prev' => $prev, 'sex' => $sex]);
    }

    public function showInactivePatients()
    {
        $patients = Patient::where('activa', 0)->get();
        $prev = Prevition::all();
        $sex = Sex::all();
        return view('admin.patientInactive', ['patients' => $patients, 'prev' => $prev, 'sex' => $sex]);
    }

    public function showFunctionarys()
    {
        $functionary = Functionary::where('activa', 1)->get();
        $user = User::all();
        $speciality = Speciality::all();
        $fs = FunctionarySpeciality::all();
        return view('general.functionarys', ['functionary' => $functionary, 'user' => $user, 'speciality' => $speciality, 'fs' => $fs]);
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
        return view('general.test');
    }

    /***************************************************************************************************************************
                                             VIEWS       GET METHOD SHOW ADD
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
        return view('admin.releaseForm');
    }
    public function showAddProvenance()
    {
        return view('admin.provenanceForm');
    }
    
    public function showAddStage(){
        $patient = Patient::all();
        $functionary = Functionary::all();
        $diagnosis = Diagnosis::all();
        $program = Program::all();
        $release = Release::all();
        $Sigges = SiGGES::all();
        $provenance = Provenance::all();
        return view('admin.stageCreateForm', compact('patient','functionary','diagnosis','program','release','Sigges','provenance'));
  
        
    }  
    public function showAddPrevition()
    {
        return view('admin.previtionForm');
    }
    public function showAddProgram()
    {
        return view('admin.programForm');
    }
    public function showAddDiagnosis()
    {
        return view('admin.diagnosisForm');
    }

    public function showAddAtributes()
    {
        return view('admin.atributesForm');
    }

    public function showAddSex()
    {
        return view('admin.sexForm');
    }
    public function showAddType()
    {
        return view('admin.typeForm');
    }

    public function showAddSIGGES()
    {
        return view('admin.siggesForm');
    }

    public function showAddSpeciality()
    {
        $speciality = Speciality::where('activa', 1)->get();
        return view('admin.specialityForm', ['specialitys' => $speciality]);
    }

    public function showAddProvision()
    {
        $type = Type::where('activa', 1)->get();
        return view('admin.provisionForm', ['type' => $type]);
    }

    public function showAsignSpeciality()
    {
        $speciality = Speciality::orderBy('descripcion')
            ->get();

        $functionary = Functionary::orderBy('nombre1')
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
                $rows[$record1->nombre1 . " " . $record1->nombre2][$record2->descripcion] = $ids;
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
    
    public function showEditPatient($id){
        $patient = Patient::find($id);
        $prev = Prevition::all();
        $sex = Sex::all();
        $patient_prev = "";
        $patient_sex = "";
        $patient_birthday = "";

        if($patient){
            $patient_prev = Patient::find($id)->prevition;
            $patient_sex = Patient::find($id)->sex;

            // Change formate date to retrieve to the datapicker
            $patient_birthday = explode("-", $patient->fecha_nacimiento);
            $patient_birthday = join("/", array($patient_birthday[2],$patient_birthday[1],$patient_birthday[0]));

        }

        return view('admin.editPatient', ['patient' => $patient, 'patient_prev' => $patient_prev,'patient_sex' => $patient_sex, 'patient_birthday' => $patient_birthday, 'prev' => $prev, 'sex' => $sex]);
    }

    /***************************************************************************************************************************
                                                    POST METHOD (REGIST & ASIG)
     ****************************************************************************************************************************/
    public function registerStage(Request $request){

        $validation = $request->validate([
            
            ]);

        echo $request->new_start;

        $stage = new Stage;
        
        $stage->diagnostico_id = $request->diagnostico_id;
        $stage->programa_id = $request->programa_id;
        $stage->alta_id = $request->alta_id;
        $stage->sigges_id = $request->sigges_id;
        $stage->procedencia_id = $request->procedencia_id;
        $stage->funcionario_id = $request->funcionario_id;
        $stage->paciente_id = $request->paciente_id;
        $stage->save();
        
        
        return redirect('crearetapa')->with('status', 'etapa creada');

    }
     public function registerRelease(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $alta = new Release;

        $alta->descripcion = $request->descripcion;

        $alta->save();

        return redirect('registraralta')->with('status', 'Nueva alta creada');
    }
    public function registerProvenance(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $provenance = new Provenance;

        $provenance->descripcion = $request->descripcion;

        $provenance->save();

        return redirect('registrarprocedencia')->with('status', 'Nueva procedencia creada');
    }
    public function registerProgram(Request $request)
    {
        $validacion = $request->validate([
            'especialidad' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255'

        ]);

        $program = new Program;

        $program->descripcion = $request->descripcion;
        $program->especialidad = $request->especialidad;

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

        return redirect('registraratributos')->with('status', 'Nuevo atributo creado');
    }

    public function registerSex(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $sex = new Sex;

        $sex->descripcion = $request->descripcion;

        $sex->save();

        return redirect('registrarsexo')->with('status', 'Nuevo Sexo / Genero creado');
    }
    public function registerSIGGES(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $sigges = new SiGGES;

        $sigges->descripcion = $request->descripcion;

        $sigges->save();

        return redirect('registrarsigges')->with('status', 'Nuevo tipo de SiGGES creado');
    }

    public function registerType(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $type = new Type;

        $type->descripcion = $request->descripcion;

        $type->save();

        return redirect('registrartipo')->with('status', 'Nuevo tipo de prestación creada');
    }

    public function registerSpeciality(Request $request)
    {
        $validacion = $request->validate([
            'descripcion' => 'required|string|max:255'
        ]);

        $speciality = new Speciality;

        $speciality->descripcion = $request->descripcion;

        $speciality->save();

        return redirect('registrarespecialidad')->with('status', 'Nueva especialidad creada');
    }

    public function registerPrevition(Request $request)
    {

        $validacion = $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $prevition = new Prevition;

        $prevition->nombre = $request->nombre;

        $prevition->save();

        return redirect('registrarprevision')->with('status', 'Nueva prevision creada');
    }
    public function registerDiagnosis(Request $request)
    {

        $validacion = $request->validate([
            'descripcion' => 'required|string|max:382'
        ]);

        $diagnosis = new Diagnosis;

        $diagnosis->descripcion = $request->descripcion;

        $diagnosis->save();

        return redirect('registrardiagnostico')->with('status', 'Nuevo diagnostico creado');

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
        $user->rut = $request->rut;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect('registrar')->with('status', 'Usuario creado');
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

    public function activatePatient(Request $request)
    {
        $patient = Patient::where('DNI', $request->DNI)->get();
        $patient[0]->activa = 1;
        $patient[0]->save();
        return redirect('pacientesinactivos')->with('status', 'Paciente ' . $request->DNI . ' reingresado');
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
}
