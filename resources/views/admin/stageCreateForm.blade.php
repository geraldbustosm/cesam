@extends('layouts.main')
@section('title','Crear etapa')
@section('active-ingresardatos','active')
@section('active-crearetapa','active')

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
<h1>Crear nueva etapa</h1>
<div class="div-full">
	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif
	<form method="post" action="{{ url('crearetapa') }}">
		@csrf
		<div class="form-group">
			<div class="form-row">
				

				<div class="col-4">
                    <select class="form-control" name="funcionario_id" required>
				        <option selected disabled>Por favor seleccione un funcionario </option>
				        @foreach($funcionarios as $funcionario)
				        <option value="{{ $funcionario->id}}">{{ $funcionario->primer_nombre." ".$funcionario->apellido_paterno." (".$funcionario->profesion.")"}}</option>
				        @endforeach 
			        </select>
                </div>
			</div>
		</div>
		<div class="form-group">
			<div class="form-row">
				
				<div class="col-4">
					<select class="form-control" name="diagnostico_id" required>
						<option selected disabled>Por favor seleccione un diagnostico </option>
						@foreach($diagnosis as $diagnostico)
						<option value="{{ $diagnostico->id}}">{{ $diagnostico->descripcion}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-4">
					<select class="form-control" name="programa_id" required>
						<option selected disabled>Por favor seleccione un programa </option>
						@foreach($program as $programa)
						<option value="{{ $programa->id}}">{{ $programa->descripcion}}</option>
						@endforeach
					</select>
                    
                </div>
			</div>
		</div>
		<div class="form-group">
			<div class="form-row">
				
				<div class="col-4">
					<select class="form-control" name="sigges_id" required>
						<option selected disabled>Por favor seleccione un sigges </option>
						@foreach($Sigges as $sigges)
						<option value="{{ $sigges->id}}">{{ $sigges->descripcion}}</option>
						@endforeach
					</select>
                  
				</div>
				<div class="col-4">
					<select class="form-control" name="procedencia_id" required>
						<option selected disabled>Por favor seleccione una procedencia </option>
						@foreach($provenance as $procedencia)
						<option value="{{ $procedencia->id}}">{{ $procedencia->descripcion}}</option>
						@endforeach
					</select>
                    
                </div>
			</div>
		</div>
		
        
		<!--
        <div class="form-group">
			<select class="form-control" name="alta_id" required>
				<option selected disabled>Por favor seleccione un tipo de alta </option>
				@foreach($release as $alta)
				<option value="{{ $alta->id}}">{{ $alta->descripcion}}</option>
				@endforeach
			</select>
		</div>
		-->
		<div class="form-group" class = "register">
        <input type="hidden" class="form-control {{ $errors->has('idpatient') ? ' is-invalid' : '' }}" value="<?=$idpatient;?>"  id="idpatient" name="idpatient">
		<button type="submit" class="btn btn-primary">Registrar</button>
		<input type="button" href="javascript:validator()" value="Test" id="testing" />
        </div>      
       
		
		
	</form>
</div>


@endsection