@extends('layouts.main')
@section('title','Ingresos mensuales')
@section('active-prestaciones','active')
@section('active-ingreso','active')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="div-full row">
    <div class="col">
        <h1>Despliegue de Información
            <a href="#" id="download-xlsx" style="padding: 5px;"><i title='Descargar tabla' class="material-icons">get_app</i></a>
            <a href="#" onclick="redirectREM()"><i title='Ver REM 5' class="material-icons">forward</i></a>
        </h1>
    </div>
    <form class="float-left" name="onSubmit" action="{{ url('prestaciones/ingresos') }}">
        <div class="form-row align-items-center">
            <div class="col-auto my-1">
                <label class="col-sm-2 col-form-label" for="year">Año</label>
            </div>
            <div class="col-auto my-1">
                <select class="custom-select mr-sm-2" name="year" id="year"></select>
            </div>
            <div class="col-auto my-1">
                <label for="month">Mes</label>
            </div>
            <div class="col-auto my-1">
                <select class="custom-select mr-sm-2" name="month" id="month"></select>
            </div>
        </div>
    </form>
</div>

<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <!-- Adding script using on this view -->
    <script src="{{asset('js/xlsx.full.min.js')}}"></script>
    <script src="{{asset('js/redirectRecords.js')}}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <div>
        @csrf
        <div class="table-controls-legend">
            <h3>Parametros para filtrar</h3>
        </div>
        <!-- Select parameters for filter -->
        <div class="table-controls form-row">
        <div class="form-group col-md-4 warpper">
                <select class="form-control" id="filter-field" onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                    <option selected>Columna</option>
                    <option value="DNI">Rut</option>
                    <option value="nombre1">Nombre</option>
                    <option value="apellido1">Apellido paterno</option>
                    <option value="apellido2">Apellido materno</option>
                    <option value="fecha_nacimiento">Fecha nacimiento</option>
                    <option value="fecha_ingreso">Fecha ingreso</option>
                    <option value="edad">Edad</option>
                    <option value="sexo">Sexo</option>
                    <option value="procedencia">Procedencia</option>
                    <option value="prevision">Previsión</option>
                    <option value="ges">GES</option>
                    <option value="sigges">SIGGES</option>
                    <option value="diagnostico">Diagnóstico</option>
                    <option value="medico">Médico</option>
                </select>
            </div>

            <div class="form-group col-md-2">
                <select class="form-control" id="filter-type">
                    <option selected>Tipo</option>
                    <option value="=">=</option>
                    <option value="<=">&lt;=</option>
                    <option value=">=">&gt;=</option>
                    <option value="like">igual (texto)</option>
                </select>
            </div>
            <!-- Value sought -->
            <div class="form-group col-md-4"><input class="form-control" id="filter-value" type="text" placeholder="Valor a filtrar"></div>
            <!-- Clean filters -->
            <a href="#" id="filter-clear" style="padding: 5px;"><i title='Restablecer valores' class="material-icons">highlight_off</i><span></span></a>
        </div>

        <div id="example-table"></div>
        <script type="text/javascript">
            document.getElementById('records_Submenu').className += ' show';
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
                $("#filter-field").val("Columna");
                $("#filter-type").val("Tipo");
                $("#filter-value").val("");

                table.clearFilter();
            });
            
            //define some sample data
            var tableData = <?php echo json_encode($data); ?>;
            var list = <?php echo json_encode($list); ?>;
            var currDate = <?php echo json_encode($date); ?>;

            //create Tabulator on DOM element with id "example-table"
            var table = new Tabulator("#example-table", {
                height: "420px",
                data: tableData,
                movableColumns: true,
                columns: [
                    {title:"# Ficha", field:"numero_ficha"},
                    {title:"Rut", field:"DNI"},
                    {title:"Nombre", field:"nombre1"},
                    {title:"Apellido paterno", field:"apellido1"},
                    {title:"Apellido materno", field:"apellido2"},
                    {title:"Fecha nacimiento", field:"fecha_nacimiento"},
                    {title:"Edad", field:"edad"},
                    {title:"Sexo", field:"sexo"},
                    {title:"Procedencia", field:"procedencia"},
                    {title:"Fecha ingreso", field:"fecha_ingreso"},
                    {title:"Previsión", field:"prevision"},
                    {title:"GES", field:"ges"},
                    {title:"SIGGES", field:"sigges"},
                    {title:"Dirección", field:"direccion"},
                    {title:"SENAME", field:"SENAME"},
                    {title:"Médico", field:"medico"},
                ],
            });

            // Complete table
            for(i=0 ; i<list.length ; i++){
                table.addColumn({ title:`Diagnóstico ${i+1}`, field:`diagnostico_${i}`, width:150}, false);
            };

            //trigger redirect to REM view
            function redirectREM() {
                window.location = "/prestaciones/ingresos/info"
            };

            //trigger download of data.xlsx file
            $("#download-xlsx").click(function() {
                table.download("xlsx", "data.xlsx", {
                    sheetName: "Reporte"
                });
            });
        </script>
    </div>
</div>
@endsection
@push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">