<div class="company_content_space pt-4">
  <h2 class=" mb-5"><?=$comp_item["post_title"];?> Financials</h2>
  <script>
  jQuery(document).ready(function(){
    jQuery(".financial_page_tabs .nav-link").click(function(e){
      jQuery(".financial_page_tabs .nav-link").removeClass("act_tab");
      jQuery(this).addClass("act_tab");

      jQuery(".financials_tab").removeClass("act_tab");
      jQuery(jQuery(this).attr("href")).addClass("act_tab");

      e.preventDefault();
      return false;
    });
  });
  </script>
  <ul class="nav nav-pills financial_page_tabs d-flex">
    <li class="nav-item">
      <a class="nav-link act_tab" href="#income_tab" >income statement</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#balance_tab">balance sheet</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#cash_tab">Cash flow</a>
    </li>
  </ul>
  <div id="income_tab" class="financials_tab act_tab">
    <table class="table table-striped table-borderless sec_table mt-3">
      <thead>
        <tr>
          <th scope="col" class="grey_head"></th>
          <th scope="col"></th>
          <?php foreach($financials["dates"] as $f=>$v){ ?>
            <th scope="col"><?=date("M y",strtotime($v)); ?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach($financials["IncomeStatement"] as $f=>$v){ ?>
          <tr>
            <td class="font-weight-normal">
              <?php
              $pieces = preg_split('/(?=[A-Z])/', $f);
              foreach($pieces as $pf=>$pv){
                if(strlen($pieces[$pf])>1){
                  echo $pv." ";
                }else{
                  echo $pv;
                }
              }
              ?>
            </td>
            <td>
              <script type="text/javascript">
              var config<?=$f;?> = {
                type: 'line',
                data: {
                  labels: ['', '', '', '', ''],
                  datasets: [{
                    borderColor: "#95a3b3",
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    data: [
                    <?php
                    $arr = array();
                    foreach($financials["dates"] as $df=>$dv){
                      if(intval($v[$df])){$arr[]=$v[$df];}else{$arr[]=0;}
                    }
                    echo implode(",",array_reverse($arr));
                    ?>
                    ],
                    fill: false,
                  }]
                },
                options: {
                  responsive: true,
                  legend: {
                    display: false,
                  },
                  title: {
                    display: false,
                  },
                  tooltips: {
                    enabled: false,
                  },
                  scales: {
                    xAxes: [{
                      display: false,

                    }],
                    yAxes: [{
                      display: false,

                    }]
                  }
                }
              };
              jQuery(document).ready(function(){
                var ctx<?=$f;?> = document.getElementById('canvas<?=$f;?>').getContext('2d');
                window.myLine<?=$f;?> = new Chart(ctx<?=$f;?>, config<?=$f;?>);
              })
              </script>
              <div style="width:70px; height:35px;">
                <canvas id="canvas<?=$f;?>"></canvas>
              </div>
              <ul class="bar_chart d-none">
                <?php
                $min = min($v);
                $max = max($v);
                $range = $max-$min;

                foreach($financials["dates"] as $df=>$dv){
                  if(0>=$min){
                    if(intval($v[$df])>=0){
                      $bot = 100*(abs($min))/$range;
                    }else{
                      $bot = (abs($min)-abs($v[$df]))/abs($min)*(100*(abs($min))/$range);
                    }
                  }else{
                    $bot = 0;
                  }
                  if(abs($v[$df])){
                    if(intval($v[$df])>=0){
                      $cl = 'pos';
                    }else{
                      $cl = 'neg';
                    }

                    $hei = (100*(abs($v[$df])/$range))."%";
                  }else{
                    $hei = "1px";
                  }
                  if(!$bot || is_nan($bot)){
                    $bot = 0;
                  }
                  if($v[$df]=="-"){
                    $cl = 'pos';
                  }
                  ?>
                  <li class="<?=$cl; ?>"><div style="height:<?=$hei; ?>; bottom:<?=$bot; ?>%;"></div></li>
                <?php } ?>
              </ul>
            </td>
            <?php foreach($financials["dates"] as $df=>$dv){
              if($v[$df]>0){
                $val_class= "green";
              }else{
                $val_class= "red";
              }
              ?>
              <td class="<?=$val_class; ?>"><?=number_format($v[$df],2); ?></td>
            <?php } ?>
          </tr>
        <?php } ?>

      </tbody>
    </table>
  </div>
  <div id="balance_tab" class="financials_tab">
    <table class="table table-striped table-borderless sec_table mt-3">
      <thead>
        <tr>
          <th scope="col" class="grey_head"></th>
          <th scope="col"></th>
          <?php foreach($financials["dates"] as $f=>$v){ ?>
            <th scope="col"><?=date("M y",strtotime($v)); ?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach($financials["BalanceSheet"] as $f=>$v){ ?>
          <tr>
            <td class="font-weight-normal">
              <?php
              $pieces = preg_split('/(?=[A-Z])/', $f);
              foreach($pieces as $pf=>$pv){
                if(strlen($pieces[$pf])>1){
                  echo $pv." ";
                }else{
                  echo $pv;
                }
              }
              ?>
            </td>
            <td>
              <script type="text/javascript">
              var config<?=$f;?> = {
                type: 'line',
                data: {
                  labels: ['', '', '', '', ''],
                  datasets: [{
                    borderColor: "#95a3b3",
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    data: [
                    <?php
                    $arr = array();
                    foreach($financials["dates"] as $df=>$dv){
                      if(intval($v[$df])){$arr[]=$v[$df];}else{$arr[]=0;}
                    }
                    echo implode(",",array_reverse($arr));
                    ?>
                    ],
                    fill: false,
                  }]
                },
                options: {
                  responsive: true,
                  legend: {
                    display: false,
                  },
                  title: {
                    display: false,
                  },
                  tooltips: {
                    enabled: false,
                  },
                  scales: {
                    xAxes: [{
                      display: false,

                    }],
                    yAxes: [{
                      display: false,

                    }]
                  }
                }
              };
              jQuery(document).ready(function(){
                var ctx<?=$f;?> = document.getElementById('canvas<?=$f;?>').getContext('2d');
                window.myLine<?=$f;?> = new Chart(ctx<?=$f;?>, config<?=$f;?>);
              })
              </script>
              <div style="width:70px; height:35px;">
                <canvas id="canvas<?=$f;?>"></canvas>
              </div>
              <ul class="bar_chart d-none">
                <?php
                $min = min($v);
                $max = max($v);
                $range = $max-$min;

                foreach($financials["dates"] as $df=>$dv){
                  if(0>=$min){
                    if(intval($v[$df])>=0){
                      $bot = 100*(abs($min))/$range;
                    }else{
                      $bot = (abs($min)-abs($v[$df]))/abs($min)*(100*(abs($min))/$range);
                    }
                  }else{
                    $bot = 0;
                  }
                  if(abs($v[$df])){
                    if(intval($v[$df])>=0){
                      $cl = 'pos';
                    }else{
                      $cl = 'neg';
                    }

                    $hei = (100*(abs($v[$df])/$range))."%";
                  }else{
                    $hei = "1px";
                  }
                  if(!$bot || is_nan($bot)){
                    $bot = 0;
                  }
                  if($v[$df]=="-"){
                    $cl = 'pos';
                  }
                  ?>
                  <li class="<?=$cl; ?>"><div style="height:<?=$hei; ?>; bottom:<?=$bot; ?>%;"></div></li>
                <?php } ?>
              </ul>
            </td>
            <?php foreach($financials["dates"] as $df=>$dv){
              if($v[$df]>0){
                $val_class= "green";
              }else{
                $val_class= "red";
              }
              ?>
              <td class="<?=$val_class; ?>"><?=number_format($v[$df],2); ?></td>
            <?php } ?>
          </tr>
        <?php } ?>

      </tbody>
    </table>
  </div>
  <div id="cash_tab" class="financials_tab">
    <table class="table table-striped table-borderless sec_table mt-3">
      <thead>
        <tr>
          <th scope="col" class="grey_head"></th>
          <th scope="col"></th>
          <?php foreach($financials["dates"] as $f=>$v){ ?>
            <th scope="col"><?=date("M y",strtotime($v)); ?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach($financials["CashFlow"] as $f=>$v){ ?>
          <tr>
            <td class="font-weight-normal">
              <?php
              $pieces = preg_split('/(?=[A-Z])/', $f);
              foreach($pieces as $pf=>$pv){
                if(strlen($pieces[$pf])>1){
                  echo $pv." ";
                }else{
                  echo $pv;
                }
              }
              ?>
            </td>
            <td>
              <script type="text/javascript">
              var config<?=$f;?> = {
                type: 'line',
                data: {
                  labels: ['', '', '', '', ''],
                  datasets: [{
                    borderColor: "#95a3b3",
                    pointRadius: 0,
                    pointHoverRadius: 0,
                    data: [
                    <?php
                    $arr = array();
                    foreach($financials["dates"] as $df=>$dv){
                      if(intval($v[$df])){$arr[]=$v[$df];}else{$arr[]=0;}
                    }
                    echo implode(",",array_reverse($arr));
                    ?>
                    ],
                    fill: false,
                  }]
                },
                options: {
                  responsive: true,
                  legend: {
                    display: false,
                  },
                  title: {
                    display: false,
                  },
                  tooltips: {
                    enabled: false,
                  },
                  scales: {
                    xAxes: [{
                      display: false,

                    }],
                    yAxes: [{
                      display: false,

                    }]
                  }
                }
              };
              jQuery(document).ready(function(){
                var ctx<?=$f;?> = document.getElementById('canvas<?=$f;?>').getContext('2d');
                window.myLine<?=$f;?> = new Chart(ctx<?=$f;?>, config<?=$f;?>);
              })
              </script>
              <div style="width:70px; height:35px;">
                <canvas id="canvas<?=$f;?>"></canvas>
              </div>
              <ul class="bar_chart d-none">
                <?php
                $min = min($v);
                $max = max($v);
                $range = $max-$min;

                foreach($financials["dates"] as $df=>$dv){
                  if(0>=$min){
                    if(intval($v[$df])>=0){
                      $bot = 100*(abs($min))/$range;
                    }else{
                      $bot = (abs($min)-abs($v[$df]))/abs($min)*(100*(abs($min))/$range);
                    }
                  }else{
                    $bot = 0;
                  }
                  if(abs($v[$df])){
                    if(intval($v[$df])>=0){
                      $cl = 'pos';
                    }else{
                      $cl = 'neg';
                    }

                    $hei = (100*(abs($v[$df])/$range))."%";
                  }else{
                    $hei = "1px";
                  }
                  if(!$bot || is_nan($bot)){
                    $bot = 0;
                  }
                  if($v[$df]=="-"){
                    $cl = 'pos';
                  }
                  ?>
                  <li class="<?=$cl; ?>"><div style="height:<?=$hei; ?>; bottom:<?=$bot; ?>%;"></div></li>
                <?php } ?>
              </ul>
            </td>
            <?php foreach($financials["dates"] as $df=>$dv){
              if($v[$df]>0){
                $val_class= "green";
              }else{
                $val_class= "red";
              }
              ?>
              <td class="<?=$val_class; ?>"><?=number_format($v[$df],2); ?></td>
            <?php } ?>
          </tr>
        <?php } ?>

      </tbody>
    </table>
  </div>
</div>
