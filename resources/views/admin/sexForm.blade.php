@extends('layouts.main')
@section('title','Registrar Sexo/Genero')
@section('active-ingresardatos','active')
@section('active-ingresarsexo','active')

@section('content')
<h1>Registrar Sexo o Genero</h1>
<div class="div-full">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="post" action="{{ url('registrarsexo') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{ old('descripcion') }}" id="descripcion" name="descripcion" placeholder="Sexo o Genero">
        </div>
        
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection