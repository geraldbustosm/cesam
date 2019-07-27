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
			<input type="text" class="form-control {{ $errors->has('direccion') ? ' is-invalid' : '' }}" value="{{ old('direccion') }}" id="direccion" name="direccion" placeholder="Dirección actual">
		</div>
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('direccion_opcional') ? ' is-invalid' : '' }}" value="{{ old('direccion_opcional') }}" id="direccion_opcional" name="direccion_opcional" placeholder="Dirección opcional">
			<small id="addressHelp" class="form-text text-muted">La dirección no será de visualización pública.</small>
		</div>
		<div class="form-group">
			<label for="sexo">Sexo</label><br>
			<input type="radio" id="sexo" name="sexo" value="m" required checked> Masculino &nbsp;
			<input type="radio" id="sexo" name="sexo" value="f"> Femenino &nbsp;
			<input type="radio" id="sexo" name="sexo" value="o"> Otro
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
					var date = new Date(from[2], from[1] - 1, from[0]);
				});
			</script>
		</div>
		<button type="submit" class="btn btn-primary">Registrar</button>
		<input type="button" href="javascript:validator()" value="Test" id="testing"/>
	</form>
</div>

<!-- Adding script using on this view -->
<script src="{{asset('js/idValidator.js')}}"></script>
@endsection