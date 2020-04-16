@extends('layouts.main')
@section('title','Fichas')
@section('active-prestaciones','active')

@section('content')
<div class="div-full">
    <div class="row">
        <div class="col-16">
            <h1>Ficha Paciente</h1>
        </div>
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
        <input type="hidden" class="form-control {{ $errors->has('id') ? ' is-invalid' : '' }}" value="{{ old('id') }}" id="id" name="id">
    </form>
    <form name="onSubmitAttendance" method="post" action="{{ url('registrar/atencion') }}">
        @csrf
        <input type="hidden" class="form-control {{ $errors->has('DNI_stage') ? ' is-invalid' : '' }}" value="{{ old('DNI_stage') }}" id="DNI_stage" name="DNI_stage">
    </form>
    <form name="onSubmitPCI" method="post" action="{{ url('pci-etapa') }}">
        @csrf
        <input type="hidden" class="form-control {{ $errors->has('patient_stage') ? ' is-invalid' : '' }}" value="{{ old('patient_stage') }}" id="patient_stage" name="patient_stage">
        <input type="hidden" class="form-control {{ $errors->has('pci') ? ' is-invalid' : '' }}" value="{{ old('pci') }}" id="pci" name="pci">
        <input type="hidden" class="form-control {{ $errors->has('id_stage') ? ' is-invalid' : '' }}" value="{{ old('id_stage') }}" id="id_stage" name="id_stage">
    </form>
    <form name="onSubmitDelete" method="post" action="{{ url('eliminar-atención') }}">
        @csrf
        <input type="hidden" class="form-control {{ $errors->has('id_attendance') ? ' is-invalid' : '' }}" value="{{ old('id_attendance') }}" id="id_attendance" name="id_attendance">
    </form>
</div>

<div class="div-full jumbotron jumbotron-fluid" style="padding: 10px 25px;">
    <h5><b>Paciente:</b> {{ $patient->nombre1 }} {{ $patient->apellido1 }} {{ $patient->apellido2 }}</h5>
    <h5 class="mb-2"><b>Rut:</b> {{ $patient->DNI }}</h5>
    <h5><b>Medico a cargo:</b> {{$stage->functionary->user->primer_nombre}} {{$stage->functionary->user->apellido_paterno}} {{$stage->functionary->user->apellido_materno}}</h5>
    <p><b>Diagnósticos:</b> {{ $diagnosis }}
        <br><b>PCI:</b> {{ $stage->PCI }}
        <br><b>Atributos: </b>{{ $attributes }}
    </p>
</div>
<div class="div-full">
    @if($stage->activa == 1)
    <button type="button" class="btn btn-primary mb-2" id="addAttendance">Añadir prestación</button>
    <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#pciModal"> PCI </button>
    <a href="/alta/{{$patient->DNI}}" class="btn btn-primary mb-2" role="button">Dar alta</a>
    @endif
    @if(Auth::user()->rol == 1)
    <a href="/paciente-atributos/{{$patient->DNI}}" class="btn btn-primary mb-2" role="button" style="margin-left: 40px;">Editar atributos</a>
    <a href="/etapas/edit/{{$stage->id}}" class="btn btn-primary mb-2" role="button">Editar diagnósticos</a>
    <a href="/cambiar-medico/{{$patient->DNI}}" class="btn btn-primary mb-2" role="button">Cambiar medico a cargo</a>
    @endif
</div>
<div class="div-full">
    @php
    $stageCount = $stage->attendance->count();
    @endphp
    @foreach($patientAttendances as $value)
    <div class="card">
        <div class="card-header">
            <div><b> Prestación #{{$stageCount}} </b></div>
            <div>
                <a href="/paciente/{{$patient->DNI}}/etapa/{{$stage->id}}/atencion/{{$value->id}}/edit"><i class="material-icons">create</i><span></span></a>
                @if($auth->rol == 1)
                <a class="" href="#" onclick="deleteAttendance(<?php echo json_encode($value->id); ?>)"><i class="material-icons">delete</i><span></span></a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle mb-2 text-muted">Profesional: {{ $value->functionary->user->primer_nombre }}, {{ $value->functionary->profesion}}</h6>
            <h6 class="card-subtitle mb-4 text-muted">Fecha: {{ $value->fecha }}</h6>
            <h6 class="card-subtitle mb-2 text-muted">Glosa Trazadora: {{ $value->provision->glosaTrasadora }}</h6>
            <h6 class="card-subtitle mb-2 text-muted">Atención: {{ $value->fecha }}</h6>
            <h6 class="card-subtitle mb-2 text-muted">Observaciones: {{ $value->fecha }}</h6>
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

<!-- Modal to continue with PCI date -->
<div class="modal fade" id="pciModal" tabindex="-1" role="dialog" aria-labelledby="pciModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pciModalLabel">Cambiar la fecha del PCI</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="datepicker" name="datepicker" placeholder="Nueva fecha PCI" required>
                <script>
                    var config = {
                        format: 'dd/mm/yyyy',
                        locale: 'es-es',
                        uiLibrary: 'bootstrap4',
                        startView: 3,
                    };
                    $('#datepicker').datepicker(config);
                </script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnPCI" onclick="addPCI();">Continuar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var patient_id = <?php echo json_encode($patient->id); ?>;
    var patient_dni = <?php echo json_encode($patient->DNI); ?>;
    var curr_stage_id = <?php echo json_encode($stage->id); ?>;

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
                        if (value.id != stage_id) {
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
        document.getElementById('id').value = patient_id;
        document.onSubmit.submit();
    });

    $('#addAttendance').on('click', function() {
        document.getElementById('DNI_stage').value = patient_id;
        document.onSubmitAttendance.submit();
    });

    function addPCI() {
        var pci = document.getElementById("datepicker").value;
        if (pci) {
            document.getElementById('btnPCI').disabled = true;
            document.getElementById('pci').value = pci;
            document.getElementById('patient_stage').value = patient_dni;
            document.getElementById('id_stage').value = curr_stage_id;
            document.onSubmitPCI.submit();
        } else {
            Swal.fire('Error', `Seleccione una fecha`, 'error');
        }
    };

    function deleteAttendance(id) {
        // Show modal
        $('#confirmModal').modal('show');
        // Get continue button from modal
        var btn = document.getElementById('continueBtn');
        // When is clicked
        btn.addEventListener("click", function() {
            // Get hidded input for submit and set value with id
            document.getElementById("id_attendance").value = id;
            // Submit the data
            document.onSubmitDelete.submit();
        });
    }
</script>
@endsection