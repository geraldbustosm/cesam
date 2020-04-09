@extends('layouts.main')
@section('title','Editar atención')
@section('active-ingresardatos','active')

@section('content')
<h1>Editar Atención</h1>
<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @else
    <form method="post" action="{{ url('editar/atencion') }}">
        @csrf
        <!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

        <input type="hidden" id="attendance_id" name="attendance_id" value="{{$attendance->id}}">
        <input type="hidden" id="id" name="id" value="{{$patient->id}}">
        <div class="form-group">
            <p class="titulo2">Paciente: {{$patient->nombre1}} {{$patient->nombre2}} {{$patient->apellido1}} {{$patient->apellido2}}</p>
        </div>
        <div class="row">
            <div class="column">

                <div class="form-group col-10">
                    <label class="form-group col-12" for="datepicker">Fecha de la atención</label>
                    <input class="form-control col-12"id="datepicker" name="datepicker" value="{{$fecha}}" required>
                    <script>
                        var config = {
                            format: 'dd/mm/yyyy',
                            locale: 'es-es',
                            uiLibrary: 'bootstrap4',
                            maxDate: new Date,
                        };
                        $('#datepicker').datepicker(config);
                    </script>
                </div>
                <div class="form-group col-10">
                    <label class="form-group col-12" for="time" class="form-group col-6">Hora inicio de la atención</label>
                    <input class="form-control col-12" id="timeInit" name="timeInit"  value="{{$hora}}" required>
                    <script>
                        $('#timeInit').timepicker({
                            mode: '24hr',
                            defaultTime: 'value',
                            minuteStep: 1,
                            disableFocus: true,
                            template: 'dropdown',
                            showMeridian: false
                        });
                    </script>
                </div>
                <div class="form-group col-10">
                    <label class="form-group col-12" for="timeEnd" class="form-group col-6">Hora termino de la atención</label>
                    <input class="form-control col-12" id="timeEnd" name="timeEnd" value="" required>
                    <script>
                        $('#timeEnd').timepicker({
                            mode: '24hr',
                            defaultTime: 'value',
                            minuteStep: 1,
                            disableFocus: true,
                            template: 'dropdown',
                            showMeridian: false
                        });
                    </script>
                </div>
                <div class="form-grou col-10">
                    <label for="duration">Duración <b>(HH:MM)</b> </label>
                    <br>
                    <input class = "form-control col-12" id="duration" name="duration" value="" required readonly>
                    <script type="text/javascript">
                        var init = document.getElementById('timeInit');
                        var end = document.getElementById('timeEnd');
                        end.onchange = function(e) {
                            function toSeconds(time_str) {
                                // Extract hours, minutes and seconds
                                var parts = time_str.split(':');
                                // compute  and return total seconds
                                return parts[0] * 3600 + // an hour has 3600 seconds
                                    parts[1] * 60; // a minute has 60 seconds
                            }
                            var a = init.value; //start time
                            var b = end.value; // end time
                            var difference = (toSeconds(b) - toSeconds(a));
                            // format time difference
                            if (difference > 0) {
                                var result = [
                                    Math.floor(difference / 3600), // an hour has 3600 seconds
                                    Math.floor((difference % 3600) / 60) // a minute has 60 seconds
                                ];
                                // 0 padding and concatation
                                result = result.map(function(v) {
                                    return v < 10 ? '0' + v : v;
                                }).join(':');
                                document.getElementById('duration').value = result;
                            } else {
                                document.getElementById('duration').value = '00:00';
                                alert("Error en la duración");
                            }
                        }
                    </script>
                </div>
                <div class="form-group col-10" style="min-width:200px">
                        <label for="title">Tipo de paciente</label>
                        <select name="selectType" class="form-control" style="min-width:200px">
                            <option value="1" {{($attendance->repetido ? 'checked' : '' )}}>Repetido</option>
                            <option value="0">Nuevo</option>
                        </select>
                    </div>
            </div>
            <div class="column">
                
                <div class="form-group">
                    <label for="title"><b>Asigne el funcionario y la prestación:</b></label>
                </div>
                <div class="panel-heading">Seleccione el funcionario</div>
                <div class="form-group">
                    <select id="functionary" name="functionary" class="form-control" style="width:350px" required>
                        <option value="" selected disabled>Seleccione un Funcionario</option>
                        @foreach($functionarys as $functionary)
                            <option value="{{$functionary->id}}"> {{$functionary->user->primer_nombre}} {{$functionary->user->apellido_paterno}} {{$functionary->user->apellido_materno}}, {{$functionary->profesion}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Seleccione la especialidad:</label>
                    <select name="speciality" id="speciality" class="form-control" style="width:350px"></select>
                </div>
                <div class="form-group">
                    <label for="title">Seleccione la glosa:</label>
                    <select name="provision" id="provision" class="form-control" style="width:350px"></select>
                </div>

                <div class="alert alert-danger collapse" role="alert" name="errorAge" id="errorAge">
                    La edad del paciente no esta en el rango de la prestación!!!
                </div>
                <div class="form-group">
                    <label for="title">Seleccione la actividad:</label>
                    <select name="activity" id="activity" class="form-control" style="width:350px"></select>
                </div>
                <div class="form-group">
                    <label for="selectA">Asistencia: </label>
                    <select name="selectA" class="form-control" style="width:350px">
                        <option value="1" selected>Si </option>
                        <option value="0">No</option>
                    </select>
                </div>
                <script type="text/javascript">
                    var btn = document.getElementsByName("register");

                    $('#functionary').change(function() {
                        var functionaryID = $(this).val();
                        if (functionaryID) {
                            $.ajax({
                                type: "GET",
                                url: "{{url('lista-especialidades')}}?functionary_id=" + functionaryID,
                                success: function(res) {
                                    if (res) {
                                        btn[0].style = "";
                                        btn[1].style = "";
                                        $('#errorAge').hide();
                                        $("#speciality").empty();
                                        $("#provision").empty();
                                        $("#activity").empty();
                                        $("#speciality").append('<option>Seleccione la especialidad</option>');
                                        $.each(res, function(key, value) {
                                            $("#speciality").append('<option value="' + value.id + '">' + value.descripcion + '</option>');
                                        });
                                    } else {
                                        $("#speciality").empty();
                                    }
                                }
                            });
                        } else {
                            $("#speciality").empty();
                            $("#functionary").empty();
                        }
                    });

                    $('#speciality').on('change', function() {
                        $('#errorAge').hide();
                        var specialityID = $(this).val();
                        if (specialityID) {
                            $.ajax({
                                type: "GET",
                                url: "{{url('lista-prestaciones')}}?speciality_id=" + specialityID,
                                success: function(res) {
                                    if (res) {
                                        btn[0].style = "";
                                        btn[1].style = "";
                                        $("#provision").empty();
                                        $("#provision").append('<option>Seleccione la prestación</option>');
                                        $.each(res, function(key, value) {
                                            $("#provision").append('<option value="' + value.id + '">' + value.glosaTrasadora + '</option>');
                                        });
                                    } else {
                                        $("#provision").empty();
                                    }
                                }
                            });
                        } else {
                            $("#provision").empty();
                        }
                    });

                    $('#speciality').on('change', function() {
                        var specialityID = $(this).val();
                        if (specialityID) {
                            $.ajax({
                                type: "GET",
                                url: "{{url('lista-actividades')}}?speciality_id=" + specialityID,
                                success: function(res) {
                                    if (res) {
                                        $("#activity").empty();
                                        $("#activity").append('<option>Seleccione la actividad</option>');
                                        $.each(res, function(key, value) {
                                            $("#activity").append('<option value="' + value.id + '">' + value.descripcion + '</option>');
                                        });
                                    } else {
                                        $("#activity").empty();
                                    }
                                }
                            });
                        } else {
                            $("#activity").empty();
                        }
                    });

                    $('#provision').on('change', function() {
                        $('#errorAge').hide();
                        btn[0].style = "";
                        btn[1].style = "";
                        var provisionID = $(this).val();
                        if (provisionID) {
                            $.ajax({
                                type: "GET",
                                url: "{{url('age-check')}}?provision_id=" + provisionID,
                                success: function(res) {
                                    if (res < 0) {
                                        $('#errorAge').show();
                                        btn[0].style.display = "none";
                                        btn[1].style.display = "none";
                                    } else {
                                        $('#errorAge').addClass('hide');
                                    }
                                }
                            });
                        } else {
                            $("#activity").empty();
                        }
                    });
                </script>
                <div class="form-group" class="register">
                    <input type="hidden" class="form-control {{ $errors->has('DNI') ? ' is-invalid' : '' }}" value="{{$patient->dni}}" id="DNI" name="DNI">
                    <input type="hidden" class="form-control {{ $errors->has('id_stage') ? ' is-invalid' : '' }}" value="{{ $stage->id }}" id="id_stage" name="id_stage">
                    <button type="submit" name="register" id="register" value="1" class="btn btn-primary">Editar atención</button>
                    <button type="submit" name="register" id="register" value="2" class="btn btn-primary">Agregar Otro</button>
                </div>
            </div>
        </div>
    </form>
    @endif
    @endsection
    @push('styles')
    <link href="{{ asset('css/attendance.css') }}" rel="stylesheet">