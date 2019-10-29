@extends('layouts.main')
@section('title','Dashboard')
@section('active-escritorio','active')

@section('content')
<link rel="stylesheet" href="{{asset('css/color.css')}}">
    <div class="w">
    <section>
        <div>
        <canvas id="pie-chart"></canvas>
        </div>
        <div>
        <canvas id="chart"></canvas>
        </div>
        <div>
        <canvas id="pie-chart2"></canvas>
        </div>
        <div>
        <canvas id="line-chart3"></canvas>
        </div>
    </section>
    </div>
    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.js" charset="utf-8"></script>
        <!-- Import D3 Scale Chromatic via CDN -->
        <script src="https://d3js.org/d3-color.v1.min.js"></script>
        <script src="https://d3js.org/d3-interpolate.v1.min.js"></script>
        <script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
        <script type="text/javascript" src="{{asset('js/color-generator.js')}}"></script>

        <script>
        var url = "{{url('charts')}}";
        var Years = new Array();
        var Labels = new Array();
        var Prices = new Array();
        $(document).ready(function(){
          $.get(url, function(response){
            response.forEach(function(data){
                Years.push(data.stockYear);
                Labels.push(data.stockName);
                Prices.push(data.stockPrice);
            });
            var barChartData = {
                      labels:Years,
                      datasets: [{
                          label: 'Infosys Price',
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
                                beginAtZero:false
                            },
                            scaleLabel: {
                                    display: true,
                                    labelString: 'Funcionarios',
                                    fontSize: 20 
                                }
                        }]            
                    } ,
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
            success: function(result){
                result.forEach(function(entry) {
                    labels.push(entry.stockYear);
                    data.push(entry.stockPrice);
                });
                const arrayLength = labels.length;
                console.log(Array.isArray( labels));
                console.log(labels);
                console.log(data);
                console.log(labels.length);
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
                    datasets: [
                        {
                        backgroundColor: COLORS,
                        hoverBackgroundColor: COLORS,
                        data: chartData.data
                        }
                    ],
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
            error: function(error_data){
                console.log("error")
                console.log(error_data)
            }
        })
       
        
       
        </script>
         <script>
        var data2 = [];
        var labels2 = [];
        var endpoint = "{{url('charts2')}}";
        $.ajax({
            method: "GET",
            dataType: 'json',
            url: endpoint,
            success: function(result2){
                result2.forEach(function(entry) {
                    labels2.push(entry.glosa);
                    data2.push(entry.numero);
                });
                const arrayLength = labels.length;
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
                    datasets: [
                        {
                        backgroundColor: COLORS,
                        hoverBackgroundColor: COLORS,
                        data: chartData2.data
                        }
                    ],
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
            error: function(error_data){
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
            success: function(result3){
                result3.forEach(function(entry) {
                    labels3.push(entry.Month);
                    data3.push(entry.Cantidad);
                });
                const arrayLength = labels.length;
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
                    datasets: [
                        {
                        label: "Numero de Canastas Abiertas",
                        borderColor: "#3cba9f",
                        data: chartData3.data
                        }
                    ],
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
            error: function(error_data){
                console.log("error")
                console.log(error_data)
            }
        })
       
        
       
        </script>
@endsection