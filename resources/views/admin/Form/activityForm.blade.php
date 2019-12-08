@extends('admin.Views.registerMain')
@section('title','Registrar actividad')
@section('active-registrar','active')
@section('active-ingresardatos','active')

@section('sub-content')
<style>
.custom-control-label::before, 
.custom-control-label::after {
    left: -1.8rem;
    width: 1.75rem;
    height: 1.75rem;
}

</style>
<h1>Registrar Actividades</h1>
<div class="div-full">
    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        {{ $error }}
        @endforeach
    </div>
    @endif
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    @if (session('err'))
    <div class="alert alert-danger" role="alert">
        {{ session('err') }}
    </div>
    @endif
    <form method="post" action="{{ url('registrar/actividad') }}">
        @csrf
        <div class="form-group">
            <div class="form-row">
                <div class="col-6">
                    <input type="text" class="form-control {{ $errors->has('activity') ? ' is-invalid' : '' }}" value="{{ old('activity') }}" id="activity" name="activity" placeholder="Tipo de Actividad">
                    <br>
                    
                    <div class="custom-control custom-checkbox checkbox-xl">
                        <input type="checkbox" class="custom-control-input" name="openCanasta" id="openCanasta" value="1" >
                        <label style="font-weight: bold;   font-size: 110%; "class="custom-control-label" for="openCanasta">&nbsp; ¿actividad abre canasta?</label>
                    </div>
                    <br>

                    <div class="custom-control custom-checkbox checkbox-xl">
                        <input type="checkbox" class="custom-control-input" name="noAssist" id="noAssist" value="1" >
                        <label style="font-weight: bold;   font-size: 110%; "class="custom-control-label" for="noAssist">&nbsp; ¿actividad sin asistencia? (informes)</label>
                    </div>
                    <br><br>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                    <br><br>
                </div>
                <div class="col">
                    <div>
                        <input class="form-control" id="searchbox" type="text" placeholder="Búsqueda...">
                    </div><br>
                    <div class="">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 3%;">#</th>
                                    <th style="width: 70%;">Actividades</th>
                                    <th style="width: 10%;">Canasta</th>
                                    <th style="width: 10%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <!-- Fill on js -->
                            </tbody>
                        </table>
                    </div>
                    <div class="div-full">
                        <ul class="pagination justify-content-center" id="paginate">
                            <!-- Generate in patientFilter.js->generatePaginationNum(); -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Form to send id at controller -->
<form name="onSubmit" method="post" action="{{ url('desactivar-actividad') }}">
    @csrf
    <div class="form-group">
        <input type="hidden" class="form-control {{ $errors->has('id') ? ' is-invalid' : '' }}" value="{{ old('id') }}" id="id" name="id">
    </div>
</form>
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
                ¿Desea eliminar la actividad?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="continueBtn">Continuar</button>
            </div>
        </div>
    </div>
</div>
<!-- Getting data -->
<script>
    var fullArray = <?php echo json_encode($data); ?>;
    var table = <?php echo json_encode($table); ?>;
    document.getElementById('data_Submenu').className += ' show';
</script>
@endsection