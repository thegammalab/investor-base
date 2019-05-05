<?php
$results = apply_filters('tdf_get_posts',"companies",5,0,array("search"=>array(),"order"=>"title_asc"));
 ?>
<div class="widgetone">
  <h4><img src="<?php echo get_bloginfo("template_directory"); ?>/assets/images/market.png" alt="">Market Activity</h4>
  <ul class="nav nav-pills nav-justified" id="widgettab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="win-tab" data-toggle="tab" href="#win" role="tab" aria-controls="win" aria-selected="true">winners</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="lose-tab" data-toggle="tab" href="#lose" role="tab" aria-controls="lose" aria-selected="false">losers</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-controls="contact" aria-selected="false">active</a>
    </li>
  </ul>
  <div class="tab-content" id="widgettabContent">
    <div class="tab-pane fade show active" id="win" role="tabpanel" aria-labelledby="win-tab">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Symbol</th>
            <th scope="col">Last price</th>
            <th scope="col">Change</th>
            <th scope="col">% change</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results["items"] as $item){ ?>
            <tr>
              <th scope="row"><a style="color:#000;" href="<?=$item["post_permalink"];?>"><?=$item["meta_company_code"];?><span><?=$item["post_title"];?></span></a></th>
              <td><?=$item["meta_price"];?>--</td>
              <td class="green"><?=$item["meta_chg"];?>--</td>
              <td class="green"><?=$item["meta_chg_perc"];?>--</td>
            </tr>

          <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="tab-pane fade" id="lose" role="tabpanel" aria-labelledby="lose-tab">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Symbol</th>
            <th scope="col">Last price</th>
            <th scope="col">Change</th>
            <th scope="col">% change</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results["items"] as $item){ ?>
            <tr>
              <th scope="row"><a style="color:#000;" href="<?=$item["post_permalink"];?>"><?=$item["meta_company_code"];?><span><?=$item["post_title"];?></span></a></th>
              <td><?=$item["meta_price"];?>--</td>
              <td class="green"><?=$item["meta_chg"];?>--</td>
              <td class="green"><?=$item["meta_chg_perc"];?>--</td>
            </tr>

          <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="tab-pane fade" id="active" role="tabpanel" aria-labelledby="active-tab">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Symbol</th>
            <th scope="col">Last price</th>
            <th scope="col">Change</th>
            <th scope="col">% change</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($results["items"] as $item){ ?>
            <tr>
              <th scope="row"><a style="color:#000;" href="<?=$item["post_permalink"];?>"><?=$item["meta_company_code"];?><span><?=$item["post_title"];?></span></a></th>
              <td><?=$item["meta_price"];?>--</td>
              <td class="green"><?=$item["meta_chg"];?>--</td>
              <td class="green"><?=$item["meta_chg_perc"];?>--</td>
            </tr>

          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
