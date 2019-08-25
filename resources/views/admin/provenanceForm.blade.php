@extends('layouts.main')
@section('title','Registrar procedencia')
@section('active-ingresarprocedencia','active')

@section('content')
<h1>Registrar Procedencia</h1>
<div class="div-full">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="post" action="{{ url('registrarprocedencia') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{ old('descripcion') }}" id="descripcion" name="descripcion" placeholder="Tipo de procedencia">
        </div>
        
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection