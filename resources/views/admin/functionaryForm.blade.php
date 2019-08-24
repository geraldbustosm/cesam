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
			
            <input type="text" class="form-control {{ $errors->has('profesion') ? ' is-invalid' : '' }}" value="{{ old('profesion') }}" id="profesion" name="profesion" placeholder="Profesion">

		</div>
		       
        <div class="form-group">
            <select class="form-control" name="user" required>
                <option selected disabled>Por favor seleccione un usuario para asignar al funcionario</option>
                @foreach($user as $user)
                <option value="{{ $user->id}}">{{ $user->nombre}}</option>
                @endforeach
            </select>
        </div>
                
		<button type="submit" class="btn btn-primary">Registrar</button>
	</form>
</div>
@endsection