@extends('layouts.main')
@section('title','Editar paciente')
@section('active-pacientes','active')

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
<h1>Editar paciente</h1>
<div class="div-full">
	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif
    
    @if ($patient)
    <form method="post" action="{{ url('pacientes/edit') }}"> 
		@csrf
		<div class="form-group">
            <label for="dni">Rut o pasaporte</label>
			<input type="text" class="form-control {{ $errors->has('dni') ? ' is-invalid' : '' }}" value="{{$patient->DNI}}" id="dni" name="dni" placeholder="Rut o pasaporte">
		</div>
		<div class="form-group">
            <label for="nombre">Nombre completo</label>
			<input type="text" class="form-control {{ $errors->has('nombre') ? ' is-invalid' : '' }}" value="{{$patient->nombre1}} {{$patient->nombre2}} {{$patient->apellido1}} {{$patient->apellido2}}" id="nombre" name="nombre" placeholder="Nombre completo">
		</div>

		<div class="form-group">
            <label for="prevision">Previsión</label>
			<select class="form-control" name="patient_prev" required>
                <option>{{$patient_prev->nombre}}</option>
				@foreach($prev as $prevision)
                    @if ($prevision->nombre != $patient_prev->nombre)
				        <option value="{{ $prevision->id}}">{{ $prevision->nombre}}</option>
                    @endif
				@endforeach
			</select>
           </div>

		<div class="form-group">
            <label for="sex">Sexo</label>
			<select class="form-control" name="sex" required>
				<option>{{$patient_sex->descripcion}}</option>
				@foreach($sex as $sexo)
                    @if ($sexo->descripcion != $patient_sex->descripcion)
				        <option value="{{ $sexo->id}}">{{ $sexo->descripcion}}</option>
                    @endif
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label for="datepicker">Fecha de nacimiento</label>
			<input id="datepicker" name="datepicker" value="{{$patient_birthday}}" width="276" required>
			<script>
				var config = {
					format: 'dd-mm-yyyy',
					locale: 'es-es',
					uiLibrary: 'bootstrap4'
				};
				$('#datepicker').datepicker(config);

				$("#datepicker").on("change", function() {
					var from = $("#datepicker").val().split("/");
					// Probar usando la id 'datepicker' en vez de var 'date'
					var date = new Date(from[2], from[1] - 1, from[0]);
				});
			</script>
		</div>
		<button type="submit" class="btn btn-primary">Editar paciente</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert"><p>No se encontró al paciente</p></div>
@endif

<!-- Adding script using on this view -->
<script src="{{asset('js/idValidator.js')}}"></script>
@endsection