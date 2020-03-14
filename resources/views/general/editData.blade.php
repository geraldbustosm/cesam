@extends('layouts.main')
@section('title','Editar mis datos')
@section('active-editarmisdatos','active')
@section('active-editarinformacion','active')

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
<h1>Editar mis datos</h1>
<div class="div-full">
	@if (session('success'))
	<div class="alert alert-success" role="alert">
		{{ session('success') }}
	</div>
	@endif
	@if (session('wrong'))
	<div class="alert alert-danger" role="alert">
		{{ session('wrong') }}
	</div>
	@endif

	<form method="post" action="{{ url('misdatos/edit') }}">
		@csrf
		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

		<!-- UID -->
		<div class="form-gorup">
			<label for="rut">Rut</label>
			<input type="text" class="form-control {{ $errors->has('rut') ? ' is-invalid' : '' }}" value="{{$auth->rut}}" id="rut" name="rut" placeholder="RUT">
		</div><br>

		<!-- Names -->
		<div class="form-group">
			<label for="nombres">Nombre completo</label>
			<div class="form-row">
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('nombres') ? ' is-invalid' : '' }}" value="{{$auth->primer_nombre}} {{$auth->segundo_nombre}}" id="nombres" name="nombres" placeholder="Nombres">
				</div>
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('apellido_paterno') ? ' is-invalid' : '' }}" value="{{$auth->apellido_paterno}}" id="apellido_paterno" name="apellido_paterno" placeholder="Primer apellido">
				</div>
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('apellido_materno') ? ' is-invalid' : '' }}" value="{{$auth->apellido_materno}}" id="apellido_materno" name="apellido_materno" placeholder="Segundo Apellido">
				</div>
			</div>
		</div>

		<div class="form-group">
			<label for="nombre">Nombre de usuario</label>
			<div class="form-row">
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{$auth->nombre}}" id="nombre" name="nombre" placeholder="Nombre de usuario">
				</div>
			</div>
		</div>

		<div class="form-group">
			<label for="email">Email</label>
			<div class="form-row">
				<div class="col">
					<input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{$auth->email}}" id="email" name="email" placeholder="Correo electrónico">
				</div>
			</div>
		</div>

		<div class="form-group">
			<label for="password">Para guardar los cambios ingresa tu contraseña</label>
			<div class="form-row">
				<div class="col">
					<input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" autocomplete="new-password" value="" id="password" name="password" placeholder="Contraseña">
				</div>
			</div>
		</div>

		<button type="submit" class="btn btn-primary" id="btnSubmit">Editar mis datos</button>
	</form>
</div>

<script>
	document.getElementById('info_Submenu').className += ' show';

	// Write DNI like rut standar format
	$(document).ready(function() {
		rut = <?php echo json_encode($auth->rut) ?>;
		var len = rut.length;
		var rut = formatear(rut, rut.substring(len - 1, len));
		document.getElementById('rut').value = rut;
	});

	function formatear(Rut, digitoVerificador) {
		var sRut = new String(Rut);
		var sRutFormateado = '';
		if (digitoVerificador) {
			var sDV = sRut.charAt(sRut.length - 1);
			sRut = sRut.substring(0, sRut.length - 1);
		}
		while (sRut.length > 3) {
			sRutFormateado = "." + sRut.substr(sRut.length - 3) + sRutFormateado;
			sRut = sRut.substring(0, sRut.length - 3);
		}
		sRutFormateado = sRut + sRutFormateado;
		if (sRutFormateado != "" && digitoVerificador) {
			sRutFormateado += "-" + sDV;
		} else if (digitoVerificador) {
			sRutFormateado += sDV;
		}
		return sRutFormateado;
	}
</script>

@endsection