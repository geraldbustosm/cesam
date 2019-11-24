@extends('layouts.main')
@section('title','Prestaciones Realizadas')
@section('active-prestaciones','active')
@section('active-prestacionesrealizadas','active')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<h1>Despliegue de Información</h1>

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
    <link rel="stylesheet" href="{{asset('css/table.css')}}">

    <!-- Buttons for download table -->
    <div class="box red"></div>
    <div class="table-controls">
        <button class="btn btn-primary" id="download-xlsx">Descargar XLSX</button>
        <button class="btn btn-primary" id="download-pdf">Descargar PDF</button>
    </div>
    <br>
    <!-- Start content -->
    <div class="div-full">
        <!-- Successful alert -->
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <div style="overflow-x:auto;">
            <table class="table table-striped table-bordered table-sm" id="example-table">
                <thead>
                    <tr>
                        <th>
                            Funcionarios
                        </th>
                        @foreach($columns as $column)
                        <th>{{ $column }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $var1 => $columns)
                    <tr>
                        <td>{{ $var1 }}</td>
                        @foreach($columns as $var2 => $ids)
                        @inject('provider', 'App\Http\Controllers\AdminController')
                        <td><?php echo $provider::countActivitiesPerFunctionary($ids[0], $ids[1]); ?></td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    var table = new Tabulator("#example-table", {});

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
@endsection
@push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">