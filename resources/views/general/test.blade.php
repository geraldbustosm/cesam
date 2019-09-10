
@extends('layouts.main')
@section('title','Registrar nueva atención')
@section('active-ingresaratencion','active')


@section('content')
<h1>Registrar Atencion</h1>
<div class="div-full">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <script type="text/javascript" src="http://oss.sheetjs.com/js-xlsx/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.5/jspdf.plugin.autotable.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <form method="post" >
        @csrf
        <div class="box red"></div>
        <div class="table-controls-legend">Descargar Información</div>
        <div class="table-controls">
            <button id="download-csv">Descargar CSV</button>
            <button id="download-json">Descargar JSON</button>
            <button id="download-xlsx">Descargar XLSX</button>
            <button id="download-pdf">Descargar PDF</button>
        </div>
        <div id="example-table">
            <script type="text/javascript">
            //create Tabulator on DOM element with id "example-table"
            var table = new Tabulator("#example-table", {
                height:"311px",
                columns:[
                {title:"Nombre", field:"nombre1"},
                {title:"Apellido", field:"apellido1"},
                ],
            });

            //define some sample data
            var tabledata = {!! $main !!};

            //load sample data into the table
            table.setData(tabledata);

            //trigger download of data.csv file
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
        </div>
    </form>

@endsection
@push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
