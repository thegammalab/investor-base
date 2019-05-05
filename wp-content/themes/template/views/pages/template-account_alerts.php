<?php
/*
Template Name: Account Alerts
*/

$alerts_list = apply_filters('tdf_get_alerts',0);
$alerts_trigger_list = apply_filters('tdf_get_alerts_history',100);

$type_labels = array(
  "price_more"=>"Price reaches more than",
  "price_less"=>"Price reaches less than",
  "sharevolume_more"=>"Share volume reaches more than",
  "sharevolume_less"=>"Share volume reaches less than",
);

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
            <h1 class="mb-0 ">Manage Alerts</h1>
          </div>
          <a href="#" data-toggle="modal" data-target="#add_alert" class="btn btn-secondary add_alert float-sm-right mb-3">+ Add new alert</a>
        </div>
        <?php if(count($alerts_list)){ ?>
          <div class="row mb-3">
            <?php foreach($alerts_list as $alert){ ?>
              <div class="col-md-3 mb-3">
                <div class="alert_box">
                  <p>
                    Stock: <b><?=$alert["ticker"];?></b>
                  </p>
                  <h5>Trigger if <?=$alert["type_val"];?></h5>
                  <h3><?php if($alert["type_dir"]=="more"){echo ">";}else{echo "<";};?><?php if($alert["type_val"]=="sharevolume"){echo number_format($alert["value"],0);}else{echo number_format($alert["value"],2);}?></h3>
                  <a href="#" data-toggle="modal" data-target="#edit_alert_<?=$alert["alert_id"]; ?>">+ manage alert</a>
                </div>
              </div>

              <div class="modal fade" id="edit_alert_<?=$alert["alert_id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="registerLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg signup_modal" role="document">
                  <div class="modal-content">
                    <div class="modal-body pt-5">
                      <form action="<?=get_bloginfo("url");?>/wp-admin/admin-post.php" method="post" enctype="multipart/form-data" id="register_form" class="mx-0 text-left signup_form">
                        <?php if ($error_msg) { ?>
                          <div class="alert alert-danger">
                            <?php echo $error_msg; ?>
                          </div>
                        <?php } else { ?>
                          <div class="alert alert-danger" style="display: none;"></div>
                        <?php } ?>
                        <div class="row1">
                          <div class="watchlist_results">
                            <div class="row">
                              <div class="col-sm-6">
                                <div class="alert_box">
                                  <h3 id="">
                                    <?=$alert["ticker"];?>
                                  </h3>
                                  <div class="row">
                                    <table class="table">
                                      <?php foreach($alert["ticker_price"] as $f=>$v){ if($v){ ?>
                                        <tr>
                                          <td><?=$price_labels[$f]; ?></td>
                                          <td><b><?=$v; ?></b></td>
                                        </tr>
                                      <?php }
                                    } ?>
                                  </table>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <h3>Notify me if:</h3>
                              <div class=" form-group">
                                <select name="type" class="form-control required">
                                  <?php foreach($type_labels as $f=>$v){
                                    if($f==$alert["type"]){
                                      $sel = 'selected="selected"';
                                    }else{
                                      $sel = '';
                                    }
                                    ?>
                                    <option <?=$sel;?> value="<?=$f; ?>"><?=$v; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <div class=" form-group">
                                <input type="text" name="value" value="<?=$alert["value"];?>" class="numbersonly required form-control" />
                              </div>
                              <hr />
                              <div class="">
                                <input type="hidden" name="alert_id" value="<?=$alert["alert_id"];?>" />
                                <input type="hidden" name="action" value="tdf_alert_update" />
                                <button type="submit" class="btn btn-primary w-100">Update Alert</button>
                              </div>
                              <input type="submit" name="remove_alert" class="remove_alert_button w-100" value="Remove Alert" />

                            </div>
                          </div>
                        </div>
                      </div>

                    </form>

                  </div>
                </div>
              </div>
            </div>

          <?php } ?>
        </div>
      <?php } ?>
      <div class="white_bg">
        <?php if(count($alerts_trigger_list)){ ?>
          <div class="table-responsive">

          <table class="table table-striped table-borderless browse_table">
            <thead>
              <tr>
                <th scope="col">Company</th>
                <th scope="col">Alert</th>
                <th scope="col">Value</th>
                <th scope="col">Triggered on</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($alerts_trigger_list as $alert_trigger){ ?>
                <tr>
                  <td><a href="<?=get_permalink($alert_trigger["post_id"]); ?>" class="font-weight-bold dark_blue"><?=$alert_trigger["ticker"]; ?></a></td>
                  <td><?=$alert_trigger["alert_name"]; ?></td>
                  <td><?=$alert_trigger["value"]; ?></td>
                  <td><?=$alert_trigger["time"]; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <?php }else{ ?>
          <h3 class="no_results my-5" style="text-align:center;">No alerts have been triggered</h3>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
</section>

<div class="modal fade" id="add_alert" tabindex="-1" role="dialog" aria-labelledby="registerLabel" aria-hidden="true">
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
jQuery(".remove_alert_button").click(function(e){
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
                    url: bloginfo_url+"/wp-admin/admin-ajax.php?action=get_alert_autocomplete&key="+jQuery("#watchlist_input").val(),
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
                    <h3 id="add_alert_symbol">
                      AAPL
                    </h3>
                    <div class="row">
                      <table class="table">
                        <tr id="add_alert_last">
                          <td>Price:</td>
                          <td><b id="add_alert_last_val">$10.00</b></td>
                        </tr>
                        <tr id="add_alert_change">
                          <td>Change:</td>
                          <td><b id="add_alert_change_val">$10.00</b></td>
                        </tr>
                        <tr id="add_alert_changepercent">
                          <td>Change %:</td>
                          <td><b id="add_alert_changepercent_val">$10.00</b></td>
                        </tr>
                        <tr id="add_alert_tradevolume">
                          <td>Trade Volume:</td>
                          <td><b id="add_alert_tradevolume_val">$10.00</b></td>
                        </tr>
                        <tr id="add_alert_sharevolume">
                          <td>Share Volume:</td>
                          <td><b id="add_alert_sharevolume_val">$10.00</b></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <h3>Notify me if:</h3>
                  <div class=" form-group">
                    <select name="type" class="form-control required">
                      <?php foreach($type_labels as $f=>$v){ ?>
                        <option value="<?=$f; ?>"><?=$v; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class=" form-group">
                    <input type="text" name="value" class="numbersonly required form-control" />
                  </div>
                  <hr />
                  <div class="">
                    <input type="hidden" id="add_alert_symbol_input" name="ticker" value="" />
                    <input type="hidden" name="action" value="tdf_alert_add" />
                    <button type="submit" class="btn btn-primary w-100">Add new Alert</button>
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
