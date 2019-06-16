@extends('layouts.main')
@section('title','Pacientes')
@section('active-pacientes','active')
@section('content')
<h1>Pacientes</h1>
<div>
  <input class="form-control" id="searchbox" type="text" placeholder="Búsqueda por rut">
</div>
<div>
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
        <!-- Filling cells from javascript -->
    </tbody>
  </table>
</div>

<!-- Modal (Hidden at begin)-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      ...
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary">Save changes</button>
    </div>
  </div>
</div>

<!-- Simulando la query recibida por la vista con el metodo with utilizado
en el controlador -->
<?php
  $pacientes = array(
          array("1", "Jacob", "Thorton", "@fat", "Male"),
          array("12", "Larry", "Bird", "@thin", "Male"),
          array("13", "Mandiola", "Reggati", "@dils", "Female"),
          array("123", "Carla", "Faund", "@fest", "Female"),
          array("124", "dd", "Faundsd", "@afest", "sFemale"),
          array("125", "Larry", "Bird", "@thin", "Male")
  );
?>

<!-- Pasando la data de pacientes a javascript -->
<script type="text/javascript">
  var pacientes = <?php echo json_encode($pacientes); ?>;
</script>

<!-- Adding script using on this view -->
<script src="{{asset('js/patientFilter.js')}}"></script>
@endsection