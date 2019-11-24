@extends('layouts.main')
@section('title','Tablas REM')
@section('active-prestaciones','active')
@section('active-rem','active')

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
        <div class="table-controls form-row">
            <div class="form-group col-md-4">
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
                    <option value="<">&lt;</option>
                    <option value="<=">&lt;=</option>
                    <option value=">">&gt;</option>
                    <option value=">=">&gt;=</option>
                    <option value="!=">distinto</option>
                    <option value="like">igual</option>
                </select>
            </div>
            <!-- Value sought -->
            <div class="form-group col-md-4"><input class="form-control" id="filter-value" type="text" placeholder="Valor a filtrar"></div>
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

                table.clearFilter();
            });

            //Getting data
            var tableData = <?php echo json_encode($data); ?>;
            console.log(tableData);
            // Write data on Tabulator table
            var table = new Tabulator("#example-table", {
                height:"311px",
                data:tableData,
                dataTree:true,
                dataTreeStartExpanded:true,
                autoColumns: true,
            });
            // Delete error column
            table.deleteColumn("_children");

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
        </script>
    </div>
</div>
@endsection
@push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">