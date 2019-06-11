@extends('layouts.main')
@section('title','Paciente')
@section('active-ingresarpaciente','active')

@section('content')
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

<div class="page-content">
    <h1>Ingresar pacientes</h1>
    <div class="div-full">
    <form action="">
        <div class="form-group">
            <input type="text" class="form-control" id="userID" placeholder="Ingresar rut" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="userName" placeholder="Nombre completo">
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-7">
                  <input type="text" class="form-control" id="userCountry" placeholder="País" required>
                </div>
                <div class="col">
                  <input type="text" class="form-control" id="userCity" placeholder="Ciudad">
                </div>
            </div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="userAddress1" placeholder="Dirección actual" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="userAddress2" placeholder="Dirección opcional">
            <small id="addressHelp" class="form-text text-muted">La dirección no será de visualización pública.</small>
        </div>
        <div class="form-group">
            <label for="userAdd">Sexo</label><br>
            <input type="radio" name="gender" value="m" required> Masculino &nbsp;
            <input type="radio" name="gender" value="f" required> Femenino &nbsp;
            <input type="radio" name="gender" value="o" required> Otro
        </div>
        <div class="form-group">
            <label for="userAdd">Fecha de nacimiento</label>
            <input id="datepicker" width="276" required>
            <script>
                $('#datepicker').datepicker({
                    uiLibrary: 'bootstrap4'
                });
            </script>
        </div>
        <button type="submit" class="btn btn-primary" id="test">Registrar</button>
    </form>
    </div>
</div>

<!-- Añadiendo script que solo se utiliza en esta vista -->
<script src="{{asset('js/idValidator.js')}}"></script>
@endsection