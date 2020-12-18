@extends('layouts.main')
@section('title','Misión y visión')
@section('active-escritorio','active')
@section('content')

<input id="functID" name="functID" type="hidden" value="{{ Auth::user()->functionary()->id }}">
<div class="jumbotron jumbotron-fluid div-full">
  <div class="container">
    <h1 class="display-4">Misión y visión</h1>
    <p class="lead" style="font-size: 18px;">Ser un Centro Comunitario reconocido territorialmente en el desarrollo de la Salud Mental
      con una marcada participación democrática de los sujetos individuales y colectivos de la
      población, promoviendo el bienestar físico psicológico, social y espiritual, con un enfoque
      de recuperación, intercultural y de derecho
    </p>
    <p class="lead" style="font-size: 18px;">
      En relación al mensaje comunicacional de la visión desea plasmar, a fin de fortalecer al
      equipo de trabajo, se pueden señalar:
    </p>
    <ul style="width: 90%;">
      <li><p>Somos un equipo de atención de especialidad, competente para abordar casos de alta complejidad.</p></li>
      <li><p>Somos un equipo trasdisciplinario, llamado a mirar contextual, relacional e integralmente.</p></li>
      <li><p>Somos un equipo respetuoso y democrático en el proceso de atención.</p></li>
      <li><p>Somos un equipo colaborativo y constructivo en la toma de decisiones clínicas, técnicas y en su operar administrativo.</p></li>
      <li><p>Somos un equipo creativo que busca innovar y diversificar en sus procesos de atención, a fin de llevar la atención requerida a los usuarios, cuidando su continuidad de cuidados, la oportunidad de su atención y el logro de su recuperación.</p></li>
    </ul>
  </div>
</div>

<div>
    <canvas id="pie-chart2" height="400px" width="900px"></canvas>
</div>


<script src="{{asset('js/chart.bundle.js')}}"></script>
<script src="{{asset('js/d3-color.v1.min.js')}}"></script>
<script src="{{asset('js/d3-interpolate.v1.min.js')}}"></script>
<script src="{{asset('js/d3-scale-chromatic.v1.min.js')}}"></script>
<script src="{{asset('js/color-generator.js')}}"></script>
<script>
    var data2 = [];
    var data3 = [];
    var labels2 = [];
    var ID = document.getElementById("functID").value; 
    var endpoint = "{{url('chartForFunctionaryHome')}}?functionary_id="+ID;
    $.ajax({
        method: "GET",
        dataType: 'json',
        url: endpoint,
        success: function(result4) {
            result4.forEach(function(entry) {
                labels2.push(entry.glosa);
                data2.push(entry.numero);
                data3.push(entry.realizadas);
            });
            const arrayLength = labels2.length;
            const chartData2 = {
                labels: labels2,
                data: data2,
            };
            const colorScale = d3.interpolateCool;
            const colorScale2 = d3.interpolateReds;
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
                var COLORS2 = interpolateColors(dataLength, colorScale2, colorRangeInfo);

                /* Create chart */
                const myChart = new Chart(chartElement, {
                    type: 'bar',
                    data: {
                        labels: chartData2.labels,
                        datasets: [
                          {
                            label: 'Dataset 2',
                            backgroundColor: COLORS2,
                            hoverBackgroundColor: COLORS2,
                            data: chartData2.data
                          },
                          {
                            label: 'Dataset 1',
                            backgroundColor: COLORS,
                            hoverBackgroundColor: COLORS,
                            data: chartData2.data
                          },
                          
                        ],
                    },
                    options: {
                        title: {
                          display: true,
                          text: 'Chart.js Bar Chart - Stacked'
                        },
                        tooltips: {
                          mode: 'index',
                          intersect: false
                        },
                        scales: {
                          xAxes: [{
                            stacked: true,
                          }],
                          yAxes: [{
                            stacked: false
                          }]
                        },
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
@endsection