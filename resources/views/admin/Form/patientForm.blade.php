@extends('layouts.main')
@section('title','Paciente')
@section('active-ingresarpersonas','active')
@section('active-ingresarpaciente','active')

@section('content')
<h1>Ingresar pacientes</h1>
<div class="div-full">
	@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<!-- Return alert for success query -->
	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif
	<!-- Return alert for error query -->
	@if (session('err'))
	<div class="alert alert-danger" role="alert">
		{{ session('err') }}
	</div>
	@endif
	<form name="onSubmit" id="onSubmit" method="post" action="{{ url('registrar/paciente') }}">
		@csrf
		<!-- UID -->
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('dni') ? ' is-invalid' : '' }}" value="{{ old('dni') }}" id="dni" name="dni" placeholder="Rut o pasaporte" required>
		</div>
		<!-- Names -->
		<div class="form-group">
			<div class="form-row">
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" id="name" name="name" placeholder="Nombre completo" required>
				</div>
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ old('last_name') }}" id="last_name" name="last_name" placeholder="Primer apellido" required>
				</div>
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('second_last_name') ? ' is-invalid' : '' }}" value="{{ old('second_last_name') }}" id="second_last_name" name="second_last_name" placeholder="Segundo Apellido">
				</div>
			</div>
		</div>
		<!-- Location -->
		<div class="form-group">
			<div class="form-row">
				<div class="col-7">
					<input type="text" class="form-control {{ $errors->has('pais') ? ' is-invalid' : '' }}" value="{{ old('pais') }}" id="pais" name="pais" placeholder="Nacionalidad" required>
				</div>
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('region') ? ' is-invalid' : '' }}" value="{{ old('region') }}" id="region" name="region" placeholder="Region" required>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="form-row">
				<div class="col-3">
					<input type="text" class="form-control {{ $errors->has('comuna') ? ' is-invalid' : '' }}" value="{{ old('comuna') }}" id="comuna" name="comuna" placeholder="Comuna" required>
				</div>
				<div class="col-3">
					<input type="text" class="form-control {{ $errors->has('calle') ? ' is-invalid' : '' }}" value="{{ old('calle') }}" id="calle" name="calle" placeholder="Calle" required>
				</div>
				<div class="col-3">
					<input type="text" class="form-control {{ $errors->has('numero') ? ' is-invalid' : '' }}" value="{{ old('numero') }}" id="numero" name="numero" placeholder="Numero" required>
				</div>
				<div class="col-3">
					<input type="text" class="form-control {{ $errors->has('depto') ? ' is-invalid' : '' }}" value="{{ old('depto') }}" id="depto" name="depto" placeholder="Departamento (opcional)">
				</div>
			</div>
		</div>
		<!-- End location -->
		<!-- Sex dropdown -->
		<div class="form-group">
			<select class="form-control" name="patient_sex" required>
				<option value="" selected disabled>Por favor seleccione un genero / sexo </option>
				@foreach($sex as $sexo)
				<option value="{{ $sexo->id}}">{{ $sexo->descripcion}}</option>
				@endforeach
			</select>
		</div>
		<!-- Prevition dropdown -->
		<div class="form-group">
			<select class="form-control" name="prevition" required>
				<option value="" selected disabled>Por favor seleccione una prevision de salud</option>
				@foreach($previtions as $prevition)
				<option value="{{ $prevition->id}}">{{ $prevition->descripcion}}</option>
				@endforeach
			</select>
		</div>
		<!-- Birthdate datepicker -->
		<div class="form-group">
			<input id="datepicker" name="datepicker" placeholder="Fecha de nacimiento" required>
			<script>
				var config = {
					format: 'dd/mm/yyyy',
					locale: 'es-es',
					uiLibrary: 'bootstrap4',
					maxDate: new Date,
					startView: 3,
				};
				$('#datepicker').datepicker(config);
			</script>
		</div>

		<div class="form-group text-center">
			<button type="submit" class="btn btn-primary" id="btnSubmit">Registrar</button>
		</div>

		<div class="form-group">
			<div class="overflow-auto" style="height:200px;">
				@foreach($attributes as $attributes)
				<div class="card">
					<div class="checkbox-container">
						<label class="checkbox-label">
							<input type="checkbox" name="options[]" value="{{ $attributes->id}}">
							<span class="checkbox-custom rectangular"></span>
						</label>
					</div>
					<div class="input-title">{{ $attributes->descripcion}}</div>

				</div>
				@endforeach
			</div>
		</div>
	</form>
</div>
<script>
	document.getElementById('people_Submenu').className += ' show';
</script>
<!-- Adding script using on this view -->
<script src="{{asset('js/checkDate.js')}}"></script>
<script src="{{asset('js/rutValidator.js')}}"></script>
<script src="{{asset('js/idValidator.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/card.css')}}">
@endsection