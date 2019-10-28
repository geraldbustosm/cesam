@extends('layouts.main')
@section('title','Fichas')
@section('active-prestaciones','active')

@section('content')
<h1>Ficha Paciente</h1>
<div>
    <select class="form-control">
    <option value="3" selected>Ficha 3 (Activa)</option>
    <option value="2">Ficha 2</option>
    <option value="1">Ficha 1</option>
    </select>
</div>

<div>
    <p><b>Paciente: </b> {{ $patient->nombre1}}</p>
    <p class="mb-2"><b>Rut: </b>{{ $patient->DNI}}</p>
    <button type="button" class="btn btn-primary mb-2">Añadir prestación</button>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. 
    Soluta excepturi hic odit saepe. Distinctio exercitationem 
    totam tempore iste quae, ea quo voluptates unde quos, quod 
    velit assumenda quia corrupti quidem consectetur doloribus 
    saepe optio impedit rem dolor nisi quisquam a beatae quasi! 
    Sed dignissimos harum iste delectus ducimus eum sint. 
    Mollitia labore harum libero, blanditiis dolores ipsa ipsam quia sapiente?<p>

    <?php foreach($patientAtendances as $value): ?>
    <div class="card">
            <div class="card-header">
                <div>Prestación: </div>
                <div>
                    <a class="" href="{{url('#')}}"><i class="material-icons">create</i><span></span></a>
                    <a class="" href="{{url('#')}}"><i class="material-icons">delete</i><span></span></a>
                </div>
            </div>
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Profesional:{{ $value->functionary->user->primer_nombre }},( {{ $value->functionary->profesion}} )</h6>
                <h6 class="card-subtitle mb-4 text-muted">{{ $value->fecha}}</h6>
                <h6 class="card-subtitle text-muted">{{ $value->provision->glosaTrasadora}}</p>
            </div>
        </div>
    <?php endforeach; ?>
    
</div>
@endsection