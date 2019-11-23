<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Address;
use App\Attendance;
use App\Attributes;
use App\Diagnosis;
use App\Functionary;
use App\FunctionarySpeciality;
use App\Patient;
use App\Prevition;
use App\Program;
use App\Provenance;
use App\Provision;
use App\Release;
use App\User;
use App\SiGGES;
use App\Sex;
use App\Speciality;
use App\Stage;
use App\Type;

use Carbon\Carbon;
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
                                                    VIEWS FOR ADMIN ROLE ONLY
     ****************************************************************************************************************************/
    // clinical records
    public function showMonthlyRecords()
    {
        $patient = DB::table('paciente')
            ->join('prevision', 'paciente.prevision_id', '=', 'prevision.id')
            ->join('sexo', 'paciente.sexo_id', '=', 'sexo.id')
            ->join('etapa', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('atencion', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->join('funcionarios', 'funcionarios.id', '=', 'atencion.funcionario_id')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'funcionarios.id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->join('prestacion', 'prestacion.id', '=', 'atencion.prestacion_id')
            ->join('tipo_prestacion', 'tipo_prestacion.id', '=', 'prestacion.tipo_id')
            ->join('programa', 'programa.id', '=', 'etapa.programa_id')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->where('paciente.activa', '=', 1)
            ->select(
                'paciente.*',
                'prevision.*',
                'etapa.*',
                'atencion.*',
                'actividad.descripcion as actividad',
                'procedencia.descripcion as procedencia',
                'especialidad.*',
                'prestacion.*',
                'sexo.descripcion as sexo',
                'tipo_prestacion.descripcion as tipo',
                'programa.descripcion as programa',
                DB::raw("(CASE WHEN atencion.abre_canasta = 1 THEN 'SI' ELSE 'NO' END) AS canasta"),
                DB::raw("(CASE WHEN atencion.asistencia = 1 THEN 'SI' ELSE 'NO' END) AS asistencia"),
                DB::raw("CONCAT(users.nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as nombre_funcionario")
            )
            ->selectRaw('DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS edad')
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->get();

        return view('general.monthlyRecords', ['main' => json_encode($patient)]);
    }
    public function showSummaryRecords()
    {
        $patient = DB::table('paciente')
            ->join('prevision', 'paciente.prevision_id', '=', 'prevision.id')
            ->join('sexo', 'paciente.sexo_id', '=', 'sexo.id')
            ->join('etapa', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('atencion', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('actividad', 'actividad.id', '=', 'atencion.actividad_id')
            ->join('funcionarios', 'funcionarios.id', '=', 'atencion.funcionario_id')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'funcionarios.id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->join('prestacion', 'prestacion.id', '=', 'atencion.prestacion_id')
            ->join('tipo_prestacion', 'tipo_prestacion.id', '=', 'prestacion.tipo_id')
            ->join('programa', 'programa.id', '=', 'etapa.programa_id')
            ->join('procedencia', 'procedencia.id', '=', 'etapa.procedencia_id')
            ->where('paciente.activa', '=', 1)
            ->select(
                'paciente.*',
                'prevision.*',
                'etapa.*',
                'atencion.*',
                'actividad.descripcion as actividad',
                'procedencia.descripcion as procedencia',
                'especialidad.*',
                'prestacion.*',
                'sexo.descripcion as sexo',
                'tipo_prestacion.descripcion as tipo',
                'programa.descripcion as programa',
                DB::raw("(CASE WHEN atencion.abre_canasta = 1 THEN 'SI' ELSE 'NO' END) AS canasta"),
                DB::raw("(CASE WHEN atencion.asistencia = 1 THEN 'SI' ELSE 'NO' END) AS asistencia"),
                DB::raw("CONCAT(users.nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as nombre_funcionario")
            )
            ->selectRaw('DATEDIFF(hour,paciente.fecha_nacimiento,GETDATE())/8766 AS edad')
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->get();

        return view('general.test', ['main' => json_encode($patient)]);
    }
    // Pacientes inactivos
    /*public function showInactivePatients()
    {
        // Get patients from database where 'activa' attribute is 0 bits
        $patients = Patient::where('activa', 0)->get();
        // Get the list of previtions
        $prev = Prevition::all();
        // Get the list of genders
        $sex = Sex::all();
        // Count patients
        $cantPatients = $patients->count();
        // Redirect to the view with list of: inactive patients, all previtions and all genders
        return view('admin.Views.patientInactive', compact('patients', 'prev', 'sex', 'cantPatients'));
    }
    // Funcionarios inactivos
    public function showInactiveFunctionarys()
    {
        // Get Functionarys from database where 'activa' attribute is 0 bits
        $functionary = Functionary::where('activa', 0)->get();
        // Get the list of users
        $user = User::all();
        // Get the list of specialitys
        $speciality = Speciality::all();
        // Get the list of speciality per functionary
        $fs = FunctionarySpeciality::all();
        // Redirect to the view with list of: active functionarys, all users, all speciality and speciality per functionarys 
        return view('admin.Views.funtionaryInactive', compact('functionary', 'user', 'speciality', 'fs'));
    }
    // ???
    public function data()
    {
        $data = Patient::all()->toJson();
        return $data;
    }
    */
    /***************************************************************************************************************************
                                             VIEWS OF FORMS (ONLY ADMIN)
     ****************************************************************************************************************************/
    // Usuario
    /*public function showAddUser()
    {
        // Redirect to the view
        return view('admin.Form.userForm');
    }
    // Actividades
    public function showAddActivity()
    {
        // Get activitis in alfabetic order
        $data = Activity::orderBy('descripcion')->get();
        // Redirect to the view with list of activitis (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.activityForm', ['data' => $data, 'table' => 'Actividades']);
    }
    // Alta
    public function showAddRelease()
    {
        // Get releases in alfabetic order
        $data = Release::orderBy('descripcion')->get();
        // Redirect to the view with list of releases (standard name: data) and name of table in spanish (standard name: table) 
        return view('admin.Form.releaseForm', ['data' => $data, 'table' => 'Altas']);
    }
    // Atención
    public function showAddAttendance()
    {
        // Get active functionarys
        $users = Functionary::where('activa', 1)->get();
        // Redirect to the view with list of functionarys
        return view('general.attendanceForm', compact('users'));
    }
    // Atributo
    public function showAddAttributes()
    {
        // Get attributes in alfabetic order
        $data = Attributes::orderBy('descripcion')->get();
        // Redirect to the view with list of attriutes (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.attributesForm', ['data' => $data, 'table' => 'Atributos']);
    }
    // Diagnóstico
    public function showAddDiagnosis()
    {
        // Get diagnosis in alfabetic order
        $data = Diagnosis::orderBy('descripcion')->get();
        // Redirect to the view with list of diagnosis (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.diagnosisForm', ['data' => $data, 'table' => 'Diagnósticos']);
    }
    // Especialidad
    public function showAddSpeciality()
    {
        // Get specialitys in alfabetic order
        $data = Speciality::orderBy('descripcion')->get();
        // Redirect to the view with list of specialitys (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.specialityForm', ['data' => $data, 'table' => 'Especialidades']);
    }
    // Etapa
    public function showAddStage()
    {
        // Get list of each table from database
        $patient = Patient::all();
        $functionary = Functionary::all();
        $diagnosis = Diagnosis::all();
        $program = Program::all();
        $release = Release::all();
        $Sigges = SiGGES::all();
        $provenance = Provenance::all();
        // Redirect to the view with list of: patients, functionarys, diagnosis, programs, releases, sigges and provenances
        return view('admin.Form.stageCreateForm', compact('patient', 'functionary', 'diagnosis', 'program', 'release', 'Sigges', 'provenance'));
    }
    // Funcionario
    public function showAddFunctionary()
    {
        // Get active users
        $user = User::where('activa', 1)->get();
        // Redirect to the view with list of users
        return view('admin.Form.functionaryForm', compact('user'));
    }
    // Paciente
    public function showAddPatient()
    {
        // Get list of genders
        $sex = Sex::all();
        // Get list of previtions
        $previtions = Prevition::all();
        // Redirect to view with list of genders and previtions
        return view('admin.Form.patientForm', compact('sex', 'previtions'));
    }
    // Prestación
    public function showAddProvision()
    {
        // Get active types
        $type = Type::where('activa', 1)->get();
        // Get provisions
        $data = Provision::all();
        // Redirect to the view with list of types
        return view('admin.Form.provisionForm', compact('type', 'data'));
    }
    // Previsión
    public function showAddPrevition()
    {
        // Get previtions in alfabetic order
        $data = Prevition::orderBy('descripcion')->get();
        // Redirect to the view with list of previtions (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.previtionForm', ['data' => $data, 'table' => 'Previsiones']);
    }
    // Procedencia
    public function showAddProvenance()
    {
        // Get prevenances in alfabetic order
        $data = Provenance::orderBy('descripcion')->get();
        // Redirect to the view with list of prevenances (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.provenanceForm', ['data' => $data, 'table' => 'Procedencias']);
    }
    // Programa
    public function showAddProgram()
    {
        // Get list of specialitys
        $speciality = Speciality::all();
        // Get programs in alfabetic order
        $data = Program::orderBy('descripcion')->get();
        // Redirect to the view with list of specialitys, programs (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.programForm', compact('speciality', 'data'));
    }
    // Sexo / Género
    public function showAddSex()
    {
        // Get genders in alfabetic order
        $data = Sex::orderBy('descripcion')->get();
        // Redirect to the view with list of genders (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.sexForm', ['data' => $data, 'table' => 'Géneros']);
    }
    // SiGGES
    public function showAddSIGGES()
    {
        // Get sigges in alfabetic order
        $data = SiGGES::orderBy('descripcion')->get();
        // Redirect to the view with list of sigges (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.siggesForm', ['data' => $data, 'table' => 'Tipo GES']);
    }
    // Tipo
    public function showAddType()
    {
        // Get types in alfabetic order
        $data = Type::orderBy('descripcion')->get();
        // Redirect to the view with list of types (standard name: data) and name of table in spanish (standard name: table)
        return view('admin.Form.typeForm', ['data' => $data, 'table' => 'Tipo prestaciones']);
    }
    // Asignar Especialidad
    public function showAsignSpeciality()
    {
        // Get specialitys in alfabetic order
        $speciality = Speciality::orderBy('descripcion')->get();
        // Get functionarys in alfabetic order by profesions
        $functionary = Functionary::orderBy('profesion')->get();
        // Create some variables
        $rows = [];
        $columns = [];
        $ids = [];
        // First loop (by speciality)
        foreach ($speciality as $index => $record) {
            // Get uniques profesions
            if (!in_array($record->profesion, $columns)) {
                // Add the profesion into columns
                $columns[] = $record->descripcion;
            }
        }
        // Second loop (by functionary)
        foreach ($functionary as $index => $record1) {
            // Get the functionary_id and add it into the first position of ids
            $ids[0] = $record1->id;
            // Third loop (by speciality)
            foreach ($speciality as $index => $record2) {
                // Get the speciality_id and add it into the second position of ids
                $ids[1] = $record2->id;
                // Get full name of functionary and add it into rows
                $rows[$record1->user->primer_nombre . " " . $record1->user->segundo_nombre][$record2->descripcion] = $ids;
            }
        }
        // Redirect to the view with specialitys per each functionary
        return view('admin.Asingment.specialityAsign', compact('rows', 'columns'));
    }
     // Asignar Especialidad
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
         return view('admin.Asingment.typeAsign', compact('rows', 'columns'));
     }
    // Asignar Actividad
    public function showAsignActivity()
    {
        // Get specialitys in alfabetic order
        $speciality = Speciality::orderBy('descripcion')->get();
        // Get activity's in alfabetic order
        $activity = Activity::orderBy('descripcion')->get();
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
        // Second loop (by activity)
        foreach ($activity as $index => $record1) {
            // Get the activity and add it into the first position of ids
            $ids[0] = $record1->id;
            // Third loop (by speciality)
            foreach ($speciality as $index => $record2) {
                // Get the speciality_id and add it into the second position of ids
                $ids[1] = $record2->id;
                // Get unique code and name of provision and add it into rows
                $rows[$record1->descripcion][$record2->descripcion] = $ids;
            }
        }
        // Redirect to the view with specialitys per each provision
        return view('admin.Asignment.activityAsign', compact('rows', 'columns'));
    }
    // Asignar Prestación
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
                $columns[] = " | " . $record->descripcion . " | ";
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
    */
    /***************************************************************************************************************************
                                             VIEWS OF EDIT (ONLY ADMIN)
     ****************************************************************************************************************************/
    // Actividad

    /*public function showEditActivity($id){
        
        // Get the specific activity
        $activity = Activity::find($id);

        // Redirect to the view with selected activity
        return view('admin.Edit.activityEdit', compact('activity'));
    }

     // Alta
    public function showEditRelease($id)
    {
        // Get the specific release
        $release = Release::find($id);
        // Redirect to the view with selected release
        return view('admin.Edit.releaseEdit', compact('release'));
    }
    // Atributo
    public function showEditAttribute($id)
    {
        // Get the specific attribute
        $attribute = Attributes::find($id);
        // Redirect to the view with selected attribute
        return view('admin.Edit.attributeEdit', compact('attribute'));
    }
    // Diagnóstico
    public function showEditDiagnostic($id)
    {
        // Get the specific diagnosis
        $diagnostic = Diagnosis::find($id);
        // Redirect to the view with selected diagnosis
        return view('admin.Edit.diagnosticEdit', compact('diagnostic'));
    }
    // Especialidad
    public function showEditSpeciality($id)
    {
        // Get the specific speciality
        $speciality = Speciality::find($id);
        // Redirect to the view with selected speciality
        return view('admin.Edit.specialityEdit', compact('speciality'));
    }
    // Paciente
    public function showEditPatient($dni)
    {
        // Get the first patient that match with DNI
        $patient = Patient::where('DNI', $dni)->first();
        // Get previtions
        $prev = Prevition::all();
        // Get genders
        $sex = Sex::all();
        // Create variable for date
        $patient_birthdate = "";
        // If patient exist, then change formate date to retrieve to the datepicker
        if ($patient) {
            // Separate birthdate (dd-mm-yyyy) into array
            $patient_birthdate = explode("-", $patient->fecha_nacimiento);
            // Set date array as new date format (yyyy/mm/dd)
            $patient_birthdate = join("/", array($patient_birthdate[2], $patient_birthdate[1], $patient_birthdate[0]));
        }
        // Redirect to the view with list of prevition and gender, also return the patient and birthdate
        return view('admin.Edit.patientEdit', compact('patient', 'patient_birthdate', 'prev', 'sex'));
    }
    // Previsión
    public function showEditPrevition($id)
    {
        // Get the specific prevition
        $prevition = Prevition::find($id);
        // Redirect to the view with selected prevition
        return view('admin.Edit.previtionEdit', compact('prevition'));
    }
    // Procedencia
    public function showEditProvenance($id)
    {
        // Get the specific provenance
        $provenance = Provenance::find($id);
        // Redirect to the view with selected provenance
        return view('admin.Edit.provenanceEdit', compact('provenance'));
    }
    // Sexo / género
    public function showEditSex($id)
    {
        // Get the specific gender
        $sex = Sex::find($id);
        // Redirect to the view with selected gender
        return view('admin.Edit.sexEdit', compact('sex'));
    }
    // SiGGES
    public function showEditSiGGES($id)
    {
        // Get the specific sigges
        $sigges = SiGGES::find($id);
        // Redirect to the view with selected sigges
        return view('admin.Edit.siggesEdit', compact('sigges'));
    }
    // Tipo
    public function showEditType($id)
    {
        // Get the specific type
        $type = Type::find($id);
        // Redirect to the view with selected type
        return view('admin.Edit.typeEdit', compact('type'));
    }
    */
    /***************************************************************************************************************************
                                                    FORMS (POST)
     ****************************************************************************************************************************/
    // Usuario
    /*public function registerUser(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'nombre' => 'required|string|max:255',
            'rut' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'rol' => 'required|integer|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        // Create a new 'object' user
        $user = new User;
        // Set the variables to the object user
        // the variables name of object must be the same that database
        // nombre, primer_nombre, segundo_nombre, apellido_paterno, apellido_materno, rut, email, rol, password
        $user->nombre = $request->nombre;
        $user->primer_nombre = $request->primer_nombre;
        $user->segundo_nombre = $request->segundo_nombre;
        $user->apellido_paterno = $request->apellido_paterno;
        $user->apellido_materno = $request->apellido_materno;
        $user->rut = $request->rut;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->password = Hash::make($request->password);
        // Pass the user to database
        $user->save();
        // Redirect to the view with successful status
        return redirect('registrar/usuario')->with('status', 'Usuario creado');
    }
    // Actividades
    public function registerActivity(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'activity' => 'required|string|max:255'
        ]);
        // Create a new 'object' activity
        $activity = new Activity;
        // Set the variables to the object activity
        // the variables name of object must be the same that database for save it
        // descripcion
        $activity->descripcion = $request->activity;
        // descripcion
        $activity->actividad_abre_canasta = $request->input('openCanasta', 0);
        // Pass the activity to database
        $activity->save();
        // Redirect to the view with successful status
        return redirect('registrar/actividad')->with('status', 'Nueva actividad creada');
    }
    // Alta
    public function registerRelease(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'medical_discharge' => 'required|string|max:255'
        ]);
        // Create a new 'object' release
        $release = new Release;
        // Set the variables to the object release
        // the variables name of object must be the same that database for save it
        // descripcion
        $release->descripcion = $request->medical_discharge;
        // Pass the release to database
        $release->save();
        // Redirect to the view with successful status
        return redirect('registrar/alta')->with('status', 'Nueva alta creada');
    }
    // Atención
    public function registerAttendance(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            //'descripcion' => 'required|string|max:255'
        ]);
        // Create a new 'object' attendance
        $attendance = new Attendance;
        // Get the specific functionary
        $functionary = Functionary::find($request->functionary);
        // Set some variables with functionary info and inputs of view
        // the variables name of object must be the same that database for save it
        // from attendanceForm: provision, id_stage, DNI, speciality, assistance, duration of attendance, date of attendance
        // attendancte -> funcionario_id, etapa_id, prestacion_id, fecha, hora, asistencia, duracion
        $attendance->funcionario_id = $request->functionary;
        $attendance->etapa_id = $request->id_stage;
        $attendance->prestacion_id = $request->get('provision');
        $attendance->asistencia = $request->get('selectA');
        $attendance->hora = $request->get('timeInit');
        $attendance->duracion = $request->get('duration');
        // Re-format database date to datepicker type
        $var = $request->get('datepicker');
        $date = str_replace('/', '-', $var);
        $correctDate = date('Y-m-d', strtotime($date));
        $attendance->fecha = $correctDate;
        // Pass the attendance to database
        $attendance->save();
        // Update variable for functionary
        // functionary -> horasRealizadas
        $duration   = $request->get('duration');
        $vector     = explode(":", $duration);
        $hours      = $vector[0];
        $minutes    = $vector[1];
        // Get previous hours worked
        $anterior   = $functionary->horasRealizadas;
        // Add the new hours
        $functionary->horasRealizadas = $anterior + $hours + $minutes / 60;
        // Save the update
        $functionary->save();
        // Get the patient
        $idPatient = $request->get('id');
        $patient = Patient::find($idPatient);
        // Get the active stage
        $stage   = Stage::find($request->id_stage);
        $patientAtendances = $stage->attendance;

        if($request->register==1){
            // Redirect to the view with successful status
            return view('admin.Views.clinicalRecords', compact('patient', 'stage', 'patientAtendances'));
        }
        if($request->register==2){
            // Get active functionarys
            $users = Functionary::where('activa', 1)->get();
            return view('general.attendanceForm', ['DNI' => $idPatient])->with(compact('stage', 'users', 'patient'));
        }
    }
    // Atributo
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
    // Diagnóstico
    public function registerDiagnosis(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'diagnosis' => 'required|string|max:382'
        ]);
        // Create a new 'object' diagnosis
        $diagnosis = new Diagnosis;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $diagnosis->descripcion = $request->diagnosis;
        // Pass the new diagnosis to database
        $diagnosis->save();
        // Redirect to the view with successful status
        return redirect('registrar/diagnostico')->with('status', 'Nuevo diagnostico creado');
    }
    // Especialidad
    public function registerSpeciality(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'medical_speciality' => 'required|string|max:255'
        ]);
        // Create a new 'object' speciality
        $speciality = new Speciality;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $speciality->descripcion = $request->medical_speciality;
        // Pass the new speciality to database
        $speciality->save();
        $codigos = Provision::where('activa', '=', 1)->get('id');
        $speciality->provision()->sync($codigos);

        // Redirect to the view with successful status
        return redirect('registrar/especialidad')->with('status', 'Nueva especialidad creada');
    }
    // Etapa
    public function registerStage(Request $request)
    {
        // Check the format of each variable of 'request'
        $validation = $request->validate([]);

        echo $request->new_start;
        // Create a new 'object' stage
        $stage = new Stage;
        // Set some variables with inputs of view
        // the variables name of object must be the same that database for save it
        // diagnostico_id, programa_id, sigges_id, procedencia_id, funcionario_id, paciente_id
        $stage->diagnostico_id = $request->diagnostico_id;
        $stage->programa_id = $request->programa_id;
        //$stage->alta_id = $request->alta_id;
        $stage->sigges_id = $request->sigges_id;
        $stage->procedencia_id = $request->procedencia_id;
        $stage->funcionario_id = $request->funcionario_id;
        $stage->paciente_id = $request->idpatient;
        // Pass the new stage to database
        $stage->save();
        // Set variable with patient_id
        $DNI = $request->idpatient;
        // Get the patient
        $patient = Patient::where('id', $DNI)
            ->where('activa', 1)
            ->first();
        // Get active functionarys
        $users = Functionary::where('activa', 1)->get();
        // Redirect to the view with stage, users (functionarys), patient, DNI (id of patient, we use DNI as standard in several views)
        // Also pass to the view the id of stage as stage_id
        return view('general.attendanceForm', ['stage_id' => $stage->id])->with(compact('stage', 'users', 'patient', 'DNI'));
    }
    // Funcionario
    public function registerFunctionary(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'profesion' => 'required|string|max:255',
            'user' => 'required|integer|max:255'
        ]);
        // Create a new 'object' functionary
        $functionary = new Functionary;
        // Set some variables with inputs of view
        // the variables name of object must be the same that database for save it
        // profesion, user_id, horasDeclaradas
        $functionary->profesion = $request->profesion;
        // We need create user before the functionary
        $functionary->user_id = $request->user;
        $functionary->horasDeclaradas = $request->declared_hours;
        // Pass the functionary to database
        $functionary->save();
        // Redirect to the view with successful status
        return redirect('registrar/funcionario')->with('status', 'Funcionario creado');
    }
    // Paciente
    public function registerPatient(Request $request)
    {
        // Check the format of each variable of 'request'
        $validation = $request->validate([
            'rut' => 'required|string|unique:paciente,DNI',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'comuna' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'numero' => 'required|int',
            'patient_sex' => 'required',
            'prevition' => 'required|int',
            'numero' => 'required|int',
            'datepicker' => 'required|date_format:"d/m/Y"',
        ]);

        echo $request->new_start;
        // Separate the name string into array
        $nombre = explode(" ", $request->name);
        // Create the new 'oject' patient
        $patient = new Patient;
        // Set some variables with inputs of view
        // the variables name of object must be the same that database for save it
        // patient -> DNI, nombre1, nombre2, apellido1, apellido2, sexo_id, fecha_nacimiento, prevision_id
        $patient->nombre1 = $nombre[0];
        $patient->nombre2 = "-";
        if(count($nombre)==2){
            $patient->nombre2 = $nombre[1];
        }
        $patient->apellido1 = $request->apellido1;
        $patient->apellido2 = $request->apellido2;
        $patient->DNI = $request->id;
        $patient->prevision_id = $request->prevition;
        $patient->sexo_id = $request->patient_sex;
        // Change datepicker format to database format
        $var = $request->get('datepicker');
        $date = str_replace('/', '-', $var);
        $correctDate = date('Y-m-d', strtotime($date));
        $patient->fecha_nacimiento = $correctDate;
        // Create the new 'oject' patient
        // address -> region, comuna, calle, numero
        $address = new Address;
        $address->region = $request->region;
        $address->comuna = $request->comuna;
        $address->calle  = $request->calle;
        $address->numero = $request->numero;
        // Pass both to database
        $patient->save();
        $address->save();
        // Use the sync method to construct many-to-many associations
        $patient->address()->sync($address);
        // Redirect to the view with successful status
        return redirect('registrar/paciente')->with('status', 'Usuario creado');
    }
    // Previsión
    public function registerPrevition(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'prevition' => 'required|string|max:255'
        ]);
        // Create the 'object' prevition
        $prevition = new Prevition;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $prevition->descripcion = $request->prevition;
        // Pass the prevition to database
        $prevition->save();
        // Redirect to the view with the successful status
        return redirect('registrar/prevision')->with('status', 'Nueva prevision creada');
    }
    // Procedencia
    public function registerProvenance(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'provenance' => 'required|string|max:255'
        ]);
        // Create the new 'object' provenance
        $provenance = new Provenance;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $provenance->descripcion = $request->provenance;
        // Pass the provenance to database
        $provenance->save();
        // Redirect to the view with successful status
        return redirect('registrar/procedencia')->with('status', 'Nueva procedencia creada');
    }
    // Programa
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
    // Prestación
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
    // Sexo / género
    public function registerSex(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'sexuality' => 'required|string|max:255'
        ]);
        // Create the new 'object' sex
        $sex = new Sex;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $sex->descripcion = $request->sexuality;
        // Pass the gender to database
        $sex->save();
        // Redirect to the view with successful status
        return redirect('registrar/genero')->with('status', 'Nuevo Sexo / Genero creado');
    }
    // SiGGES
    public function registerSIGGES(Request $request)
    {
        // Check the format of each variable of 'request'
        $validacion = $request->validate([
            'sigges' => 'required|string|max:255'
        ]);
        // Create the new 'object' sigges
        $sigges = new SiGGES;
        // Set the variable 'descripcion'
        // the variables name of object must be the same that database for save it
        $sigges->descripcion = $request->sigges;
        // Pass the sigges to databes
        $sigges->save();
        // Redirect to the view with successful status
        return redirect('registrar/sigges')->with('status', 'Nuevo tipo de SiGGES creado');
    }
    // Tipo
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
    // Asignar Actividad
    public function AsignActivity(Request $request)
    {
        if (isset($_POST['enviar'])) {
            $activity = Activity::where('activa', 1)->get();
            foreach ($activity as $acty) {
                $acty->speciality()->sync([]);
            }
            if (isset($_POST['asignations'])) {
                if (is_array($_POST['asignations'])) {
                    foreach ($_POST['asignations'] as $key) {
                        $codigos = array();
                        foreach ($key as $key2 => $value) {
                            $str_arr = explode("|", $value);
                            $speciality = Speciality::find($str_arr[1]);
                            array_push($codigos, $speciality->id);
                            $activity = Activity::find($str_arr[0]);
                        }
                        $activity->speciality()->sync($codigos);
                    }
                }
            }
            return redirect('asignar/especialidad-actividad')->with('status', 'Especialidades y Prestaciones actualizadas');
        }
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
    // Asignar especialidad a tipo que abre canasta
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
    */
    /***************************************************************************************************************************
                                                    EDIT (POST)
     ****************************************************************************************************************************/
    // Actividad
    /*public function editActivity(Request $request)
    {
        // URL to redirect when process finish.
        $url = "actividad/edit/" . $request->id;
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the release that want to update
        $activity = Activity::find($request->id);
        // If found it then update the data
        if ($activity) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $activity->descripcion = $request->descripcion;

            // Set variable openCanasta when that option was clicked
            if($request->openCanasta){
                $activity->actividad_abre_canasta = 1;
            }else{
                $activity->actividad_abre_canasta = 0;
            }
            // Pass the new info for update
            $activity->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la información de la actividad');
    }
    // Alta
    public function editRelease(Request $request)
    {
        // URL to redirect when process finish.
        $url = "alta/edit/" . $request->id;
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the release that want to update
        $release = Release::find($request->id);
        // If found it then update the data
        if ($release) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $release->descripcion = $request->descripcion;
            // Pass the new info for update
            $release->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción del alta');
    }
    // Atributo
    public function editAttribute(Request $request)
    {
        // URL to redirect when process finish.
        $url = "atributo/edit/" . $request->id;
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
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción del atributo');
    }
    // Diagnóstico
    public function editDiagnostic(Request $request)
    {
        // URL to redirect when process finish.
        $url = "diagnostico/edit/" . $request->id;
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the diagnostic that want to update
        $diagnostic = Diagnosis::find($request->id);
        // If found it then update the data
        if ($diagnostic) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $diagnostic->descripcion = $request->descripcion;
            // Pass the new info for update
            $diagnostic->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción del diagnostico');
    }
    // Especialidad
    public function editSpeciality(Request $request)
    {
        // URL to redirect when process finish.
        $url = "especialidad/edit/" . $request->id;
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the speciality that want to update
        $speciality = Speciality::find($request->id);
        // If found it then update the data
        if ($speciality) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $speciality->descripcion = $request->descripcion;
            // Pass the new info for update
            $speciality->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción de la especialidad');
    }
    */
    // Paciente
    /*public function editPatient(Request $request)
    {
        // URL to redirect when process finish.
        $url = "pacientes/edit/" . $request->dni;
        // Validate the request variables
        $validation = $request->validate([
            'dni' => 'required|string|max:255',
            'nombres' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'apellido2' => 'required|string|max:255',
            'datepicker' => 'required|date_format:"d/m/Y"',
        ]);
        */
    /* add this things adter
                'pais' => 'required|string|max:255',
                'region' => 'required|string|max:255',
                'numero' => 'required|int',
                'direccion' => 'string|max:255|nullable',
            */
    /*

        // Get the patient that want to update
        $patient = Patient::find($request->id);
        // If found it then update the data
        if ($patient) {
            // Set some variables with inputs of view
            // patient -> DNI, nombre1, nombre2, apellido1, apellido2, sexo_id, prevision_id
            $nombre = explode(" ", $request->nombres);
            $patient->nombre1 = $nombre[0];
            $patient->nombre2 = $nombre[1];
            $patient->apellido1 = $request->apellido1;
            $patient->apellido2 = $request->apellido2;
            $patient->DNI = $request->dni;
            $patient->prevision_id = $request->prev;
            $patient->sexo_id = $request->sex;
            // Change datepicker format to database format
            $var = $request->get('datepicker');
            $date = str_replace('/', '-', $var);
            $correctDate = date('Y-m-d', strtotime($date));
            $patient->fecha_nacimiento = $correctDate;
            // Pass the new info for update
            $patient->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizaron los datos del paciente');
    }
    // Previsión
    public function editPrevition(Request $request)
    {
        // URL to redirect when process finish.
        $url = "prevision/edit/" . $request->id;
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the prevition that want to update
        $prevition = Prevition::find($request->id);
        // If found it then update the data
        if ($prevition) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $prevition->descripcion = $request->descripcion;
            // Pass the new info for update
            $prevition->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción de la previsión');
    }
    // Procedencia
    public function editProvenance(Request $request)
    {
        // URL to redirect when process finish.
        $url = "procedencia/edit/" . $request->id;
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the provenance that want to update
        $provenance = Provenance::find($request->id);
        // If found it then update the data
        if ($provenance) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $provenance->descripcion = $request->descripcion;
            // Pass the new info for update
            $provenance->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción de la procedencia');
    }
    // Sexo / género
    public function editSex(Request $request)
    {
        // URL to redirect when process finish.
        $url = "sexo/edit/" . $request->id;
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the gender that want to update
        $sex = Sex::find($request->id);
        // If found it then update the data
        if ($sex) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $sex->descripcion = $request->descripcion;
            // Pass the new info for update
            $sex->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción del atributo');
    }
    // SiGGES
    public function editSiGGES(Request $request)
    {
        // URL to redirect when process finish.
        $url = "sigges/edit/" . $request->id;
        // Validate the request variable
        $validation = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);
        // Get the gender that want to update
        $sigges = SiGGES::find($request->id);
        // If found it then update the data
        if ($sigges) {
            // Set the variable 'descripcion'
            // the variables name of object must be the same that database for save it
            $sigges->descripcion = $request->descripcion;
            // Pass the new info for update
            $sigges->save();
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción del tipo GES');
    }
    // Tipo
    public function editType(Request $request)
    {
        // URL to redirect when process finish.
        $url = "prestacion/edit/" . $request->id;
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
        }
        // Redirect to the URL with successful status
        return redirect($url)->with('status', 'Se actualizó la descripción de la prestación');
    }
    */
    /***************************************************************************************************************************
                                                    ACTIONS BUTTONS FUNCTIONS
     ****************************************************************************************************************************/
    // Activate
    /*public function activatePatient(Request $request)
    {
        // Get the patient
        $patient = Patient::where('DNI', $request->DNI)->get();
        // Update active to 1 bits
        $patient[0]->activa = 1;
        // Send update to database
        $patient[0]->save();
        // Redirect to the view with successful status (showing the DNI)
        return redirect('pacientes/inactivos')->with('status', 'Paciente ' . $request->DNI . ' reingresado');
    }
    public function activateFunctionary(Request $request)
    {
        // Get the functionary
        $functionary = Functionary::where('id', $request->id)->get();
        // Update active to 1 bits
        $functionary[0]->activa = 1;
        // Send update to database
        $functionary[0]->save();
        // Get the user, because have the personal info
        $user = User::where('id', $functionary[0]->user_id)->get();
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('funcionarios/inactivos')->with('status', 'Funcionario ' . $user[0]->rut . ' re-incorporado');
    }
    // Deactivate
    public function deletingPatient(Request $request)
    {
        // Get the patient
        $patient = Patient::where('DNI', $request->DNI)->get();
        // Update active to 0 bits
        $patient[0]->activa = 0;
        // Send update to database
        $patient[0]->save();
        // Redirect to the view with successful status (showing the DNI)
        return redirect('pacientes')->with('status', 'Paciente ' . $request->DNI . ' eliminado');
    }
    public function deletingFunctionary(Request $request)
    {
        // Get the functionary
        $functionary = Functionary::where('id', $request->id)->get();
        // Update active to 0 bits
        $functionary[0]->activa = 0;
        // Send update to database
        $functionary[0]->save();
        // Get the user, because have the personal info
        $user = User::where('id', $functionary[0]->user_id)->get();
        // Redirect to the view with successful status (showing the user_rut)
        return redirect('funcionarios')->with('status', 'Funcionario ' . $user[0]->rut . ' eliminado');
    }
    */
    /***************************************************************************************************************************
                                                    HELPERS AND LOGIC FUNCTIONS
     ****************************************************************************************************************************/
    // Check for a speciality linked to the functionary (parameter)
    // Called from specialityAsign
    public static function existFunctionarySpeciality($idFunct, $idSp)
    {
        // Create boolean variable
        $value = false;
        // Query to check if speciality have a functionary
        $doesClientHaveProduct = Speciality::where('id', $idSp)
            ->whereHas('functionary', function ($q) use ($idFunct) {
                $q->where('dbo.funcionarios.id', $idFunct);
            })
            ->count();
        // If found it then change the boolean as True
        if ($doesClientHaveProduct) {
            $value = true;
        }
        // Return the boolean
        return $value;
    }
    // Check for a speciality linked to the functionary (parameter)
    // Called from specialityAsign
    public static function existTypeSpeciality($idSp, $idType)
    {
        // Create boolean variable
        $value = false;
        // Query to check if speciality have a functionary
        $doesSpecialityHaveType = Speciality::where('id', $idSp)
            ->whereHas('type', function ($q) use ($idType) {
                $q->where('dbo.tipo_prestacion.id', $idType);
            })
            ->count();
        // If found it then change the boolean as True
        if ($doesSpecialityHaveType) {
            $value = true;
        }
        // Return the boolean
        return $value;
    }
    // Check activity for the speciality (parameter)
    // Called from activityAsign
    public static function existActivitySpeciality($idprov, $idSp)
    {
        // Create boolean variable
        $value = false;
        // Query to check if speciality have a activity
        $doesActivityHaveSpeciality = Speciality::where('id', $idSp)
            ->whereHas('activity', function ($q) use ($idprov) {
                $q->where('dbo.actividad.id', $idprov);
            })
            ->count();
        // If found it then change the boolean as True
        if ($doesActivityHaveSpeciality) {
            $value = true;
        }
        // Return the boolean
        return $value;
    }
    // Check provision for the speciality (parameter)
    // Called from provisionAsign
    public static function existProvisionSpeciality($idprov, $idSp)
    {
        // Create boolean variable
        $value = false;
        // Query to check if speciality have a provision
        $doesProvisionHaveSpeciality = Speciality::where('id', $idSp)
            ->whereHas('provision', function ($q) use ($idprov) {
                $q->where('dbo.prestacion.id', $idprov);
            })
            ->count();
        // If found it then change the boolean as True
        if ($doesProvisionHaveSpeciality) {
            $value = true;
        }
        // Return the boolean
        return $value;
    }
    /***************************************************************************************************************************
                                                    ATTENDANCE LOGIC
     ****************************************************************************************************************************/
    // Check for an active stage for the patient (parameter)
    /*
    public function checkCurrStage(Request $request)
    {
        // Set variable with patient DNI (rut)
        $DNI = $request->DNI_stage;
        // Get the patient
        $patient = Patient::where('DNI', $DNI)
            ->where('activa', 1)
            ->first();
        // Set variable with patient id (from database)
        $id_patient = $patient->id;
        // Get the active stage
        $stage = Stage::where('paciente_id', $id_patient)
            ->where('activa', 1)
            ->first();
        // If have no active stage
        if (empty($stage)) {
            return $this->showAddStage($id_patient);
        } else {
            // Get active functionarys
            $users = Functionary::where('activa', 1)->get();
            return view('general.attendanceForm', ['DNI' => $DNI])->with(compact('stage', 'users', 'patient'));
        }
    }
    // Create a new stage for a patient
    public function showAddStage($patient_id)
    {
        // Get diagnosis, program, release, sigges, provenance
        $diagnosis = Diagnosis::where('activa', 1)->get();
        $program = Program::where('activa', 1)->get();
        $Sigges = SiGGES::where('activa', 1)->get();
        $provenance = Provenance::all();
        // Get the functionarys with user info (personal information)
        $functionarys = Functionary::join('users', 'users.id', '=', 'funcionarios.user_id')
            ->select('funcionarios.id', 'funcionarios.profesion', 'users.primer_nombre', 'users.apellido_paterno')
            ->where('funcionarios.activa', 1)
            ->get();
        // return view('admin.stageCreateForm', compact('id_patient', 'functionary', 'diagnosis', 'program', 'release', 'Sigges', 'provenance'));
        return view('admin.Form.stageCreateForm', ['idpatient' => $patient_id])->with(compact('functionarys', 'diagnosis', 'program', 'Sigges', 'provenance'));
    }*/
    // Return a specialitys from one functionary
    public function getSpecialityPerFunctionary(Request $request)
    {
        // Get the functionary
        $functionary = Functionary::find($request->functionary_id);
        // Create a variable for send to the view
        $speciality = $functionary->speciality;
        // Return specialitys
        return response()->json($speciality);
    }
    // Return a provisions from one speciality
    public function getProvisionPerSpeciality(Request $request)
    {
        // Get the speciality
        $specility = Speciality::find($request->speciality_id);
        // Create a variable for send to the view
        $provision = $specility->provision;
        // Return provisions
        return response()->json($provision);
    }
    // Return a activitys from one speciality
    public function getActivityPerSpeciality(Request $request)
    {
        // Get the speciality
        $specility = Speciality::find($request->speciality_id);
        // Create a variable for send to the view
        $activity = $specility->activity;
        // Return activity's
        return response()->json($activity);
    }
    // Compare age of patient and range age of provision
    public function checkAge(Request $request)
    {
        // Get the patient
        $patient = Patient::find(1);
        // Get the provision
        $provision = Provision::find($request->provision_id);
        // Get age fo patient
        $years = Carbon::parse($patient->fecha_nacimiento)->age;
        // Get ranges
        $inf = $provision->rangoEdad_inferior;
        $sup = $provision->rangoEdad_superior;
        // Default
        $response = 0;
        // Check if age is in range
        if (($inf <= $years) && ($years <= $sup)) {
            $response = 1;
        } else {
            $response = -1;
        }
        if (($inf == 0) && ($sup == 0)) {
            $response = 1;
        }
        // Return provisions
        return response()->json($response);
    }
}
