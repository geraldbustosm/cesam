@extends('layouts.main')
@section('title','Registrar Especialidad')
@section('active-ingresardatos','active')
@section('active-ingresarespecialidad','active')

@section('content')
<div>
    <ul class="nav" id="topNav">
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/actividad')}}">Actividades</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/alta')}}">Altas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/atributos')}}">Atributos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/diagnostico')}}">Diagnósticos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/especialidad')}}">Especialidades</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/especialidad-glosa')}}">Especialidad-Glosa</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/genero')}}">Géneros</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/prestacion')}}">Glosas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/grupo-altas')}}">Grupo-Altas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/prevision')}}">Previsiones</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/procedencia')}}">Procedencias</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/programa')}}">Programas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/sigges')}}">SiGGES</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('registrar/tipo')}}">Tipos</a>
        </li>
    </ul><br>
</div>
<div class="sub-content div-full">@yield('sub-content')</div>

<!-- Adding script using on this view -->
<script type="text/javascript" src="{{asset('js/pagination.js')}}"></script>
<script type="text/javascript" src="{{asset('js/actionButtons.js')}}"></script>
<script type="text/javascript" src="{{asset('js/tableGenerator.js')}}"></script>
@endsection