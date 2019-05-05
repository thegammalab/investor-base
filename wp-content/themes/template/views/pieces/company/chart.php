<!-- 
<script src="https://moment.github.io/luxon/global/luxon.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
		<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@0.1.1"></script> -->
<!-- <script src="https://www.chartjs.org/chartjs-chart-financial/moment.js" type="text/javascript"></script> -->
<!-- <script src="https://www.chartjs.org/chartjs-chart-financial/chartjs-chart-financial.js" type="text/javascript"></script> -->
<!-- <script src="https://www.chartjs.org/chartjs-chart-financial/utils.js" type="text/javascript"></script> -->
<?php
// $dataset = array();
// $th_vals = array();
// foreach(array_slice($quotes,0,360) as $item){
//   if($item["close"]){
//     $th_vals[]=$item["close"];
//   }
//   if($item["high"]){
//     $th_vals[]=$item["high"];
//   }
//   if($item["low"]){
//     $th_vals[]=$item["low"];
//   }
//   if($item["open"]){
//     $th_vals[]=$item["open"];
//   }

//   $dataset[]=array(
//     "c"=>$item["close"],
//     "h"=>$item["high"],
//     "l"=>$item["low"],
//     "o"=>$item["open"],
//     "t"=>strtotime($item["date"])*1000,
//   );
// }
// $th_min = 0.95*min($th_vals);
?>

<div class="company_content_space pt-4">
  <h2 class="mb-5">Charting for <?= $comp_item["post_title"]; ?></h2>
  <div class="qm-wrap-ichart">
    <div data-qmod-tool="interactivechart" data-qmod-params='{"chart":{"colors":["#5CD1B3","#ff3900","#00b655","#ff9000","#717171","#8085e9"],"upColor":"#008000","downColor":"#3ff0000","chartType":"4"},"volumeEnabled":true,"chartTypeEnabled":true,"marketSessionEnabled":true,"compareOptionsEnabled":false,"compareEnabled":false,"eventsEnabled":true,"dateRange":"3","marketSession":"1","compareOption":"0","lang":"en","symbol":"<?= $the_price["symbol"]; ?>"}' class="qtool"></div>
  </div>
  <script id="qmod" type="application/javascript" src="//qmod.quotemedia.com/js/qmodLoader.js" data-qmod-wmid="102396" data-qmod-env="app" async data-qmod-version=""></script>

  <!-- <div style="width:100%;  overflow:auto;">
    <canvas style="width:100%; " id="chart_full"></canvas>
  </div>
  <script>
  var data_full_chart = JSON.parse('<?= json_encode($dataset); ?>');

  // Candlestick
  var ctx_full = document.getElementById("chart_full").getContext("2d");
  ctx_full.canvas.height = 550;

  new Chart(ctx_full, {
    type: 'candlestick',

    data: {
      datasets: [{
        label: "",
        data: data_full_chart,
        fractionalDigitsCount: 2,
      }]
    },
    options: {
      legend: {
                  display: false
               },
      tooltips: {
        position: 'nearest',
        mode: 'index',
      },
      scales: {
        yAxes: [{
          display: true,
          ticks: {
            suggestedMin: <?= $th_min; ?>,    // minimum will be 0, unless there is a lower value.
            // OR //
          }
        }],
        xAxes: [{
          display: false,
          ticks: {
            autoskip: true,
            autoSkipPadding: 30,
            stepSize: 30,
            unitStepSize: 30
          },
        }]
      }
    },
  });
setTimeout(function(){
jQuery("#pills-thechart").removeClass("show").removeClass("active");
},1000);
  </script> -->
</div>