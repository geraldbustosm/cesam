@extends('layouts.main')
@section('title','Paciente')
@section('active-ingresarpaciente','active')

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="https://unpkg.com/gijgo@1.9.13/js/messages/messages.es-es.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

@section('content')
<h1>Ingresar pacientes</h1>
<div class="div-full">
	@if (session('status'))
		<div class="alert alert-success" role="alert">
			{{ session('status') }}
		</div>
	@endif
	<form method="post" action="{{ url('ingresarpaciente') }}">
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('userID') ? ' is-invalid' : '' }}" value="{{ old('userID') }}" id="userID" name="userID" placeholder="Rut o pasaporte">
		</div>
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('userName') ? ' is-invalid' : '' }}" value="{{ old('userName') }}" id="userName" name="userName" placeholder="Nombre completo">
		</div>
		<div class="form-group">
			<div class="form-row">
				<div class="col-7">
				  <input type="text" class="form-control {{ $errors->has('userCountry') ? ' is-invalid' : '' }}" value="{{ old('userCountry') }}" id="userCountry" name="userCountry" placeholder="País">
				</div>
				<div class="col">
				  <input type="text" class="form-control {{ $errors->has('userCity') ? ' is-invalid' : '' }}" value="{{ old('userCity') }}" id="userCity" name="userCity" placeholder="Ciudad">
				</div>
			</div>
		</div>
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('userAddress1') ? ' is-invalid' : '' }}" value="{{ old('userAddress1') }}" id="userAddress1" name="userAddress1" placeholder="Dirección actual">
		</div>
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('userAddress2') ? ' is-invalid' : '' }}" value="{{ old('userAddress2') }}" id="userAddress1" name="userAddress2" placeholder="Dirección opcional">
			<small id="addressHelp" class="form-text text-muted">La dirección no será de visualización pública.</small>
		</div>
		<div class="form-group">
			<label for="userAdd">Sexo</label><br>
			<input type="radio" id="gender" name="gender" value="m" required> Masculino &nbsp;
			<input type="radio" id="gender" name="gender" value="f" required> Femenino &nbsp;
			<input type="radio" id="gender" name="gender" value="o" required> Otro
		</div>
		<div class="form-group">
			<label for="userAdd">Fecha de nacimiento</label>
			<input id="birthdate" name="birthdate" width="276" required>
			<script>
				var config = {
					locale: 'es-es',
					uiLibrary: 'bootstrap4'
				};
				$('#birthdate').datepicker(config);
			</script>
		</div>
		<button type="submit" class="btn btn-primary">Registrar</button>
	</form>
</div>
@endsection

<!-- Adding script using on this view -->
<script src="{{asset('js/idValidator.js')}}"></script>