@extends('layouts.main')
@section('title','Editar atributo')
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
<h1>Editar atributo</h1>
<div class="div-full">
	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif
    
    @if ($attribute)
    <form method="post" action="{{ url('atributo/edit') }}"> 
		@csrf

		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">
		
		<!-- Enviamos el ID del alta para luego actualizarlo -->
		<input id="id" name="id" type="hidden" value="{{$attribute->id}}">

		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{$attribute->descripcion}}" placeholder="{{$attribute->descripcion}}" id="descripcion" name="descripcion">
		</div>

		<button type="submit" class="btn btn-primary">Editar atributo</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert"><p>No se encontró el atributo</p></div>
@endif
@endsection