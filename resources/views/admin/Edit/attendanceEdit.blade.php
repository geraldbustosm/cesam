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
    <form method="post" action="{{ url('editar/atencion') }}" name="onSubmitAttendance" id="attendanceEdit">
        @csrf
        <!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
        <input type="hidden" name="_method" value="PUT">

        <input type="hidden" id="attendance_id" name="attendance_id" value="{{$attendance->id}}">
        <input type="hidden" id="id" name="id" value="{{$patient->id}}">
        <div class="form-group">
            <p class="titulo2">Paciente: {{$patient->nombre1}} {{$patient->nombre2}} {{$patient->apellido1}} {{$patient->apellido2}}</p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group col-12">
                        <label class="form-group col-12" for="datepicker">Fecha de la atención</label>
                        <input class="form-control col-12" id="datepicker" name="datepicker" value="{{$fecha}}" required>
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
                    <div class="form-group col-12">
                        <label class="form-group col-12" for="time" class="form-group col-6">Hora inicio de la atención</label>
                        <input class="form-control col-12" id="timeInit" name="timeInit" value="{{$hora}}" required>
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
                    <div class="form-group col-12">
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
                    <div class="form-grou col-12">
                        <label for="duration">Duración <b>(HH:MM)</b> </label>
                        <br>
                        <input class="form-control col-12" id="duration" name="duration" value="" required readonly>
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
                    <div class="form-group col-12">
                        <label for="title">Tipo de paciente</label>
                        <select name="selectType" class="form-control" style="min-width:200px">
                            <option value="1" {{($attendance->repetido ? 'selected' : '' )}}>Repetido</option>
                            <option value="0" {{($attendance->repetido ? '' : 'selected' )}}>Nuevo</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="form-group col-12">
                        <label for="title"><b>Asigne el funcionario y la prestación:</b></label>
                    </div>
                    <div class="form-group col-12">
                        <label for="functionary">Seleccione la especialidad:</label>
                        <select id="functionary" name="functionary" class="form-control" required>
                            <option value="" selected disabled>Seleccione un Funcionario</option>
                            @foreach($functionarys as $functionary)
                            <option value="{{$functionary->id}}"> {{$functionary->user->primer_nombre}} {{$functionary->user->apellido_paterno}} {{$functionary->user->apellido_materno}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12">
                        <label for="speciality">Seleccione la especialidad:</label>
                        <select name="speciality" id="speciality" class="form-control" required></select>
                    </div>
                    <div class="form-group col-12">
                        <label for="provision">Seleccione la glosa:</label>
                        <select class="div-full search-select" id="provision" name="provision" required>
                            <option value="" selected disabled>Seleccione la glosa</option>
                            @foreach($provision as $index)
                            @if ($lastProvision)
                            <option value="{{ $index->id}}" {{ ($lastProvision->prestacion_id == $index->id ? 'selected' : '') }}> {{ $index->glosaTrasadora}}</option>
                            @else
                            <option value="{{ $index->id}}">{{ $index->glosaTrasadora}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-danger collapse" role="alert" name="errorAge" id="errorAge">
                        La edad del paciente no esta en el rango de la prestación!!!
                    </div>
                    <div class="form-group col-12">
                        <label for="activity">Seleccione la actividad:</label>
                        <select name="activity" id="activity" class="form-control" required></select>
                    </div>
                    <div class="form-group col-12">
                        <label for="selectAssist">Asistencia: </label>
                        <select name="selectAssist" class="form-control" required>
                            <option value="1" {{($attendance->asistencia ? 'selected' : '')}}>Si </option>
                            <option value="0" {{($attendance->asistencia ? '' : 'selected')}}>No</option>
                        </select>
                    </div>

                    <div class="form-group text-center">
                        <input type="hidden" class="form-control {{ $errors->has('DNI') ? ' is-invalid' : '' }}" value="{{$patient->dni}}" id="DNI" name="DNI">
                        <input type="hidden" class="form-control {{ $errors->has('id_stage') ? ' is-invalid' : '' }}" value="{{ $stage->id }}" id="id_stage" name="id_stage">
                    </div>
                </div>
            </div>
        </div><br>
        <div class="form-group text-center"><button type="submit" class="btn btn-primary" id="btnEdit" style="width: 300px;">Editar atención</button></div>
    </form>
</div>
<script src="{{asset('js/checkDate.js')}}"></script>

<script>
    var btn = document.getElementById("btnEdit");

    /**
     * This function must be here because ajax dosn't work on js file
     */

    $('.search-select').select2();

    $('#functionary').change(function() {
        var functionaryID = $(this).val();
        if (functionaryID) {
            $.ajax({
                type: "GET",
                url: "{{url('lista-especialidades')}}?functionary_id=" + functionaryID,
                success: function(res) {
                    if (res) {
                        btn.style = "";
                        $('#errorAge').hide();
                        $("#speciality").empty();
                        $("#activity").empty();
                        $("#speciality").append('<option value="" selected disabled>Seleccione la especialidad</option>');
                        $.each(res, function(key, value) {
                            if (value.activa == true)
                                $("#speciality").append('<option value="' + value.id + '">' + value.descripcion + '</option>');
                        });
                    } else $("#speciality").empty();
                }
            });
        } else {
            $("#speciality").empty();
            $("#functionary").empty();
        }
    });

    $('#speciality').on('change', function() {
        btn.style = "";
        var specialityID = $(this).val();
        if (specialityID) {
            $.ajax({
                type: "GET",
                url: "{{url('lista-actividades')}}?speciality_id=" + specialityID,
                success: function(res) {
                    if (res) {
                        $("#activity").empty();
                        $("#activity").append('<option value="" selected disabled>Seleccione la actividad</option>');
                        $.each(res, function(key, value) {
                            if (value.activa == true)
                                $("#activity").append('<option value="' + value.id + '">' + value.descripcion + '</option>');
                        });
                    } else $("#activity").empty();
                }
            });
        } else $("#activity").empty();
    });

    $('#provision').on('change', function() {
        btn.style = "";
        var provisionID = $(this).val();
        var patientID = <?php echo json_encode($patient->id); ?>;
        if (provisionID) {
            $.ajax({
                type: "GET",
                url: "{{url('age-check')}}",
                data: {
                    provision_id: provisionID,
                    patient_id: patientID
                },
                success: function(res) {
                    if (res < 0) {
                        $('#errorAge').show();
                        btn.style.display = "none";
                    } else $('#errorAge').hide();
                }
            });
        } else $("#activity").empty();
    });

    $('#activity').on('change', function() {
        if ($("#activity option:selected").text().toLowerCase().includes('informe') ||
            $("#activity option:selected").text().toLowerCase().includes('ipg')) {
            $("#selectAssist").empty();
            $("#selectAssist").append('<option value="0" selected>No</option>');
        } else {
            $("#selectAssist").empty();
            $("#selectAssist").append('<option value="1" selected>Si</option>');
            $("#selectAssist").append('<option value="0">No</option>');
        }
    });

    var form = document.getElementById('attendanceEdit');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (getThisDate()) document.onSubmitAttendance.submit();
        else {
            Swal.fire('Error!', `Fecha no válida`, 'error');
            $('#datepicker').val('');
        }
    });

    function getThisDate() {
        var datepicker = $('#datepicker').datepicker();
        var date = String(datepicker.value());
        var date = date.split("/");
        if (date.length < 3) date = date[0].split("-");
        if (checkDate(date)) return true;
        else return false;
    }

    function checkDate(date) {
        var curDate = new Date();
        var curYear = curDate.getFullYear();
        var curMonth = curDate.getMonth() + 1;
        var curDay = curDate.getDate();
        var year = parseInt(date[2]);
        var month = parseInt(date[1]);
        if (year == curYear && month == curMonth) return checkDays(date, curDay);
        else if (year == curYear && month < curMonth && month > 0) return checkDays(date, 0);
        else if (year <= curYear - 1 && (month <= 12 && month > 0)) return checkDays(date, 0);
        else return false;
    }

    function checkDays(date, curDay) {
        var year = parseInt(date[2]);
        var month = parseInt(date[1]);
        var day = parseInt(date[0]);
        var newDate = new Date(year, month, curDay);
        if (day > 0 && day <= newDate.getDate()) return true;
        else return false;
    }
</script>
@endif
@endsection
@push('styles')
<link href="{{ asset('css/attendance.css') }}" rel="stylesheet">