@extends('layouts.main')
@section('title','Registrar alta')
@section('active-ingresardatos','active')
@section('active-ingresaralta','active')

@section('content')
<h1>Registrar Alta</h1>
<div class="div-full">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="post" action="{{ url('registraralta') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('descripcion') ? ' is-invalid' : '' }}" value="{{ old('descripcion') }}" id="descripcion" name="descripcion" placeholder="Motivo del Alta">
        </div>
        
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection