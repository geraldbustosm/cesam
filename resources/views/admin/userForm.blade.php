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
            <input type="text" class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ old('nombre') }}" id="nombre" name="nombre" placeholder="Nombre completo">
        </div>
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('rut') ? ' is-invalid' : '' }}" value="{{ old('rut') }}" id="rut" name="rut" placeholder="Rut o pasaporte">
        </div>
        <div class="form-group">
            <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" id="email" name="email" placeholder="Correo">
        </div>
        <div class="form-group">
            <select id="rol" name="rol" class="form-control">
                <option value="0" disabled selected>Rol de usuario</option>
                <option value="1">Administrador</option>
                <option value="2">Funcionario</option>
                <option value="3">Secretaria</option>
            </select>
        </div>
        <div class="form-group">
            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="Contraseña">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar contraseña">
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection