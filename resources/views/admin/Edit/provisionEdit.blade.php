@extends('layouts.main')
@section('title','Editar prestación')
@section('active-ingresardatos','active')

@section('content')

<h1>Editar prestación</h1>
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

	@if ($provision)
	<form method="post" action="{{ url('prestaciones/edit') }}">
		@csrf

		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

		<!-- Enviamos el ID de la actividad para luego actualizarlo -->
		<input id="id" name="id" type="hidden" value="{{$provision->id}}">

		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('glosaTrasadora') ? ' is-invalid' : '' }}" value="{{$provision->glosaTrasadora}}" placeholder="{{$provision->glosaTrasadora}}" id="glosaTrasadora" name="glosaTrasadora">
		</div>

		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('codigo') ? ' is-invalid' : '' }}" value="{{$provision->codigo}}" placeholder="{{$provision->codigo}}" id="codigo" name="codigo">
		</div>

		<div class="form-group">
			<select class="form-control" name="lower_age" required autofocus>
				<option selected disabled>Por favor seleccione el rango de edad inferior</option>
				@for ($i = 0; $i <= 120; $i++)
					@if($i == $provision->rangoEdad_inferior)
						<option selected value="{{ $i }}">{{ $i }}</option>
					@else
						<option value="{{ $i }}">{{ $i }}</option>
					@endif
				@endfor
			</select>
		</div>

		<div class="form-group">
			<select class="form-control" name="senior_age" required autofocus>
				<option selected disabled>Por favor seleccione el rango de edad superior</option>
				@for ($i = 0; $i <= 120; $i++)
					@if($i == $provision->rangoEdad_superior)
						<option selected value="{{ $i }}">{{ $i }}</option>
					@else
						<option value="{{ $i }}">{{ $i }}</option>
					@endif
				@endfor
			</select>
		</div>

		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('frecuencia') ? ' is-invalid' : '' }}" value="{{$provision->frecuencia}}" placeholder="{{$provision->frecuencia}}" id="frecuencia" name="frecuencia">
		</div>

		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('ps_fam') ? ' is-invalid' : '' }}" value="{{$provision->ps_fam}}" placeholder="{{$provision->ps_fam}}" id="ps_fam" name="ps_fam">
		</div>

		<div class="form-group">
			<select class="form-control" name="tipo_prestacion">
            	<option value="{{ $tipo_prestacion->id }}">{{ $tipo_prestacion->descripcion }}</option>

				@foreach($tipos_prestaciones as $tp)
					@if($tp->id != $provision->tipo_id)
                 		<option value="{{ $tp->id }}">{{ $tp->descripcion }}</option>
					@endif
				@endforeach
            </select>
		</div>

		<button type="submit" class="btn btn-primary">Editar prestacion</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert">
	<p>No se encontró la prestación</p>
</div>
@endif
@endsection