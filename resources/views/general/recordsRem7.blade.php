@extends('layouts.main')
@section('title','Tablas REM-7')
@section('active-prestaciones','active')
@section('active-rem7','active')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="div-full row">
    <div class="col">
        <h1>Despliegue de Información <a href="#" id="download-xlsx" style="padding: 5px;"><i title='Descargar tabla' class="material-icons">get_app</i></a></h1>
    </div>
    <form class="float-left" name="onSubmit" action="{{ url('prestaciones/rem7') }}">
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
    <script src="{{asset('js/rem7.js')}}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <div>
        @csrf
        <div class="table-controls-legend">
            <h3>Parametros para filtrar</h3>
        </div>
        <!-- Select parameters for filter -->
        <div class="table-controls form-row">
            <div class="form-group col-md-4">
                <select class="form-control" id="filter-field">
                    <option selected>Columna</option>
                    <option value="actividad">Actividad</option>
                    <option value="especialidad">Especialidad</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <select class="form-control" id="filter-type">
                    <option selected>Tipo</option>
                    <option value="=">=</option>
                    <option value="<=">&lt;=</option>
                    <option value=">=">&gt;=</option>
                    <option value="like">igual</option>
                </select>
            </div>
            <!-- Value sought -->
            <div class="form-group col-md-4"><input class="form-control" id="filter-value" type="text" placeholder="Valor a filtrar"></div>
            <!-- Clean filters -->
            <a href="#" id="filter-clear" style="padding: 5px;"><i title='Restablecer valores' class="material-icons">highlight_off</i><span></span></a>
        </div>
        <!-- Target for tabulator table -->
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
            var list = <?php echo json_encode($list); ?>;
            var provenances = <?php echo json_encode($provenances); ?>;
            var currDate = <?php echo json_encode($date); ?>;

            // Write data for download
            var table = new Tabulator("#example-table", {
                height: "420px",
                data: tableData,
                // autoColumns: true,
                columns: [
                    { title: "ESPECIALIDADES Y SUB-ESPECIALIDADES", field: "nombre_funcionario" },
                    { title: "ESPECIALIDAD", field: "especialidad" },
                    { title: "TOTAL", field: "Ambos", width: 120, bottomCalc: "sum" },
                ],
            });
        </script>
    </div>
</div>
@endsection
@push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">