@extends('layouts.main')
@section('title','Prestaciones mensuales')
@section('active-prestaciones','active')
@section('active-mensual','active')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<h1>Despliegue de Infromación</h1>

<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <!-- Adding script using on this view -->
    <script type="text/javascript" src="http://oss.sheetjs.com/js-xlsx/xlsx.full.min.js"></script>
    <script src="{{asset('js/jspdf.min.js')}}"></script>
    <script src="{{asset('js/jspdf.plugin.autotable.js')}}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <div>
        @csrf
        <!-- Buttons for download table -->
        <div class="box red"></div>
        <div class="table-controls">
            <button class="btn btn-primary" id="download-xlsx">Descargar XLSX</button>
            <button class="btn btn-primary" id="download-pdf">Descargar PDF</button>
        </div>
        <br>
        <div class="table-controls-legend">
            <h3>Parametros para filtrar</h3>
        </div>
        <!-- Select parameters for filter -->
        <div class="table-controls">
            <span>
                <label>Columna: </label>
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
                    <option value="!=">distinto</option>
                    <option value="like">igual</option>
                </select>
            </span>
            <!-- Value sought -->
            <span><label>Valor: </label> <input id="filter-value" type="text" placeholder="valor a filtrar"></span>
            <!-- Clean filters -->
            <button id="filter-clear">Limpiar Filtro</button>

        </div>
        <div id="example-table"></div>
        <script type="text/javascript">
            //Trigger setFilter function with correct parameters
            function updateFilter() {

                var filter = $("#filter-field").val() == "function" ? customFilter : $("#filter-field").val();

                if ($("#filter-field").val() == "function") {
                    $("#filter-type").prop("disabled", true);
                    $("#filter-value").prop("disabled", true);
                } else {
                    $("#filter-type").prop("disabled", false);
                    $("#filter-value").prop("disabled", false);
                }

                table.setFilter(filter, $("#filter-type").val(), $("#filter-value").val());
            }
            //Update filters on value change
            $("#filter-field, #filter-type").change(updateFilter);
            $("#filter-value").keyup(updateFilter);

            //Clear filters on "Clear Filters" button click
            $("#filter-clear").click(function() {
                $("#filter-field").val("");
                $("#filter-type").val("=");
                $("#filter-value").val("");

                table.clearFilter();
            });

            //create Tabulator on DOM element with id "example-table"
            var table = new Tabulator("#example-table", {
                height: "311px",
                movableColumns: true,
                columns: [
                    {title:"Programa", field:"programa"},
                    {title:"Procedencia", field:"procedencia"},
                    {title:"Fecha Nacimiento", field:"fecha_nacimiento"},
                    {title:"Edad", field:"edad"},
                    {title:"Sexo", field:"sexo"},
                    {title:"Nombre", field:"nombre1"},
                    {title:"Apellido Paterno", field:"apellido1"},
                    {title:"Apellido Materno", field:"apellido2"},
                    {title:"Abre Canasta", field:"canasta"},
                    {title:"Fecha Atencion", field:"fecha"},
                    {title:"RUT", field:"DNI"},
                    {title:"Prestación", field:"codigo"},
                    {title:"Glosa trazadora", field:"glosaTrasadora"},
                    {title:"Tipo", field:"tipo"},
                    {title:"PS-FAM", field:"ps_fam"},
                    {title:"Especialidad de prestación", field:""},
                    {title:"Actividad", field:"actividad"},
                    {title:"Tipo de usuario", field:""},
                    {title:"Asistencia", field:"asistencia"},
                    {title:"# atención mensual", field:""},
                    {title:"Funcionario", field:"nombre_funcionario"},
                    {title:"Especialidad del funcionario", field:"descripcion"},
                ],
            });

            //define some sample data
            var tabledata = {!! $main !!};

            //load sample data into the table
            table.setData(tabledata);

            //trigger download of data.xlsx file
            $("#download-xlsx").click(function() {
                table.download("xlsx", "data.xlsx", {
                    sheetName: "Reporte"
                });
            });

            //trigger download of data.pdf file
            $("#download-pdf").click(function() {
                table.download("pdf", "data.pdf", {
                    orientation: "landscape", //set page orientation (portrait or landscape)
                    title: "Reporte", //add title to report
                    format: "legal"
                });
            });

            document.getElementById('records_Submenu').className += ' show';
        </script>
    </div>
@endsection
@push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">