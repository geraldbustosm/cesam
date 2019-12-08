<?php

namespace App\Http\Controllers;

use App\Diagnosis;
use App\Functionary;
use App\Patient;
use App\Provision;
use App\Speciality;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function foo()
    {
<<<<<<< HEAD
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
            ->whereMonth('atencion.fecha', Carbon::now()->month)
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
            ->get();

        return view('general.recordsMonthly', ['main' => json_encode($patient)]);
    }
    // View for monthly summary
    public function showSummaryRecords()
    {
        // Get the functionarys
        $functionarys = DB::table('funcionarios')
            ->join('users', 'users.id', '=', 'funcionarios.user_id')
            ->select(
                'funcionarios.id as id',
                DB::raw("CONCAT(users.nombre,' ', users.apellido_paterno, ' ', users.apellido_materno) as nombre_funcionario")
            )
            ->get();
        // Get the activities
        $activities = Activity::where('activa', 1)->orderBy('descripcion')->select('id', 'descripcion')->get();
        // Some variables
        $num = 0;
        $dataTotal = [];
        $dataAttend = [];
        $dataNoAttend = [];
        // For each activity
        foreach ($activities as $index => $record1) {
            // Create necesary objects
            $obj = new \stdClass();
            $objChildren1 = new \stdClass();
            $objChildren2 = new \stdClass();
            // Adding the first attribute
            $obj->actividad = $record1->descripcion;
            $objChildren1->actividad = $record1->descripcion;
            $objChildren2->actividad = $record1->descripcion;
            // For each Functionary
            foreach ($functionarys as $index => $record2) {
                // Get name, count attend and count no attend              
                $nombre = $record2->nombre_funcionario;
                $attend = $this->countActivitiesPerFunctionary($record2->id, $record1->id, 1);
                $notAttend = $this->countActivitiesPerFunctionary($record2->id, $record1->id, 2);
                // Setting counts attributes (use the name of functionary like name of attribute)
                $obj->$nombre = $notAttend + $attend;
                $objChildren1->$nombre = $attend;
                $objChildren2->$nombre = $notAttend;
            }
            // Setting children objects to main object
            $obj->_children[0] = $objChildren1;
            $obj->_children[1] = $objChildren2;
            // Adding object to arrays
            $dataTotal[$num] = $obj;
            $dataAttend[$num] = $objChildren1;
            $dataNoAttend[$num] = $objChildren2;
            // Next position
            $num++;
        }
        // Return to the view
        return view('general.recordsSummary', compact('dataTotal', 'dataAttend', 'dataNoAttend', 'functionarys'));
    }
    // Total attend per functionary/activity
    public static function countActivitiesPerFunctionary($idfunc, $idAct, $assit)
    {
        // Query to count total activities
        $total = Attendance::whereMonth('atencion.fecha', Carbon::now()->month)
            ->where('atencion.funcionario_id', $idfunc)
            ->where('atencion.actividad_id', $idAct)
            ->where('atencion.asistencia', $assit)
            ->get();
        // Return value
        return $total->count();
    }
    // View for REM
    public function showRemRecords()
    {
        $end = 80;
        $interval = 5;
        $data = $this->getRemTable($interval, $end);
        $iterator = 0;
        $list = [];
        while ($iterator < $end) {
            $str = $iterator . " - " . ($iterator + $interval - 1);
            array_push($list, $str);
            $iterator = $iterator + $interval;
        }
        $str = $iterator . "+";
        array_push($list, $str);

        return view('general.recordsRem', compact('data', 'list'));
    }
    // Table for REM
    public function getRemTable($interval, $end)
    {
        $activities = Activity::where('activa', 1)->orderBy('descripcion')->select('id', 'descripcion')->get();

        $data = [];
        $num = 0;
        foreach ($activities as $record1) {
            $speciality = $record1->speciality;
            foreach ($speciality as $record2) {
                // Create necesary objects
                $obj = new \stdClass();
                $obj->actividad = $record1->descripcion;
                $obj->especialidad = $record2->descripcion;
                $iterator = 0;
                while ($iterator < $end) {
                    $strH = $iterator . " - " . ($iterator + $interval - 1) . " - H";
                    $strM = $iterator . " - " . ($iterator + $interval - 1) . " - M";
                    // $obj->$strH =  $this->test($iterator, $iterator + $interval, $record1->id, $record2->id, "hombre");
                    $obj->$strH =  $this->test($iterator, $iterator + $interval, $record1->id, $record2->id, "hombre");
                    $obj->$strM =  $this->test($iterator, $iterator + $interval, $record1->id, $record2->id, "mujer");
                    $iterator = $iterator + $interval;
                }
                $strH = $iterator . "+ - H";
                $strM = $iterator . "+ - M";
                $obj->$strH = $this->test($iterator, 300, $record1->id, $record2->id, "hombre");
                $obj->$strM = $this->test($iterator, 300, $record1->id, $record2->id, "mujer");
                // Adding object to array
                $data[$num] = $obj;
                // Next position
                $num++;
            }
        }
        return $data;
    }
    // test =SUMA(C3:AM34)
    public function test($min, $max, $idAct, $idSp, $sex)
    {
        $from = Carbon::now()->subYears($max - 1)->addDays(1);
        $to = Carbon::now()->subYears($min - 1)->addDays(1);
        // Query to count total activities
        $total = DB::table('atencion')
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'atencion.funcionario_id')
            ->join('etapa', 'etapa.id', '=', 'atencion.etapa_id')
            ->join('paciente', 'paciente.id', '=', 'etapa.paciente_id')
            ->join('sexo', 'sexo.id', '=', 'paciente.sexo_id')
            ->whereMonth('atencion.fecha', Carbon::now()->month)
            ->where('funcionario_posee_especialidad.especialidad_id', $idSp)
            ->where('atencion.actividad_id', $idAct)
            ->whereRaw('lower(sexo.descripcion) like lower(?)', ["%{$sex}%"])
            // ->whereBetween('paciente.fecha_nacimiento', [$from, $to])
            ->get();
        // Return value
        return $total->count();
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
=======
        return false;
>>>>>>> be804d428a214a746ccabe16a2ca20a1c8d56fb5
    }
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
