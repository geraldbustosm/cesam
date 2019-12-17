@extends('layouts.main')
@section('title','Registrar Especialidad')
@section('active-ingresardatos','active')
@section('active-ingresarespecialidad','active')

@section('content')
<div>
    <ul class="nav" id="topNav">
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/actividad')}}">Actividades</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/alta')}}">Altas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/atributo')}}">Atributos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/diagnostico')}}">Diagnósticos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/especialidad')}}">Especialidades</a>
        </li>        
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/especialidad-glosa')}}">Especialidad-Glosa</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/genero')}}">Géneros</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/prestacion')}}">Glosas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/grupo-altas')}}">Grupo-Altas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/prevision')}}">Previsiones</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/procedencia')}}">Procedencias</a>
        </li>        
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/programa')}}">Programas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/sigges')}}">SiGGES</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{url('inactivo/tipo')}}">Tipos</a>
        </li>
    </ul><br>
</div>
<div class="sub-content div-full">@yield('sub-content')</div>

<!-- Adding script using on this view -->
<script type="text/javascript" src="{{asset('js/pagination.js')}}"></script>
<script type="text/javascript" src="{{asset('js/actionButtons.js')}}"></script>
<script type="text/javascript" src="{{asset('js/tableGenerator.js')}}"></script>
@endsection