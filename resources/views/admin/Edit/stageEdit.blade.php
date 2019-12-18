@extends('layouts.main')
@section('title','Editar etapa')
@section('active-ingresardatos','active')

@section('content')

<h1>Editar etapa</h1>
<div class="div-full">
	@if (session('status'))
		<div class="alert alert-success" role="alert">
			{{ session('status') }}
		</div>
    @endif

	@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	@if ($stage)
	<form method="post" action="{{ url('etapas/edit') }}">
		@csrf

		<!-- Por convención, para update utilizaremos metodo PUT (no un simple metodo post) -->
		<input type="hidden" name="_method" value="PUT">

		<!-- Enviamos el ID de la actividad para luego actualizarlo -->
		<input id="id" name="id" type="hidden" value="{{$stage->id}}">

		<div class="form-group">
			<label for="functionarys">Médico a cargo</label>
			<select class="form-control" name="functionarys">
				<option value="{{$stage->funcionario_id}}">{{$functionary->user->primer_nombre}} {{$functionary->user->apellido_paterno}}</option>
				@foreach($functionarys as $fc)
					@if($fc->id != $functionary->id)
						<option value="{{$fc->id}}">{{$fc->user->primer_nombre}} {{$fc->user->apellido_paterno}}</option>
					@endif
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label for="sigges">SiGGES</label>
			<select class="form-control" name="sigges">
				<option value="{{$stage->sigges_id}}">{{$sigge->descripcion}}</option>
				@foreach($sigges as $sg)
					@if($sigge->id != $sg->id)
						<option value="{{$sg->id}}">{{$sg->descripcion}}</option>
					@endif
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label for="programs">Programa</label>
			<select class="form-control" name="programs">
				<option value="{{$stage->programa_id}}">{{$program->descripcion}}</option>
				@foreach($programs as $pg)
					@if($program->id != $pg->id)
						<option value="{{$pg->id}}">{{$pg->descripcion}}</option>
					@endif
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label for="provenances">Procedencia</label>
			<select class="form-control" name="provenances">
				<option value="{{$stage->procedencia_id}}">{{$provenance->descripcion}}</option>
				@foreach($provenances as $pv)
					@if($provenance->id != $pv->id)
						<option value="{{$pv->id}}">{{$pv->descripcion}}</option>
					@endif
				@endforeach
			</select>
		</div>
		
		<h4>Seleccione los diagnosticos</h4>
		<div class="form-group">
			<div class="card p-2">
				@foreach($diagnosis as $dg)
						@php
							$isInDiagnostic = false;
						@endphp
						@foreach($stage->diagnosis as $stageDiagnostic)
							@if($dg->id == $stageDiagnostic->id)
								@php $isInDiagnostic = true; @endphp
							@endif
						@endforeach
						
						@if($isInDiagnostic)
							<label><input type="checkbox" name="options[]" value="{{ $dg->id}}" checked> {{ $dg->descripcion}}</label>
						@else
							<label><input type="checkbox" name="options[]" value="{{ $dg->id}}"> {{ $dg->descripcion}}</label>
						@endif
				@endforeach
				</div>
			</div>
		</div>

		<button type="submit" class="btn btn-primary">Editar etapa</button>
	</form>
</div>
@else
<div class="alert alert-danger" role="alert">
	<p>No se encontró la etapa</p>
</div>
@endif
@endsection