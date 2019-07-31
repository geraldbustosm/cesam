@extends('layouts.main')
@section('title','Pacientes')
@section('active-pacientes','active')
@section('content')

<h1>Pacientes</h1>
<div>
  <input class="form-control" id="searchbox" type="text" placeholder="Búsqueda por rut">
</div>

<table class="table table-striped">
  <thead>
    <tr>
      <th class="column-width">#</th>
      <th class="column-width" scope="col">Identificación</th>
      <th class="column-width" scope="col">Nombre</th>
      <th class="column-width" scope="col">Sexo</th>
      <th class="column-width" scope="col">Edad</th>
      <th class="column-width" scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody id="table-body">
    <!-- Fill on js -->
  </tbody>
</table>

<div class="div-full">
  <ul class="pagination justify-content-center" id="paginate"> 
    <!-- Generate in patientFilter.js->generatePaginationNum(); -->
  </ul>
</div>

<script>
  var patientsArr = <?php echo json_encode($patients); ?>;
</script>

<!-- Adding script using on this view -->
<script src="{{asset('js/patientFilter.js')}}"></script>
@endsection