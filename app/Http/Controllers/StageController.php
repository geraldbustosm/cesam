<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Activity;
use Illuminate\Http\Request;
use App\Patient;
use App\Functionary;
use App\Diagnosis;
use App\Program;
use App\SiGGES;
use App\Provenance;
use App\Provision;
use App\Stage;
use Illuminate\Support\Facades\Auth;

class StageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /***************************************************************************************************************************
                                                    CREATE FORM
     ****************************************************************************************************************************/
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
            ->join('funcionario_posee_especialidad', 'funcionario_posee_especialidad.funcionarios_id', '=', 'funcionarios.id')
            ->join('especialidad', 'especialidad.id', '=', 'funcionario_posee_especialidad.especialidad_id')
            ->select('funcionarios.id', 'users.primer_nombre', 'users.apellido_paterno')
            ->whereRaw("lower(especialidad.descripcion) like '%medico%'")
            ->orWhereRaw("lower(especialidad.descripcion) like '%médico%'")
            ->where('funcionarios.activa', 1)
            ->get();
        // return to the view, with 'id_patient', 'functionary', 'diagnosis', 'program', 'release', 'Sigges', 'provenance'
        return view('admin.Form.stageCreateForm', ['idpatient' => $patient_id])->with(compact('functionarys', 'diagnosis', 'program', 'Sigges', 'provenance'));
    }
    /***************************************************************************************************************************
                                                    EDIT FORM
     ****************************************************************************************************************************/
    public function showEditStage($id)
    {
        $stage = Stage::find($id);
        $functionarys = Functionary::where('activa', 1)->get();
        $functionary = Functionary::find($stage->funcionario_id);
        $sigges = SiGGES::where('activa', 1)->get();
        $sigge = SiGGES::find($stage->sigges_id);
        $programs = Program::where('activa', 1)->get();
        $program = Program::find($stage->programa_id);
        $provenances = Provenance::where('activa', 1)->get();
        $provenance = Provenance::find($stage->procedencia_id);
        $diagnosis = Diagnosis::where('activa', 1)->get();
        return view('admin.Edit.stageEdit', compact('stage', 'functionarys', 'functionary', 'sigges', 'sigge', 'programs', 'program', 'provenances', 'provenance', 'diagnosis', 'stageDiagnosis'));
    }
    /***************************************************************************************************************************
                                                    CREATE PROCESS
     ****************************************************************************************************************************/
    public function registerStage(Request $request)
    {
        // Check the format of each variable of 'request'
        $validation = $request->validate([]);
        // Check have not anoter stage active
        $checkStages = Stage::where('paciente_id', $request->idpatient)->where('activa', 1)->count();
        if ($checkStages > 0) return redirect("ficha/" . $request->idpatient);
        // Create a new 'object' stage
        $stage = new Stage;
        // Set some variables with inputs of view
        // the variables name of object must be the same that database for save it
        // diagnostico_id, programa_id, sigges_id, procedencia_id, funcionario_id, paciente_id
        $stage->programa_id = $request->programa_id;
        $stage->sigges_id = $request->sigges_id;
        $stage->procedencia_id = $request->procedencia_id;
        $stage->funcionario_id = $request->funcionario_id;
        $stage->paciente_id = $request->idpatient;
        // Pass the new stage to database
        $stage->save();
        $stage->diagnosis()->sync($request->options);
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Registrar etapa (ficha)', $stage->id, $stage->table);
        app('App\Http\Controllers\AdminController')->addLog('Registrar diagnósticos de la etapa id: ' . $stage->id, $request->options, 'etapa_posee_diagnostico');
        // Set variable with patient_dni
        $DNI = $request->idpatient;
        // Get the patient
        $patient = Patient::where('id', $DNI)
            ->where('activa', 1)
            ->first();
        // Get active functionarys
        $users = Functionary::where('activa', 1)->get();
        // Get provisions
        $provision = Provision::where('activa', 1)->get();
        // Get last provision used
        $lastProvision = Attendance::where('activa', 1)
            ->where('etapa_id', $stage->id)
            ->latest('created_at')
            ->first();
        // Redirect to the view with stage, users (functionarys), patient, DNI (id of patient, we use DNI as standard in several views)
        // Also pass to the view the id of stage as stage_id
        return view('general.attendanceForm')->with(compact('stage', 'users', 'patient', 'DNI', 'provision', 'lastProvision'));
    }

    /***************************************************************************************************************************
                                                    EDIT PROCESS
     ****************************************************************************************************************************/
    public function editStage(Request $request)
    {
        // Get the stage
        $stage = Stage::find($request->id);
        $patient = Patient::find($stage->paciente_id);

        // Massage and redirect url
        $url = 'etapas/edit/' . $stage->id;
        $msg = 'Se actualizaron los datos de la etapa del paciente ' . $patient->nombre1 . ' ' . $patient->apellido1;
        // Updating data
        $stage->programa_id = $request->programs;
        $stage->sigges_id = $request->sigges;
        $stage->procedencia_id = $request->provenances;
        $stage->funcionario_id = $request->functionarys;
        $stage->diagnosis()->sync($request->options);
        $stage->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Actualizar etapa (ficha)', $stage->id, $stage->table);
        app('App\Http\Controllers\AdminController')->addLog('Actualizar diagnósticos de la etapa id: ' . $stage->id, $request->options, 'etapa_posee_diagnostico');
        return redirect($url)->with('status', $msg);
    }
    /***************************************************************************************************************************
                                                    OTHER PROCESS
     ****************************************************************************************************************************/
    // Check for an active stage for the patient (parameter)
    public function checkCurrStage(Request $request)
    {
        // Get the patient
        $patient = Patient::where('id', $request->DNI_stage)
            ->where('activa', 1)
            ->first();
        // Get the active stage
        $stage = Stage::where('paciente_id', $patient->id)
            ->where('activa', 1)
            ->first();
        $provision = Provision::where('activa', 1)->get();
        // If have no active stage
        if (empty($stage)) {
            // Call function 'showAddStage'
            return $this->showAddStage($patient->id);
        } else {
            // Get active functionarys
            $users = Functionary::where('activa', 1)->get();
            foreach($users as $index) app('App\Http\Controllers\UserController')->formatRut($index->user);
            $user = Auth::user();
            // Get last provision
            $lastProvision = Attendance::where('activa', 1)
            ->where('etapa_id', $stage->id)
            ->latest('created_at')
            ->first();
            // Check role of functionary
            if ($user->rol == 1) return view('general.attendanceForm', ['DNI' => $patient->DNI])->with(compact('stage', 'users', 'patient', 'provision', 'lastProvision'));
            else if ($user->rol == 2) {
                $functionary = Functionary::where('user_id', $user->id)->first();
                return view('general.attendanceFormFunctionary', ['DNI' => $patient->DNI, 'currUser' => $user])
                            ->with(compact('stage', 'users', 'patient', 'functionary', 'provision', 'lastProvision'));
            }
            else if ($user->rol == 3) {
                $users = Functionary::where('activa', 1)->get();
                $attendance = $stage->attendance->first();
                $functionary = $attendance->functionary;
                $speciality = $functionary->speciality->first();
                $DNI = $patient->dni;
                $activity = $speciality->activity;
                if ($attendance->count() != 0) return view('general.attendanceFormLast', ['DNI' => $DNI])->with(compact('stage', 'users', 'patient', 'functionary', 'speciality', 'attendance', 'activity'));
                else return view('general.attendanceForm', ['DNI' => $DNI])->with(compact('stage', 'users', 'patient', 'provision', 'lastProvision'));
            } else return view('general.attendanceForm', ['DNI' => $patient->dni])->with(compact('stage', 'users', 'patient', 'provision', 'lastProvision'));
        }
    }

    public function addPCI(Request $request)
    {
        $stage = Stage::find($request->id_stage);
        // Change datepicker format to database format
        $var = $request->pci;
        $date = str_replace('/', '-', $var);
        $pci = date('Y-m-d', strtotime($date));
        $stage->pci = $pci;
        $stage->save();
        // Regist in logs events
        app('App\Http\Controllers\AdminController')->addLog('Actualizar etapa (PCI)', $stage->id, $stage->table);
        return redirect('ficha/' . $request->patient_stage);
    }
}
