@extends('layouts.main')
@section('title','Editar actividad')
@section('active-ingresardatos','active')

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
<h1>Editar actividad</h1>
<div class="div-full">
	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif
    
    @if ($activity)
    <form method="post" action="{{ url('actividad/edit') }}"> 
		@csrf

		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">
		
		<!-- Enviamos el ID de la actividad para luego actualizarlo -->
		<input id="id" name="id" type="hidden" value="{{$activity->id}}">

		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{$activity->descripcion}}" placeholder="{{$activity->descripcion}}" id="descripcion" name="descripcion">
		</div>

        <div class="form-group form-check">
            <input type="checkbox" name="openCanasta" id="openCanasta" value="1" {{$activity->actividad_abre_canasta == 1 ? 'checked' : ''}}>
            <label class="form-check-label" for="openCanasta"> Abre canasta</label>
        </div>

		<button type="submit" class="btn btn-primary">Editar actividad</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert"><p>No se encontró la actividad</p></div>
@endif
@endsection