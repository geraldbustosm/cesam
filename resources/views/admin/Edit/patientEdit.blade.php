@extends('layouts.main')
@section('title','Editar paciente')
@section('active-pacientes','active')

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
<h1>Editar paciente</h1>
<div class="div-full">
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
	@if ($patient)
	<form name="onSubmit" id="onSubmit" method="post" action="{{ url('pacientes/edit') }}">
		@csrf
		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

		<!-- Enviamos el ID del paciente para luego actualizarlo -->
		<input id="id" name="id" type="hidden" value="{{$patient->id}}">

		<div class="form-row">
			<div class="col-2">
				<label for="dni">Rut o pasaporte</label>
			</div>
			<div class="col-3">
				<input type="text" class="form-control {{ $errors->has('dni') ? ' is-invalid' : '' }}" value="{{$patient->dni}}" id="dni" name="dni" placeholder="Rut o pasaporte">
			</div>
		</div><br>

		<!-- Names -->
		<div class="form-row">
			<div class="col-2">
				<label for="nombre">Nombre completo</label>
			</div>
			<div class="col-3">
				<input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{$patient->nombre1}} {{$patient->nombre2}}" id="name" name="name" placeholder="Nombres">
			</div>
			<div class="col-3">
				<input type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{$patient->apellido1}}" id="last_name" name="last_name" placeholder="Primer apellido">
			</div>
			<div class="col-3">
				<input type="text" class="form-control {{ $errors->has('second_last_name') ? ' is-invalid' : '' }}" value="{{$patient->apellido2}}" id="second_last_name" name="second_last_name" placeholder="Segundo Apellido">
			</div>
		</div>
		<br>
		<div class="form-row">
			<div class="col-2">
				<label for="prevision">Previsión</label>
			</div>
			<div class="col-3">
				<select class="form-control" name="prevition" required>
					<option value="{{$patient->prevition->id}}">{{$patient->prevition->descripcion}}</option>
					@foreach($prev as $prevision)
					@if ($prevision->descripcion != $patient->prevition->descripcion)
					<option value="{{$prevision->id}}">{{$prevision->descripcion}}</option>
					@endif
					@endforeach
				</select>
			</div>
		</div>
		<br>
		<div class="form-row">
			<div class="col-2">
				<label for="sex">Sexo</label>
			</div>
			<div class="col-3">
				<select class="form-control" name="patient_sex" required>
					<option value="{{$patient->sex->id}}">{{$patient->sex->descripcion}}</option>
					@foreach($sex as $sexo)
					@if ($sexo->descripcion != $patient->sex->descripcion)
					<option value="{{$sexo->id}}">{{ $sexo->descripcion}}</option>
					@endif
					@endforeach
				</select>
			</div>
		</div>
		<br>
		<div class="form-row">
			<div class="col-2">
				<input id="datepicker" name="datepicker" value="{{$patient_birthdate}}" placeholder="Fecha de nacimiento" required>
				<script>
					var config = {
						format: 'dd/mm/yyyy',
						locale: 'es-es',
						uiLibrary: 'bootstrap4',
						maxDate: new Date,
					};
					$('#datepicker').datepicker(config);
				</script>
			</div>
		</div>
		<br>
		<div class="form-group">
			<div class="form-row">
				<div class="col-7">
					<input type="text" class="form-control {{ $errors->has('pais') ? ' is-invalid' : '' }}" value="{{$address->pais}}" id="pais" name="pais" placeholder="Nacionalidad">
				</div>
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('region') ? ' is-invalid' : '' }}" value="{{$address->region}}" id="region" name="region" placeholder="Region">
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="form-row">
				<div class="col-3">
					<input type="text" class="form-control {{ $errors->has('comuna') ? ' is-invalid' : '' }}" value="{{$address->comuna}}" id="comuna" name="comuna" placeholder="Comuna">
				</div>
				<div class="col-3">
					<input type="text" class="form-control {{ $errors->has('calle') ? ' is-invalid' : '' }}" value="{{$address->calle}}" id="calle" name="calle" placeholder="Calle">
				</div>
				<div class="col-3">
					<input type="text" class="form-control {{ $errors->has('numero') ? ' is-invalid' : '' }}" value="{{$address->numero}}" id="numero" name="numero" placeholder="Nmero">
				</div>
				<div class="col-3">
					<input type="text" class="form-control {{ $errors->has('depto') ? ' is-invalid' : '' }}" value="{{$address->departamento}}" id="depto" name="depto" placeholder="Departamento (opcional)">
				</div>
			</div>
		</div>
		<br>

		<div class="form-row">
			<div class="col-4">
				<button type="submit" class="btn btn-primary">Editar paciente</button>
			</div>
			<div class="col-4">
				<div class="overflow-auto" style="height:200px;">
					@foreach($attributes as $att)

					<div class="card">
						<div class="checkbox-container">
							<label class="checkbox-label">
								<input type="checkbox" name="options[]" value="{{ $att->id}}" {{(in_array($att->id,$patient->attributes()->pluck('atributos.id')->toArray()) ? 'checked' : '')}}>
								<span class="checkbox-custom rectangular"></span>
							</label>
						</div>
						<div class="input-title">{{ $att->descripcion}}</div>

					</div>

					@endforeach
				</div>
			</div>
		</div>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert">
	<p>No se encontró al paciente</p>
</div>
@endif

<!-- Adding script using on this view -->
<script src="{{asset('js/rutValidator.js')}}"></script>
<script src="{{asset('js/idValidator.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/card.css')}}">
@endsection