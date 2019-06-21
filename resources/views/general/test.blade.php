@extends('layouts.main')
@section('title','Pacientes')
@section('active-testing','active')
@section('content')

<div class="container" style="margin: 0; padding: 0;" id="target">
  <div class="row">
    <div class="col">
      <h1>Test Page</h1>
    </div>
    <div class="col" style="max-width: 30%;">
      <input class="form-control" id="searchbox" type="text" placeholder="Búsqueda por rut">
    </div>
  </div>
  <div class="row">
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
        <!-- Acá se rellenará con filas desde javascript -->
      </tbody>
    </table>
    <button type="submit" class="btn btn-outline-primary" id="export">Exportar en formato PDF</button>
  </div>
</div>

<!-- Modal (Inicialmente invisible)-->
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

<!-- Añadiendo script que solo se utiliza en esta vista -->
<script src="{{asset('js/patientFilter.js')}}"></script>
<script src="{{asset('js/pdfExport.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>

<!-- Demostración de 'bypass' (parece funcionar con div's)
<div class="details" id="render_me">
  elements
  etc
  <div id="versionhistory">
    details not wanted in the PDF
  </div>
</div>
<button onclick="javascript:printMotion()">PDF</button>

<script>
  printMotion = function () {
          var doc = new jsPDF('p', 'pt', 'letter', true);

          var specialElementHandlers = {
            '#versionhistory': function(element, renderer){
               console.log("test");
               return true;
            }
          };

          doc.fromHTML($('#render_me').get(0), 15, 15, {
            'width': 500,
              'elementHandlers': specialElementHandlers
          });

          doc.save('thisMotion.pdf');
        }
</script>
-->

@endsection