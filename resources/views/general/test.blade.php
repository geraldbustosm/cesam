
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
        <div class="table-controls-legend">Parametros para filtrar</div>

        <div class="table-controls">
            <span>
            <label>Field: </label>
            <select id="filter-field">
                <option></option>
                <option value="nombre1">Nombre</option>
                <option value="apellido1">Apellido</option>
                
            </select>
            </span>

            <span>
            <label>Tipo: </label>
            <select id="filter-type">
                <option value="=">=</option>
                <option value="<">&lt;</option>
                <option value="<=">&lt;=</option>
                <option value=">">&gt;</option>
                <option value=">=">&gt;=</option>
                <option value="distinto">!=</option>
                <option value="igual">like</option>
            </select>
            </span>

            <span><label>Valor: </label> <input id="filter-value" type="text" placeholder="valor a filtrar"></span>

            <button id="filter-clear">Limpiar Filtro</button>
        </div>
        <div id="example-table"></div>
            <script type="text/javascript">
            //Trigger setFilter function with correct parameters
            function updateFilter(){

            var filter = $("#filter-field").val() == "function" ? customFilter : $("#filter-field").val();

            if($("#filter-field").val() == "function" ){
                $("#filter-type").prop("disabled", true);
                $("#filter-value").prop("disabled", true);
            }else{
                $("#filter-type").prop("disabled", false);
                $("#filter-value").prop("disabled", false);
            }

            table.setFilter(filter, $("#filter-type").val(), $("#filter-value").val());
            }
            //Update filters on value change
            $("#filter-field, #filter-type").change(updateFilter);
            $("#filter-value").keyup(updateFilter);

            //Clear filters on "Clear Filters" button click
            $("#filter-clear").click(function(){
                $("#filter-field").val("");
                $("#filter-type").val("=");
                $("#filter-value").val("");

                table.clearFilter();
            });


            //create Tabulator on DOM element with id "example-table"
            var table = new Tabulator("#example-table", {
                height:"311px",
                movableColumns: true,
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
        
    </form>

@endsection
@push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
