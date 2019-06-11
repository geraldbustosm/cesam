@extends('layouts.main')
@section('title','Registrar usuario')
@section('active-pacientes','active')

@section('content')
<h1>Registrar usuario</h1>
<div class="div-full">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="post" action="{{ url('registrar') }}">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ old('nombre') }}" id="nombre" name="nombre" placeholder="Felipe Rojas">
        </div>
        <div class="form-group">
            <label for="rut">RUT</label>
            <input type="text" class="form-control {{ $errors->has('rut') ? ' is-invalid' : '' }}" value="{{ old('rut') }}" id="rut" name="rut" placeholder="192571995">
        </div>
        <div class="form-group">
            <label for="email">Correo electronico</label>
            <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" id="email" name="email" placeholder="frojas@correo.com">
        </div>
        <div class="form-group">
            <label for="rol">Rol</label>
            <select id="rol" name="rol" class="form-control">
                <option value="1">Administrador</option>
                <option value="2">Funcionario</option>
                <option value="3">Secretaria</option>
            </select>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirmar contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection