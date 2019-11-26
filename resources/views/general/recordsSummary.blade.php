@extends('layouts.main')
@section('title','Prestaciones Realizadas')
@section('active-prestaciones','active')
@section('active-prestacionesrealizadas','active')

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
    <script src="{{asset('js/xlsx.full.min.js')}}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <div>
        @csrf
        <!-- Buttons for download table -->
        <div class="box red"></div>
        <div class="table-controls">
            <button class="btn btn-primary" id="download-xlsx">Descargar XLSX</button>
        </div>
        <br>
        <div class="table-controls-legend">
            <h3>Parametros para filtrar</h3>
        </div>
        <!-- Select parameters for filter -->
        <div class="table-controls form-row">
            <div class="form-group col-md-2">
                <select class="form-control" id="filter-assist">
                    <option selected>Asistencia</option>
                    <option value="attend">Con asistencia</option>
                    <option value="no-attend">Sin asistencia</option>
                    <option value="all">Todas</option>
                </select>
            </div>

            <div class="form-group col-md-3">
                <select class="form-control" id="filter-field">
                    <option selected>Columna</option>
                    <option value="actividad">Actividad</option>
                    @foreach($functionarys as $name)
                    <option value="{{ $name->nombre_funcionario }}">{{ $name->nombre_funcionario }}</option>
                    @endforeach
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
                $("#filter-assist").val("Asistencia");
                writeTable(tableData);                
                // Delete error column
                table.deleteColumn("_children");
                table.clearFilter();
            });
            //Getting data
            var tableData = <?php echo json_encode($dataTotal); ?>;
            var tableAttend = <?php echo json_encode($dataAttend); ?>;
            var tableNoAttend = <?php echo json_encode($dataNoAttend); ?>;
            var tableTotal = <?php echo json_encode($dataTotal); ?>;
            var table;
            // Write table on load page
            window.onload = function() {
                changeSomeData();
                writeTable(tableData);                
                // Delete error column
                table.deleteColumn("_children");
            };
            // Put data on table
            function writeTable(data){
                var tableData = data;
                // Write data on Tabulator table
                table = new Tabulator("#example-table", {
                    height:"380px",
                    data:tableData,
                    dataTree:true,
                    dataTreeStartExpanded:true,
                    autoColumns: true,
                });
            }
            // Change some data
            function changeSomeData() {
                for ( var i=0 ; i<tableData.length ; i++ ) {
                    tableData[i]._children[0].actividad = "Con asistencia";
                    tableData[i]._children[1].actividad = "Sin asistencia";
                    delete tableTotal[i]._children;
                }
            }
            // Change for assist at attend, no assist or both
            $("#filter-assist").change( function() {
                if ( $("#filter-assist").val() == "attend" ) {
                    // Write data on Tabulator table
                    writeTable(tableAttend);
                } else if ( $("#filter-assist").val() == "no-attend" ) {
                    // Write data on Tabulator table
                    writeTable(tableNoAttend);
                } else if ( $("#filter-assist").val() == "all" ) {
                    writeTable(tableTotal);
                }
            });
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