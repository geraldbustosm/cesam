@extends('layouts.main')
@section('title','Pacientes Inactivos')
@section('active-pacientes','active')
@section('active-pacientesinactivos','active')
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
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th style="width: 3%;">#</th>
      <th style="width: 15%;">Identificación</th>
      <th style="width: 40%;">Nombre</th>
      <th style="width: 10%;">Sexo</th>
      <th style="width: 10%;">Edad</th>
      <th style="width: 10%">Previsión</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody id="table-body">
    <!-- Fill on js -->
  </tbody>
</table>

<form name="onSubmit" method="post" action="{{ url('pacientesinactivos') }}">
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
  var patientsArr = <?php echo json_encode($patients); ?>;
  var prevArr = <?php echo json_encode($prev); ?>;
  var sexArr = <?php echo json_encode($sex); ?>;
</script>

<!-- Adding script using on this view -->
<script type="text/javascript" src="{{asset('js/patientFilter.js')}}"></script>
@endsection