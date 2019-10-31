@extends('layouts.main')
@section('title','Editar mis datos')
@section('active-editarmisdatos','active')

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
					<input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{$auth->email}}" id="email" name="email" placeholder="Nombre de usuario">
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

<!-- Adding script using on this view -->
<script src="{{asset('js/idValidator.js')}}"></script>
<script>
	document.getElementById('info_Submenu').className += ' show';
</script>

@endsection