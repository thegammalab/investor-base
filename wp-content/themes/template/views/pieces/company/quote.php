<div class="row">
  <div class="col-lg-8">
    <div class="company_content_space">
      <?php if(count($quotes_chart)){ ?>
      <div>
        <!-- <img src="<?php echo get_bloginfo("template_directory"); ?>/assets/images/quote.jpg" alt=""> -->
        <div style="border:1px solid #f6f6f6; padding:20px 0px; margin-bottom:30px;">
          <div class="wrapper col-12 mb-3"><canvas id="chart-price" style="height:300px;"></canvas></div>
          <div class="wrapper col-12"><canvas id="chart-volume" style="height:150px;"></canvas></div>
        </div>
        <script>
        var config_price = {
          type: 'line',
          data: {
            labels: [<?php echo implode(",",$chart_labels); ?>],
            datasets: [{
              borderColor: "#84dcc6",
              backgroundColor: "rgba(132,220,198,0.4)",
              pointRadius: 0,
              pointHoverRadius: 0,
              data: [<?php echo implode(",",$quotes_chart); ?>],
              fill: "start",
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            spanGaps: false,
            elements: {
              line: {
                tension: 0.000001
              }
            },

            legend: {
              display: false,
            },
            title: {
              display: false,
            },
            plugins: {
              filler: {
                propagate: false
              }
            },
            tooltips: {
              enabled: false,
            },
            scales: {
              xAxes: [{
                display: true,
                position: 'top',

              }],
              yAxes: [{
                position: 'right',
                display: true,
                afterFit: function(scaleInstance) {
                  scaleInstance.width = 60; // sets the width to 100px
                }
              }]
            }
          }
        };
        var config_volume = {
          type: 'bar',
          data: {
            labels: [<?php echo implode(",",$chart_labels); ?>],
            datasets: [{
              borderColor: "#84dcc6",
              backgroundColor: "#84dcc6",
              pointRadius: 0,
              pointHoverRadius: 0,
              data: [<?php echo implode(",",$volumes_chart); ?>],
              fill: "start",
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            spanGaps: false,
            elements: {
              line: {
                tension: 0.000001
              }
            },

            legend: {
              display: false,
            },
            title: {
              display: false,
            },
            plugins: {
              filler: {
                propagate: false
              }
            },
            tooltips: {
              enabled: false,
            },
            scales: {
              xAxes: [{
                display: true,

              }],
              yAxes: [{
                position: 'right',
                display: true,
                afterFit: function(scaleInstance) {
                  scaleInstance.width = 63; // sets the width to 100px
                }
              }]
            }
          }
        };
        jQuery(document).ready(function(){
          var ctx_price = document.getElementById('chart-price').getContext('2d');
          window.config_price = new Chart(ctx_price, config_price);
          var ctx_volume = document.getElementById('chart-volume').getContext('2d');
          window.config_volume = new Chart(ctx_volume, config_volume);
        })
        </script>
      </div>
    <?php } ?>
    <?php
    $fields = array(
      "last"=>"Open",
      "prevclose"=>"Prev. Close:",
      "dividend_amount" => "Dividend",
      "dividend_yield" => "Yield",
      "high" => "High",
      "low" => "Low",
      "dividend_freq" => "Div. Frequency",
      "dividend_paydate" => "Ex-Div Date",
      "bid" => "Bid",
      "ask" => "Ask",
      "sharevolume" => "Share Vol.",
      "tradevolume" => "Trade Vol.",

      "sharesoutstanding" => "Shares Out.",
      "marketcap" => "Market Cap",
    );
    foreach($fields as $f=>$v){
      if($day_price[$f]){
        $price_vals[$f]=$day_price[$f];
      }
      if($price_info[$f]){
        $price_vals[$f]=$price_info[$f];
      }

    }

    $price_pieces = array_chunk($price_vals,3,true);
    ?>
    <div class="d-none d-sm-block">
      <table class="table table-striped table-borderless browse_table mt-3">

        <tbody>
          <?php foreach($price_pieces as $f=>$v){ ?>
            <tr>
              <?php foreach($v as $f1=>$v1){ ?>
                <td><?=$fields[$f1]; ?>:	</td>
                <td class="font-weight-bold dark_blue"><?php if(is_int($v1)){echo number_format($v1);}else{echo $v1;}; ?></td>
              <?php } ?>
            </tr>
          <?php } ?>
        </tbody>
      </table>
</div>
<div class="d-block d-sm-none">
  <table class="table table-striped table-borderless browse_table mt-3">

    <tbody>
      <?php foreach($price_pieces as $f=>$v){ ?>
        <?php foreach($v as $f1=>$v1){ ?>

        <tr>
            <td><?=$fields[$f1]; ?>:	</td>
            <td class="font-weight-bold dark_blue"><?php if(is_int($v1)){echo number_format($v1);}else{echo $v1;}; ?></td>
        </tr>
      <?php } ?>

      <?php } ?>
    </tbody>
  </table>
</div>
    </div>
    <?php if(count($news["items"])){ ?>
      <div class="news_about_company">
        <h2>News About <?=$item["meta_company_code"];?></h2>
        <div class="border_bottom">
          <?php
          for($i=0;$i<5;$i++){
            if($item=$news["items"][$i]){
              include(get_stylesheet_directory()."/views/posts/post/content-item.php");
            }
          }
          $item = $comp_item;
          ?>
        </div>
        <?php if(count($news["items"])>5){ ?>
          <div class="button_border">
            <button type="button" id="gotonews" class="btn btn-primary d-flex m-auto">GET MORE NEWS</button>
          </div>
          <script>
            jQuery(document).ready(function(){
              jQuery("#gotonews").click(function(){
                jQuery("#pills-newslist-tab").click();
                jQuery([document.documentElement, document.body]).animate({
                        scrollTop: jQuery("#pills-tabContent").offset().top
                    }, 1000);


              });
            });
          </script>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
  <div class="col-lg-4">

    <div class="widget_sidebar">
      <?php
      $search = array();
      $search["tax_exchanges"]=array($exchange["id"]);
      if(isset($comp_item["tax_array_company_industry"][0])){
        $search["tax_company_industry"]=array($comp_item["tax_array_company_industry"][0]->term_id);
      }
      $search["meta_".$exchange["code"]."_hasprice"]=1;
      $results = apply_filters('tdf_get_posts',"companies",5,0,array("search"=>$search,"order"=>"rand","get_basic_info"=>true));
      if(count($results["items"])){
      ?>
        <div class="widgetone">
          <h4><img src="<?php echo get_bloginfo("template_directory"); ?>/assets/images/market.png" alt="">Similar companies</h4>

          <table class="table position-relative" style="z-index:100;">
            <thead>
              <tr>
                <th scope="col">Symbol</th>
                <th scope="col">Last price</th>
                <th scope="col">Change</th>
                <th scope="col">% change</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($results["items"] as $sim_item){
                if(get_post_meta($sim_item["post_id"],$exchange["code"]."_change",true)>=0){
                  $val_class = "green";
                }else{
                  $val_class = "red";
                }
                ?>
                <tr>
                  <th scope="row"><a href="<?=get_permalink($sim_item["post_id"])."".$exchange["code"]; ?>"><?=get_post_meta($sim_item["post_id"],$exchange["code"]."_symbol",true); ?> <span><?=$sim_item["post_title"]; ?></span></a></th>
                  <td><?=number_format(get_post_meta($sim_item["post_id"],$exchange["code"]."_last",true),2); ?></td>
                  <td class="<?=$val_class;?>"><?=number_format(get_post_meta($sim_item["post_id"],$exchange["code"]."_change",true),2); ?></td>
                  <td class="<?=$val_class;?>"><?=round(get_post_meta($sim_item["post_id"],$exchange["code"]."_changepercent",true),3); ?>%</td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      <?php } ?>
      <?php if($item["post_excerpt"] || $day_price["low"] || $day_price["high"] || $price_info["week52low_content"] || $price_info["week52high_content"]){ ?>

      <div class="widgettwo">
        <h4><img src="<?php echo get_bloginfo("template_directory"); ?>/assets/images/phone.png" alt="">About <?=$item["meta_company_code"];?></h4>
        <div class="about_widget">
          <?php if($item["post_excerpt"]){ ?>
            <p><?=$item["post_excerpt"];?></p>
            <a href="#">Read more <img src="<?php echo get_bloginfo("template_directory"); ?>/assets/images/blue_arrow.png" alt=""></a>
          <?php } ?>
          <?php if($day_price["low"] && $day_price["high"]){ ?>
            <div class="mb-5">
              <div class="slider_top d-flex">
                <p class="flex-grow-1">Day Low <span><?=number_format($day_price["low"],2);?></span></p>
                <p class="text-right">Day high <span><?=number_format($day_price["high"],2);?></span></p>
              </div>
              <div class="grey_slider">
                <div class="blue_slider" style="width:<?=(min(100,100*($day_price["close"]-$day_price["low"])/($day_price["high"]-$day_price["low"]))); ?>%;"></div>
              </div>
            </div>
          <?php } ?>
          <?php if($price_info["week52low_content"] && $price_info["week52high_content"]){ ?>
            <div class="slider_top d-flex">
              <p class="flex-grow-1">52 week low <span><?=number_format(($price_info["week52low_content"]),2);?></span></p>
              <p class="text-right">52 week low high <span><?=number_format(($price_info["week52high_content"]),2);?></span></p>
            </div>
            <div class="grey_slider">
              <div class="blue_slider" style="width:<?=(min(100,100*($day_price["close"]-$price_info["week52low_content"])/($price_info["week52high_content"]-$price_info["week52low_content"]))); ?>%;"></div>
            </div>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

      <?php dynamic_sidebar("company_sidebar"); ?>
    </div>
  </div>
</div>
