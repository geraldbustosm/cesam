@extends('layouts.main')
@section('title','Fichas')
@section('active-prestaciones','active')

@section('content')
<h1>Registrar alta</h1>
<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <div class="alert alert-danger collapse" role="alert" name="error" id="error" style="min-width:200px">
        Debe seleccionar un alta!
    </div>
    <form name="onSubmit" method="post" action="{{ url('alta') }}">
        @csrf
        <div class="form-group">
            <select name="releases" id="releases" class="form-control">
                <option selected disabled>Seleccione el alta</option>
                @foreach($release as $value)
                <option value="{{ $value->id }}">{{ $value->descripcion }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <input type="hidden" class="form-control {{ $errors->has('DNI') ? ' is-invalid' : '' }}" value="{{ old('DNI') }}" id="DNI" name="DNI">
        </div>

        <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#confirmModal">Dar alta</button>
    </form>
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
                ¿Desea dar de alta al paciente?
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
        var tagDNI = document.getElementById('DNI');
        tagDNI.value = <?php echo json_encode($DNI); ?>;
    });

    $('#continueBtn').on('click', function() {
        if ($("#releases option:selected").text().includes("Seleccione")) {
            $('#confirmModal').modal('hide');
            $('#error').show();
            var count = 1;
            clearInterval(timer);
            // Start count of 7 sec
            var timer = setInterval(function() {
                count--;
                if (count == 0) {
                    // Hide alert
                    $('#error').hide();
                }
                // 7000 = 7 sec
            }, 7000);
        } else {
            document.onSubmit.submit();
        }
    });
</script>
@endsection