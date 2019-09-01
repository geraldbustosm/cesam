@extends('layouts.main')
@section('title','Registrar nueva atención')
@section('active-ingresaratension','active')

@section('content')
<h1>Registrar Atencion</h1>
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
			<label for="time">Hora de la atención</label>
            <input id="time" name="time" width="276" value  = "" required>
            <script>
                $('#time').timepicker({
                    defaultTime: 'value',
                    minuteStep: 1,
                    disableFocus: true,
                    template: 'dropdown',
                    showMeridian:false
                });
            </script>
        </div>
        <div class="form-group">
			<label for="duration">Duración</label>
            <input id="duration" name="duration" width="276" value  = "" required>
            <input type="text" class="form-control" id="duration" value="86400" required>
            <script>
                 $('#duration').durationPicker({
                    translations: {
                    day: 'dia',
                    hour: 'hora',
                    minute: 'minuto',
                    second: 'segundo',
                    days: 'dias',
                    hours: 'horas',
                    minutes: 'minutos',
                    seconds: 'segundos',
                    },
                    onChanged: function (newVal) {
                        $('#duration-label').text(newVal);
                    }
                    });
            </script>
        </div>
        <div class="form-group">
        <label for="selectA">Asistenscia: </label>
        <select name="selectA">
            <option value="1">Si </option> 
            <option value="0" selected>No</option>
        </select>
        <div class="form-group">
            <label for="title">Asigne el funcionario y la prestacion:</label>
        </div>
        <div class="panel panel-default">
        <div class="panel-heading">Seleccione el funcionario</div>
        <div class="panel-body">
            <div class="form-group">
                <select id="functionary" name="category_id" class="form-control" style="width:350px" >
                        <option value="" selected disabled>Seleccione un Funcinario</option>
                        @foreach($users as $key => $user)
                            <option value="{{$user->id}}"> {{$user->profesion}}</option>
                        @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title">Seleccione la especialidad:</label>
                <select name="speciality" id="speciality" class="form-control" style="width:350px"></select>
            </div>

            <div class="form-group">
                <label for="title">Seleccione la prestación:</label>
                <select name="provision" id="provision" class="form-control" style="width:350px"></select>
            </div>
        </div>
            
        <script type="text/javascript">
            $('#functionary').change(function(){
                var functionaryID = $(this).val();    
                if(functionaryID){
                    $.ajax({
                        type:"GET",
                        url:"{{url('get-speciality-list')}}?functionary_id="+functionaryID,
                        success:function(res){               
                            if(res){
                                $("#speciality").empty();
                                $("#speciality").append('<option>Seleccione por favor</option>');
                                $.each(res,function(key,value){
                                    $("#speciality").append('<option value="'+value.id+'">'+value.descripcion+'</option>');
                                });
                            }else{
                                $("#speciality").empty();
                            }
                        }
                    });
                }else{
                    $("#speciality").empty();
                    $("#city").empty(); 
                }      
                });

            $('#speciality').on('change',function(){
                var specialityID = $(this).val();    
                if(specialityID){
                    $.ajax({
                        type:"GET",
                        url:"{{url('get-provision-list')}}?speciality_id="+specialityID,
                        success:function(res){               
                            if(res){
                                $("#provision").empty();
                                $.each(res,function(key,value){
                                $("#provision").append('<option value="'+value.id+'">'+value.glosaTrasadora+'</option>');
                            });
                            }else{
                                $("#provision").empty();
                            }
                        }
                    });
                    }else{
                    $("#provision").empty();
                    }                     
            });
        </script>

        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>
@endsection

