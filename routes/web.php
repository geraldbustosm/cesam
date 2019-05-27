<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('dashboard', function (){
    return view('dashboard');
});

Route::get('pacientes', function(){
    return view('pacientes');
});

Route::get('ingresarpaciente', function(){
    return view('ingresarpaciente');
});

Route::get('infopaciente', function(){
    return view('infopaciente');
});

Route::get('fichas', function(){
    return view('fichas');
});