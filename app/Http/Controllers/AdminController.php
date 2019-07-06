<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
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
        return view('general.patient');
    }

    public function showAddPatient(){
        return view('admin.patientForm');
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
        return view('general.test');
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

    public function registerPatient(Request $request){

        $validacion = $request->validate([
            'name' => 'required|string|max:255',
            'id' => 'required|string|max:255|unique:paciente',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'first_address' => 'required|string|max:255',
            'optional_address' => 'string|max:255|nullable',
            'gender' => 'required',
            'datepicker' => 'required|date_format:"d/m/Y"',
        ]);

        $paciente = new Paciente;
        
        
        $paciente->nombre = $request->name;
        $paciente->id = $request->id;
        $paciente->country = $request->country;
        $paciente->city = $request->city;
        $paciente->first_address = $request->first_address;
        $paciente->optional_address = $request->optional_address;
        $paciente->gender = $request->gender;
        $paciente->fecha_nacimiento = $request->datepicker;

    }

}
