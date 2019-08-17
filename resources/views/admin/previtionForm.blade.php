@extends('layouts.main')
@section('title','Registrar prevision')
@section('active-ingresarprevision','active')

@section('content')
<h1>Registrar Prevision</h1>
<div class="div-full">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="post" action="{{ url('registrarprevision') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ old('nombre') }}" id="nombre" name="nombre" placeholder="Tipo de prevision">
        </div>
        
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection