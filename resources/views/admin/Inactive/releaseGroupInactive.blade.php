@extends('admin.Views.inactiveMain')
@section('title','Grupo de Altas Inactivas')
@section('active-ingresardatos','active')
@section('active-inactivos','active')

@section('sub-content')
<h1>Grupo de Altas Inactivas</h1>
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

    <div class="col">
        <div>
            <input class="form-control" id="searchbox" type="text" placeholder="Búsqueda...">
        </div><br>
        <div class="">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 3%;">#</th>
                        <th style="width: 70%;">Grupo de Altas</th>
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
<!-- Form to send id at controller -->
<form name="onSubmit" method="post" action="{{ url('activar-grupo-altas') }}">
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
                ¿Desea reactivar el grupo?
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