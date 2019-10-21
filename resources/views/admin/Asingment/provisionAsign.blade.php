@extends('layouts.main')
@section('title','Asignacion Especialidades-Prestaciones')
@section('active-ingresardatos','active')
@section('active-asignarespecialidadprestacion','active')

@section('content')
<!-- Alerts of errors -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<h1>Asigne las especialidades a las prestaciones</h1>
<div class="div-full">
    <!-- Successful alert -->
	@if (session('status'))
		<div class="alert alert-success" role="alert">
			{{ session('status') }}
		</div>
    @endif
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
	<form method="post" action="{{ url('asignar/especialidad-prestacion') }}">
		@csrf
        <table  class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th><!-- Empty for the left top corner of the table --></th>
                    @foreach($columns as $column)
                    <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach($rows as $kriteria1 => $columns)
                <tr>
                    <td><strong>{{ " ".$kriteria1." " }}</strong></td>
                    @foreach($columns as $kriteria2 => $nombre1)
                    @inject('provider', 'App\Http\Controllers\AdminController')
                    <td>  
                    <label class="pure-material-checkbox">                 
                    <input type="checkbox" 
                           name="asignations[<?=strtoupper($nombre1[0]);?>][<?=strtoupper($nombre1[1]);?>]" 
                           value="<?=strtoupper($nombre1[0])."|".strtoupper($nombre1[1]);?>"                                  
                           <?php if($provider::existProvisionSpeciality($nombre1[0],$nombre1[1])) { echo 'checked';}?> 
                    >
                    <span></span>
                    </label>
                    </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
        
		<button type="submit" name= "enviar" class="btn btn-primary">Registrar</button>
	</form>
</div>
<script>
    document.getElementById('data_Submenu').className += ' show';
</script>
@endsection