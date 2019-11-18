@extends('layouts.main')
@section('title','Editar contraseña')
@section('active-editarmisdatos','active')
@section('active-editarcontraseña','active')

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
<h1>Editar contraseña</h1>
<div class="div-full">

	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif

	<form method="post" action="{{ url('password/edit') }}">
		@csrf
		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">
		
		<div class="form-group">
			<input type="password" class="form-control {{ $errors->has('actual_password') ? ' is-invalid' : '' }}" autocomplete="new-password" value="" id="actual_password" name="actual_password" placeholder="Actual contraseña">
		</div>

		<div class="form-group">
			<input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" autocomplete="new-password" value="" id="password" name="password" placeholder="Nueva contraseña">
		</div>

		<div class="form-group">
			<input type="password" class="form-control {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" autocomplete="new-password" value="" id="password_confirmation" name="password_confirmation" placeholder="Repita su nueva contraseña">
		</div>

		<button type="submit" class="btn btn-primary" id="btnSubmit">Editar contraseña</button>
	</form>
</div>

<script>
	document.getElementById('info_Submenu').className += ' show';
</script>
@endsection