@extends('layouts.main')
@section('title','Fichas')
@section('active-prestaciones','active')

@section('content')
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

<div>
    <p><b>Paciente: </b>{{ $patient->nombre1 }} {{ $patient->apellido1 }} {{ $patient->apellido2 }}</p>
    <p class="mb-2"><b>Rut: </b>{{ $patient->DNI}}</p>
    <button type="button" class="btn btn-primary mb-2">Añadir prestación</button>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
        Soluta excepturi hic odit saepe. Distinctio exercitationem
        totam tempore iste quae, ea quo voluptates unde quos, quod
        velit assumenda quia corrupti quidem consectetur doloribus
        saepe optio impedit rem dolor nisi quisquam a beatae quasi!
        Sed dignissimos harum iste delectus ducimus eum sint.
        Mollitia labore harum libero, blanditiis dolores ipsa ipsam quia sapiente?<p>
    
    @php
        $stageCount = $stage->attendance->count();
    @endphp
    @foreach($patientAtendances as $value)
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
                <h6 class="card-subtitle mb-4 text-muted">{{ $value->fecha}}</h6>
                <h6 class="card-subtitle text-muted">{{ $value->provision->glosaTrasadora}}</p>
            </div>
        </div>
        @php
            $stageCount = $stageCount - 1;
        @endphp
    @endforeach
    <script>
        $( document ).ready(function() {
            // Run code
            var id = <?php echo json_encode($patient->id); ?>;
            var stage_id = <?php echo json_encode($activeStage->id); ?>;
            var currStage = <?php echo json_encode($stage->id); ?>;
            $.ajax({
                type: "GET",
                url: "{{ url('etapas') }}?id=" + id,
                success: function(res) {
                    if (res) {
                        cant = 0;
                        $("#stages").append('<option value="' + stage_id + '">Ficha Activa</option>');
                        $.each(res, function(key, value) {
                            cant += 1;
                            if (currStage == value.id) {
                                $("#stages").append('<option value="' + value.id + '"selected>Ficha ' + cant + '</option>');
                            } else {
                                $("#stages").append('<option value="' + value.id + '">Ficha ' + cant + '</option>');
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
            tagID.value = <?php echo json_encode($patient->id); ?>;;
            document.onSubmit.submit();
        });
    </script>
@endsection