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

    public function showPatients(){
        $patients = Patient::all()->where('activa', 1);
        $prev = Prevition::all();
        $sex = Sex::all();
        return view('general.patient', ['patients' => $patients, 'prev' => $prev, 'sex' => $sex]);
    }

    public function showFunctionarys(){
        $functionary = Functionary::all()->where('activa', 1);
        $user = User::all();
        $speciality = Speciality::all();
        $fs = FunctionarySpeciality::all();
        return view('general.functionarys', ['functionary' => $functionary, 'user' => $user, 'speciality'=>$speciality, 'fs'=>$fs]);
    }

    public function showPatientInfo(){
        return view('admin.patientInfo');
    }

    public function showClinicalfunctionary(){
        return view('admin.clinicalfunctionary');
    }

    public function showAddUser(){
        return view('admin.userForm');
    }

    public function showTesting(){
        $patients = DB::table('paciente')->paginate(5);        
        return view('general.test', ['patients' => $patients]);
    }

    public function showAddFunctionary(){
        $user = User::all()->where('activa', 1);
        return view('admin.functionaryForm',compact('user'));
    }

    public function showAddRelease(){
        return view('admin.releaseForm');
    }
    public function showAddPrevition(){
        return view('admin.previtionForm');
    }

    public function showAddAtributes(){
        return view('admin.atributesForm');
    }

    public function showAddSex(){
        return view('admin.sexForm');
    }

    public function showAddSpeciality(){
        $speciality = Speciality::all()->where('activa', 1);
        return view('admin.specialityForm', ['specialitys' => $speciality]);
    }

    public static function existFunctionarySpeciality($idFunct,$idSp){   
        
        $value=false;
        $doesClientHaveProduct = Speciality::where('id', $idSp)
                    ->whereHas('functionary', function($q) use($idFunct) {
                            $q->where('dbo.funcionarios.id', $idFunct);
                    })
                    ->count();
        if($doesClientHaveProduct ){
                $value = true ;
            }
          
        return $value;
    }
    public function showAsignSpeciality(){   

        $speciality = Speciality::orderBy('descripcion')
            ->get();
        
        $functionary = Functionary::orderBy('nombre1')            
            ->get();
        $rows = [];
        $columns = [];
        $ids = [];
        
        foreach($speciality as $index => $record) {
            if(!in_array($record->profesion, $columns)) {
                $columns[] = " | ".$record->descripcion." | ";
            }
        }
        
        foreach($functionary as $index => $record1) {
            $ids [0]=$record1->id;
            foreach($speciality as $index => $record2) {
                    $ids [1]=$record2->id;           
                    $rows[$record1->nombre1." ".$record1->nombre2][$record2->descripcion] = $ids;
            } 
        }
        return view('admin.specialityAsign', compact('rows','columns'));
    }
    public function AsignSpeciality(Request $request){   
       
        if (isset($_POST['enviar'])) {
            if (is_array($_POST['asignations'])) {
                $functionarys = Functionary::where('activa', 1)->get();
                foreach ($functionarys as $func ){
                    $func->speciality()->sync([]);
                }
                //$selected = '';              
                foreach ($_POST['asignations'] as $key) {
                    //$especialidadesPorFuncionario= array();
                    $codigos= array();
                    
                    //$functionary = Functionary::find($id);
                    foreach ( $key as $key2 => $value) {
                            $speciality = Speciality::find($value[1]);
                            //array_push($especialidadesPorFuncionario,$speciality->descripcion);
                            array_push($codigos,$speciality->id);
                            $functionary = Functionary::find($value[0]);
                    }
                    //$selected .= $functionary->nombre1." : ".implode( ", ",$especialidadesPorFuncionario).'<br> ';
                    
                    $functionary->speciality()->sync($codigos);
                }
            }
            else {
                $selected = 'Debes seleccionar un país';
            }
        
            //echo '<div>Has seleccionado: <br>'.$selected.'</div>';
             return redirect('asignarespecialidad')->with('status', 'Especialidades actualizadas');
        }
       
    }
    public function registerUser(Request $request){

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

    public function registerFunctionary(Request $request){

        $validacion = $request->validate
        ([
            'nombre1' => 'required|string|max:255',
            'nombre2' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'apellido2' => 'required|string|max:255',
            'profesion' => 'required|string|max:255',
            'user' => 'required|integer|max:255'            
        ]);

        $functionary = new Functionary;

        $functionary->nombre1 = $request->nombre1;
        $functionary->nombre2 = $request->nombre2;
        $functionary->apellido1 = $request->apellido1;
        $functionary->apellido2 = $request->apellido2;
        $functionary->profesion = $request->profesion;
        $functionary->user_id = $request->user;

        $functionary->save();

        return redirect('registrarfuncionario')->with('status', 'Funcionario creado');
    }
    public function registerRelease(Request $request){

        $validacion = $request->validate
        ([
            'descripcion' => 'required|string|max:255'                      
        ]);

        $alta = new Release;

        $alta->descripcion = $request->descripcion;

        $alta->save();

        return redirect('registraralta')->with('status', 'Nueva alta creada');
    }


    public function registerAtributes(Request $request){

        $validacion = $request->validate
        ([
            'descripcion' => 'required|string|max:255'                      
        ]);

        $atributo = new Atributes;

        $atributo->descripcion = $request->descripcion;

        $atributo->save();

        return redirect('registraratributos')->with('status', 'Nuevo atributo creado');
    }

    public function registerSex(Request $request){

        $validacion = $request->validate
        ([
            'descripcion' => 'required|string|max:255'                      
        ]);

        $sex = new Sex;

        $sex->descripcion = $request->descripcion;

        $sex->save();

        return redirect('registrarsexo')->with('status', 'Nuevo Sexo / Genero creado');
    }


    public function registerSpeciality(Request $request){

        $validacion = $request->validate
        ([
            'descripcion' => 'required|string|max:255'                      
        ]);

        $speciality = new Speciality;

        $speciality->descripcion = $request->descripcion;

        $speciality->save();

        return redirect('registrarespecialidad')->with('status', 'Nueva especialidad creada');
    }
    public function registerPrevition(Request $request){

        $validacion = $request->validate
        ([
            'nombre' => 'required|string|max:255'                      
        ]);

        $prevition = new Prevition;

        $prevition->nombre = $request->nombre;

        $prevition->save();

        return redirect('registrarprevision')->with('status', 'Nueva prevision creada');
    }

}
