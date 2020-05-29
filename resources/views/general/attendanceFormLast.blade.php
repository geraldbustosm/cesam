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
    <form method="post" action="{{ url('ultima-atencion') }}">
        @csrf
        <input type="hidden" id="id" name="id" value=<?= $patient->id; ?>>
        <div class="form-group">
            <p class="titulo2">Paciente: <?= $patient->nombre1 . " " . $patient->nombre2 . " " . $patient->apellido1 . " " . $patient->apellido2; ?></p>
        </div>
        <div class="row">
            <div class="column">

                <div class="form-group col-10">
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
                <div class="form-group col-10">
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
                                alert("Error en la duración");
                            }
                        }
                    </script>
                </div>
                <div class="form-group col-10">
                    <label for="selectAssist">Asistencia: </label>
                    <select name="selectAssist" id="selectAssist" class="form-control col-12">
                        <option value="1" selected>Si </option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>
            <div class="column">

                <div class="form-group">
                    <label for="title"><b>Asingne la actividad:</b></label>
                </div>
                <div class="panel-heading">Funcionario</div>
                <div class="form-control">
                    <label class="form-group col-10">{{ $functionary->user->primer_nombre." ".$functionary->user->apellido_paterno." - ".$user->user->run }}</label>
                    <input hidden id="functionary" name="functionary" value="{{ $functionary->id}}"></input>
                </div>
                <div class="form-group">
                    <label for="title">Especialidad:</label>
                    <label class="form-control col-10">{{ $speciality->descripcion }}</label>
                    <input hidden id="speciality" name="speciality" value="{{ $speciality->id }}"></input>
                </div>
                <div class="form-group">
                    <label for="title">Prestación:</label>
                    <label class="form-control col-10">{{ $attendance->provision->glosaTrasadora }}</label>
                    <input hidden id="provision" name="provision" value="{{ $attendance->provision->id }}"></input>
                </div>
                <div class="form-group">
                    <select class="form-control" name="activity" required>
                        <option value="" selected disabled>Seleccione la actividad: </option>
                        @foreach($activity as $activity)
                        <option value="{{ $activity->id}}">{{ $activity->descripcion}}</option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group" class="register">
                    <input type="hidden" class="form-control {{ $errors->has('DNI') ? ' is-invalid' : '' }}" value="<?= $DNI; ?>" id="DNI" name="DNI">
                    <input type="hidden" class="form-control {{ $errors->has('id_stage') ? ' is-invalid' : '' }}" value="<?= $stage->id; ?>" id="id_stage" name="id_stage">
                    <button type="submit" name="register" id="register" value="1" class="btn btn-primary">Registrar</button>
                    <button type="button" id="addAttendance" class="btn btn-primary" formnovalidate>Modificar Atención</button>
                </div>
            </div>
        </div>
    </form>
</div>
<form name="onSubmitStage" method="post" action="{{ url('registrar/atencion') }}">
    @csrf
    <div class="form-group">
        <input type="hidden" class="form-control {{ $errors->has('DNI_stage') ? ' is-invalid' : '' }}" value="{{ old('DNI_stage') }}" id="DNI_stage" name="DNI_stage">
    </div>
</form>
<link href="{{ asset('css/attendance.css') }}" rel="stylesheet">
<script>
    $('#addAttendance').on('click', function() {
        var tagID = document.getElementById('DNI_stage');
        tagID.value = <?php echo $patient->id ?>;
        document.onSubmitStage.submit();
    });
</script>
@endsection
@push('styles')