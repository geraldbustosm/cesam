@extends('layouts.main')
@section('title','Pacientes por funcionarios')
@section('active-pacientes','active')

@section('content')
<div class="div-full">
@if($functionary)
<h1>Médico: {{$functionary->user->primer_nombre }} {{$functionary->user->apellido_paterno}}</h1><hr><br>

<h3>Lista de pacientes:</h3><br>

@if(count($patients) > 0)
<!-- Table with users -->
<table class="table table-striped">
  <thead>
    <tr>
      <th style="width: 3%;">#</th>      
      <th style="width: 15%;">Rut</th>
      <th style="width: 30%;">Paciente</th>
    </tr>
  </thead>
  <tbody id="table-body">

       @php
       $i = 1;
       @endphp 

       @foreach($patients as $patient)
        <tr>
        <th scope="row">{{$i}}</th>
        <td>{{$patient->DNI}}</td>
        <td>{{$patient->nombre1}} {{$patient->nombre2}} {{$patient->apellido1}} {{$patient->apellido2}}</td>
        </tr>
        @php
        $i++;
        @endphp 
        @endforeach
  </tbody>
</table>
@else
<div class="alert alert-warning" role="alert">
	<p>El funcionario no poseé pacientes</p>
</div>
@endif



</div>
@else
<div class="alert alert-danger" role="alert">
	<p>No se encontró el funcionario</p>
</div>
@endif
</div>
@endsection