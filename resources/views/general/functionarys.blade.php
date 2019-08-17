@extends('layouts.main')
@section('title','Funcionarios')
@section('active-funcionarios','active')
@section('active-funcionariosactivos','active')
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

<form name="onSubmit" method="post" action="{{ url('pacientes') }}">
  @csrf
  <div class="form-group">
    <input type="hidden" class="form-control {{ $errors->has('DNI') ? ' is-invalid' : '' }}" value="{{ old('DNI') }}" id="DNI" name="DNI">
  </div>
</form>

<div class="div-full">
  <ul class="pagination justify-content-center" id="paginate">
    <!-- Generate in patientFilter.js->generatePaginationNum(); -->
  </ul>
</div>

<script>
  var functionaryArr = <?php echo json_encode($functionary); ?>;
  var userArr = <?php echo json_encode($user); ?>;
  var specialityArr = <?php echo json_encode($speciality); ?>;
  var fsArr = <?php echo json_encode($fs); ?>;
</script>

<!-- Adding script using on this view -->
<script type="text/javascript" src="{{asset('js/functionarysFilter.js')}}"></script>
@endsection