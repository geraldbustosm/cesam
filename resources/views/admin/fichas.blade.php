@extends('layouts.main')
@section('title','Fichas')
@section('active-pacientes','active')

@section('content')
<h1>Fichas</h1>
<div>
    <select class="form-control">
    <option value="3" selected>Ficha 3 (Activa)</option>
    <option value="2">Ficha 2</option>
    <option value="1">Ficha 1</option>
    </select>
</div>

<div>
    <p><b>Paciente: </b>Felipe Rojas</p>
    <p class="mb-2"><b>Rut: </b>19.245.333-4</p>
    <button type="button" class="btn btn-primary mb-2">Añadir prestación</button>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta excepturi hic odit saepe. Distinctio exercitationem totam tempore iste quae, ea quo voluptates unde quos, quod velit assumenda quia corrupti quidem consectetur doloribus saepe optio impedit rem dolor nisi quisquam a beatae quasi! Sed dignissimos harum iste delectus ducimus eum sint. Mollitia labore harum libero, blanditiis dolores ipsa ipsam quia sapiente?<p>

    <div class="card">
        <div class="card-header">
            <div>Prestación 1</div>
            <div>
                <a class="" href="{{url('ingresarpaciente')}}"><i class="material-icons">create</i><span></span></a>
                <a class="" href="{{url('ingresarpaciente')}}"><i class="material-icons">delete</i><span></span></a>
            </div>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">Profesional: Psicologo</h6>
            <h6 class="card-subtitle mb-4 text-muted">Fecha: 23/07/2014</h6>
            <h6 class="card-subtitle text-muted">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>Prestación 1</div>
            <div>
                <a class="" href="{{url('ingresarpaciente')}}"><i class="material-icons">create</i><span></span></a>
                <a class="" href="{{url('ingresarpaciente')}}"><i class="material-icons">delete</i><span></span></a>
            </div>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">Profesional: Psicologo</h6>
            <h6 class="card-subtitle mb-4 text-muted">Fecha: 23/07/2014</h6>
            <h6 class="card-subtitle text-muted">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>Prestación 1</div>
            <div>
                <a class="" href="{{url('ingresarpaciente')}}"><i class="material-icons">create</i><span></span></a>
                <a class="" href="{{url('ingresarpaciente')}}"><i class="material-icons">delete</i><span></span></a>
            </div>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">Profesional: Psicologo</h6>
            <h6 class="card-subtitle mb-4 text-muted">Fecha: 23/07/2014</h6>
            <h6 class="card-subtitle text-muted">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        </div>
    </div>
</div>
@endsection