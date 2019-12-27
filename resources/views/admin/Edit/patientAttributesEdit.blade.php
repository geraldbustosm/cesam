@extends('layouts.main')
@section('title','Editar atributos del paciente')
@section('active-ingresardatos','active')

@section('content')

<h1>Editar atributos del paciente</h1>
<div class="div-full">
	@if (session('status'))
		<div class="alert alert-success" role="alert">
			{{ session('status') }}
		</div>
    @endif

	@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	@if ($patient)
	<form method="post" action="{{ url('paciente-atributos/edit') }}">
		@csrf

		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

		<!-- Enviamos el ID de la actividad para luego actualizarlo -->
		<input id="dni" name="dni" type="hidden" value="{{$patient->DNI}}">
		
		<h4>Seleccione los atributos</h4>
		<div class="form-group">
			<div class="card p-2">
				@foreach($attributes as $at)
						@php
							$isInAttribute = false;
						@endphp
						@foreach($patient->attributes as $patientAttribute)
							@if($at->id == $patientAttribute->id)
								@php $isInAttribute = true; @endphp
							@endif
						@endforeach
						
						@if($isInAttribute)
							<label><input type="checkbox" name="options[]" value="{{ $at->id}}" checked> {{ $at->descripcion}}</label>
						@else
							<label><input type="checkbox" name="options[]" value="{{ $at->id}}"> {{ $at->descripcion}}</label>
						@endif
				@endforeach
				</div>
			</div>
		</div>

		<button type="submit" class="btn btn-primary">Editar atributos del paciente</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert">
	<p>No se encontró el paciente</p>
</div>
@endif
@endsection