<div class="widgettwo">
  <h4><img src="<?php echo get_bloginfo("template_directory"); ?>/assets/images/phone.png" alt="">your account</h4>
  <ul class="nav nav-pills nav-justified" id="widgettab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="watchlist-tab" data-toggle="tab" href="#watchlist" role="tab" aria-controls="win" aria-selected="true">Watchlist</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="portfolio-tab" data-toggle="tab" href="#portfolio" role="tab" aria-controls="portfolio" aria-selected="false">POrtfolio</a>
    </li>

  </ul>
  <div class="tab-content" id="widgettabContent">
    <div class="tab-pane fade show active" id="watchlist" role="tabpanel" aria-labelledby="watchlist-tab">
      <?php
      $my_watchlist = get_my_watchlist();
      $results = apply_filters('tdf_get_posts',"companies",25,get_query_var("paged"),array("search"=>array("pid"=>$my_watchlist),"order"=>"title_asc"));
      if(count($results["items"])){
        ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Symbol</th>
              <th scope="col">Last price</th>
              <th scope="col">% change</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($results["items"] as $item){ ?>
              <tr>
                <th scope="row"><a style="color:#000;" href="<?=$item["post_permalink"];?>"><?=$item["meta_company_code"];?><span><?=$item["post_title"];?></span></a></th>
                <td><?=$item["meta_price"];?>--</td>
                <td class="green"><?=$item["meta_chg_perc"];?>--</td>
              </tr>

            <?php } ?>

          </tbody>
        </table>
      <?php }else{ ?>
        <h3 class="no_results pt-3 light_nores" style="text-align:center; color: rgba(149, 163, 179, 0.2);">Sorry, no results</h3>
      <?php } ?>
    </div>
    <div class="tab-pane fade" id="portfolio" role="tabpanel" aria-labelledby="portfolio-tab">
      <h3 class="no_results pt-3" style="text-align:center;color: rgba(149, 163, 179, 0.2);">Sorry, no results</h3>

    </div>
  </div>
</div>
