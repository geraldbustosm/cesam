<?php

namespace App\Http\Controllers;
use App\Functionary;
use App\User;
use App\Patient;
use App\Release;
use App\Atributes;
use App\Sex;
use App\Speciality;

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
        $patients = Patient::all();
        return view('general.patient', ['patients' => $patients]);
    }

    public function showPatientInfo(){
        return view('admin.patientInfo');
    }

    public function showClinicalRecords(){
        return view('admin.clinicalRecords');
    }

    public function showAddUser(){
        return view('admin.userForm');
    }

    public function showTesting(){
        $patients = DB::table('paciente')->paginate(5);        
        return view('general.test', ['patients' => $patients]);
    }

    public function showAddFunctionary(){
        $user = User::all();
        return view('admin.functionaryForm',compact('user'));
    }

    public function showAddRelease(){
        return view('admin.releaseForm');
    }

    public function showAddAtributes(){
        return view('admin.atributesForm');
    }

    public function showAddSex(){
        return view('admin.sexForm');
    }

    public function showAddSpeciality(){
        return view('admin.specialityForm');
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

}
