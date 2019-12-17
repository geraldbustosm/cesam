@extends('layouts.main')
@section('title','Fichas')
@section('active-prestaciones','active')

@section('content')
<div class="div-full">
    <div class="row">
        <div class="col-16"><h1>Ficha Paciente</h1></div>
        <div class="col-2 ml-auto"><select name="stages" id="stages" class="form-control"></select></div>
    </div>
    
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
    <form name="onSubmit" method="post" action="{{ url('etapa') }}">
        @csrf
        <!-- <div class="form-group">
            <select name="stages" id="stages" class="form-control"></select>
        </div> -->
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
    <form name="onSubmitDelete" method="post" action="{{ url('eliminar-atención') }}">
        @csrf
        <div class="form-group">
            <input type="hidden" class="form-control {{ $errors->has('id_attendance') ? ' is-invalid' : '' }}" value="{{ old('id_attendance') }}" id="id_attendance" name="id_attendance">
        </div>
    </form>
</div>
<div class="div-full">
    <h4><b>Paciente: </b>{{ $patient->nombre1 }} {{ $patient->apellido1 }} {{ $patient->apellido2 }}</h4>
    <h5 class="mb-2"><b>Rut: </b>{{ $patient->DNI }}</h5>
    <br>
    <button type="button" class="btn btn-primary mb-2" id="addAttendance">Añadir prestación</button>
    @if($stage->activa == 1)
    <button type="button" class="btn btn-primary mb-2" id="addRelease">Dar alta</button>
    @endif
</div>
<div class="div-full">
    <p>
        <b>Diagnósticos: </b>{{ $diagnosis }}
    <p>
    <p>
        <b>Atributos: </b>{{ $attributes }}
    <p>

    @php
    $stageCount = $stage->attendance->count();
    @endphp
    @foreach($patientAttendances as $value)
    <div class="card">
        <div class="card-header">
            <div>Prestación #{{$stageCount}} </div>
            <div>
                <a class="" href="#" onclick="redirectEdit(<?php echo json_encode($value->id); ?>)"><i class="material-icons">create</i><span></span></a>
                @if($auth->rol == 1)
                <a class="" href="#" onclick="deleteAttendance(<?php echo json_encode($value->id); ?>)"><i class="material-icons">delete</i><span></span></a>
                @endif
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
<!-- Modal to continue with action -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirmar Acción</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Desea eliminar la atención? <b>(No se podrá recuperar)</b>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="continueBtn">Continuar</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        // Run code
        var test = <?php echo json_encode($patientAttendances); ?>;
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

    function redirectEdit(id){
        var DNI = <?php echo json_encode($patient->DNI); ?>;
        var stage = <?php echo json_encode($stage->id); ?>;
        window.location.href = `/paciente/${DNI}/etapa/${stage}/atencion/${id}/edit`;
    }

    function deleteAttendance(id){
        // Show modal
        $('#confirmModal').modal('show');
        // Get continue button from modal
        var btn = document.getElementById('continueBtn');
        // When is clicked
        btn.addEventListener("click", function() {
            // Get hidded input for submit
            var tagID = document.getElementById("id_attendance");
            // Set value with id
            tagID.value = id;
            // Submit the data
            document.onSubmitDelete.submit();
        });
    }
</script>
@endsection