@extends('layouts.main')
@section('title','Editar funcionario')
@section('active-ingresardatos','active')

@section('content')
<h1>Editar funcionario</h1>
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

	@if ($functionary)
	<form method="post" action="{{ url('funcionario/edit') }}">
		@csrf

		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

		<!-- Enviamos el ID del funcionario para luego actualizarlo -->
		<input id="id" name="id" type="hidden" value="{{$functionary->user_id}}">

		<div class="form-group">
			<label for="profesion">Profesión</label>
            <select class="form-control" name="profesion" required>
                <option selected disabled>Por favor seleccione una especialidad para asignar al funcionario</option>
                @foreach($speciality as $speciality)
                <option value="{{ $speciality->id}}" {{ ($speciality->id == $funcSpec ? 'selected' : '') }}>{{ $speciality->descripcion}}</option>
                @endforeach
            </select>
        </div>
		<div class="form-group">
			<label for="horasDeclaradas">Horas declaradas</label>
			<input type="text" class="form-control {{ $errors->has('horasDeclaradas') ? ' is-invalid' : '' }}" value="{{$functionary->horasDeclaradas}}" placeholder="{{$functionary->horasDeclaradas}}" id="horasDeclaradas" name="horasDeclaradas">
		</div>
		<div class="form-group">
			<label for="horasRealizadas">Horas realizadas</label>
			<input type="text" class="form-control {{ $errors->has('horasRealizadas') ? ' is-invalid' : '' }}" value="{{$functionary->horasRealizadas}}" placeholder="{{$functionary->horasRealizadas}}" id="horasRealizadas" name="horasRealizadas">
		</div>

		<button type="submit" class="btn btn-primary">Editar funcionario</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert">
	<p>No se encontró el funcionario</p>
</div>
@endif
@endsection