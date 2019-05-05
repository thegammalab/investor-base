<?php
/*
Template Name: Account Portfolio
*/


$portfolio_list = apply_filters('tdf_get_portfolio',UID);

$price_labels = array(
  "last" => "Price",
  "change" => "Change",
  "changepercent" => "Change %",
  "tradevolume" => "Trade Volume",
  "sharevolume" => "Share Volume",
);

?>
<section class="my_account mb-6">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <?php include(get_stylesheet_directory()."/views/pieces/account_side.php"); ?>
      </div>
      <div class="col-lg-9">
        <div class="d-sm-flex align-items-center mb-4">
          <div class="page_title d-flex flex-grow-1">
            <h1 class="mb-0 ">Manage Portfolio</h1>
          </div>
          <a href="#" data-toggle="modal" data-target="#add_portfolio"  class="btn btn-secondary add_alert float-sm-right mb-3">+ Add to portfolio</a>
        </div>
        <ul class="portfolio_list mb-4">
          <li class="total_value">Total value <span><?=number_format($portfolio_list["total_value"],2);?></span></li>
          <li># of items <span><?=count($portfolio_list["items"]);?></span></li>
          <li>Daily change  <span><?=$portfolio_list["daily_change"];?></span></li>
          <li>Daily change (%) <span><?=$portfolio_list["daily_changepercent"];?></span></li>
        </ul>
        <div class="white_bg">
          <?php if(!count($portfolio_list["items"])){ ?>
          <h3 class="no_results my-5" style="text-align:center;">Sorry, no results</h3>
<?php }else{ ?>
  <div class="table-responsive">

          <table class="table table-striped table-borderless browse_table">
            <thead>
              <tr>
                <th scope="col">Symbol</th>
                <th scope="col">Name</th>
                <th scope="col">Price </th>
                <th scope="col">Change </th>
                <th scope="col">%Change </th>
                <th scope="col">Volume </th>
                <th scope="col"># of units</th>
                <th scope="col">Total Value</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($portfolio_list["items"] as $portfolio_item){
if($portfolio_item["ticker_price"]["change"]>=0){
  $change_class="green";
}else{
  $change_class="red";

}
                 ?>
              <tr>
                <td><a href="<?=$portfolio_item["permalink"]; ?>"><?=$portfolio_item["ticker"]; ?></a></td>
                <td><a href="<?=$portfolio_item["permalink"]; ?>"><?=$portfolio_item["title"]; ?></a></td>
                <td><?=$portfolio_item["ticker_price"]["last"]; ?></td>
                <td class="<?=$change_class;?>"><?=$portfolio_item["ticker_price"]["change"]; ?></td>
                <td class="<?=$change_class;?>"><?=$portfolio_item["ticker_price"]["changepercent"]; ?>%</td>
                <td><?=$portfolio_item["ticker_price"]["tradevolume"]; ?></td>
                <td><b><?=$portfolio_item["count"]; ?></b></td>
                <td><b><?=$portfolio_item["value"]; ?></b></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
<?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="add_portfolio" tabindex="-1" role="dialog" aria-labelledby="registerLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg signup_modal" role="document">
    <div class="modal-content">

      <div class="modal-body pt-5">
        <form action="<?=get_bloginfo("url");?>/wp-admin/admin-post.php" method="post" enctype="multipart/form-data" id="register_form" class="mx-0 text-left signup_form">
          <?php $preferences = get_user_meta(UID,"preferences",false);  ?>
          <?php if ($error_msg) { ?>
            <div class="alert alert-danger">
              <?php echo $error_msg; ?>
            </div>
          <?php } else { ?>
            <div class="alert alert-danger" style="display: none;"></div>
          <?php } ?>
          <div class="row1">
            <div id="watchlist_search">
              <div class="search_input">
                <input id="watchlist_input" type="text" class="form-control" placeholder="Search for companies...">
                <button class="btn w-25 text-right">Search</button>
              </div>
              <script>
              jQuery(document).ready(function(){
                jQuery(".remove_portfolio_button").click(function(e){
                  if(confirm("Are you sure? This cannot be undone.")){
                  }else{
                    e.preventDefault();
                    return false;
                  }

                });

                var watchlist_autocomplete;
                jQuery("body").click(function(){
                  jQuery("#watchlist_input").val("");
                  jQuery('#watchlist_autocomplete').css("height","0").html("");

                })

                jQuery("#watchlist_input").keyup(function(){
                  var text = jQuery(this).val();
                  watchlist_autocomplete = jQuery.ajax({
                    url: bloginfo_url+"/wp-admin/admin-ajax.php?action=get_portfolio_autocomplete&key="+jQuery("#watchlist_input").val(),
                    beforeSend : function()    {
                      if(watchlist_autocomplete != null) {
                        watchlist_autocomplete.abort();
                      }
                    },
                    success: function(data) {
                      if(data){
                        jQuery('#watchlist_autocomplete').css("height","auto").html(data).show();
                      }else{
                        jQuery('#watchlist_autocomplete').css("height","0").html("");
                      }
                    }
                  });
                });
              });
              </script>
              <div id="watchlist_autocomplete">

              </div>
            </div>
            <div id="watchlist_results" style="display:none;">
              <div class="row pt-4">
                <div class="col-sm-6">
                  <div class="alert_box">
                    <h3 id="add_portfolio_symbol">
                      AAPL
                    </h3>
                    <div class="row">
                      <table class="table">
                        <tr id="add_portfolio_last">
                          <td>Price:</td>
                          <td><b id="add_portfolio_last_val">$10.00</b></td>
                        </tr>
                        <tr id="add_portfolio_change">
                          <td>Change:</td>
                          <td><b id="add_portfolio_change_val">$10.00</b></td>
                        </tr>
                        <tr id="add_portfolio_changepercent">
                          <td>Change %:</td>
                          <td><b id="add_portfolio_changepercent_val">$10.00</b></td>
                        </tr>
                        <tr id="add_portfolio_tradevolume">
                          <td>Trade Volume:</td>
                          <td><b id="add_portfolio_tradevolume_val">$10.00</b></td>
                        </tr>
                        <tr id="add_portfolio_sharevolume">
                          <td>Share Volume:</td>
                          <td><b id="add_portfolio_sharevolume_val">$10.00</b></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <h3>How many shares?</h3>

                  <div class=" form-group">
                    <input type="text" name="value" class="numbers_only required form-control" />
                  </div>
                  <hr />
                  <div class="">
                    <input type="hidden" id="add_portfolio_symbol_input" name="ticker" value="" />
                    <input type="hidden" name="action" value="tdf_portfolio_add" />
                    <button type="submit" class="btn btn-primary w-100">Add to Portfolio</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>
