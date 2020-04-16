@extends('layouts.main')
@section('title','Cambiar medico a cargo')
@section('active-ingresardatos','active')

@section('content')
<h1>Cambiar medico a cargo</h1>
<div class="div-full">
	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif
	@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	@if (Auth::user()->rol == 1)
	<form method="post" action="{{ url('cambiar-medico') }}">
		@csrf

		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

        <!-- Enviamos el DNI -->
		<input id="dni" name="dni" type="hidden" value="{{$patient->DNI}}">

		<!-- Enviamos el ID de la ficha activa -->
		<input id="id_etapa" name="id_etapa" type="hidden" value="{{$activeStage->id}}">

		<div class="form-group">
			<label for="">Cambiar a</label>
            <input type="text" class="form-control" value="{{$medicalInCharge->user->primer_nombre}} {{$medicalInCharge->user->apellido_paterno}} {{$medicalInCharge->user->apellido_materno}}" disabled>
		</div>

		<div class="form-group">
			<label for="medical">Por</label>
            <select name="medical_id" id="medical" class="form-control" required>
				<option value="" selected disabled> Seleccione otro médico</option>
                @foreach($medicals as $medico)
                    @if($medico->id != $medicalInCharge->id)
                        <option value="{{$medico->id}}">{{$medico->user->primer_nombre}} {{$medico->user->apellido_paterno}} {{$medico->user->apellido_materno}}</option>
                    @endif
                @endforeach
            </select>
		</div>

		<button type="submit" class="btn btn-primary">Cambiar medico a cargo</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert">
	<p>No se encontró el funcionario</p>
</div>
@endif
@endsection