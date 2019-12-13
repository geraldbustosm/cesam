@extends('layouts.main')
@section('title','Editar programa')
@section('active-ingresardatos','active')

@section('content')

<h1>Editar programa</h1>
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

	@if ($program)
	<form method="post" action="{{ url('programas/edit') }}">
		@csrf

		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

		<!-- Enviamos el ID de la actividad para luego actualizarlo -->
		<input id="id" name="id" type="hidden" value="{{$program->id}}">

		<div class="form-group">
			<select class="form-control" name="speciality">
            	<option value="{{ $program->especialidad_programa_id }}">{{ $specialityprogram->descripcion }}</option>

				@foreach($data as $sp)
					@if($specialityprogram->id != $sp->id)
                 		<option value="{{ $sp->id }}">{{ $sp->descripcion }}</option>
					@endif
				@endforeach
            </select>
		</div>

		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{$program->descripcion}}" placeholder="{{$program->descripcion}}" id="descripcion" name="descripcion">
		</div>

		<button type="submit" class="btn btn-primary">Editar programa</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert">
	<p>No se encontró el programa</p>
</div>
@endif
@endsection