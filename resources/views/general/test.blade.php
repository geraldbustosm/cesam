@extends('layouts.main')
@section('title','Pacientes')
@section('active-pacientes','active')
@section('active-pacientesactivos','active')
@section('content')
<!DOCTYPE html>
<html>
<head>
<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/2.11.0/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/2.11.0/tabulator.min.js"></script>
<script type="text/javascript" src="http://oss.sheetjs.com/js-xlsx/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.5/jspdf.plugin.autotable.js"></script>
<script src="{{ ('js/app.js') }}"></script>
<link rel="stylesheet" href="{{('css/app.css')}}">
<meta name="csrf-token" content="{{ csrf_token() }}"> 
</head>

<body>


<div class="table-controls"> 
  <button id = "download-csv">Descargar CSV</button>
  <button id = "download-json">Descargar JSON</button>
  <button id = "download-xlsx">Descargar XLSX (Excel)</button>
  <button id = "download-pdf">Descargar PDF</button>
</div>
<div id="example-table"></div>

<script type="text/javascript">
  
  var tabledata1 = {!! $main !!};
  document.write(tabledata1);
  var table1 = new Tabulator("#example-table", {
        data: tabledata1,
        autoColumns:true,
        layout:"fitDataFill",
        movableColumns:true,
        selectable:true,
        clipboard:true,
    });
$("#download-csv").click(function(){
    table.download("csv", "data.csv");
});

//trigger download of data.json file
$("#download-json").click(function(){
    table.download("json", "data.json");
});

//trigger download of data.xlsx file
$("#download-xlsx").click(function(){
    table.download("xlsx", "data.xlsx", {sheetName:"My Data"});
});

//trigger download of data.pdf file
$("#download-pdf").click(function(){
    table.download("pdf", "data.pdf", {
        orientation:"portrait", //set page orientation to portrait
        title:"Example Report", //add title to report
    });
});
</script>
</body>

</html> 
@endsection