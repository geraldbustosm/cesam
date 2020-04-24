@extends('layouts.main')
@section('title','Prestaciones Realizadas')
@section('active-prestaciones','active')
@section('active-prestacionesrealizadas','active')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="div-full row">
    <div class="col">
        <h1>Despliegue de Información   <a href="#" id="download-xlsx" style="padding: 5px;"><i title='Descargar tabla' class="material-icons">get_app</i></a></h1>
    </div>
    <form class="float-left" name="onSubmit" action="{{ url('prestaciones/resumen') }}">
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

            <div class="form-group col-md-3">
                <select class="form-control" id="filter-field">
                    <option selected>Columna</option>
                    <option value="actividad">Actividad</option>
                </select>
            </div>

            <div class="form-group col-md-2">
                <select class="form-control" id="filter-type">
                    <option selected>Tipo</option>
                    <option value="<=">&lt;=</option>
                    <option value=">=">&gt;=</option>
                    <option value="like">igual (texto)</option>
                </select>
            </div>
            <!-- Value sought -->
            <div class="form-group col-md-3"><input class="form-control" id="filter-value" type="text" placeholder="Valor a filtrar"></div>
            <!-- Clean filters -->
            <a href="#" id="filter-clear" style="padding: 5px;"><i title='Restablecer valores' class="material-icons">highlight_off</i><span></span></a>
        </div>

        <div id="example-table"></div>
        <script type="text/javascript">
            // Keep open sidebar
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
                // Delete error column
                table.clearFilter();
            });
            //Getting data
            var tableData = <?php echo json_encode($table); ?>;
            var functionaries = <?php echo json_encode($list); ?>;
            var currDate = <?php echo json_encode($date); ?>;
            // Write data on Tabulator table
            table = new Tabulator("#example-table", {
                height:"420px",
                data:tableData,
                // autoColumns: true,
                columns: [
                    {title: "Actividad", field:"actividad"}
                ],
            });
            // Complete table
            for(i=0 ; i<functionaries.length ; i++){
                table.addColumn(
                    {//create column group
                        title:`${functionaries[i].nombre_funcionario}`,
                        columns:[
                            {title:"Con Asistenia", field:`${functionaries[i].rut}-si`, width:160, bottomCalc:"sum"},
                            {title:"Sin Asistenia", field:`${functionaries[i].rut}-no`, width:160, bottomCalc:"sum"},
                        ],
                    }, false);
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