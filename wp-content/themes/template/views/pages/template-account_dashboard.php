<?php
/*
Template Name: Account Dashboard
*/
?>
<section class="my_account mb-6">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <?php include(get_stylesheet_directory()."/views/pieces/account_side.php"); ?>
      </div>
      <div class="col-lg-9">
        <div class="d-flex align-items-center mb-4">
          <div class="page_title d-flex flex-grow-1">
            <h1 class="mb-0 ">Welcome, <?=apply_filters('tdf_get_display_name',UID); ?></h1>
          </div>
        </div>
        <?php
        $alerts_trigger_list = apply_filters('tdf_get_alerts_history',3);
        if(count($alerts_trigger_list)){
          ?>
          <div class="white_bg">
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
          </div>
          <hr class="my-5">
        <?php } ?>
        <h2>What are you looking to do?</h2>
        <ul class="list_buttons d-sm-flex" >
          <li class="flex-fill"><a href="<?=get_permalink(PAGE_ID_ACCOUNT_WATCHLIST); ?>">Manage Watchlist</a></li>
          <li class="flex-fill"><a href="<?=get_permalink(PAGE_ID_ACCOUNT_PORTFOLIO); ?>">Manage Portfolio</a></li>
          <li class="flex-fill"><a href="<?=get_permalink(PAGE_ID_ACCOUNT_ALERTS); ?>">Manage Alerts</a></li>
        </ul>



      </div>
    </div>
  </div>
</section>
