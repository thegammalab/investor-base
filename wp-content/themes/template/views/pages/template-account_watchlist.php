<?php
/*
Template Name: Account Watchlist
*/

$my_watchlist = get_my_watchlist();
$watchlist_items = array(99999999999);
foreach($my_watchlist as $w_itm){
  $watchlist_data[$w_itm["post_id"]]=$w_itm;
  $watchlist_items[]=$w_itm["post_id"];
}
$results = apply_filters('tdf_get_posts',"companies",25,get_query_var("paged"),array("search"=>array("pid"=>$watchlist_items),"order"=>"title_asc"));

?>
<section class="my_account">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <?php include(get_stylesheet_directory()."/views/pieces/account_side.php"); ?>
      </div>
      <div class="col-lg-9">
        <div class="page_title d-flex align-items-center">
          <h1 class="mb-4">My Watchlist</h1>
        </div>
        <div class="white_bg mb-6">
          <?php if(count($results["items"])){ ?>
            <div class="table-responsive">

            <table class="table table-striped table-borderless browse_table">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Symbol</th>
                  <th scope="col">Name</th>
                  <th scope="col">Price </th>
                  <th scope="col">Change </th>
                  <th scope="col">%Change </th>
                  <th scope="col">Volume </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($results["items"] as $item){
                  $w_it = ($watchlist_data[$item["post_id"]]);
                  $priceinfo = get_post_meta($item["post_id"],$w_it["exchange"]."_pricedata",true);
if($priceinfo["change"]>=0){
  $ch_class = 'green';
}else{
  $ch_class = 'red';
}
                  ?>
                  <tr>
                    <th scope="row">
                      <?=get_watchlist_button($w_it["code"]);?>
                    </th>
                    <td class="font-weight-bold dark_blue"><a href="<?=$item["post_permalink"]."/".$w_it["exchange"];?>"><?=$w_it["code"];?></a></td>
                    <td><a href="<?=$item["post_permalink"]."/".$w_it["exchange"];?>"><?=$item["post_title"];?></a></td>
                    <td><?=number_format($priceinfo["last"],3);?></td>
                    <td class="<?=$ch_class; ?>"><?=$priceinfo["change"];?></td>
                    <td class="<?=$ch_class; ?>"><?=round($priceinfo["changepercent"],3);?>%</td>
                    <td><?=number_format($priceinfo["sharevolume"],0);?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
</div>
            <?php


            if(function_exists('wp_paginate')){
              wp_paginate(array( 'query' => $results["query"] ));

            }
          }else{
            ?>
            <h3 class="no_results my-5" style="text-align:center;">Sorry, no results</h3>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>
