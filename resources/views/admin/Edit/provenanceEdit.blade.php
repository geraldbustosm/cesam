@extends('layouts.main')
@section('title','Editar procedencia')
@section('active-ingresardatos','active')

@section('content')
<h1>Editar procedencia</h1>
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

	@if ($provenance)
	<form method="post" action="{{ url('procedencias/edit') }}">
		@csrf

		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

		<!-- Enviamos el ID del alta para luego actualizarlo -->
		<input id="id" name="id" type="hidden" value="{{$provenance->id}}">

		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{$provenance->descripcion}}" placeholder="{{$provenance->descripcion}}" id="descripcion" name="descripcion">
		</div>

		<button type="submit" class="btn btn-primary">Editar procedencia</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert">
	<p>No se encontró la procedencia</p>
</div>
@endif
@endsection