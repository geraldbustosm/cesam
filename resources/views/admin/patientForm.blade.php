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
	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif
	<form method="post" action="{{ url('registrarpaciente') }}">
		@csrf
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('id') ? ' is-invalid' : '' }}" value="{{ old('id') }}" id="id" name="id" placeholder="Rut o pasaporte">
		</div>
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ old('nombre') }}" id="nombre" name="nombre" placeholder="Nombre completo">
		</div>
		<div class="form-group">
			<div class="form-row">
				<div class="col-7">
					<input type="text" class="form-control {{ $errors->has('pais') ? ' is-invalid' : '' }}" value="{{ old('pais') }}" id="pais" name="pais" placeholder="País">
				</div>
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('ciudad') ? ' is-invalid' : '' }}" value="{{ old('ciudad') }}" id="ciudad" name="ciudad" placeholder="Ciudad">
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
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('direccion_opcional') ? ' is-invalid' : '' }}" value="{{ old('direccion_opcional') }}" id="direccion_opcional" name="direccion_opcional" placeholder="Dirección opcional">
			<small id="addressHelp" class="form-text text-muted">La dirección no será de visualización pública.</small>
		</div>

		<div class="form-group">
			<select class="form-control" name="patient_sex" required>
				<option selected disabled>Por favor seleccione un genero / sexo </option>
				@foreach($sex as $sexo)
				<option value="{{ $sexo->id}}">{{ $sexo->descripcion}}</option>
				@endforeach
			</select>
		</div>

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

				$("#datepicker").on("change", function() {
					var from = $("#datepicker").val().split("/");
					// Probar usando la id 'datepicker' en vez de var 'date'
					var date = new Date(from[2], from[1] - 1, from[0]);
				});
			</script>
		</div>
		<button type="submit" class="btn btn-primary">Registrar</button>
		<input type="button" href="javascript:validator()" value="Test" id="testing" />
	</form>
</div>

<!-- Adding script using on this view -->
<script src="{{asset('js/idValidator.js')}}"></script>
@endsection