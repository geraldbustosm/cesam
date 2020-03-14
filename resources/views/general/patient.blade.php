@extends('layouts.main')
@section('title','Pacientes')
@section('active-pacientes','active')
@section('active-pacientesactivos','active')
@section('content')

<h1>Pacientes</h1>

<div>
  <input class="form-control" id="searchbox" type="text" placeholder="Búsqueda por rut">
</div>
<div class="div-full">
  @if (session('status'))
  <div class="alert alert-success" role="alert">
    {{ session('status') }}
  </div>
  @endif
  @if (session('error'))
  <div class="alert alert-danger" role="alert">
    {{ session('error') }}
  </div>
  @endif
</div>

<!-- Select max records per page of pagination -->
<div class="form-group">
  <!-- Generate numbers -->
  <select class="form-control" id="elements" onchange='javascript:changeTotalRecords()' required>
    <option value="0" selected disabled>Seleccione elementos por página</option>
    @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}">{{ $i }}</option>
      @endfor
      <option value="{{ $cantPatients }}">Todos</option>
  </select>
</div>

<!-- Table with patients -->
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Identificación</th>
      <th>Nombre</th>
      <th>Sexo</th>
      <th>Edad</th>
      <th>Previsión</th>
      <th style="width:15%">Acciones</th>
    </tr>
  </thead>
  <tbody id="table-body">
    <!-- Fill on js -->
  </tbody>
</table>

<!-- Submit section -->
<form name="onSubmit" method="post" action="{{ url('pacientes') }}">
  @csrf
  <div class="form-group">
    <input type="hidden" class="form-control {{ $errors->has('id') ? ' is-invalid' : '' }}" value="{{ old('id') }}" id="id" name="id">
  </div>
</form>
<form name="onSubmitStage" method="post" action= "{{ url('registrar/atencion') }}">
  @csrf
  <div class="form-group">
    <input type="hidden" class="form-control {{ $errors->has('DNI_stage') ? ' is-invalid' : '' }}" value="{{ old('DNI_stage') }}" id="DNI_stage" name="DNI_stage">
  </div>
</form>
<!-- End submit section -->

<!-- Pagination -->
<div class="div-full">
  <ul class="pagination justify-content-center" id="paginate">
    <!-- Generate in patientFilter.js->generatePaginationNum(); -->
  </ul>
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
        ¿Desea eliminar al paciente?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="continueBtn">Continuar</button>
      </div>
    </div>
  </div>
</div>

<script>
  var fullArray = <?php echo json_encode($patients); ?>;
  document.getElementById('patients_Submenu').className += ' show';
</script>

<!-- Adding script using on this view -->
<script type="text/javascript" src="{{asset('js/pagination.js')}}"></script>
<script type="text/javascript" src="{{asset('js/actionButtons.js')}}"></script>
<script type="text/javascript" src="{{asset('js/tablePatients.js')}}"></script>
@endsection