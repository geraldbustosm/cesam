@extends('layouts.main')
@section('title','Registrar programa')
@section('active-ingresarprograma','active')

@section('content')
<h1>Registrar Programa</h1>
<div class="div-full">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="post" action="{{ url('registrarprograma') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('especialidad') ? ' is-invalid' : '' }}" value="{{ old('especialidad') }}" id="especialidad" name="especialidad" placeholder="Tipo de especialidad">
        </div>
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{ old('descripcion') }}" id="descripcion" name="descripcion" placeholder="Tipo de programa">
        </div>
        
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection