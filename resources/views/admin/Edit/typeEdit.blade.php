@extends('layouts.main')
@section('title','Editar tipo de prestación')
@section('active-ingresardatos','active')

@section('content')
<h1>Editar tipo de prestación</h1>
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

	@if ($type)
	<form method="post" action="{{ url('tipos/edit') }}">
		@csrf

		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

		<!-- Enviamos el ID del alta para luego actualizarlo -->
		<input id="id" name="id" type="hidden" value="{{$type->id}}">

		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{$type->descripcion}}" placeholder="{{$type->descripcion}}" id="descripcion" name="descripcion">
		</div>

		<button type="submit" class="btn btn-primary">Editar tipo de prestación</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert">
	<p>No se encontró el tipo de prestación</p>
</div>
@endif
@endsection