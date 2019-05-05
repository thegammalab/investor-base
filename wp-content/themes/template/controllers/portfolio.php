<?php
if ( ! class_exists('TDF_Portfolio') ){

  class TDF_Portfolio {

    public function __construct() {
      add_filter( 'tdf_get_portfolio', array( $this, 'get_portfolio' ), 10);
      add_filter( 'tdf_get_portfolio_ids', array( $this, 'get_portfolio_ids' ), 10);

      add_action( 'admin_post_tdf_portfolio_add', array( $this, 'add_portfolio' ));
      add_action( 'admin_post_nopriv_tdf_portfolio_add', array( $this, 'add_portfolio' ));

      add_action( 'admin_post_tdf_portfolio_update', array( $this, 'update_portfolio' ));
      add_action( 'admin_post_nopriv_tdf_portfolio_update', array( $this, 'update_portfolio' ));
      //
      // add_action( 'wp_ajax_update_portfolio', array( $this, 'update_portfolio' ));
      // add_action( 'wp_ajax_nopriv_update_portfolio', array( $this, 'update_portfolio' ));

      add_action( 'wp_ajax_get_portfolio_autocomplete', array( $this, 'get_portfolio_autocomplete' ));
      add_action( 'wp_ajax_nopriv_get_portfolio_autocomplete', array( $this, 'get_portfolio_autocomplete' ));
    }

    function get_portfolio_ids(){
      global $wpdb;
      $results = $wpdb->get_results("SELECT DISTINCT `post_id`,`meta_value` FROM `wp_portfolio`, `wp_postmeta` WHERE `wp_postmeta`.`meta_key`='company_code' AND `wp_postmeta`.`meta_value`=`wp_portfolio`.`ticker` AND `wp_portfolio`.`user_id`='".UID."'");
      $arr = array();
      foreach($results as $items){
        $key = $wpdb->get_var("SELECT `meta_key` FROM `wp_postmeta` WHERE `meta_key`!='company_code' AND `meta_value`='".$items->meta_value."'");
        $key_pieces = explode("_",$key);
        $arr[$items->post_id]=array(
          "code"=>$items->meta_value,
          "exchange"=>$key_pieces[0]
        );
      }
      return $arr;
    }


    function get_portfolio(){
      global $wpdb;
      $portfolio = array(
        "items"=>array(),
        "total_value"=>0,
        "daily_change"=>0,
        "daily_changepercent"=>0,
      );

      $old_total_val = 0;
      $total_val = 0;

      $results = $wpdb->get_results("SELECT * FROM `wp_portfolio` WHERE `user_id`='".get_current_user_id()."'");
      foreach($results as $item){
        $post_id = $wpdb->get_var("SELECT `post_id` FROM `".$wpdb->postmeta."` WHERE `meta_key` LIKE 'company_code' AND `meta_value` LIKE '".$item->ticker."'");
        $the_price = apply_filters('tdf_get_company_latest_price',$post_id);

        $total_val+=$item->value*$the_price["last"];
        $old_total_val+=$item->value*($the_price["last"]-$the_price["change"]);

        $portfolio["items"][]=array(
          "portfolio_id"=>$item->id,
          "type"=>$item->type,
          "title"=>get_the_title($post_id),
          "permalink"=>get_permalink($post_id),
          "ticker"=>$item->ticker,
          "count"=>$item->value,
          "value"=>number_format($item->value*$the_price["last"],2),
          "post_id"=>$post_id,
          "ticker_price"=>array(
            "last"=>round($the_price["last"],3),
            "change"=>round($the_price["change"],3),
            "changepercent"=>round($the_price["changepercent"],3),
            "tradevolume"=>number_format($the_price["tradevolume"],0),
            "sharevolume"=>number_format($the_price["sharevolume"],0),
          )
        );
      }

      $portfolio["total_value"] = $total_val;
      $portfolio["daily_change"] = round($total_val-$old_total_val,3);
      $portfolio["daily_changepercent"] = round(($total_val-$old_total_val)/$total_val*100,3);

      return $portfolio;
    }

    function get_portfolio_history($portfolio_id=0){
      $portfolio = array();

      return $portfolio;
    }

    function add_portfolio(){
      global $wpdb;
      if(get_current_user_id()){
        $wpdb->query("INSERT INTO `wp_portfolio` SET `user_id`='".get_current_user_id()."', `ticker`='".$_REQUEST["ticker"]."', `value`='".$_REQUEST["value"]."'");
        header("Location:".get_permalink(PAGE_ID_ACCOUNT_PORTFOLIO)."?action=portfolio_added");
      }else{
        header("Location:".get_bloginfo("url"));
      }
    }

    function update_portfolio(){
      global $wpdb;
      if(get_current_user_id()){
        if($_REQUEST["remove_portfolio"]){
          $wpdb->query("DELETE FROM `wp_portfolio` WHERE `user_id`='".get_current_user_id()."' AND `id`='".$_REQUEST["portfolio_id"]."'");
          header("Location:".get_permalink(PAGE_ID_ACCOUNT_PORTFOLIO)."?action=portfolio_removed");

        }else{
          $wpdb->query("UPDATE `wp_portfolio` SET `value`='".$_REQUEST["value"]."' WHERE `user_id`='".get_current_user_id()."' AND `id`='".$_REQUEST["portfolio_id"]."'");
          header("Location:".get_permalink(PAGE_ID_ACCOUNT_PORTFOLIO)."?action=portfolio_updated");

        }
      }else{
        header("Location:".get_bloginfo("url"));
      }
    }


    function get_portfolio_autocomplete(){
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
                jQuery("#add_portfolio_symbol").html(jQuery(this).attr("data-symbol"));

                jQuery("#add_portfolio_symbol_input").val(jQuery(this).attr("data-symbol"));

                jQuery("#add_portfolio_last").hide();
                if(jQuery(this).attr("data-last")){
                  jQuery("#add_portfolio_last").show();
                  jQuery("#add_portfolio_last_val").html(jQuery(this).attr("data-last"));
                }

                jQuery("#add_portfolio_change").hide();
                if(jQuery(this).attr("data-change")){
                  jQuery("#add_portfolio_change").show();
                  jQuery("#add_portfolio_change_val").html(jQuery(this).attr("data-change"));
                }

                jQuery("#add_portfolio_changepercent").hide();
                if(jQuery(this).attr("data-changepercent")){
                  jQuery("#add_portfolio_changepercent").show();
                  jQuery("#add_portfolio_changepercent_val").html(jQuery(this).attr("data-changepercent"));
                }

                jQuery("#add_portfolio_tradevolume").hide();
                if(jQuery(this).attr("data-tradevolume")){
                  jQuery("#add_portfolio_tradevolume").show();
                  jQuery("#add_portfolio_tradevolume_val").html(jQuery(this).attr("data-tradevolume"));
                }

                jQuery("#add_portfolio_sharevolume").hide();
                if(jQuery(this).attr("data-sharevolume")){
                  jQuery("#add_portfolio_sharevolume").show();
                  jQuery("#add_portfolio_sharevolume_val").html(jQuery(this).attr("data-sharevolume"));
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

  new TDF_Portfolio;
}
