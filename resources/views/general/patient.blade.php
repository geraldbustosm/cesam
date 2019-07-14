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
      <!-- Filling cells from javascript -->
  </tbody>
  <div>
    <ul class="pagination pagination-sm justify-content-center" id="maxPages">
      <!-- Filling list from javascript -->
    </ul>
  </div>
</table>

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
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      <button type="button" class="btn btn-primary">Guardar</button>
    </div>
  </div>
</div>

<!-- Pasando la data de pacientes a javascript -->
<script type="text/javascript">
  var pacientes = <?php echo json_encode($patients->items()); ?>;
  var object = <?php echo json_encode($patients); ?>;
</script>

<!-- Adding script using on this view -->
<script src="{{asset('js/patientFilter.js')}}"></script>
@endsection