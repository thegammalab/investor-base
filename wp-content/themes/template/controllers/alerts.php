<?php
if ( ! class_exists('TDF_Alerts') ){

  class TDF_Alerts {

    public function __construct() {
      add_filter( 'tdf_get_alerts', array( $this, 'get_alerts' ), 10);
      add_filter( 'tdf_get_alerts_history', array( $this, 'get_alerts_history' ), 10, 1);

      add_filter( 'tdf_has_alerts', array( $this, 'has_alerts' ), 10, 1);

      add_action( 'admin_post_tdf_alert_add', array( $this, 'add_alert' ));
      add_action( 'admin_post_nopriv_tdf_alert_add', array( $this, 'add_alert' ));

      add_action( 'admin_post_tdf_alert_update', array( $this, 'update_alert' ));
      add_action( 'admin_post_nopriv_tdf_alert_update', array( $this, 'update_alert' ));
      //
      // add_action( 'wp_ajax_update_alert', array( $this, 'update_alert' ));
      // add_action( 'wp_ajax_nopriv_update_alert', array( $this, 'update_alert' ));

      add_action( 'wp_ajax_get_alert_autocomplete', array( $this, 'get_alert_autocomplete' ));
      add_action( 'wp_ajax_nopriv_get_alert_autocomplete', array( $this, 'get_alert_autocomplete' ));

      add_action( 'wp_ajax_get_alert_notif', array( $this, 'get_alert_notif' ));
      add_action( 'wp_ajax_nopriv_get_alert_notif', array( $this, 'get_alert_notif' ));
    }

    function has_alerts($uid=0){
      global $wpdb;
      if(!$uid){
        $uid = get_current_user_id();
      }
      if($wpdb->get_var("SELECT * FROM `wp_alerts_triggered`,`wp_alerts` WHERE  `wp_alerts`.`id`=`wp_alerts_triggered`.`alert_id` AND `user_id`='".$uid."' AND `read`='0'")){
        return 1;
      }else{
        return 0;
      }
    }

    function get_alert_notif(){
      global $wpdb;
      $uid = get_current_user_id();
      if($uid){
        $res = $wpdb->get_results("SELECT *,`wp_alerts_triggered`.`value` AS `new_value`,`wp_alerts_triggered`.`id` AS `alert_id` FROM `wp_alerts_triggered`,`wp_alerts` WHERE  `wp_alerts`.`id`=`wp_alerts_triggered`.`alert_id` AND `user_id`='".$uid."' AND `notif_alert`='0' ORDER BY `time` DESC");
        foreach($res as $it){
          $post_id = $wpdb->get_var("SELECT `post_id` FROM `wp_postmeta` WHERE `meta_value` = '".$it->ticker."' AND `meta_key` LIKE '%_symbol'");
          if($it->type=="sharevolume_more" || $it->type=="sharevolume_less"){
            $type = 'trade volume';
          }
          $wpdb->query("UPDATE `wp_alerts_triggered` SET `notif_alert`='".time()."' WHERE `id`='".$it->alert_id."'");

          ?>
          <li><i class="fas fa-exclamation-circle"></i> <a href="<?=get_permalink($post_id); ?>"><b><?=$it->ticker; ?></b></a> <?=$type; ?> has reached <b><?=number_format($it->new_value,2); ?></b></li>
          <?php
        }
      }
      die();
    }

    function get_alerts(){
      global $wpdb;
      $alerts = array();
      $results = $wpdb->get_results("SELECT * FROM `wp_alerts` WHERE `user_id`='".get_current_user_id()."'");
      foreach($results as $item){
        $post_id = $wpdb->get_var("SELECT `post_id` FROM `".$wpdb->postmeta."` WHERE `meta_key` LIKE 'company_code' AND `meta_value` LIKE '".$item->ticker."'");
        $the_price = apply_filters('tdf_get_company_latest_price',$post_id);
        $type_pieces = explode("_",$item->type);

        $alerts[]=array(
          "alert_id"=>$item->id,
          "type"=>$item->type,
          "type_val"=>$type_pieces[0],
          "type_dir"=>$type_pieces[1],
          "ticker"=>$item->ticker,
          "value"=>$item->value,
          "post_id"=>$post_id,
          "ticker_price"=>array(
            "last"=>$the_price["last"],
            "change"=>$the_price["change"],
            "changepercent"=>$the_price["changepercent"],
            "tradevolume"=>$the_price["tradevolume"],
            "sharevolume"=>$the_price["sharevolume"],
          )
        );
      }
      return $alerts;
    }

    function get_alerts_history($max_items=0){
      global $wpdb;
      $uid = get_current_user_id();
      $alerts = array();
      $sql = "SELECT *,`wp_alerts_triggered`.`value` AS `new_value`,`wp_alerts_triggered`.`id` AS `alert_id` FROM `wp_alerts_triggered`,`wp_alerts` WHERE  `wp_alerts`.`id`=`wp_alerts_triggered`.`alert_id` AND `user_id`='".$uid."' ORDER BY `time` DESC";
      if($max_items){
        $sql.=" LIMIT 0,".$max_items;
      }
      $results = $wpdb->get_results($sql);
      foreach($results as $item){
        $post_id = $wpdb->get_var("SELECT `post_id` FROM `".$wpdb->postmeta."` WHERE `meta_key` LIKE 'company_code' AND `meta_value` LIKE '".$item->ticker."'");
        $the_price = apply_filters('tdf_get_company_latest_price',$post_id);
        $type_pieces = explode("_",$item->type);

        $wpdb->query("UPDATE `wp_alerts_triggered` SET `read`='".time()."' WHERE `id`='".$item->alert_id."'");

        $alerts[]=array(
          "alert_id"=>$item->id,
          "post_id" => $post_id,
          "alert_name"=>"Trigger if ".$type_pieces[0]." ".$type_pieces[1]." than ".$item->value,
          "value" => $item->new_value,
          "ticker"=>$item->ticker,
          "time"=> date("F j, Y, g:i a",$item->time),
        );
      }
      return $alerts;
    }

    function add_alert(){
      global $wpdb;
      if(get_current_user_id()){
        $wpdb->query("INSERT INTO `wp_alerts` SET `user_id`='".get_current_user_id()."', `ticker`='".$_REQUEST["ticker"]."', `type`='".$_REQUEST["type"]."', `value`='".$_REQUEST["value"]."'");
        header("Location:".get_permalink(PAGE_ID_ACCOUNT_ALERTS)."?action=alert_added");
      }else{
        header("Location:".get_bloginfo("url"));
      }
    }

    function update_alert(){
      global $wpdb;
      if(get_current_user_id()){
        if($_REQUEST["remove_alert"]){
          $wpdb->query("DELETE FROM `wp_alerts` WHERE `user_id`='".get_current_user_id()."' AND `id`='".$_REQUEST["alert_id"]."'");
          header("Location:".get_permalink(PAGE_ID_ACCOUNT_ALERTS)."?action=alert_removed");

        }else{
          $wpdb->query("UPDATE `wp_alerts` SET `type`='".$_REQUEST["type"]."', `value`='".$_REQUEST["value"]."' WHERE `user_id`='".get_current_user_id()."' AND `id`='".$_REQUEST["alert_id"]."'");
          header("Location:".get_permalink(PAGE_ID_ACCOUNT_ALERTS)."?action=alert_updated");

        }
      }else{
        header("Location:".get_bloginfo("url"));
      }
    }


    function get_alert_autocomplete(){
      global $wpdb;
      $key =$_GET["key"];
      session_start();

      $exchange = $_SESSION["exchange_code"];
      $comp_res = array();
      $news_res = array();
      if($key){
        $res = $wpdb->get_results("SELECT `post_id`,`meta_key`,`meta_value` FROM `".$wpdb->postmeta."` WHERE `meta_key` LIKE 'company_code' AND `meta_value` LIKE '".$key."%' LIMIT 0,5");
        foreach($res as $item){
          $key_pieces = explode($item->meta_key);

          $exchange = $key_pieces[0];
          $the_price = apply_filters('tdf_get_company_latest_price',$item->post_id,$exchange);

          $comp_res[$item->post_id]= array(
            "post_id"=>$item->post_id,
            "exchange"=>$exchange,
            "symbol"=>$item->meta_value,
            "title"=>get_the_title($item->post_id),
            "last"=>$the_price["last"],
            "change"=>$the_price["change"],
            "changepercent"=>$the_price["changepercent"],
            "tradevolume"=>$the_price["tradevolume"],
            "sharevolume"=>$the_price["sharevolume"],
          );
        }

        $res = $wpdb->get_results("SELECT `ID`,`post_title`,`post_type` FROM `".$wpdb->posts."` WHERE `post_title` LIKE '%".$key."%' AND `post_type`='companies' LIMIT 0,20");
        foreach($res as $item){
          $exchange = apply_filters('tdf_get_company_exchanges',$item->ID);
          $the_price = apply_filters('tdf_get_company_latest_price',$item->ID,$exchange["code"]);

          $comp_res[$item->ID]= array(
            "post_id"=>$item->ID,
            "exchange"=>"",
            "symbol"=>get_post_meta($item->ID,"company_code",true),
            "title"=>$item->post_title,
            "last"=>$the_price["last"],
            "change"=>$the_price["change"],
            "changepercent"=>$the_price["changepercent"],
            "tradevolume"=>$the_price["tradevolume"],
            "sharevolume"=>$the_price["sharevolume"],
          );

        }
        if(count($comp_res) ){
          ?>
          <div id="watchlist_autocomplete_inner">
            <ul id="company_results">
              <?php
              $comp_res= array_slice($comp_res,0,5);
              foreach($comp_res as $item){
                $perma = get_permalink($item["post_id"])."/".$item["exchange"];
                ?>
                <li>
                  <div class="row">
                    <div class="col-sm-3"><a href="#" class="autocomplete_watchlist" data-symbol="<?=$item["symbol"]; ?>" data-name="<?=$item["title"]; ?>" data-last="<?=$item["last"]; ?>" data-change="<?=$item["change"]; ?>" data-changepercent="<?=$item["changepercent"]; ?>" data-sharevolume="<?=$item["sharevolume"]; ?>" data-tradevolume="<?=$item["tradevolume"]; ?>"><strong><?=$item["symbol"]; ?></strong></a></div>
                    <div class="col-sm-9"><a href="#" class="autocomplete_watchlist" data-symbol="<?=$item["symbol"]; ?>" data-name="<?=$item["title"]; ?>" data-last="<?=$item["last"]; ?>" data-change="<?=$item["change"]; ?>" data-changepercent="<?=$item["changepercent"]; ?>" data-sharevolume="<?=$item["sharevolume"]; ?>" data-tradevolume="<?=$item["tradevolume"]; ?>"><?=$item["title"]; ?></a></div>
                  </div>
                </li>
                <?php
              }
              ?>
            </ul>
            <script>
            jQuery(document).ready(function(){
              jQuery(".autocomplete_watchlist").click(function(){
                jQuery("#watchlist_input").val("");
                jQuery('#watchlist_autocomplete').css("height","0").html("");

                jQuery("#watchlist_results").show();
                jQuery("#add_alert_symbol").html(jQuery(this).attr("data-symbol"));

                jQuery("#add_alert_symbol_input").val(jQuery(this).attr("data-symbol"));

                jQuery("#add_alert_last").hide();
                if(jQuery(this).attr("data-last")){
                  jQuery("#add_alert_last").show();
                  jQuery("#add_alert_last_val").html(jQuery(this).attr("data-last"));
                }

                jQuery("#add_alert_change").hide();
                if(jQuery(this).attr("data-change")){
                  jQuery("#add_alert_change").show();
                  jQuery("#add_alert_change_val").html(jQuery(this).attr("data-change"));
                }

                jQuery("#add_alert_changepercent").hide();
                if(jQuery(this).attr("data-changepercent")){
                  jQuery("#add_alert_changepercent").show();
                  jQuery("#add_alert_changepercent_val").html(jQuery(this).attr("data-changepercent"));
                }

                jQuery("#add_alert_tradevolume").hide();
                if(jQuery(this).attr("data-tradevolume")){
                  jQuery("#add_alert_tradevolume").show();
                  jQuery("#add_alert_tradevolume_val").html(jQuery(this).attr("data-tradevolume"));
                }

                jQuery("#add_alert_sharevolume").hide();
                if(jQuery(this).attr("data-sharevolume")){
                  jQuery("#add_alert_sharevolume").show();
                  jQuery("#add_alert_sharevolume_val").html(jQuery(this).attr("data-sharevolume"));
                }

              });

            });
            </script>

          </div>
          <?php
        }
      }
      die();
    }
  }

  new TDF_Alerts;
}
