@extends('layouts.main')
@section('title','Misión y visión')
@section('active-escritorio','active')
@section('content')

<h1>Misión y visión</h1>
<input id="functID" name="functID" type="hidden" value="{{ Auth::user()->functionary()->id }}">
<div class="row">
  <div class="col">
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente laborum autem repudiandae distinctio laboriosam quo, harum sint adipisci fugiat iusto nesciunt aut eum qui quos quaerat doloremque molestiae debitis error? Praesentium maxime consequatur a unde. Eaque tenetur quae saepe minima exercitationem officiis sapiente labore eligendi tempora quam nesciunt, nihil quidem ipsam iste qui corrupti doloremque, temporibus hic ea repudiandae. Nostrum veritatis maxime obcaecati sapiente architecto ullam cumque porro, sit earum necessitatibus beatae perspiciatis voluptatem, quibusdam ea quae? Eaque sequi sunt nam deserunt voluptas omnis ab voluptatum corporis pariatur autem sapiente libero recusandae officia veniam iure, aliquid esse nemo laborum, repudiandae fugiat. Nobis necessitatibus quisquam obcaecati commodi explicabo corporis nostrum inventore, porro soluta. Laborum nostrum veniam quos nihil placeat nam ipsum expedita, perspiciatis, facere quaerat ab vel natus eveniet nobis fugiat eligendi ex qui molestias quasi earum aliquam. Fugiat, accusantium alias saepe cumque quibusdam dicta sunt, error commodi consectetur hic magni, magnam temporibus labore neque nisi expedita? Enim adipisci eum odio aliquid, laudantium quasi odit hic quod. Magni vitae ut sed asperiores doloremque eum, numquam ullam atque at optio fugit illum quia sapiente sit hic praesentium facere recusandae quod odio labore fugiat modi consectetur iure? Tempore fuga repudiandae rerum molestiae ratione pariatur qui aut natus quae voluptatibus harum, atque quibusdam magnam!</p>
  </div>
</div>

<div class="row">
  <div class="col">
      <div>
          <canvas id="pie-chart2" height="400px" width="900px"></canvas>
      </div>
  </div>
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
    var endpoint = "{{url('charts5')}}?functionary_id="+ID;
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