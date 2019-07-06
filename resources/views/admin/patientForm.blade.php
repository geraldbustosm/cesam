@extends('layouts.main')
@section('title','Registrar paciente')
@section('active-ingresarpaciente','active')

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="https://unpkg.com/gijgo@1.9.13/js/messages/messages.es-es.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

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
    <h1>Ingresar pacientes</h1>
    <div class="div-full">
    <form method="post" action="{{ url('registrarpaciente') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{ old('name') }}" id="name" name="name" placeholder="Nombre completo" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('id') ? ' is-invalid' : '' }}" value="{{ old('id') }}" id="id" name="id" placeholder="Ingresar rut o pasaporte" required>
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-7">
                  <input type="text" class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}" value="{{ old('country') }}" id="country" name="country" placeholder="País" required>
                </div>
                <div class="col">
                  <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" value="{{ old('city') }}" id="city" name="city" placeholder="Ciudad" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('first_address') ? ' is-invalid' : '' }}" value="{{ old('first_address') }}" id="first_address" name="first_address" placeholder="Dirección actual" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('optional_address') ? ' is-invalid' : '' }}" value="{{ old('optional_address') }}" id="optional_address" name="optional_address" placeholder="Dirección opcional">
            <small id="addressHelp" class="form-text text-muted">La dirección no será de visualización pública.</small>
        </div>
        <div class="form-group">
            <label>Sexo</label><br>
            <input type="radio" name="gender" value="m" required> Masculino &nbsp;
            <input type="radio" name="gender" value="f" required> Femenino &nbsp;
            <input type="radio" name="gender" value="o" required> Otro
        </div>
        <div class="form-group">
            <label>Fecha de nacimiento</label>
            <input id="datepicker" name="datepicker" width="276" required>
            <script>
                $('#datepicker').datepicker({
                    locale: 'es-es',
                    uiLibrary: 'bootstrap4',
                    format: 'dd/mm/yyyy',
                    startDate: '-3d'
                });
            </script>
        </div>
        <button type="submit" class="btn btn-primary" id="regist">Registrar</button>
    </form>
    </div>
@endsection

<!-- Adding script using on this view -->
<script src="{{asset('js/idValidator.js')}}"></script>