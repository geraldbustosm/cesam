@extends('layouts.main')
@section('title','Fichas')
@section('active-prestaciones','active')

@section('content')
@if (session('status'))
    <div class="col-12 alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <div class="col-12 alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<h1>Ficha Paciente</h1>
<form name="onSubmit" method="post" action="{{ url('etapa') }}">
    @csrf
    <div class="form-group">
        <select name="stages" id="stages" class="form-control"></select>
    </div>
    <div class="form-group">
        <input type="hidden" class="form-control {{ $errors->has('id') ? ' is-invalid' : '' }}" value="{{ old('id') }}" id="id" name="id">
    </div>
</form>
<form name="onSubmitAttendance" method="post" action="{{ url('registrar/atencion') }}">
    @csrf
    <div class="form-group">
        <input type="hidden" class="form-control {{ $errors->has('DNI_stage') ? ' is-invalid' : '' }}" value="{{ old('DNI_stage') }}" id="DNI_stage" name="DNI_stage">
    </div>
</form>

<div>
    <p><b>Paciente: </b>{{ $patient->nombre1 }} {{ $patient->apellido1 }} {{ $patient->apellido2 }}</p>
    <p class="mb-2"><b>Rut: </b>{{ $patient->DNI}}</p>
    <button type="button" class="btn btn-primary mb-2" id="addAttendance">Añadir prestación</button>
    @if($stage->activa == 1)
    <button type="button" class="btn btn-primary mb-2" id="addRelease">Dar alta</button>
    @endif
    <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit.
        Soluta excepturi hic odit saepe. Distinctio exercitationem
        totam tempore iste quae, ea quo voluptates unde quos, quod
        velit assumenda quia corrupti quidem consectetur doloribus
        saepe optio impedit rem dolor nisi quisquam a beatae quasi!
        Sed dignissimos harum iste delectus ducimus eum sint.
        Mollitia labore harum libero, blanditiis dolores ipsa ipsam quia sapiente?
    <p>

    @php
    $stageCount = $stage->attendance->count();
    @endphp
    @foreach($patientAttendances as $value)
    <div class="card">
        <div class="card-header">
            <div>Prestación #{{$stageCount}} </div>
            <div>
                <a class="" href="{{url('#')}}"><i class="material-icons">create</i><span></span></a>
                <a class="" href="{{url('#')}}"><i class="material-icons">delete</i><span></span></a>
            </div>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">Profesional: {{ $value->functionary->user->primer_nombre }}, {{ $value->functionary->profesion}}</h6>
            <h6 class="card-subtitle mb-4 text-muted">Fecha: {{ $value->fecha }}</h6>
            <h6 class="card-subtitle text-muted">Glosa Trazadora: {{ $value->provision->glosaTrasadora }}</p>
            <h6 class="card-subtitle text-muted">Atención: {{ $value->fecha }}</p>
            <h6 class="card-subtitle text-muted">Observaciones: {{ $value->fecha }}</p>
        </div>
    </div>
    @php
        $stageCount = $stageCount - 1;
    @endphp
    @endforeach
</div>
<script>
    $(document).ready(function() {
        // Run code
        var test = <?php echo json_encode($patientAttendances); ?>;
        console.log(test);
        var id = <?php echo json_encode($patient->id); ?>;
        var stage_id = <?php echo json_encode($activeStage->id); ?>;
        var currStage = <?php echo json_encode($stage->id); ?>;
        $.ajax({
            type: "GET",
            url: "{{ url('etapas') }}?id=" + id,
            success: function(res) {
                if (res) {
                    cant = res.length + 1;
                    $("#stages").append('<option value="' + stage_id + '">Ficha Activa</option>');
                    $.each(res, function(key, value) {
                        cant -= 1;
                        if (value.id != stage_id){
                            if (currStage == value.id) {
                                $("#stages").append('<option value="' + value.id + '"selected>Ficha ' + cant + '</option>');
                            } else {
                                $("#stages").append('<option value="' + value.id + '">Ficha ' + cant + '</option>');
                            }
                        }
                    });
                } else {
                    $("#stages").empty();
                }
            }
        })
    });

    $('#stages').on('change', function() {
        var tagID = document.getElementById('id');
        tagID.value = <?php echo json_encode($patient->id); ?>;
        document.onSubmit.submit();
    });

    $('#addAttendance').on('click', function() {
        var tagID = document.getElementById('DNI_stage');
        tagID.value = <?php echo json_encode($patient->id); ?>;
        document.onSubmitAttendance.submit();
    });

    $('#addRelease').on('click', function() {
        DNI = <?php echo json_encode($patient->DNI); ?>;
        window.location.href = `/alta/${DNI}`;
    });
</script>
@endsection