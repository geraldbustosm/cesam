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
      <th class="column-width" scope="col">First</th>
      <th class="column-width" scope="col">Last</th>
      <th class="column-width" scope="col">Handle</th>
      <th class="column-width" scope="col">Descripcion</th>
      <th class="column-width" scope="col">Acciones</th>
    </tr>
  </thead>
  <tbody id="table-body">
    <!-- Fill on js -->
  </tbody>
</table>

<ul class="pagination justify-content-end" id="paginate"> 
  <!-- Generate in js->generatePaginationNum(); -->
</ul>

<script>
  var patientsArr = <?php echo json_encode($patients); ?>;
</script>

<!-- Adding script using on this view -->
<script src="{{asset('js/jquery-3.4.0.min.js')}}"></script>
<!-- <script src="{{asset('js/patientSearch.js')}}"></script> -->
<script src="{{asset('js/patientFilter.js')}}"></script>
@endsection