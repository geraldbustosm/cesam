@extends('layouts.main')
@section('title','Registrar nueva atención')
@section('active-ingresardatos','active')

@section('content')
<h1>Registrar Atención</h1>
<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <form method="post" action="{{ url('ficha') }}" name="onSubmitAttendance" id="attendanceForm">
        @csrf
        <input type="hidden" id="id" name="id" value="{{$patient->id}}">
        <div class="form-group">
            <p class="titulo2">Paciente: <?= $patient->nombre1 . " " . $patient->nombre2 . " " . $patient->apellido1 . " " . $patient->apellido2; ?></p>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="form-group col-12" style="min-width:200px">
                        <label class="form-group col-12" for="datepicker">Fecha de la atención</label>
                        <input class="form-control col-12" id="datepicker" name="datepicker" value="" required>
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
                    <div class="form-group col-12" style="min-width:200px">
                        <label class="form-group col-12" for="time" class="form-group col-6">Hora inicio de la atención</label>
                        <input class="form-control col-12" id="timeInit" name="timeInit" value="" required>
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
                    <div class="form-group col-12" style="min-width:200px">
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
                    <div class="form-grou col-12" style="min-width:200px">
                        <label for="duration" style="margin-top: 15px">Duración <b>(HH:MM)</b> </label>
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
                    </div><br>
                    <div class="form-group col-12" style="min-width:200px">
                        <label for="title">Tipo de paciente</label>
                        <select name="selectType" class="form-control" style="min-width:200px">
                            <option value="1" selected>Repetido</option>
                            <option value="0">Nuevo</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group col-12" style="margin-top: 20px">
                        <label for="title"><b>Asigne el funcionario y la prestación:</b></label>
                    </div>

                    <div class="form-group col-12" style="margin-top: 20px">
                        <label for="title">Seleccione el funcionario:</label>
                        <select id="functionary" name="functionary" class="form-control" required>
                            <option value="" selected disabled>Seleccione un Funcionario</option>
                            @foreach($users as $key => $user)
                            <option value="{{$user->id}}"> {{$user->user->primer_nombre." ".$user->user->apellido_paterno." - ".$user->user->run}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12" style="margin-top: 20px">
                        <label for="title">Seleccione la especialidad:</label>
                        <select name="speciality" id="speciality" class="form-control" required></select>
                    </div>
                    <div class="form-group col-12" style="margin-top: 20px">
                        <label for="title">Seleccione la glosa:</label>
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
                    <div class="alert alert-danger collapse" role="alert" name="errorAge" id="errorAge" style="min-width:200px">
                        La edad del paciente no esta en el rango de la prestación!!!
                    </div>
                    <div class="form-group col" style="margin-top: 20px">
                        <label for="title">Seleccione la actividad:</label>
                        <select name="activity" id="activity" class="form-control" required></select>
                    </div>
                    <div class="form-group col" style="margin-top: 20px">
                        <label for="selectAssist">Asistencia: </label>
                        <select name="selectAssist" id="selectAssist" class="form-control col-12">
                            <option value="1" selected>Si </option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <script type="text/javascript">
                        var btn = document.getElementsByName("register");

                        $('.search-select').select2();

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
                            btn[0].style = "";
                            btn[1].style = "";
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
                            btn[0].style = "";
                            btn[1].style = "";
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
                                            btn[0].style.display = "none";
                                            btn[1].style.display = "none";
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
                    </script>
                    <div class="form-group col-10" class="register">
                        <input type="hidden" class="form-control {{ $errors->has('DNI') ? ' is-invalid' : '' }}" value="<?= $DNI; ?>" id="DNI" name="DNI">
                        <input type="hidden" class="form-control {{ $errors->has('id_stage') ? ' is-invalid' : '' }}" value="<?= $stage->id; ?>" id="id_stage" name="id_stage">
                        <input type="hidden" class="form-control {{ $errors->has('clicked') ? ' is-invalid' : '' }}" id="clicked" name="clicked">
                        <button type="submit" name="register" id="register" value="1" class="btn btn-primary">Registrar</button>
                        <button type="submit" name="register" id="registerAnother" value="2" class="btn btn-primary">Agregar Otro</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="{{asset('js/checkDate.js')}}"></script>
<script>
    $('#register').on('click', function() {
        $('#clicked').val(1);
    })
    $('#registerAnother').on('click', function() {
        $('#clicked').val(1);
    })

    var form = document.getElementById('attendanceForm');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (getThisDate()) document.onSubmitAttendance.submit();
        else {
            Swal.fire('Error!', `Fecha no válida`, 'error');
            $('#datepicker').val('');
        }
    });
</script>
@endsection
@push('styles')