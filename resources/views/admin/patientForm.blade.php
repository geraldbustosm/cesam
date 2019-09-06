@extends('layouts.main')
@section('title','Paciente')
@section('active-ingresarpersonas','active')
@section('active-ingresarpaciente','active')

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif
<h1>Ingresar pacientes</h1>
<div class="div-full">
	<!-- Return alert for success query -->
	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif
	<form name="onSubmit" method="post" action="{{ url('registrarpaciente') }}">
		@csrf
		<!-- UID -->
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('id') ? ' is-invalid' : '' }}" value="{{ old('id') }}" id="id" name="id" placeholder="Rut o pasaporte">
		</div>
		<!-- Check UID for Chilean -->
		<div class="form-group">
			<div class="alert alert-success" role="alert" id="success">
				Rut / Pasaporte correcto!
			</div>
			<div class="alert alert-danger" role="alert" id="danger">
				Rut inválido...
			</div>
		</div>
		<!-- Names -->
		<div class="form-group">
			<div class="form-row">
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ old('nombre') }}" id="nombre" name="nombre" placeholder="Nombre completo">
				</div>
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('apellido1') ? ' is-invalid' : '' }}" value="{{ old('apellido1') }}" id="apellido1" name="apellido1" placeholder="Primer apellido">
				</div>
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('apellido2') ? ' is-invalid' : '' }}" value="{{ old('apellido2') }}" id="apellido2" name="apellido2" placeholder="Segundo Apellido">
				</div>
			</div>
		</div>
		<!-- Location -->
		<div class="form-group">
			<div class="form-row">
				<div class="col-7">
					<input type="text" class="form-control {{ $errors->has('pais') ? ' is-invalid' : '' }}" value="{{ old('pais') }}" id="pais" name="pais" placeholder="País">
				</div>
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('region') ? ' is-invalid' : '' }}" value="{{ old('region') }}" id="region" name="region" placeholder="Region">
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="form-row">
				<div class="col-4">
					<input type="text" class="form-control {{ $errors->has('comuna') ? ' is-invalid' : '' }}" value="{{ old('comuna') }}" id="comuna" name="comuna" placeholder="Comuna">
				</div>
				<div class="col-4">
					<input type="text" class="form-control {{ $errors->has('calle') ? ' is-invalid' : '' }}" value="{{ old('calle') }}" id="calle" name="calle" placeholder="Calle">
				</div>
				<div class="col-4">
					<input type="text" class="form-control {{ $errors->has('numero') ? ' is-invalid' : '' }}" value="{{ old('numero') }}" id="numero" name="numero" placeholder="Numero">
				</div>
			</div>
		</div>
		<!-- End location -->
		<!-- Sex dropdown -->
		<div class="form-group">
			<select class="form-control" name="patient_sex" required>
				<option selected disabled>Por favor seleccione un genero / sexo </option>
				@foreach($sex as $sexo)
				<option value="{{ $sexo->id}}">{{ $sexo->descripcion}}</option>
				@endforeach
			</select>
		</div>
		<!-- Prevition dropdown -->
		<div class="form-group">
			<select class="form-control" name="prevition" required>
				<option selected disabled>Por favor seleccione una prevision de salud</option>
				@foreach($previtions as $prevition)
				<option value="{{ $prevition->id}}">{{ $prevition->descripcion}}</option>
				@endforeach
			</select>
		</div>
		<!-- Birthdate datepicker -->
		<div class="form-group">
			<label for="datepicker">Fecha de nacimiento</label>
			<input id="datepicker" name="datepicker" width="276" required>
			<script>
				var config = {
					format: 'dd/mm/yyyy',
					locale: 'es-es',
					uiLibrary: 'bootstrap4'
				};
				$('#datepicker').datepicker(config);
			</script>
		</div>

		<button type="button" class="btn btn-primary" id="btnSubmit">Registrar</button>
	</form>
</div>

<script>document.getElementById('people_Submenu').className += ' show';</script>
<!-- Adding script using on this view -->
<script src="{{asset('js/idValidator.js')}}"></script>
@endsection