@extends('layouts.main')
@section('title','Crear etapa')
@section('active-ingresardatos','active')

@section('content')

<h1>Crear nueva etapa</h1>
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
    <form method="post" action="{{ url('crear/etapa') }}" id="sectionForm">
        @csrf
        <div class="form-group">
            <div class="form-row">
                <div class="col-4">
                    <select class="form-control" name="funcionario_id" required>
                        <option selected disabled>Por favor seleccione un funcionario </option>
                        @foreach($functionarys as $funcionario)
                        <option value="{{ $funcionario->id}}">{{ $funcionario->primer_nombre." ".$funcionario->apellido_paterno }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <select class="form-control" name="programa_id" required>
                        <option selected disabled>Por favor seleccione un programa </option>
                        @foreach($program as $programa)
                        <option value="{{ $programa->id}}">{{ $programa->descripcion}}</option>
                        @endforeach
                    </select>

                </div>

            </div>
        </div>

        <div class="form-group">
            <div class="form-row">

                <div class="col-4">
                    <select class="form-control" name="sigges_id" required>
                        <option selected disabled>Por favor seleccione un sigges </option>
                        @foreach($Sigges as $sigges)
                        <option value="{{ $sigges->id}}">{{ $sigges->descripcion}}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-4">
                    <select class="form-control" name="procedencia_id" required>
                        <option selected disabled>Por favor seleccione una procedencia </option>
                        @foreach($provenance as $procedencia)
                        <option value="{{ $procedencia->id}}">{{ $procedencia->descripcion}}</option>
                        @endforeach
                    </select>

                </div>

            </div>
        </div>
        <div class="form-group">
            <h4> Seleccione diagnosticos </h4>
            <div class="form-row">
                <div class="col-4">
                    <div class="overflow-auto" style="height:450px">
                        @foreach($diagnosis as $diagnostico)

                        <div class="card">
                            <div class="checkbox-container">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="options[]" value="{{ $diagnostico->id}}">
                                    <span class="checkbox-custom rectangular"></span>
                                </label>
                            </div>
                            <div class="input-title">{{ $diagnostico->descripcion}}</div>

                        </div>

                        @endforeach
                    </div>
                </div>
                <div class="col-4 center-block">
                    <div class="form-group text-center" class="register">
                        <input type="hidden" class="form-control {{ $errors->has('idpatient') ? ' is-invalid' : '' }}" value="<?= $idpatient; ?>" id="idpatient" name="idpatient">
                        <button type="submit" class="btn btn-primary ">Registrar</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
<script src="{{asset('js/checkbox-required.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/card.css')}}">
@endsection