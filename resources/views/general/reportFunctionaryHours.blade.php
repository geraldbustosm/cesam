@extends('layouts.main')
@section('title','Reportar horas')
@section('active-editarhoras','active')

@section('content')
<h1>Reportar Horas</h1>
<link rel="stylesheet" href="{{asset('css/color.css')}}">
<div class="div-full">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <form method="Post" action="{{ url('horas/edit') }}">
        @csrf
        <div class="form-group">
            <h4 class="titulo2">Funcionario: {{ $user->primer_nombre."  ".$user->segundo_nombre." ".$user->apellido_paterno." ".$user->apellido_materno }}</h4>
            <input id="functID" name="functID" type="hidden" value="{{ $functionary->id }}">
            <span class="titulo3">Horas Totales:</span>
            <span class="horasTotales">{{$functionary->horasDeclaradas }}</span>
            <span id="horasRestantes" class="horasRestantes">Horas Restantes: {{$functionary->horasDeclaradas }}</span>
        </div>
        <div class="row">
            <div class="col-12 col-md-auto">
                <div class="form-group">
                </div>
                <?php $i=0; ?>
                
                <div class="" style="height:450px; width:750px; max-width: 100%; ">
                    @foreach($activity as $activity)
                    <div class="form-group">
                        <div class="container" style=" max-width: 350px; ">
                            <div class="slider" style=" max-width: 150px; font-size: 15px;">
                                
                                    <label  class="rangeTitle"for="customRange">{{$activity->descripcion}}</label>
                                    <input name="hours2[]" type="range" value="0" max="{{$functionary->horasDeclaradas }}" data-target=".file<?php echo ++$i; ?>" class="custom-range x" id="customRange<?php echo $i; ?>">
                                    <input id="activityId<?php echo $i; ?>" name="activityId[]" type="hidden" value="{{$activity->id}}">
                                
                            </div>
                            <div name="hours[]"  class="result file<?php echo $i; ?>" id="result<?php echo $i; ?>">0</div>
                        </div>
                    </div>
                    @endforeach                  
                </div>
            </div>
            
            <div class="col-12 col-md-auto  ">
                <div >
                    <canvas  id="pie-chart2" ></canvas>
                </div>
            </div>
        </div>
       <div class="row">
       <div class="col-12 col-md-auto">
            <div  class="register">
                <button type="submit" class="btn btn-primary ">Registrar</button>
            </div>
		</div>
       </div>
        
        
    </form>
    <script src="{{asset('js/chart.bundle.js')}}"></script>
    <!-- Import D3 Scale Chromatic -->
    <script src="{{asset('js/d3-color.v1.min.js')}}"></script>
    <script src="{{asset('js/d3-interpolate.v1.min.js')}}"></script>
    <script src="{{asset('js/d3-scale-chromatic.v1.min.js')}}"></script>
    <script src="{{asset('js/color-generator.js')}}"></script>
    <script type="text/javascript">
        
        var num = <?php echo $i ?>;
        $(document).ready(function () {
            // Read value on change load;
            $('.x').change(function(){
                $($(this).data('target')).html($(this).val());
                var sum = 0;
                $('.result').each(function(){
                    if(isNaN(parseFloat($(this).text()))){
                        sum +=0;  
                    }else{
                        sum += parseFloat($(this).text()); 
                    }
                });
                var total = parseFloat($('.horasTotales').text());
                var hh = total -sum;
                if(hh<0){
                    alert("Amig@, estas trabajando de sobra, procura que tus horas totales sumen "+total);
                    $(".horasRestantes").css("background-color","red");
                    }else{
                        $(".horasRestantes").css("background-color","transparent");
                    }
                document.getElementById('horasRestantes').innerHTML = "Horas Restantes: " +hh;
                
            });
        });
    </script>
    <script>
        var data2 = [];
        var labels2 = [];
        var ID = document.getElementById("functID").value; 
        var endpoint = "{{url('charts4')}}?functionary_id="+ID;
        $.ajax({
            method: "GET",
            dataType: 'json',
            url: endpoint,
            success: function(result4) {
                result4.forEach(function(entry) {
                    labels2.push(entry.glosa);
                    data2.push(entry.numero);
                });
                const arrayLength = labels2.length;
                const chartData2 = {
                    labels: labels2,
                    data: data2,
                };
                const colorScale = d3.interpolateCool;
                const colorRangeInfo = {
                    colorStart: 0.1,
                    colorEnd: 0.65,
                    useEndAsStart: true,
                };
                /* Set up Chart.js Pie Chart */
                function createChart(chartId, chartData2, colorScale, colorRangeInfo) {
                    /* Grab chart element by id */
                    const chartElement = document.getElementById(chartId);

                    const dataLength = chartData2.data.length;

                    /* Create color array */
                    var COLORS = interpolateColors(dataLength, colorScale, colorRangeInfo);

                    /* Create chart */
                    const myChart = new Chart(chartElement, {
                        type: 'doughnut',
                        data: {
                            labels: chartData2.labels,
                            datasets: [{
                                backgroundColor: COLORS,
                                hoverBackgroundColor: COLORS,
                                data: chartData2.data
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: true,
                            },
                            hover: {
                                onHover: function(e) {
                                    var point = this.getElementAtEvent(e);
                                    e.target.style.cursor = point.length ? 'pointer' : 'default';
                                },
                            },
                        }
                    });

                    return myChart;
                }
                /* Create Chart */
                createChart('pie-chart2', chartData2, colorScale, colorRangeInfo);
            },
            error: function(error_data) {
                console.log("error")
                console.log(error_data)
            }
        })
    </script>
    <style>
        .container {
            background-color: #20a8d5 ;
            width: 650px;
            height: 100px;
            border-radius: 10px 40px; 
        }
        .slider {
            float: left;
            width: 400px;
            height: 50px;
            margin: 15px;
        }
        .rangeTitle{
            font-weight:900;
        }
        .result {
            float: left;
            width: 100px;
            height: 40px;
            margin: 15px;
            background-color: #10788a;
            text-align: center;
            vertical-align: middle;
            line-height: 40px; 
            border: none;
            border-radius: 10px 30px;    
        }
    </style>
    @endsection
    @push('styles')