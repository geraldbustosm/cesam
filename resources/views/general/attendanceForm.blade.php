@extends('layouts.main')
@section('title','Registrar nueva atención')
@section('active-ingresaratension','active')

@section('content')
<h1>Registrar usuario</h1>
<div class="div-full">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="post" action="{{ url('registraratencion') }}">
        @csrf

        <div class="form-group">
			<label for="datepicker">Fecha de la atención</label>
			<input id="datepicker" name="datepicker" width="276" value  = "" required>
			<script>
				var config = {
					format: 'dd/mm/yyyy',
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
        <div class="form-group">
        <label for="datepicker">Asistenscia: </label>
        <select name="select">
            <option value="1">Si </option> 
            <option value="0" selected>No</option>
        </select>
        <div class="panel panel-default">
        <div class="panel-heading">Ajax dynamic dependent country state city dropdown using jquery ajax in Laravel 5.6</div>
        <div class="panel-body">
            <div class="form-group">
                <select id="country" name="category_id" class="form-control" style="width:350px" >
                        <option value="" selected disabled>Select</option>
                        @foreach($countries as $key => $country)
                            <option value="{{$key}}"> {{$country}}</option>
                        @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title">Select State:</label>
                    <select name="state" id="state" class="form-control" style="width:350px">
                    </select>
            </div>
        </div>
            
        <script type="text/javascript">
            $('#country').change(function(){
                var countryID = $(this).val();    
                if(countryID){
                    $.ajax({
                        type:"GET",
                        url:"{{url('get-state-list')}}?country_id="+countryID,
                        success:function(res){               
                            if(res){
                                $("#state").empty();
                                $("#state").append('<option>Select</option>');
                                $.each(res,function(key,value){
                                    $("#state").append('<option value="'+key+'">'+value+'</option>');
                                });
                            }else{
                                $("#state").empty();
                            }
                        }
                    });
                }else{
                    $("#state").empty();
                }      
                });
        </script>
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('primer_nombre') ? ' is-invalid' : '' }}" value="{{ old('primer_nombre') }}" id="primer_nombre" name="primer_nombre" placeholder="Primer Nombre">
        </div>

        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('segundo_nombre') ? ' is-invalid' : '' }}" value="{{ old('segundo_nombre') }}" id="segundo_nombre" name="segundo_nombre" placeholder="Segundo Nombre">
        </div>
        
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('apellido_paterno') ? ' is-invalid' : '' }}" value="{{ old('apellido_paterno') }}" id="apellido_paterno" name="apellido_paterno" placeholder="Apellido Paterno">
        </div>
        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('apellido_materno') ? ' is-invalid' : '' }}" value="{{ old('apellido_materno') }}" id="apellido_materno" name="apellido_materno" placeholder="Apellido Materno">
        </div>

        <div class="form-group">
            <input type="text" class="form-control {{ $errors->has('rut') ? ' is-invalid' : '' }}" value="{{ old('rut') }}" id="rut" name="rut" placeholder="Rut o pasaporte">
        </div>
        <div class="form-group">
            <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" id="email" name="email" placeholder="Correo">
        </div>
        <div class="form-group">
            <select id="rol" name="rol" class="form-control">
                <option value="0" disabled selected>Rol de usuario</option>
                <option value="1">Administrador</option>
                <option value="2">Funcionario</option>
                <option value="3">Secretaria</option>
            </select>
        </div>
        <div class="form-group">
            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" placeholder="Contraseña">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar contraseña">
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection