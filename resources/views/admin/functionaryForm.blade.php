@extends('layouts.main')
@section('title','Funcionario')
@section('active-ingresarpersonas','active')
@section('active-ingresarfuncionario','active')

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
<h1>Ingresar Funcionario</h1>
<div class="div-full">
	@if (session('status'))
		<div class="alert alert-success" role="alert">
			{{ session('status') }}
		</div>
	@endif
	<form method="post" action="{{ url('registrarfuncionario') }}">
		@csrf
		<div class="form-group">
			
            <input type="text" class="form-control {{ $errors->has('profesion') ? ' is-invalid' : '' }}" value="{{ old('profesion') }}" id="profesion" name="profesion" placeholder="Profesion: ">

        </div>
        <div class="form-group">
			<div class="form-row">
				<div class="col-6">
				  <input type="text" class="form-control {{ $errors->has('nombre1') ? ' is-invalid' : '' }}" value="{{ old('nombre1') }}" id="nombre1" name="nombre1" placeholder="Primer Nombre">
				</div>
				<div class="col">
				  <input type="text" class="form-control {{ $errors->has('nombre2') ? ' is-invalid' : '' }}" value="{{ old('nombre2') }}" id="nombre2" name="nombre2" placeholder="Segundo Nombre">
				</div>
                
			</div>
		</div>
		<div class="form-group">
			<div class="form-row">
                <div class="col-6">
				  <input type="text" class="form-control {{ $errors->has('apellido1') ? ' is-invalid' : '' }}" value="{{ old('apellido1') }}" id="apellido1" name="apellido1" placeholder="Apellido Paterno">
				</div>
				<div class="col">
				  <input type="text" class="form-control {{ $errors->has('apellido2') ? ' is-invalid' : '' }}" value="{{ old('apellido2') }}" id="apellido2" name="apellido2" placeholder="Apellido Materno">
				</div>
			</div>
		</div>
		<div class="form-group">
			<input type="text" class="form-control {{ $errors->has('direccion') ? ' is-invalid' : '' }}" value="{{ old('direccion') }}" id="direccion" name="direccion" placeholder="Dirección actual">
		</div>
		
       
        <div class="form-group">
            <select name="user">
                <option selected disabled>Por favor seleccione un usuario para asignar al funcionario</option>
                @foreach($user as $user)
                <option value="{{ $user->id}}">{{ $user->nombre}}</option>
                @endforeach
            </select>
        </div>
        

        
		<button type="submit" class="btn btn-primary">Registrar</button>
		<input type="button" href="javascript:validator()" value="Test" id="testing"/>
	</form>
</div>

<!-- Adding script using on this view -->
<script src="{{asset('js/idValidator.js')}}"></script>
@endsection