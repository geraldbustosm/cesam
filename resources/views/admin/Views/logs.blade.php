@extends('layouts.main')
@section('title','Registros de sistema')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="div-full">
  <h1>Logs</h1>
  <hr>
</div>

<div class="div-full" id="example-table"></div>

<script src="{{ mix('js/app.js') }}"></script>
<script>
  //Getting data
  var tableData = <?php echo json_encode($data); ?>;
  console.log(tableData)
  // Write data for download
  var table = new Tabulator("#example-table", {
    layout:"fitColumns",
    resizableRows:true,
    data: tableData,
    //autoColumns: true,
    columns:[
      {title:"ID", field:"id", width: 70},
      {title:"Fecha", field:"fecha", width: 250},
      {title:"Usuario ID", field:"user_id", width: 200},
      {title:"Descripción", field:"descripcion" ,formatter:"textarea"}
    ],
  });
</script>
@endsection
@push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">