@extends('layouts.main')
@section('title','Crear etapa')
@section('active-ingresardatos','active')

@section('content')

<h1>Crear nueva etapa</h1>
@if ($errors->any())
<div class="alert alert-danger">
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif
<div class="div-full">
	@if (session('status'))
	<div class="alert alert-success" role="alert">
		{{ session('status') }}
	</div>
	@endif
	<form method="post" action="{{ url('crear/etapa') }}">
		@csrf
		<div class="form-group">
			<div class="form-row">
				<div class="col-4">
					<select class="form-control" name="funcionario_id" required>
						<option selected disabled>Por favor seleccione un funcionario </option>
						@foreach($functionarys as $funcionario)
						<option value="{{ $funcionario->id}}">{{ $funcionario->primer_nombre." ".$funcionario->apellido_paterno }}</option>
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
		<div class="form-group">
			<h4> Seleccione diagnosticos </h4>
			<div class="form-row">
				<div class="col-4">
					<div class="overflow-auto" style="height:450px">
						@foreach($diagnosis as $diagnostico)

						<div class="card">
        					<div class="checkbox-container">
        						<label class="checkbox-label">
            						<input type="checkbox" name="options[]" value="{{ $diagnostico->id}}">
            						<span class="checkbox-custom rectangular"></span>
        						</label>
							</div>
        					<div class="input-title">{{ $diagnostico->descripcion}}</div>
								
						</div>
	
						@endforeach
					</div>
				</div>
				<div class="col-4 center-block">
					<div class="form-group text-center" class="register">
						<input type="hidden" class="form-control {{ $errors->has('idpatient') ? ' is-invalid' : '' }}" value="<?= $idpatient; ?>" id="idpatient" name="idpatient">
						<button type="submit" class="btn btn-primary ">Registrar</button>
					</div>
				</div>
			</div>
		</div>
		
	</form>
</div>
<style>
.card {
	height: 40px;
    background-color: #0275d8;
}

.card .checkbox-container {
	display: inline-block;
    box-sizing: border-box;
    text-align:center;
}

.input-title {
	position: absolute;
    top: 15px;
    left: 30px;
    font-size: 20px;
    color: rgba(255,255,255);
    font-weight: 400;
}


/* Styling Checkbox Starts */
.checkbox-label {
    position: relative;
    margin: auto;
    cursor: pointer;
    font-size: 22px;
    line-height: 24px;
    height: 24px;
    width: 24px;
    clear: both;
}

.checkbox-label input {
    
    opacity: 0;
    cursor: pointer;
}

.checkbox-label .checkbox-custom {
    position: absolute;
    top: 7px;
    left: 100px;
    height: 24px;
    width: 24px;
    background-color: transparent;
    border-radius: 5px;
    transition: all 0.3s ease-out;
    -webkit-transition: all 0.3s ease-out;
    -moz-transition: all 0.3s ease-out;
    -ms-transition: all 0.3s ease-out;
    -o-transition: all 0.3s ease-out;
    border: 2px solid #FFFFFF;
}


.checkbox-label input:checked ~ .checkbox-custom {
    background-color: #FFFFFF;
    border-radius: 5px;
    -webkit-transform: rotate(0deg) scale(1);
    -ms-transform: rotate(0deg) scale(1);
    transform: rotate(0deg) scale(1);
    opacity:1;
    border: 2px solid #FFFFFF;
}


.checkbox-label .checkbox-custom::after {
    position: absolute;
    content: "";
    left: 12px;
    top: 12px;
    height: 0px;
    width: 0px;
    border-radius: 5px;
    border: solid #009BFF;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(0deg) scale(0);
    -ms-transform: rotate(0deg) scale(0);
    transform: rotate(0deg) scale(0);
    opacity:1;
    transition: all 0.3s ease-out;
    -webkit-transition: all 0.3s ease-out;
    -moz-transition: all 0.3s ease-out;
    -ms-transition: all 0.3s ease-out;
    -o-transition: all 0.3s ease-out;
}


.checkbox-label input:checked ~ .checkbox-custom::after {
  -webkit-transform: rotate(45deg) scale(1);
  -ms-transform: rotate(45deg) scale(1);
  transform: rotate(45deg) scale(1);
  opacity:1;
  left: 8px;
  top: 3px;
  width: 6px;
  height: 12px;
  border: solid #009BFF;
  border-width: 0 2px 2px 0;
  background-color: transparent;
  border-radius: 0;
}

/* For Ripple Effect */
.checkbox-label .checkbox-custom::before {
    position: absolute;
    content: "";
    left: 10px;
    top: 10px;
    width: 0px;
    height: 0px;
    border-radius: 5px;
    border: 2px solid #FFFFFF;
    -webkit-transform: scale(0);
    -ms-transform: scale(0);
    transform: scale(0);    
}

.checkbox-label input:checked ~ .checkbox-custom::before {
    left: -3px;
    top: -3px;
    width: 24px;
    height: 24px;
    border-radius: 5px;
    -webkit-transform: scale(3);
    -ms-transform: scale(3);
    transform: scale(3);
    opacity:0;
    z-index: 999;
    transition: all 0.3s ease-out;
    -webkit-transition: all 0.3s ease-out;
    -moz-transition: all 0.3s ease-out;
    -ms-transition: all 0.3s ease-out;
    -o-transition: all 0.3s ease-out;
}

</style>


@endsection