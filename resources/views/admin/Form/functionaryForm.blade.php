@extends('layouts.main')
@section('title','Funcionario')
@section('active-ingresarpersonas','active')
@section('active-ingresarfuncionario','active')

@section('content')
<h1>Ingresar Funcionario</h1>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="div-full">
	@if (session('status'))
		<div class="alert alert-success" role="alert">
			{{ session('status') }}
		</div>
	@endif
	<form method="post" action="{{ url('registrar/funcionario') }}">
		@csrf
		
        <div class="form-group">
            <select class="form-control" name="speciality" required>
                <option selected disabled>Por favor seleccione una especialidad para asignar al funcionario</option>
                @foreach($speciality as $speciality)
                <option value="{{ $speciality->id}}">{{ $speciality->descripcion}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <input type="number" class="form-control {{ $errors->has('declared_hours') ? ' is-invalid' : '' }}" value="{{ old('declared_hours') }}" id="declared_hours" name="declared_hours" placeholder="Horas declaradas al ministerio">
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

<script>document.getElementById('people_Submenu').className += ' show';</script>
@endsection