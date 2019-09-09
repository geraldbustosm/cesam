@extends('layouts.main')
@section('title','Funcionarios')
@section('active-funcionarios','active')
@section('active-funcionariosinactivos','active')
@section('content')

<h1>Funcionarios</h1>

<div>
  <input class="form-control" id="searchbox" type="text" placeholder="Búsqueda por rut">
</div>
<div class="div-full">
  @if (session('status'))
  <div class="alert alert-success" role="alert">
    {{ session('status') }}
  </div>
  @endif
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th style="width: 3%;">#</th>
      <th style="width: 15%;">Usuario</th>
      <th style="width: 20%;">Nombre</th>
      <th style="width: 10%;">Profesion</th>
      <th style="width: 30%">Especialidades</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody id="table-body">
    <!-- Fill on js -->
  </tbody>
</table>
<!-- Submit section -->
<form name="onSubmit" method="post" action="{{ url('funcionarios/inactivos') }}">
  @csrf
  <div class="form-group">
    <input type="hidden" class="form-control {{ $errors->has('DNI') ? ' is-invalid' : '' }}" value="{{ old('DNI') }}" id="DNI" name="DNI">
  </div>
</form>
<!-- End submit section -->
<div class="div-full">
  <ul class="pagination justify-content-center" id="paginate">
    <!-- Generate in patientFilter.js->generatePaginationNum(); -->
  </ul>
</div>

<!-- Modal for confirm action -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirmar acción</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>¿Desea activar este funcionario de nuevo?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="continueBtn">Continuar</button>
      </div>
    </div>
  </div>
</div>

<script>
  var fullArray = <?php echo json_encode($functionary); ?>;
  var userArr = <?php echo json_encode($user); ?>;
  var specialityArr = <?php echo json_encode($speciality); ?>;
  var fsArr = <?php echo json_encode($fs); ?>;
  document.getElementById('functionarys_Submenu').className += ' show';
</script>

<!-- Adding script using on this view -->
<script type="text/javascript" src="{{asset('js/pagination.js')}}"></script>
<script type="text/javascript" src="{{asset('js/actionButtons.js')}}"></script>
<script type="text/javascript" src="{{asset('js/functionarysFilter.js')}}"></script>
@endsection