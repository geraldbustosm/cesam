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
    }
    public function index()
    {
        $user = Auth::user();
        return view('general.home');
    }
    public function showPatients(){
        return view('general.patient');
    }

    public function addPatient(){
        return view('admin.patientForm');
    }

    public function showPatientInfo(){
        return view('admin.patientInfo');
    }

    public function showClinicalRecords(){
        return view('admin.clinicalRecords');
    }

    public function addUser(){
        return view('admin.userForm');
    }
    public function registUser(Request $request){

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
}
