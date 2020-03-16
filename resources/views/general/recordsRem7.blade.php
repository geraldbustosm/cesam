@extends('layouts.main')
@section('title','Tablas REM')
@section('active-prestaciones','active')
@section('active-rem','active')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<h1>Despliegue de Información   <a href="#" id="download-xlsx" style="padding: 5px;"><i title='Descargar tabla' class="material-icons">get_app</i></a></h1>

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
            // Write data for download
            var table = new Tabulator("#example-table", {
                height:"420px",
                data:tableData,
                // autoColumns: true,
                columns: [
                    {title:"ESPECIALIDADES Y SUB-ESPECIALIDADES", field:"nombre_funcionario"},
                    {title:"ESPECIALIDAD", field:"especialidad"},
                    {title:"TOTAL", field:"Ambos", width:120, bottomCalc:"sum"},
                ],
            });
            // Complete table
            for(i=0 ; i<list.length ; i++){
                table.addColumn({title:`${list[i]}`, field:`${list[i]}`, width:150, bottomCalc:"sum"}, false);
            };
            table.addColumn(
                {//create column group
                    title:`A BENEFICIARIOS`,
                    columns:[
                        {title:`Menos de 15 años`, field:`menores`, width:150, bottomCalc:"sum"},
                        {title:`15 años y más`, field:`mayores`, width:150, bottomCalc:"sum"},
                    ],
                }, false);
            table.addColumn(
                {//create column group
                    title:`POR SEXO`,
                    columns:[
                        {title:`Hombres`, field:`Hombres`, width:150, bottomCalc:"sum"},
                        {title:`Mujeres`, field:`Mujeres`, width:150, bottomCalc:"sum"},
                    ],
                }, false);
            // Generate the sub-columns for each macro-column
            colYoung = new Array();
            colOld = new Array();
            provenances.forEach(element => colYoung.push({title:`${element.descripcion}`, field:`${element.descripcion}_m`, width:150, bottomCalc:"sum"}));
            provenances.forEach(element => colOld.push({title:`${element.descripcion}`, field:`${element.descripcion}_M`, width:150, bottomCalc:"sum"}));
            // Macro-Columns
            table.addColumn(
                {//create column group
                    title:`Menos de 15 años`,
                    columns:colYoung,
                }, false);
            table.addColumn(
                {//create column group
                    title:`De 15 y más años`,
                    columns:colOld,
                }, false);
            table.addColumn(
                {//create column group
                    title:`INASISTENTE A CONSULTA MÉDICA (NSP)`,
                    columns:[
                        {title:`NUEVAS`, field:`nuevo`, width:150, bottomCalc:"sum"},
                        {title:`CONTROLES`, field:`repetido`, width:150, bottomCalc:"sum"},
                    ],
                }, false);

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