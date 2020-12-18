@extends('layouts.main')
@section('title','Dashboard')
@section('active-escritorio','active')

@section('content')
<link rel="stylesheet" href="{{asset('css/color.css')}}">
<div class="container">
    <ul class="nav nav-tabs" id="topNav" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="charts1-tab" data-toggle="tab" href="#charts1" role="tab" aria-controls="charts1" aria-selected="true">Atenciones por funcionario&nbsp;&nbsp;</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="chart2-tab" data-toggle="tab" href="#chart2" role="tab" aria-controls="chart2" aria-selected="false">Grafico de barra&nbsp;&nbsp;</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="charts1" role="tabpanel" aria-labelledby="charts1-tab">
            <canvas id="pie-chart" height="600px" width="600px"></canvas>
        </div>
        <div class="tab-pane fade" id="chart2" role="tabpanel" aria-labelledby="chart2-tab">
            <canvas id="chart" height="600px" width="600px"></canvas>
        </div>
    </div>

    <br><br>
    <div>
        <canvas id="line-chart3" height="600px" width="600px"></canvas>
    </div>
</div>

        
    


<script src="{{asset('js/chart.bundle.js')}}"></script>
<!-- Import D3 Scale Chromatic -->
<script src="{{asset('js/d3-color.v1.min.js')}}"></script>
<script src="{{asset('js/d3-interpolate.v1.min.js')}}"></script>
<script src="{{asset('js/d3-scale-chromatic.v1.min.js')}}"></script>
<script src="{{asset('js/color-generator.js')}}"></script>

<script>
    var url = "{{url('charts')}}";
    var Years = new Array();
    var Labels = new Array();
    var Prices = new Array();
    $(document).ready(function() {
        $.get(url, function(response) {
            response.forEach(function(data) {
                Years.push(data.stockYear);
                Labels.push(data.stockName);
                Prices.push(data.stockPrice);
            });
            var barChartData = {
                labels: Years,
                datasets: [{
                    label: 'Rendimiento',
                    data: Prices,
                    borderWidth: 1
                }]
            }

            var ctx = document.getElementById('chart').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'horizontalBar',
                data: barChartData,
                options: {

                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Funcionarios',
                                fontSize: 20
                            }
                        }]
                    },
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Cantidad de atenciones por funcionario'
                    }

                }
            });
        });
    });
</script>
<script>
    var data = [];
    var labels = [];
    var endpoint = "{{url('charts')}}";
    $.ajax({
        method: "GET",
        dataType: 'json',
        url: endpoint,
        success: function(result) {
            result.forEach(function(entry) {
                labels.push(entry.stockYear);
                data.push(entry.stockPrice);
            });
            const arrayLength = labels.length;
            
            const chartData = {
                labels: labels,
                data: data,
            };
            const colorScale = d3.interpolateCool;
            const colorRangeInfo = {
                colorStart: 0,
                colorEnd: 1,
                useEndAsStart: true,
            };
            /* Set up Chart.js Pie Chart */
            function createChart(chartId, chartData, colorScale, colorRangeInfo) {
                /* Grab chart element by id */
                const chartElement = document.getElementById(chartId);

                const dataLength = chartData.data.length;

                /* Create color array */
                var COLORS = interpolateColors(dataLength, colorScale, colorRangeInfo);

                /* Create chart */
                const myChart = new Chart(chartElement, {
                    type: 'doughnut',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            backgroundColor: COLORS,
                            hoverBackgroundColor: COLORS,
                            data: chartData.data
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
            createChart('pie-chart', chartData, colorScale, colorRangeInfo);
        },
        error: function(error_data) {
            console.log("error")
            console.log(error_data)
        }
    })
</script>
<script>
    var data3 = [];
    var labels3 = [];
    var endpoint = "{{url('charts3')}}";
    $.ajax({
        method: "GET",
        dataType: 'json',
        url: endpoint,
        success: function(result3) {
            result3.forEach(function(entry) {
                labels3.push(entry.Month);
                data3.push(entry.Cantidad);
            });
            const arrayLength = labels3.length;
            const chartData3 = {
                labels: labels3,
                data: data3,
            };
            const colorScale = d3.interpolateCool;
            const colorRangeInfo = {
                colorStart: 0.1,
                colorEnd: 0.65,
                useEndAsStart: true,
            };
            /* Set up Chart.js Pie Chart */
            function createChart(chartId, chartData3) {
                /* Grab chart element by id */
                const chartElement = document.getElementById(chartId);


                /* Create chart */
                const myChart = new Chart(chartElement, {
                    type: 'line',
                    data: {
                        labels: chartData3.labels,
                        datasets: [{
                            label: "Numero de Canastas Abiertas",
                            borderColor: "#3cba9f",
                            data: chartData3.data
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
            createChart('line-chart3', chartData3);
        },
        error: function(error_data) {
            console.log("error")
            console.log(error_data)
        }
    });
</script>
@endsection