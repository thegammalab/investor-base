<?php
if ( ! class_exists('TDF_Investorbase') ){

  class TDF_Investorbase {

    public function __construct() {
      add_action( 'wp_ajax_post_like_add', array( $this, 'post_like_add' ));
      add_action( 'wp_ajax_nopriv_post_like_add', array( $this, 'post_like_add' ));

      add_action( 'wp_ajax_post_like_remove', array( $this, 'post_like_remove' ));
      add_action( 'wp_ajax_nopriv_post_like_remove', array( $this, 'post_like_remove' ));

      add_action( 'wp_ajax_watchlist_add', array( $this, 'watchlist_add' ));
      add_action( 'wp_ajax_nopriv_watchlist_add', array( $this, 'watchlist_add' ));

      add_action( 'wp_ajax_watchlist_remove', array( $this, 'watchlist_remove' ));
      add_action( 'wp_ajax_nopriv_watchlist_remove', array( $this, 'watchlist_remove' ));

      add_action( 'wp_ajax_send_invite', array( $this, 'send_invite' ));
      add_action( 'wp_ajax_nopriv_send_invite', array( $this, 'send_invite' ));

      add_filter( 'tdf_get_company_latest_price', array( $this, 'latest_price' ), 10, 2);
      add_filter( 'tdf_get_company_annual_price', array( $this, 'annual_price' ), 10, 1);

      add_filter( 'tdf_get_company_quotes', array( $this, 'company_quotes' ), 10, 2);
      add_filter( 'tdf_get_company_filings', array( $this, 'company_filings' ), 10, 2);
      add_filter( 'tdf_get_company_financials', array( $this, 'company_financials' ), 10, 2);
      add_filter( 'tdf_get_company_enh_quotes', array( $this, 'enh_company_quotes' ), 10, 2);
      add_filter( 'tdf_get_company_news', array( $this, 'company_news' ), 10, 2);

      add_action( 'wp_ajax_get_company_list', array( $this, 'get_company_list' ));
      add_action( 'wp_ajax_nopriv_get_company_list', array( $this, 'get_company_list' ));

      add_action( 'wp_ajax_get_ajax_news', array( $this, 'get_news' ));
      add_action( 'wp_ajax_nopriv_get_ajax_news', array( $this, 'get_news' ));

      add_action( 'wp_ajax_get_ajax_company_news', array( $this, 'get_company_news' ));
      add_action( 'wp_ajax_nopriv_get_ajax_company_news', array( $this, 'get_company_news' ));

      add_action( 'wp_ajax_get_search_autocomplete', array( $this, 'get_search_autocomplete' ));
      add_action( 'wp_ajax_nopriv_get_search_autocomplete', array( $this, 'get_search_autocomplete' ));

      add_action( 'wp_ajax_get_watchlist_autocomplete', array( $this, 'get_watchlist_autocomplete' ));
      add_action( 'wp_ajax_nopriv_get_watchlist_autocomplete', array( $this, 'get_watchlist_autocomplete' ));

      add_filter( 'tdf_get_company_exchanges', array( $this, 'get_company_exchanges' ), 10, 1);
      add_filter( 'tdf_get_exchange_flag', array( $this, 'get_exchange_flag' ), 10, 1);

      add_filter( 'tdf_get_alert_history', array( $this, 'get_alert_history' ), 10, 1);

    }

    function send_invite(){
      $user_data = get_userdata($_REQUEST["friend_id"]);

      $email = new TDF_Email_Model;
      $email->send_email("send_invite",$_REQUEST["invite_email"],array("user_name"=>ucfirst(get_user_meta($user_data->ID, "first_name", true)) . " " . ucfirst(get_user_meta($user_data->ID, "last_name", true)),"user_login"=>$user_data->user_login,"invite_link"=>get_bloginfo("url")."?action=invite_signup&friend_id=".$_REQUEST["friend_id"]));

    }

    function get_search_autocomplete(){
      global $wpdb;
      $key =$_GET["key"];
      $exchange = $_SESSION["exchange_code"];
      $comp_res = array();
      $news_res = array();
      if($key){
        $res = $wpdb->get_results("SELECT `post_id`,`meta_key`,`meta_value` FROM `".$wpdb->postmeta."` WHERE `meta_key` LIKE 'company_code' AND `meta_value` LIKE '".$key."%' LIMIT 0,5");
        foreach($res as $item){
          $the_exchange = get_post_meta($item->post_id,"default_exchange",true);
          $comp_res[$item->post_id]= array(
            "post_id"=>$item->post_id,
            "exchange"=>$the_exchange,
            "symbol"=>$item->meta_value,
            "title"=>get_the_title($item->post_id)
          );
        }

        $res = $wpdb->get_results("SELECT `post_id`,`meta_key`,`meta_value` FROM `".$wpdb->postmeta."` WHERE `meta_key`!='company_code' AND `meta_value` LIKE '".$key."%' LIMIT 0,5");
        foreach($res as $item){
          if(!isset($comp_res[$item->post_id])){
            $the_exchange = explode("_",$item->meta_key);

            $comp_res[$item->post_id]= array(
              "post_id"=>$item->post_id,
              "exchange"=>$the_exchange[0],
              "symbol"=>$item->meta_value,
              "title"=>get_the_title($item->post_id)
            );
          }
        }

        $res = $wpdb->get_results("SELECT `ID`,`post_title`,`post_type` FROM `".$wpdb->posts."` WHERE (`post_title` LIKE '%".$key."%') LIMIT 0,20");
        foreach($res as $item){
          if($item->post_type=="companies"){
            $the_exchange = get_post_meta($item->ID,"default_exchange",true);
            $comp_res[$item->ID]= array(
              "post_id"=>$item->ID,
              "exchange"=>$the_exchange,
              "symbol"=>get_post_meta($item->ID,"company_code",true),
              "title"=>$item->post_title
            );
          }elseif($item->post_type=="post" || $item->post_type=="analysis" || $item->post_type=="events"){
            $news_res[$item->ID]= array(
              "post_id"=>$item->ID,
              "title"=>$item->post_title
            );
          }
        }
        if(count($comp_res) || count($news_res)){
          ?>
          <div id="autocomplete_inner">
            <?php if(count($comp_res)){ ?>
              <h4>Company results</h4>
              <ul id="company_results">
                <?php
                $comp_res= array_slice($comp_res,0,5);
                foreach($comp_res as $item){
                  $perma = get_permalink($item["post_id"])."/".$item["exchange"];
                  ?>
                  <li>
                    <div class="row">
                      <div class="col-sm-3"><a href="<?=$perma;?>"><strong><?=$item["symbol"]; ?></strong></a></div>
                      <div class="col-sm-9"><a href="<?=$perma;?>"><?=$item["title"]; ?></a></div>
                    </div>
                  </li>
                <?php } ?>
              </ul>
              <?php
            }
            if(count($news_res)){ ?>
              <h4>News results</h4>
              <ul id="news_results">
                <?php
                $news_res= array_slice($news_res,0,5);
                foreach($news_res as $item){
                  $perma = get_permalink($item["post_id"])
                  ?>
                  <li>
                    <div class="row">
                      <div class="col-12"><a href="<?=$perma;?>"><?=$item["title"]; ?></a></div>
                    </div>
                  </li>
                <?php } ?>
              </ul>
            <?php } ?>
          </div>
          <?php
        }
      }
      die();
    }

    function get_watchlist_autocomplete(){
      global $wpdb;
      $key =$_GET["key"];
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
                    <div class="col-sm-3"><a href="#" class="autocomplete_watchlist" data-symbol="<?=$item["symbol"]; ?>" data-name="<?=$item["title"]; ?>" data-last="<?=$item["last"]; ?>" data-change="<?=$item["change"]; ?>" data-changepercent="<?=$item["changepercent"]; ?>"><strong><?=$item["symbol"]; ?></strong></a></div>
                    <div class="col-sm-9"><a href="#" class="autocomplete_watchlist" data-symbol="<?=$item["symbol"]; ?>" data-name="<?=$item["title"]; ?>" data-last="<?=$item["last"]; ?>" data-change="<?=$item["change"]; ?>" data-changepercent="<?=$item["changepercent"]; ?>"><?=$item["title"]; ?></a></div>
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

                jQuery("#watchlist_results").show().find("tbody").append('<tr><td><strong class="blue">'+jQuery(this).attr("data-symbol")+'</strong><input type="hidden" name="user_meta_watchlist[]" value="'+jQuery(this).attr("data-symbol")+'" /></td><td><span class="blue">'+jQuery(this).attr("data-name")+'</span></td><td>'+jQuery(this).attr("data-last")+'</td><td>'+jQuery(this).attr("data-change")+'</td><td>'+jQuery(this).attr("data-changepercent")+'</td><td><i class="fa fa-times"></i></td></tr>');

                jQuery("#watchlist_results .fa").click(function(){
                  jQuery(this).closest("tr").remove();
                  if(jQuery("#watchlist_results").find("tr").length==1){
                    jQuery("#watchlist_results").hide();
                  }
                });
              });

            });
            </script>

          </div>
          <?php
        }
      }
      die();
    }

    function get_company_list(){
      global $wpdb;
      header('Content-Type: application/json');
      header("Access-Control-Allow-Origin: *");
session_start();
      if(!$_SESSION["exchange_code"]){
        $_SESSION["exchange"] = 9866;
        $_SESSION["exchange_info"] = get_term_by("id",$_SESSION["exchange"],"exchanges");
        $_SESSION["exchange_code"] = strtolower(get_term_meta($_SESSION["exchange"],"ex_code",true));
      }

      if(isset($_GET["search"]["tax_exchanges"])){
        $_SESSION["exchange"] = $_GET["search"]["tax_exchanges"][0];
        $_SESSION["exchange_info"] = get_term_by("id",$_SESSION["exchange"],"exchanges");
        $_SESSION["exchange_code"] = strtolower(get_term_meta($_SESSION["exchange"],"ex_code",true));
      }

      $exchange_code = $_SESSION["exchange_code"];
      $per_page = 25;

      if($_GET["order"]){
        $ord = $_GET["order"];
      }else{
        $ord = 'title_asc';
      }
      $search = $_GET["search"];
      if(!$search){
        $search = array();
      }
      $search["tax_exchanges"]=array($_SESSION["exchange"]);
      $search["meta_".$exchange_code."_hasprice"]=1;
      $results = apply_filters('tdf_get_posts',"companies",$per_page,$_GET["page_no"],array("search"=>$search,"order"=>$ord,"get_basic_info"=>true));

      $arr = array();
      foreach($results["items"] as $item){
        //$the_price = apply_filters('tdf_get_snap_price',$item["post_id"]);
        $comp_code=get_post_meta($item["post_id"],$exchange_code."_symbol",true);
        $priceinfo=get_post_meta($item["post_id"],$exchange_code."_pricedata",true);
        if($priceinfo["change"]>=0){
          $change_class = 'green';
        }else{
          $change_class = 'red';
        }
        $arr[]=array(
          get_watchlist_button($comp_code),
          '<a class="font-weight-bold dark_blue" href="'.$item["post_permalink"]."".$exchange_code.'">'.$comp_code.'</a>',
          '<a class="dark_blue" href="'.$item["post_permalink"]."".$exchange_code.'">'.$item["post_title"].'</a>',
          round($priceinfo["last"],2),
          '<span class="'.$change_class.'">'.number_format($priceinfo["change"],2).'</span>',
          '<span class="'.$change_class.'">'.round($priceinfo["changepercent"],3)."%".'</span>',
          number_format($priceinfo["sharevolume"],0),
        );
      }

      $current_page = max(1,$results["query"]->query["paged"]);
      $big = 99999999999;
      $pagenum_link = get_pagenum_link( $big );
      $pagination = paginate_links(array(
        "total"=>$results["query"]->max_num_pages,
        "current"=>$current_page,
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'show_all'           => false,
        'end_size'           => 2,
        'mid_size'           => 1,
        'prev_next'          => true,
        'prev_text'          => __('<img src="'.get_bloginfo("template_directory").'/assets/images/left_arrow.png" alt="Prev page" />'),
        'next_text'          => __('<img src="'.get_bloginfo("template_directory").'/assets/images/right_arrow.png" alt="Next page" />'),
      ));

      unset($_GET["action"]);
      $pagenum_link = explode("paged",str_replace("&&","&",str_replace("#038;","&",$pagenum_link)));
      $pagination = str_replace($pagenum_link[0].http_build_query($_GET)."&paged=","#",str_replace("&&","&",str_replace("#038;","&",$pagination)));

      if($results["query"]->found_posts){
        $counts = (($current_page-1)*$per_page+1)."-".min($current_page*$per_page,$results["query"]->found_posts)." of ".($results["query"]->found_posts)." results";
      }else{
        $counts = "no results";
      }

      $data = json_encode(array("counts"=>$counts,"pagination"=>$pagination,"results"=>$arr));
      echo $data;
      die();
    }

    function get_company_news(){
      global $wpdb;

      $per_page = 10;
      $search = $_GET["search"];
      if(!$search){
        $search = array();
      }
      $results = apply_filters('tdf_get_posts',"post",$per_page,$_GET["page_no"],array("search"=>$search));
      foreach($results["items"] as $item){ ?>
        <div class="col-sm-6">
          <?php include(get_stylesheet_directory()."/views/posts/post/content-item.php"); ?>
        </div>
        <?php
      }
      die();
    }

    function get_news(){
      global $wpdb;

      $per_page = 10;
      $search = $_GET["search"];
      if(!$search){
        $search = array();
      }
      $results = apply_filters('tdf_get_posts',"post",$per_page,$_GET["page_no"],array("search"=>$search));

      for($i=0;$i<2;$i++){
        $item = $results["items"][$i];
        include(get_stylesheet_directory()."/views/posts/post/content-item.php");
      }
      ?>
      <div class="row">
        <?php
        for($i=2;$i<5;$i++){
          $item = $results["items"][$i];
          include(get_stylesheet_directory()."/views/posts/post/content-item-sm.php");
        }
        ?>
      </div>
      <?php
      for($i=5;$i<10;$i++){
        $item = $results["items"][$i];
        include(get_stylesheet_directory()."/views/posts/post/content-item.php");
      }

      die();
    }
    function get_company_exchanges($post_id){
      session_start();
      $the_exchange = get_post_meta($post_id,"default_exchange",true);
      $ex_slug = get_query_var("exchange_slug");
      $exchanges = wp_get_post_terms($post_id,"exchanges");
      $exch_list = array();
      foreach($exchanges as $ex){
        $post_ex_slug = strtolower(get_term_meta($ex->term_id,"ex_code",true));
        $exch_list[$post_ex_slug] = array(
          "code"=>$post_ex_slug,
          "object"=>$ex,
          "id"=>$ex->term_id
        );
      }

      if(isset($exch_list[$ex_slug])){
        $current_exchange = $exch_list[$ex_slug]["code"];
        $current_exchange_id = $exch_list[$ex_slug]["id"];
        $current_exchange_obj = $exch_list[$ex_slug]["object"];
      }elseif(isset($exch_list[$_SESSION["exchange_code"]])){
        $current_exchange = $exch_list[$_SESSION["exchange_code"]]["code"];
        $current_exchange_id = $exch_list[$_SESSION["exchange_code"]]["id"];
        $current_exchange_obj = $exch_list[$_SESSION["exchange_code"]]["object"];
      }elseif(isset($exch_list[$the_exchange])){
        $current_exchange = $exch_list[$the_exchange]["code"];
        $current_exchange_id = $exch_list[$the_exchange]["id"];
        $current_exchange_obj = $exch_list[$the_exchange]["object"];
      }else{
        foreach($exch_list as $f=>$v){
          if(!$current_exchange){
            if($current_exchange==$the_exchange){
              $current_exchange = $v["code"];
              $current_exchange_id = $v["id"];
              $current_exchange_obj = $v["object"];
            }
          }
        }
      }

      $arr = array(
        "exchanges" => $exchanges,
        "object" => $current_exchange_obj,
        "code" => $current_exchange,
        "id" => $current_exchange_id,
      );

      return $arr;
    }

    function get_exchange_flag($term_id){
      return '<img src="'.get_bloginfo("template_directory")."/assets/images/".get_term_meta($term_id,"country",true).".svg".'" />';
    }


    function enh_company_quotes($company_code,$start=0){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getEnhancedQuotes.json?webmasterId=".get_option("webmaster_id")."&symbols=".$company_code);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      $json = json_decode($output,true);

      $res = $json["results"]["quote"][0];
      $arr = array();
      foreach($res as $f=>$v){
        if(is_array($v)){
          foreach($v as $f1=>$v1){
            if(is_array($v1)){
              foreach($v1 as $f2=>$v2){
                $arr[$f1."_".$f2] = $v2;
              }
            }else{
              $arr[$f1]=$v1;
            }
          }
        }else{
          $arr[$f]=$v;
        }
      }

      return $arr;
    }

    function company_news($company_code,$start=0){
      global $wpdb;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getHeadlines.json?webmasterId=".get_option("webmaster_id")."&thumbnailurl=true&summary=true&summLen=300&resultsPerPage=30&topics=".$company_code);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      $json = json_decode($output,true);
      print_r($json);
      foreach($json["results"]["news"][0]["newsitem"] as $newsitem){
        echo $newsitem["newsid"];
        $post_id = $wpdb->get_var("SELECT `post_id` FROM `".$wpdb->postmeta."` WHERE `meta_key`='newsid' AND `meta_value`='".$newsitem["newsid"]."'");
        if($post_id){
          echo " - found ".$post_id."<br>";
        }
      }
      return $arr;
    }

    function company_quotes($company_code,$start=0){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getFullHistory.json?webmasterId=".get_option("webmaster_id")."&start=".(date("Y")-5)."-".date("m")."-".date("d")."&symbol=".$company_code);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      $json = json_decode($output,true);

      $array = array();
      foreach($json["results"]["history"][0]["eoddata"] as $i=>$item){
        $array[$item["date"]] = $item;
      }

      return $array;
    }

    function company_filings($company_code,$type=""){
      $array = array();

      for($p=1;$p<6;$p++){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getCompanyFilings.json?webmasterId=".get_option("webmaster_id")."&inclXbrl=true&page=".$p."&symbol=".$company_code);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($output,true);

        //print_r($json);
        foreach($json["results"]["filings"]["filing"] as $i=>$item){
          $array[]=$item;
        }
      }
      return $array;
    }

    function company_financials($company_code){
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getFinancialsEnhancedBySymbol.json?webmasterId=".get_option("webmaster_id")."&reportType=A&numberOfReports=5&symbol=".$company_code);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      $json = json_decode($output,true);

      $array = array();
      foreach($json["results"]["Company"]["Report"] as $i=>$item){
        foreach($item["CashFlow"] as $f=>$v){
          for($j=1;$j<8;$j++){
            $array["CashFlow"][$f][date("Y")-$j]="-";
          }
        }
        foreach($item["IncomeStatement"] as $f=>$v){
          for($j=1;$j<8;$j++){
            $array["IncomeStatement"][$f][date("Y")-$j]="-";
          }
        }
        foreach($item["BalanceSheet"] as $f=>$v){
          for($j=1;$j<8;$j++){
            $array["BalanceSheet"][$f][date("Y")-$j]="-";
          }
        }
      }
      foreach($json["results"]["Company"]["Report"] as $i=>$item){
        $array["dates"][substr($item["reportDate"],0,4)] = $item["reportDate"];

        foreach($item["CashFlow"] as $f=>$v){
          $array["CashFlow"][$f][substr($item["reportDate"],0,4)]=$v;
        }
        foreach($item["IncomeStatement"] as $f=>$v){
          $array["IncomeStatement"][$f][substr($item["reportDate"],0,4)]=$v;
        }
        foreach($item["BalanceSheet"] as $f=>$v){
          $array["BalanceSheet"][$f][substr($item["reportDate"],0,4)]=$v;
        }

      }      //

      return ($array);
    }

    function latest_price($company_id,$exchange_code=""){
      global $wpdb;
      if(!$exchange_code){
        $key = $wpdb->get_var("SELECT `meta_key` FROM `".$wpdb->postmeta."` WHERE `meta_value`='".get_post_meta($company_id,"company_code",true)."' AND `post_id`='".$company_id."' AND `meta_key`!='company_code'");
        $key_pieces = explode("_",$key);
        $exchange_code = $key_pieces[0];
      }
      $arr = get_post_meta($company_id,$exchange_code."_pricedata",true);
      $arr["symbol"] = get_post_meta($company_id,$exchange_code."_symbol",true);
      $arr["datetime"] = get_post_meta($company_id,$exchange_code."_datetime",true);

      return $arr;
      //return $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."company_pricing` WHERE `company_id` = '".$company_id."';", ARRAY_A);
    }

    function annual_price($company_id){
      global $wpdb;
      $annual_min = $wpdb->get_var("SELECT MIN(`close`) FROM `".$wpdb->prefix."company_pricing` WHERE `company_id` = '".$company_id."' AND `date` > '".date("Y-m-d",strtotime("-1 year"))."';");
      $annual_max = $wpdb->get_var("SELECT MAX(`close`) FROM `".$wpdb->prefix."company_pricing` WHERE `company_id` = '".$company_id."' AND `date` > '".date("Y-m-d",strtotime("-1 year"))."';");
      return array("min"=>$annual_min,"max"=>$annual_max);
    }

    function post_like_add(){
      global $wpdb;
      $post_id = intval($_GET["post_id"]);
      if($uid = get_current_user_id()){
        $wpdb->query("DELETE FROM `".$wpdb->usermeta."` WHERE `user_id`='".$uid."' AND `meta_key`='liked_posts' AND `meta_value`='".$post_id."'");
        $wpdb->query("INSERT INTO `".$wpdb->usermeta."` SET `user_id`='".$uid."', `meta_key`='liked_posts', `meta_value`='".$post_id."'");
      }
      $count = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->usermeta."` WHERE `meta_key`='liked_posts' AND `meta_value`='".$post_id."'");
      update_post_meta($post_id,"like_count",$count);
      echo $count;
      die();
    }

    function post_like_remove(){
      global $wpdb;
      $post_id = intval($_GET["post_id"]);
      if($uid = get_current_user_id()){
        $wpdb->query("DELETE FROM `".$wpdb->usermeta."` WHERE `user_id`='".$uid."' AND `meta_key`='liked_posts' AND `meta_value`='".$post_id."'");
      }
      $count = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->usermeta."` WHERE `meta_key`='liked_posts' AND `meta_value`='".$post_id."'");
      update_post_meta($post_id,"like_count",$count);
      echo $count;
      die();
    }

    function watchlist_add(){
      global $wpdb;
      $post_id = ($_GET["post_id"]);
      if($uid = get_current_user_id()){
        $wpdb->query("DELETE FROM `".$wpdb->usermeta."` WHERE `user_id`='".$uid."' AND `meta_key`='watchlist' AND `meta_value`='".$post_id."'");
        $wpdb->query("INSERT INTO `".$wpdb->usermeta."` SET `user_id`='".$uid."', `meta_key`='watchlist', `meta_value`='".$post_id."'");
      }
      $count = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->usermeta."` WHERE `meta_key`='watchlist' AND `meta_value`='".$post_id."'");
      update_post_meta($post_id,"like_count",$count);
      echo '<i class="fas fa-minus"></i>';
      if($_GET["full"]){
        echo 'in watchlist';
      }
      die();
    }

    function watchlist_remove(){
      global $wpdb;
      $post_id = ($_GET["post_id"]);
      if($uid = get_current_user_id()){
        $wpdb->query("DELETE FROM `".$wpdb->usermeta."` WHERE `user_id`='".$uid."' AND `meta_key`='watchlist' AND `meta_value`='".$post_id."'");
      }
      $count = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->usermeta."` WHERE `meta_key`='watchlist' AND `meta_value`='".$post_id."'");
      update_post_meta($post_id,"like_count",$count);
      echo '<i class="fas fa-plus"></i>';
      if($_GET["full"]){
        echo 'add to watchlist';
      }
      die();
    }
  }

  new TDF_Investorbase;
}
