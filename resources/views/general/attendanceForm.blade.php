@extends('layouts.main')
@section('title','Registrar nueva atención')
@section('active-ingresaratencion','active')


@section('content')
<h1>Registrar Atencion</h1>
<div class="div-full">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="post" action="{{ url('registraratencionOk') }}">
        @csrf
        <input type="hidden" id="id" name="id" value=<?= $patient->id;?>>
        <div class="form-group">
                    <p class = "titulo2" >Paciente: <?= $patient->nombre1." ".$patient->nombre2." ".$patient->apellido1." ".$patient->apellido2;?></p>
                </div>
        <div class="row">
            <div class = "column">
                   
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
                    <label for="time">Hora inicio de la atención</label>
                    <input id="timeInit" name="timeInit" width="276" value  = "" required>
                    <script>
                        $('#timeInit').timepicker({
                            defaultTime: 'value',
                            minuteStep: 1,
                            disableFocus: true,
                            template: 'dropdown',
                            showMeridian:false
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label for="timeEnd">Hora inicio de la atención</label>
                    <input id="timeEnd" name="timeEnd" width="276" value  = "" required>
                    <script>
                        $('#timeEnd').timepicker({
                            defaultTime: 'value',
                            minuteStep: 1,
                            disableFocus: true,
                            template: 'dropdown',
                            showMeridian:false
                        });
                    </script>
                </div>
                <div class="form-group">
                    <label for="duration">Duración, HH:MM</label>
                    <br>
                    <input id="duration" name="duration" width="300" value  = "" required >
                    <script type="text/javascript">
                    var init = document.getElementById('timeInit');
                    var end = document.getElementById('timeEnd');
                    end.onchange = function(e){
                        function toSeconds(time_str) {
                            // Extract hours, minutes and seconds
                            var parts = time_str.split(':');
                            // compute  and return total seconds
                            return parts[0] * 3600 + // an hour has 3600 seconds
                            parts[1] * 60; // a minute has 60 seconds
                        }
                        var a = init.value; //start time
                        var b = end.value; // end time
                        var difference = (toSeconds(b) - toSeconds(a));
                        // format time difference
                        if (difference>0){
                            var result = [
                                Math.floor(difference / 3600), // an hour has 3600 seconds
                                Math.floor((difference % 3600) / 60)// a minute has 60 seconds
                            ];
                            // 0 padding and concatation
                            result = result.map(function(v) {
                                return v < 10 ? '0' + v : v;
                            }).join(':');
                            document.getElementById('duration').value = result;
                        }else{
                            alert("Error en la duración");
                        }
                        alert(document.getElementById('duration').value);
                    }
                </script>         
                </div>
            </div>
            <div class = "column">
                <div class="form-group">
                    <label for="selectA">Asistenscia: </label>
                    <select name="selectA">
                        <option value="1">Si </option> 
                        <option value="0" selected>No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Asigne el funcionario y la prestacion:</label>
                </div>
                <div class="panel-heading">Seleccione el funcionario</div>
                <div class="form-group">
                    <select id="functionary" name="functionary" class="form-control" style="width:350px" >
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
                <script type="text/javascript">
                    $('#functionary').change(function(){
                        var functionaryID = $(this).val();    
                        if(functionaryID){
                            $.ajax({
                                type:"GET",
                                url:"{{url('lista-especialidades')}}?functionary_id="+functionaryID,
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
                                url:"{{url('lista-prestaciones')}}?speciality_id="+specialityID,
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
                <div class="form-group" class = "register">
                <input type="hidden" class="form-control {{ $errors->has('DNI') ? ' is-invalid' : '' }}" value="<?=$DNI;?>"  id="DNI" name="DNI">
                <input type="hidden" class="form-control {{ $errors->has('id_stage') ? ' is-invalid' : '' }}" value="<?=$stage->id;?>"  id="id_stage" name="id_stage">
                <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </div>        
        </div>
        </form>
        
       
@endsection
@push('styles')
    <link href="{{ asset('css/attendance.css') }}" rel="stylesheet">

