<?php
if ( ! class_exists('TDF_Import') ){

  class TDF_Import {

    public function __construct() {
      update_option("webmaster_id",102396);

      add_action( 'wp_ajax_import_run_categories', array( $this, 'run_categories' ));
      add_action( 'wp_ajax_nopriv_import_run_categories', array( $this, 'run_categories' ));

      add_action( 'wp_ajax_get_news', array( $this, 'get_news' ));
      add_action( 'wp_ajax_nopriv_get_news', array( $this, 'get_news' ));

      add_action( 'wp_ajax_update_images', array( $this, 'update_images' ));
      add_action( 'wp_ajax_nopriv_update_images', array( $this, 'update_images' ));

      add_action( 'wp_ajax_delete_duplicates', array( $this, 'delete_duplicates' ));
      add_action( 'wp_ajax_nopriv_delete_duplicates', array( $this, 'delete_duplicates' ));

      add_action( 'wp_ajax_update_image', array( $this, 'update_image' ));
      add_action( 'wp_ajax_nopriv_update_image', array( $this, 'update_image' ));

      add_action( 'wp_ajax_get_companies', array( $this, 'get_companies' ));
      add_action( 'wp_ajax_nopriv_get_companies', array( $this, 'get_companies' ));

      add_action( 'wp_ajax_get_company_info', array( $this, 'get_company_info' ));
      add_action( 'wp_ajax_nopriv_get_company_info', array( $this, 'get_company_info' ));

      add_action( 'wp_ajax_get_company_pricing', array( $this, 'get_company_pricing' ));
      add_action( 'wp_ajax_nopriv_get_company_pricing', array( $this, 'get_company_pricing' ));

      add_action( 'wp_ajax_get_company_snap_pricing', array( $this, 'get_company_snap_pricing' ));
      add_action( 'wp_ajax_nopriv_get_company_snap_pricing', array( $this, 'get_company_snap_pricing' ));

      add_action( 'wp_ajax_get_company_filings', array( $this, 'get_company_filings' ));
      add_action( 'wp_ajax_nopriv_get_company_filings', array( $this, 'get_company_filings' ));

      add_action( 'wp_ajax_get_company_financials', array( $this, 'get_company_financials' ));
      add_action( 'wp_ajax_nopriv_get_company_financials', array( $this, 'get_company_financials' ));

      add_action( 'wp_ajax_get_company_news_new', array( $this, 'get_company_news' ));
      add_action( 'wp_ajax_nopriv_get_company_news_new', array( $this, 'get_company_news' ));

      add_filter( 'tdf_get_snap_price', array( $this, 'get_snap_price_info' ), 10, 1);
    }

    public function delete_duplicates(){
      global $wpdb;

      $duplicate_titles = $wpdb->get_results("SELECT `post_title` FROM `wp_posts` WHERE `post_type`='post' GROUP BY `post_title` HAVING COUNT(*) > 1  LIMIT 0,1000;");

      foreach( $duplicate_titles as $item ) {
        $title = $item->post_title;
        $post_id = $wpdb->get_var("SELECT MIN(`ID`) FROM `wp_posts` WHERE `post_title` LIKE '".esc_sql($title)."' AND `post_type`='post'");
        $wpdb->query("DELETE FROM `wp_posts` WHERE `post_title` LIKE '".esc_sql($title)."' AND `post_type`='post' AND `ID`!=".$post_id);
        echo "DELETE FROM `wp_posts` WHERE `post_title` LIKE '".esc_sql($title)."' AND `post_type`='post' AND `ID`!=".$post_id.";<br>";
        //echo $post_id.": ".$title."<br>";
      }
    }

    public function update_image(){
      $this->set_post_img($_GET["post_id"]);
    }

    public function update_images(){
      global $wpdb;

      $results = $wpdb->get_results("SELECT * FROM `".$wpdb->posts."` WHERE (`post_type`='post' OR `post_type`='events' OR `post_type`='analysis')");
      foreach($results as $item){
        if(!has_post_thumbnail($item->ID)){
          echo $item->ID."<br>";
          $this->set_post_img($item->ID);
        }else{
          $att_id = get_post_meta($item->ID,"_thumbnail_id",true);
          if(get_post_meta($att_id,"from_unsplash",true)){
            update_post_meta($item->ID,"has_unsplash_img",true);

          }
        }
      }
    }

    public function set_post_img($post_id){
      global $wpdb;

      if($thumb_url = get_post_meta($post_id,"thumbnailurl",true)){
        echo $thumb_url." - ";
        if(!has_post_thumbnail($post_id)){
          $att_id = $this->get_img_from_url($thumb_url);
          set_post_thumbnail($post_id, $att_id);
          echo $att_id." - ";
        }
        echo "<br>";
      }else{
        $unsplash_count = $wpdb->get_var("SELECT COUNT(DISTINCT `post_id`) FROM `".$wpdb->postmeta."` WHERE `meta_key` = 'from_unsplash'");
        if(500 > $unsplash_count){
          // $rand = rand(1400,1600);
          // $att_id = $this->get_img_from_url("https://source.unsplash.com/collection/1966837/".$rand."x".$rand,substr(sanitize_title(get_the_title($post_id)),0,100).".jpg");
          // update_post_meta($att_id,"from_unsplash",1);
          // set_post_thumbnail($post_id, $att_id);
        }else{
          $att_id = $wpdb->get_var("SELECT `post_id` FROM `".$wpdb->postmeta."` WHERE `meta_key` = 'from_unsplash' ORDER BY RAND() LIMIT 0,1");
          set_post_thumbnail($post_id, $att_id);
          update_post_meta($post_id,"has_unsplash_img",1);

        }
      }

      return $att_id;
    }

    public function get_img_from_url($img_src,$filename="",$from_rand=0){
      $img_src_pieces = explode("/", $img_src);
      if(!$filename){
        $filename = $img_src_pieces[count($img_src_pieces) - 1];
      }
      //print_r($img_src_pieces);

      $uploaddir = wp_upload_dir();
      $uploadfile = $uploaddir['path'] . '/' . $filename;
      //echo $uploadfile;

      $contents = file_get_contents($img_src);
      $savefile = fopen($uploadfile, 'w');
      fwrite($savefile, $contents);
      fclose($savefile);

      $wp_filetype = wp_check_filetype(basename($filename), null);

      $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => $filename,
        'post_content' => '',
        'post_status' => 'inherit'
      );

      $attach_id = wp_insert_attachment($attachment, $uploadfile, $post_id);
      if($from_rand){
        //update_post_meta($attach_id,"from_unsplash",1);
      }
      $imagenew = get_post($attach_id);
      $fullsizepath = get_attached_file($imagenew->ID);
      $attach_data = wp_generate_attachment_metadata($attach_id, $fullsizepath);
      wp_update_attachment_metadata($attach_id, $attach_data);

      return $attach_id;
    }

    public function get_news($pid=0){
      ini_set("memory_limit","2048M");
      ini_set("max_execution_time","300");
      global $wpdb;
      // create curl resource
      if($pid){
        $company_code = get_post_meta($pid,"company_code",true);
        $the_term = get_term_by("name",$company_code,"post_tag");
        if(!$the_term){
          $company_term = wp_insert_term($company_code,"post_tag");
        }
      }
      $the_cats = get_terms("category","hide_empty=0");
      $the_cats_array = array();
      $the_cats_name = array();
      foreach($the_cats as $term){
        $the_cats_name[strtolower($term->slug)] = $term->term_id;
        $the_cats_array[]=strtolower($term->slug);
      }
      $the_tags = get_terms("post_tag","hide_empty=0");
      $the_tags_array = array();
      $the_tags_name = array();
      foreach($the_tags as $term){
        $the_tags_name[sanitize_title($term->name)] = $term->term_id;
        $the_tags_array[]=sanitize_title($term->name);
      }

      // print_r($the_tags_array);
      // echo '<br><br><br><br><br>';

      $ch = curl_init();
      if($pid){
        echo "<br>".$pid." - ".$company_code."<br>";
        curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getHeadlines.json?webmasterId=".get_option("webmaster_id")."&thumbnailurl=true&summary=true&summLen=300&resultsPerPage=30&topics=".$company_code);
      }else{
        curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getHeadlines.json?topics=SHOWALLNEWS&webmasterId=".get_option("webmaster_id")."&thumbnailurl=true&summary=true&summLen=300");
      }
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);

      $json = json_decode($output,true) ;
      foreach($json["results"]["news"][0]["newsitem"] as $item){
        $post_id = $wpdb->get_var("SELECT `post_id` FROM `wp_postmeta` WHERE `meta_key`='newsid' AND `meta_value`='".$item["newsid"]."'");
        if(!$post_id){
          $post_id = $wpdb->get_var("SELECT `ID` FROM `wp_posts` WHERE `post_title` LIKE '".esc_sql($item["headline"])."'");
        }
        if(!$post_id){
          $topics = str_replace(" ","",str_replace("]","",str_replace("[","",$item["topic"])));
          if(strpos($topics,",")){
            $topics_pieces = explode(",",$topics);
          }else{
            $topics_pieces = array();
          }
          if($company_code){
            $topics_pieces[]=$company_code;
          }

          $tags = array();
          $cats = array();
          foreach($topics_pieces as $topics_piece){
            if(in_array(strtolower($topics_piece),$the_cats_array)){
              $cats[]=$the_cats_name[strtolower($topics_piece)];
            }elseif(in_array(sanitize_title($topics_piece),$the_tags_array)){
              $tags[]=$the_tags_name[sanitize_title($topics_piece)];
            }else{
              $tid = wp_insert_term($topics_piece,"post_tag");
              $tags[]=$tid["term_id"];
            }
          }

          $my_post = array(
            'post_title'    => $item["headline"],
            'post_excerpt'    => $item["qmsummary"],
            'post_content'    => $item["qmsummary"],
            'post_status'   => 'publish',
            'post_date'    => $item["datetime"],
            // 'post_category' => $cats,
            // 'tags_input' => $topics_pieces
          );

          // Insert the post into the database.
          $post_id = wp_insert_post( $my_post );
          echo "<br>adding ".$post_id;
          if(count($cats)){
            wp_set_post_terms($post_id,$cats,"category");
          }
          if(count($tags)){
            wp_set_post_terms($post_id,$tags,"post_tag");
          }

          update_post_meta($post_id,"newsid",$item["newsid"]);
          update_post_meta($post_id,"storyurl",$item["storyurl"]);
          update_post_meta($post_id,"source",$item["source"]);
          update_post_meta($post_id,"thumbnailurl",$item["thumbnailurl"]);
          $this->set_post_img($post_id);
          echo " - added <br>";
        }else{

          update_post_meta($post_id,"newsid",$item["newsid"]);
          update_post_meta($post_id,"storyurl",$item["storyurl"]);
          update_post_meta($post_id,"source",$item["source"]);
          update_post_meta($post_id,"thumbnailurl",$item["thumbnailurl"]);

          $topics = str_replace(" ","",str_replace("]","",str_replace("[","",$item["topic"])));
          if(strpos($topics,",")){
            $topics_pieces = explode(",",$topics);
          }else{
            $topics_pieces = array();
          }
          if($company_code){
            $topics_pieces[]=$company_code;
          }
          // print_r($topics_pieces);

          $tags = array();
          $cats = array();
          foreach($topics_pieces as $topics_piece){
            if(in_array(strtolower($topics_piece),$the_cats_array)){
              $cats[]=$the_cats_name[strtolower($topics_piece)];
            }elseif(in_array(sanitize_title($topics_piece),$the_tags_array)){
              $tags[]=$the_tags_name[sanitize_title($topics_piece)];
            }else{
              $tid = wp_insert_term($topics_piece,"post_tag");
              $tags[]=$tid["term_id"];
            }
          }
// print_r($tags);

          //
          // $my_post = array(
          //   'ID'           => $post_id,
          //   'post_title'    => $item["headline"],
          //   'post_excerpt'    => $item["qmsummary"],
          //   'post_content'    => $item["qmsummary"],
          //   'post_status'   => 'publish',
          //   'post_date'    => $item["datetime"],
          //   // 'post_category' => $cats,
          //   // 'tags_input' => $topics_pieces
          // );
          //
          // // echo "<br>Updated ".$post_id;
          //
          // // Insert the post into the database.
          // $post_id = wp_update_post( $my_post );
          echo "updating ".$post_id;
          if(count($cats)){
            wp_set_post_terms($post_id,$cats,"category");
          }
          if(count($tags)){
            wp_set_post_terms($post_id,$tags,"post_tag");
          }
          echo " - updated<br>";
          // echo "<br><br><br><br><br>";
        }
      }
    }

    public function run_categories(){

      $topics = array(
        array('ACCHTHIN','Accident & Health Insurance','Insurance and Government Health Programs News'),
        array('ADVMKTPR','Advertising, Marketing and PR','Public Relations, Investor Relations, Marketing and Advertising News'),
        array('AERODEFS','Aerospace and Defense','Aerospace, Defense, Aviation News'),
        array('AERODFMD','Aerospace/Defense - Major Diversified','Major Diversified Aerospace and Defense Company News'),
        array('AERODFPS','Aerospace/Defense Products & Services','Aerospace and Defense Product News'),
        array('ALTENERG','Alternate Energy','Wind, Solar and Natural Gas, Renewable Energy Resources News'),
        array('ANTITRST','Antitrust','Antitrust, Monopoly and Unfair Business Practice News'),
        array('APPLIANC','Appliances','Major and Household Appliance News'),
        array('ASIAPACI','Asia Pacific','News about Asia Pacific Region'),
        array('ASSMNGMT','Asset Management','Financial Resource and Asset Management News'),
        array('AUTOMTVE','Automotive','Auto Makers, After Market and Auto Suppliers News'),
        array('BANKFINA','Banking and Finance','Banking and Financial Institutions News'),
        array('BANKING9','Banking','Consumer Banking News'),
        array('BEVBREWS','Beverages - Brewers','Brewery and Brewing News'),
        array('BEVSOFTD','Beverages - Soft Drinks','Carbonated Beverage News'),
        array('BEVWINDS','Beverages - Wineries & Distillers','Wine and Spirits News'),
        array('BIGDATA','Big Data','Custom category'),
        array('BIOTCHNO','Biotechnology','Biotechnology Companies and Research News'),
        array('BODSH','BoD/SH','Board of Directors or Shareholders discussions'),
        array('BONDS001','Bonds','Bond Market News'),
        array('BREAKING','Breaking News and Urgent Updates','timely news, natural disasters and other leading stories'),
        array('BROADCRD','Broadcasting - Radio','Over the Air and Satellite Radio News'),
        array('BROADCTV','Broadcasting - TV','Over the Air, Satellite and Cable Subscription Television News'),
        array('BUSINESS','Economy, Business, and Finance','Economic, Business and Financial News'),
        array('BUYBACK1','Stock Buyback','Corporate Stock Shares Buyback News'),
        array('CAPITALG','Capital Gains','Corporate Capital Gains News'),
        array('CELEBRTY','Celebrity News and Gossip','Gossip and News about today\'s celebrities and wannabes'),
        array('CHEMICLS','Chemicals','Chemical Companies and Research News'),
        array('CIGARETT','Cigarettes','Cigarette Companies News'),
        array('COMMODIT','Commodities','Commodities and Futures News'),
        array('COMPHARD','Computer Hardware','Computer Hardware Products and Technology News'),
        array('COMPSWSR','Computer Software','Computer Software Products and Technology News'),
        array('COMPUTER','Computer','Computer Companies, Products and Technologies News'),
        array('CONFEREN','Conference Calls, Web Events','Corporate Conference Calls, Web Events and Annual Meeting News'),
        array('CONSGOOD','Consumer Goods','Consumer and Household Goods News'),
        array('CONSUMER','Consumer Affairs','Consumer Services and Product Recall News and Advisories'),
        array('CRYPTOC','Cryptocurrency','Cyrptocurrency News'),
        array('DATAWARE','Data Warehousing','Data Warehousing Products and Technologies News'),
        array('DIVIDEND','Cash Or Stock Dividend','Corporate Dividend News'),
        array('DRUGS999','Pharmaceutical','Pharmaceutical Companies and Research News'),
        array('EARNINGS','Earnings','Corporate Earnings News'),
        array('ECONOMY1','Economy','Economic and Economy News'),
        array('EDUCATIN','Education','All Levels of Education and Instruction News'),
        array('ELECTION','Election','Stories about Elections and Candidates'),
        array('ELECTRON','Electronics','Electronics Companies, Products and Technologies News'),
        array('ENERGY01','Energy','Energy Companies, Products and Technologies News'),
        array('ENERGY99','Energy','Copy of ENERGY01'),
        array('ENTRTAIN','Entertainment','Entertainment Companies, Products and People News'),
        array('EUROPE01','Europe','News about European Region'),
        array('FEDERAL','Federal','Stories about or from Federal Agencies, Policy and Policymakers'),
        array('FINANCE1','Finance','Finance Industry News'),
        array('FINANCIL','Financial','Financial Tools, Methods and Products News'),
        array('FINASERV','Financial and Business Services','Financial and Accounting Companies, Products and Methods'),
        array('FINSERVI','Financial and Business Services','Copy of FINASERV'),
        array('FOODSBEV','Food and Beverage','Food and Beverage Companies and Research'),
        array('FORNEXCH','Foreign Exchange (FOREX)','Foreign Exchange and Currency News'),
        array('FUTURES1','Futures Trading','Futures and Commodities News'),
        array('GAMING00','Video and Online Gaming','PC and Console Gaming Companies, Products and Technologies News and Reviews'),
        array('HISTORY0','History','News about Historic People, Places and Significant Events'),
        array('HLTHCARE','Healthcare','Healthcare Providers and Specialty News'),
        array('HLTHSERV','Health Services','Healthcare Companies, Products and Research News'),
        array('HOSPITAL','Hospitals','Healthcare Facilities and Administration News'),
        array('HUMANRES','Human Resources/Labor','Human Resources, Employment and Labor News'),
        array('INDUSGDS','Industrial Goods','Industrial Products, Goods and Services News'),
        array('INSIDRTR','Insider Trading','Insider Trading History, Stock Purchases and Activity News'),
        array('INTLTRAD','International Trade','Trade and Commerce between US and other Countries News'),
        array('INTRNET9','Internet','Internet Companies, Products and Technologies News'),
        array('INTRSTRA','Interest Rates','News about US Interest Rates and Impact on Economy'),
        array('INVESTOP','Investment Opinion','Investor Opinions, Stock Ratings and Performance Analysis News'),
        array('IPONEWS1','IPO\'s','Initial Public Offering News and Events'),
        array('LEGAL000','Legal News','Legal and Law Issues and News'),
        array('LEISURE9','Leisure','Leisure, Recreation, Travel and Culture News'),
        array('MANUFCTU','Manufacturing and Engineering','Industrial Manufacturing, Engineering and Technologies News'),
        array('MARKCOMM','Market News','company communication and market moving news'),
        array('MARKECON','Market and Economy','News that may affect stock markets and global economies'),
        array('MATERCON','Materials and Construction','Building Materials and Construction Design and Equipment News'),
        array('MEDIA999','Media','Books, Film, Music and Performing Arts News and Events'),
        array('MEDMARIJ','Medical Marijuana','Medical Marijuana minus illegal and illicit terms'),
        array('MEETINGS','Meetings','Meetings'),
        array('MERGERAC','Mergers & Acquisitions','Corporate Mergers and Acquisitions News and Events'),
        array('METALMIN','Metal and Mineral','Mining, Metals and Geological Exploration News'),
        array('MORTGAGE','Mortgage','Residential and Commercial Property Financing News'),
        array('MUTUALFD','Mutual Funds','Funds Management News'),
        array('NATGAS02','Natural Gas','Natural Gas Companies, Products and Technologies News'),
        array('NATIONAL','National News','US Government News and Events'),
        array('NATURALR','Natural Resources','Environmental and Agricultural Products News and Research'),
        array('NEWYORK1','New York','News about New York City'),
        array('NONCOMPANY','Non-Company','To filter out Non-Company News Releases'),
        array('NOUS','No US Distribution','Stories that contain \'Not for Distribution in the US\''),
        array('OILENERG','Oil & Energy','Oil and Gas Companies, Products and Technologies News'),
        array('POLITICS','Politics','Presidential Candidates and Political Parties News'),
        array('PRHYPE','Press Release Hype','stock hype, self serving press releases and other content'),
        array('RANT0000','Rantings','News on a Variety of Topics with Passionate or Irreverent Language'),
        array('RATINGS1','Ratings','Ratings on Stocks, Markets and Indexes'),
        array('REALESTT','Real Estate','Residential and Commercial Real Estate News'),
        array('REMOVE','REMOVE','Stories sent by providers that should not be processed'),
        array('RETAIL99','Wholesale and Retail','Wholesale and Resale Companies, Products and Technologies News'),
        array('SCIENCE0','Science','Scientific News and Research'),
        array('SOCIALIS','Social Issues','News about Social Issues such as Race, Religion, Sex/Gender'),
        array('SOFTWARE','Software','Software Industry Companies, Products and Technologies News'),
        array('SPAM','SPAM','stories with no editorial value, collection of links or other useless info'),
        array('STATEPOL','State Politics','State Politics feed'),
        array('STKSPLIT','Stock Splits','Corporate Stock and Shares Split News'),
        array('TECHNOLO','Computing and Information Technology','Technology Companies and Products News'),
        array('TELECOMU','Telecommunications','Telecommunications Companies, Products and Technologies News'),
        array('TOBACOO9','Tobacco','Tobacco Companies,Products and Legislation News'),
        array('TRANSPRT','Transportation','Transportation and Logistics Companies, Products and Technologies News'),
        array('UTILITIE','Utilities','Utilities Companies, Products and Technologies News'),
        array('UTILITIS','Utilities','Copy of UTILITIE'),
        array('VENTUREC','Venture Capital','Venture Capital and Angel Investing Financing News'),
        array('WORLDNEW','World News','News about International Trade, Foreign Policy and Global Events'),
      );

      foreach($topics as $topic){
        $term = get_term_by("name",$topic[1],"category");
        if(!$term){
          wp_insert_term(
            $topic[1], // the term
            'category', // the taxonomy
            array(
              'description'=> $topic[2],
              'slug' => $topic[0],
            )
          );
        }
      }
    }

    public function get_company_news(){
      ini_set("memory_limit","2048M");
      ini_set("max_execution_time","300");

      global $wpdb;
      if(isset($_GET["code"])){
        $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key` LIKE '%_symbol' AND `meta_value` LIKE '".$_GET["code"]."' ORDER BY `meta_value` ASC LIMIT 0,5");

      }elseif(isset($_GET["cron"])){
        $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key`='last_updated_news' AND `meta_value`<'".(time()-3*24*3600)."' ORDER BY `meta_value` ASC LIMIT 0,5");
      }else{
        $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key`='last_updated_news' AND `meta_value`<'".(time()-3*24*3600)."' ORDER BY `meta_value` ASC");
      }
      foreach($results as $i=>$item){
        // print_r($item);
        $pid = $item->post_id;
        if($comp_code = get_post_meta($pid,"company_code",true)){
          // echo $pid.": ".$comp_code."<br>";
          $this->get_news($pid);
          update_post_meta($pid,"last_updated_news",time());
        }else{
          update_post_meta($pid,"last_updated_news",time());
        }
      }
      die();
    }

    public function get_company_financials(){
      global $wpdb;
      $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key`='last_updated_financials' AND `meta_value`<'".(time()-2*24*3600)."'");
      foreach($results as $i=>$item){
        $pid = $item->post_id;
        if(get_post_meta($pid,"company_code",true)){
          echo $i.": ".$pid."<br>";
          $this->get_financials_info($pid);
        }
      }
      die();
    }

    public function get_company_filings(){
      global $wpdb;
      $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key`='last_updated_filings' AND `meta_value`<'".(time()-2*24*3600)."'");
      foreach($results as $i=>$item){
        $pid = $item->post_id;
        if(get_post_meta($pid,"company_code",true)){
          echo $i.": ".$pid."<br>";
          $this->get_filings_info($pid);
        }
      }
      die();
    }

    public function get_company_pricing(){
      global $wpdb;
      $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key`='last_updated_price' AND `meta_value`<'".(time()-1*24*3600)."'");
      foreach($results as $i=>$item){
        $pid = $item->post_id;
        if(get_post_meta($pid,"company_code",true)){
          echo $i.": ".$pid."<br>";

          $this->get_pricing_info($pid);

        }
      }
      die();
    }

    public function get_company_snap_pricing(){
      ini_set("memory_limit","2048M");
      ini_set("max_execution_time","300");
      global $wpdb;

      // $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key`='last_updated_info'");
      // foreach($results as $i=>$item){
      //   $pid = $item->post_id;
      //   $exchanges = get_post_meta($pid,"exchanges",true);
      //   foreach($exchanges as $exc=>$exc_item){
      //     if($price_info = get_post_meta($pid,strtolower($exc)."_pricedata",true)){
      //       update_post_meta($pid,strtolower($exc)."_testing",1);
      //       update_post_meta($pid,"last_updated_snap_price",time());
      //
      //       if($price_info["last"]){
      //         update_post_meta($pid,strtolower($exc)."_last",$price_info["last"]);
      //       }
      //       if($price_info["tradevolume"]){
      //         update_post_meta($pid,strtolower($exc)."_tradevolume",$price_info["tradevolume"]);
      //       }
      //       if($price_info["sharevolume"]){
      //         update_post_meta($pid,strtolower($exc)."_sharevolume",$price_info["sharevolume"]);
      //       }
      //       if($price_info["change"]){
      //         update_post_meta($pid,strtolower($exc)."_change",$price_info["change"]);
      //       }
      //       if($price_info["changepercent"]){
      //         update_post_meta($pid,strtolower($exc)."_changepercent",$price_info["changepercent"]);
      //       }
      //     }else{
      //       update_post_meta($pid,"last_updated_snap_price",0);
      //
      //     }
      //   }
      // }
      $min_dif = 30;
      if(isset($_GET["start"])){
        $start = $_GET["start"];
        $total = 500;
        $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key`='last_updated_snap_price' ORDER BY `meta_value` ASC LIMIT ".$start.",".$total);

      }elseif(isset($_GET["cron"])){
        $start = 0;
        $total = 20;
        $min_dif = 300;
        $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key`='last_updated_snap_price' AND `meta_value`<'".(time()-3*3600)."' ORDER BY `meta_value` ASC LIMIT 0,50");
      }else{
        $start=0;
        $total = 99999;
        $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key`='last_updated_snap_price' ORDER BY `meta_value` ASC LIMIT ".$start.",".$total);

      }
      foreach($results as $i=>$item){
        $pid = $item->post_id;
        // if(get_post_meta($pid,"last_updated_snap_price",true)<(time()-$min_dif*60)){
        $this->get_snap_price_info($pid);
        update_post_meta($pid,"last_updated_snap_price",time());
        // }

      }

      die();
    }

    public function get_company_info(){
      ini_set("memory_limit","2048M");
      ini_set("max_execution_time","300");
      global $wpdb;
      $results = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_key`='last_updated_info' AND `meta_value`<'".(time()-14*24*3600)."'");
      foreach($results as $i=>$item){
        $pid = $item->post_id;
        if(get_post_meta($pid,"company_code",true)){
          $this->get_basic_info($pid);
          $this->get_snap_price_info($pid);
        }
      }
      die();
    }

    function get_financials_info($pid){
      global $wpdb;

      $ch = curl_init();
      echo "http://app.quotemedia.com/data/getFinancialsEnhancedBySymbol.json?webmasterId=".get_option("webmaster_id")."&reportType=A&numberOfReports=5&symbol=".get_post_meta($pid,"company_code",true);
      curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getFinancialsEnhancedBySymbol.json?webmasterId=".get_option("webmaster_id")."&reportType=A&numberOfReports=5&symbol=".get_post_meta($pid,"company_code",true));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      $json = json_decode($output,true);

      //print_r($json);

      foreach($json["results"]["Company"]["Report"] as $i=>$item){
        update_post_meta($pid,"financials_".$i."_date",$item["reportDate"]);
        update_post_meta($pid,"financials_".$i."_year",substr($item["reportDate"],0,4));

        if(!$wpdb->get_var("SELECT `id` FROM `".$wpdb->prefix."company_financials` WHERE `company_id`='".$pid."' AND `date`='".$item["reportDate"]."'")){
          $wpdb->query("INSERT INTO `".$wpdb->prefix."company_financials` SET `company_id`='".$pid."', `date`='".$item["reportDate"]."', `CashFlow`='".json_encode($item["CashFlow"])."', `IncomeStatement`='".json_encode($item["IncomeStatement"])."', `BalanceSheet`='".json_encode($item["BalanceSheet"])."'");
        }
      }
      update_post_meta($pid,"last_updated_financials",time());
    }

    function get_filings_info($pid){
      global $wpdb;

      $ch = curl_init();
      echo "http://app.quotemedia.com/data/getCompanyFilings.json?webmasterId=".get_option("webmaster_id")."&inclXbrl=true&page=1&symbol=".get_post_meta($pid,"company_code",true);
      curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getCompanyFilings.json?webmasterId=".get_option("webmaster_id")."&inclXbrl=true&page=1&symbol=".get_post_meta($pid,"company_code",true));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      $json = json_decode($output,true);

      //print_r($json);

      foreach($json["results"]["filings"]["filing"] as $i=>$item){
        if(!$wpdb->get_var("SELECT `id` FROM `".$wpdb->prefix."company_filings` WHERE `company_id`='".$pid."' AND `description`='".$item["formdescription"]."' AND `date`='".$item["datefiled"]."'")){
          $wpdb->query("INSERT INTO `".$wpdb->prefix."company_filings` SET `company_id`='".$pid."', `form_type`='".$item["formtype"]."', `date`='".$item["datefiled"]."', `description`='".$item["formdescription"]."', `pages`='".$item["pages"]."', `pdf`='".$item["pdflink"]."', `www`='".$item["htmllink"]."', `doc`='".$item["doclink"]."', `xls`='".$item["xlslink"]."', `xls`='".$item["xbrllink"]."'");
        }

      }
      update_post_meta($pid,"last_updated_filings",time());
    }

    function get_pricing_info($pid){
      global $wpdb;

      $ch = curl_init();
      echo "http://app.quotemedia.com/data/getFullHistory.json?webmasterId=".get_option("webmaster_id")."&start=".(date("Y")-5)."-".date("m")."-".date("d")."&symbol=".get_post_meta($pid,"company_code",true);
      curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getFullHistory.json?webmasterId=".get_option("webmaster_id")."&start=".(date("Y")-5)."-".date("m")."-".date("d")."&symbol=".get_post_meta($pid,"company_code",true));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      $json = json_decode($output,true);

      foreach($json["results"]["history"][0]["eoddata"] as $i=>$item){
        if(!$wpdb->get_var("SELECT `id` FROM `".$wpdb->prefix."company_pricing` WHERE `company_id`='".$pid."' AND `date`='".$item["date"]."'")){
          $sql = "INSERT INTO `".$wpdb->prefix."company_pricing` SET `company_id`='".$pid."'";
        }
        foreach($item as $f=>$v){
          $sql.=", `".$f."`='".$v."'";
          if($item["date"]==date("Y-m-d")){
            update_post_meta($pid,"price_".$f,$v);
          }
        }
        $wpdb->query($sql);
      }

      update_post_meta($pid,"last_updated_price",time());
    }

    function get_basic_info($pid){
      global $wpdb;

      $company_code = "";
      if($code = get_post_meta($pid,"tsxc_symbol",true)){
        $company_code = $code;
      }elseif($code = get_post_meta($pid,"tsxvc_symbol",true)){
        $company_code = $code;
      }elseif($code = get_post_meta($pid,"cnq_symbol",true)){
        $company_code = $code;
      }elseif($code = get_post_meta($pid,"tsx_symbol",true)){
        $company_code = $code;
      }elseif($code = get_post_meta($pid,"alpha_symbol",true)){
        $company_code = $code;
      }elseif($code = get_post_meta($pid,"omega_symbol",true)){
        $company_code = $code;
      }elseif($code = get_post_meta($pid,"chix_symbol",true)){
        $company_code = $code;
      }elseif($code = get_post_meta($pid,"aqn_symbol",true)){
        $company_code = $code;
      }
      echo $pid.": ".$company_code."<br>";
      if($company_code){
        update_post_meta($pid,"company_code",$company_code);

        $ch = curl_init();
        echo "http://app.quotemedia.com/data/getCompanyBySymbol.json?webmasterId=".get_option("webmaster_id")."&symbol=".$company_code;
        curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getCompanyBySymbol.json?webmasterId=".get_option("webmaster_id")."&symbol=".$company_code);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($output,true);

        if(isset($json["results"])){
          if(isset($json["results"]["company"])){
            $my_post = array(
              'ID'       => $pid,
              'post_title'  => $json["results"]["company"][0]["symbolinfo"][0]["equityinfo"]["longname"],
              'post_name'  => sanitize_title($json["results"]["company"][0]["symbolinfo"][0]["equityinfo"]["longname"]),
              'post_content'  => $json["results"]["company"][0]["profile"]["longdescription"],
              'post_excerpt'  => $json["results"]["company"][0]["profile"]["shortdescription"],
              'post_status' => 'publish'
            );
            $post_id = wp_update_post($my_post);

            $company_cat = array();
            if($term_name = $json["results"]["company"][0]["profile"]["classification"]["sector"]){
              if($the_term = get_term_by("name",$term_name,"company_cat")){
                $term_id = $the_term->term_id;
              }else{
                $the_term = wp_insert_term($term_name, 'company_cat');
                $term_id = $the_term["term_id"];
              }
              $company_cat[]=$term_id;
            }
            $company_industry = array();
            if($term_name = $json["results"]["company"][0]["profile"]["classification"]["industry"]){
              if($the_term = get_term_by("name",$term_name,"company_industry")){
                $term_id = $the_term->term_id;
              }else{
                $the_term = wp_insert_term($term_name, 'company_industry');
                $term_id = $the_term["term_id"];
              }
              $company_industry[]=$term_id;
            }
            $company_sics = array();
            foreach($json["results"]["company"][0]["profile"]["classification"]["sics"]["sic"] as $sic){
              if($the_term = get_term_by("name",$sic["name"],"company_sics")){
                $term_id = $the_term->term_id;
              }else{
                $the_term = wp_insert_term($sic["name"], 'company_sics');
                $term_id = $the_term["term_id"];
              }

              $company_sics[]=$term_id;
            }


            (wp_set_post_terms($pid, $company_cat, "company_cat"));
            (wp_set_post_terms($pid, $company_industry, "company_industry"));
            (wp_set_post_terms($pid, $company_sics, "company_sics"));

            update_post_meta($pid,"cik",$json["results"]["company"][0]["profile"]["classification"]["cik"]);
            update_post_meta($pid,"naics",$json["results"]["company"][0]["profile"]["classification"]["naics"]);
            update_post_meta($pid,"web",$json["results"]["company"][0]["profile"]["info"]["website"]);
            update_post_meta($pid,"facisimile",$json["results"]["company"][0]["profile"]["info"]["facisimile"]);
            update_post_meta($pid,"phone",$json["results"]["company"][0]["profile"]["info"]["telephone"]);
            update_post_meta($pid,"email",$json["results"]["company"][0]["profile"]["info"]["email"]);

            update_post_meta($pid,"address",$json["results"]["company"][0]["profile"]["info"]["address"]["address1"]);
            update_post_meta($pid,"address2",$json["results"]["company"][0]["profile"]["info"]["address"]["address2"]);
            update_post_meta($pid,"city",$json["results"]["company"][0]["profile"]["info"]["address"]["city"]);
            update_post_meta($pid,"postcode",$json["results"]["company"][0]["profile"]["info"]["address"]["postcode"]);
            update_post_meta($pid,"state",$json["results"]["company"][0]["profile"]["info"]["address"]["state"]);
            update_post_meta($pid,"country",$json["results"]["company"][0]["profile"]["info"]["address"]["country"]);
            update_post_meta($pid,"new_updated_info",time());

            update_post_meta($pid,"last_updated_info",time());
          }else{
            // $my_post = array(
            //   'ID'       => $pid,
            //   // 'post_status'=>'trash'
            // );
            // $post_id = wp_update_post($my_post);
            delete_post_meta($pid,"last_updated_snap_price");
            update_post_meta($pid,"new_updated_info",time());

            update_post_meta($pid,"last_updated_info",time());
          }
        }
      }
      // else{
      //   $my_post = array(
      //     'ID'       => $pid,
      //     'post_status'=>'trash'
      //   );
      //   $post_id = wp_update_post($my_post);
      //   delete_post_meta($pid,"last_updated_snap_price");
      //   update_post_meta($pid,"new_updated_info",time());
      //
      //   update_post_meta($pid,"last_updated_info",time());
      // }

    }

    function get_snap_price_info($pid){
      global $wpdb;
      $sql = 'SELECT * FROM `wp_alerts` WHERE 1=0 ';
      $codes = array();
      $ex_codes = array();
      $company_code = get_post_meta($pid,"company_code",true);
      $exchanges = get_post_meta($pid,"exchanges",true);
      foreach($exchanges as $ex_code=>$exc){
        $ex_codes[$exc["symbol"]][]=$ex_code;
        $codes[]=$exc["symbol"];
        $sql.=" OR `ticker`='".$exc["symbol"]."'";
      }
      $company_codes = implode(",",$codes);

      $res = $wpdb->get_results($sql);
      $alerts = array();
      foreach($res as $it){
        $alerts[$it->ticker][$it->type][]=array(
          "alert_id"=>$it->id,
          "user_id"=>$it->user_id,
          "value"=>$it->value,
          "active"=>$it->active,
        );
      }

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getSnapQuotes.json?webmasterId=".get_option("webmaster_id")."&symbols=".$company_codes);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      $json = json_decode($output,true);
      // print_r($json);

      // echo "----------";
      // print_r($ex_codes);
      if($json["quotedata"]){
        foreach($json["quotedata"] as $price_info){

          foreach($ex_codes[$price_info["symbol"]] as $exc){
            if($price_info["symbol"]==$company_code){
              update_post_meta($pid,"default_exchange",strtolower($exc));
            }
            // echo strtolower($exc)."::";
            //           print_r($price_info);
            foreach($price_info["pricedata"] as $f=>$v){
              delete_post_meta($pid,strtolower($exc)."_".$f);
            }
            if($price_info["pricedata"]["last"]){
              foreach($alerts[$price_info["symbol"]]["price_less"] as $it){
                if($it["value"] > $price_info["pricedata"]["last"]){
                  if($it["active"]==1){
                    $wpdb->query("UPDATE `wp_alerts` SET `active`='0' WHERE `id`='".$it["alert_id"]."'");
                    $wpdb->query("INSERT INTO `wp_alerts_triggered` SET `alert_id`='".$it["alert_id"]."', `time`='".time()."', `value`='".$price_info["pricedata"]["last"]."'");
                  }
                }
                if($price_info["pricedata"]["last"] > $it["value"]){
                  if($it["active"]==0){
                    $wpdb->query("UPDATE `wp_alerts` SET `active`='1' WHERE `id`='".$it["alert_id"]."'");
                  }
                }
              }
              foreach($alerts[$price_info["symbol"]]["price_more"] as $it){
                if($price_info["pricedata"]["last"] > $it["value"]){
                  $wpdb->query("UPDATE `wp_alerts` SET `active`='0' WHERE `id`='".$it["alert_id"]."'");
                  $wpdb->query("INSERT INTO `wp_alerts_triggered` SET `alert_id`='".$it["alert_id"]."', `time`='".time()."', `value`='".$price_info["pricedata"]["last"]."'");
                }
                if($it["value"] > $price_info["pricedata"]["last"]){
                  if($it["active"]==0){
                    $wpdb->query("UPDATE `wp_alerts` SET `active`='1' WHERE `id`='".$it["alert_id"]."'");
                  }
                }
              }
              update_post_meta($pid,strtolower($exc)."_last",$price_info["pricedata"]["last"]);
            }

            if($price_info["pricedata"]["sharevolume"]){
              foreach($alerts[$price_info["symbol"]]["sharevolume_less"] as $it){
                if($it["value"] > $price_info["pricedata"]["sharevolume"]){
                  $wpdb->query("UPDATE `wp_alerts` SET `active`='0' WHERE `id`='".$it["alert_id"]."'");
                  $wpdb->query("INSERT INTO `wp_alerts_triggered` SET `alert_id`='".$it["alert_id"]."', `time`='".time()."', `value`='".$price_info["pricedata"]["sharevolume"]."'");
                }
                if($price_info["pricedata"]["sharevolume"] > $it["value"]){
                  if($it["active"]==0){
                    $wpdb->query("UPDATE `wp_alerts` SET `active`='1' WHERE `id`='".$it["alert_id"]."'");
                  }
                }
              }
              foreach($alerts[$price_info["symbol"]]["sharevolume_more"] as $it){
                if($price_info["pricedata"]["sharevolume"] > $it["value"]){
                  $wpdb->query("UPDATE `wp_alerts` SET `active`='0' WHERE `id`='".$it["alert_id"]."'");
                  $wpdb->query("INSERT INTO `wp_alerts_triggered` SET `alert_id`='".$it["alert_id"]."', `time`='".time()."', `value`='".$price_info["pricedata"]["sharevolume"]."'");
                }
                if($it["value"] > $price_info["pricedata"]["sharevolume"]){
                  if($it["active"]==0){
                    $wpdb->query("UPDATE `wp_alerts` SET `active`='1' WHERE `id`='".$it["alert_id"]."'");
                  }
                }
              }
              update_post_meta($pid,strtolower($exc)."_tradevolume",$price_info["pricedata"]["tradevolume"]);
            }
            if($price_info["pricedata"]["sharevolume"]){
              update_post_meta($pid,strtolower($exc)."_sharevolume",$price_info["pricedata"]["sharevolume"]);
            }
            if($price_info["pricedata"]["change"]){
              update_post_meta($pid,strtolower($exc)."_change",$price_info["pricedata"]["change"]);
            }
            if($price_info["pricedata"]["changepercent"]){
              update_post_meta($pid,strtolower($exc)."_changepercent",$price_info["pricedata"]["changepercent"]);
            }
            if($price_info["symbol"]){
              update_post_meta($pid,strtolower($exc)."_symbol",$price_info["symbol"]);
            }
            if($price_info["datetime"]){
              update_post_meta($pid,strtolower($exc)."_datetime",$price_info["datetime"]);
            }

            update_post_meta($pid,strtolower($exc)."_pricedata",$price_info["pricedata"]);
          }
          if($price_info["pricedata"]["last"]){
            update_post_meta($pid,strtolower($exc)."_hasprice",1);
          }else{
            foreach($ex_codes[$price_info["symbol"]] as $exc){
              update_post_meta($pid,strtolower($exc)."_hasprice",0);
            }
          }
        }

        update_post_meta($pid,"last_updated_snap_price",time());
      }
    }

    public function get_companies(){
      global $wpdb;
      // create curl resource

      $exchanges = get_terms("exchanges","hide_empty=0");
      foreach($exchanges as $ex){
        $ex_code = get_term_meta($ex->term_id,"ex_code",true);
        $ex_country = get_term_meta($ex->term_id,"country",true);
        echo $ex_code.":<br>";
        if(isset($_GET["exchange"]) && $ex_code==$_GET["exchange"]){
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, "http://app.quotemedia.com/data/getSymbolList.json?webmasterId=".get_option("webmaster_id")."&symbolcount=10000&instrumentType=Equity&exgroup=".$ex_code);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $output = curl_exec($ch);
          curl_close($ch);
          //print_r($output);

          $json = json_decode($output,true) ;
          foreach($json["results"]["lookupdata"] as $m=>$item){
            $code_pieces = explode(":",$item["symbolstring"]);
            $post_id = $wpdb->get_var("SELECT `post_id` FROM `wp_postmeta` WHERE `meta_key`='company_code' AND `meta_value` LIKE '".$code_pieces[0].":%'");
            echo "#".$m." ".$code_pieces[0]." (".$item["symbolstring"].") - ".$item["equityinfo"]["longname"]." - ";
            if(!$post_id){
              $post_id = $wpdb->get_var("SELECT `ID` FROM `wp_posts` WHERE `post_title` LIKE '".($item["equityinfo"]["longname"])."'");
            }
            if(!$post_id){
              $post_id = $wpdb->get_var("SELECT `ID` FROM `wp_posts` WHERE `post_title` LIKE '".($item["equityinfo"]["shortname"])."'");
            }
            if(!$post_id && $ex_country=="canada"){

              $my_post = array(
                'post_title'    => $item["equityinfo"]["longname"],
                'post_status'   => 'publish',
                'post_type'  => 'companies'
              );

              // Insert the post into the database.
              $post_id = wp_insert_post( $my_post );
              update_post_meta($post_id,"company_code",$item["symbolstring"]);

              $exchanges = array(
                $ex_code => array(
                  "symbol" => $item["symbolstring"],
                  "instrumenttype" => $item["equityinfo"]["instrumenttype"],
                  "issuetype" => $item["equityinfo"]["issuetype"],
                  "sectype" => $item["equityinfo"]["sectype"],
                  "shortname" => $item["equityinfo"]["shortname"],
                  "longname" => $item["equityinfo"]["longname"]

                )
              );
              wp_set_post_terms($post_id, array($ex->term_id), "exchanges", true);
              update_post_meta($post_id,"exchanges",$exchanges);
              // update_post_meta($post_id,"last_updated",0);

              update_post_meta($post_id,"last_updated_info",0);
              update_post_meta($post_id,"last_updated_snap_price",0);
              // update_post_meta($post_id,"last_updated_filings",0);
              update_post_meta($post_id,"last_updated_news",0);
              echo " - added ".$post_id;
            }elseif($post_id){
              if($ex_country=="canada" || ($ex_country!="canada" && $wpdb->get_var("SELECT `ID` FROM `wp_posts` WHERE (`post_title` LIKE '".($item["equityinfo"]["longname"])."' OR `post_title` LIKE '".($item["equityinfo"]["shortname"])."') AND `ID`='".$post_id."'"))){
                echo "SELECT `ID` FROM `wp_posts` WHERE (`post_title` LIKE '".($item["equityinfo"]["longname"])."' OR `post_title` LIKE '".($item["equityinfo"]["shortname"])."') AND `ID`='".$post_id."'<br>";

                $exchanges = get_post_meta($post_id,"exchanges",true);
                if(!is_array($exchanges)){
                  $exchanges = array();
                }

                $exchanges[$ex_code]= array(
                  "symbol" => $item["symbolstring"],
                  "instrumenttype" => $item["equityinfo"]["instrumenttype"],
                  "issuetype" => $item["equityinfo"]["issuetype"],
                  "sectype" => $item["equityinfo"]["sectype"],
                  "shortname" => $item["equityinfo"]["shortname"]
                );
                update_post_meta($post_id,"exchanges",$exchanges);
                wp_set_post_terms($post_id, array($ex->term_id), "exchanges", true);
                update_post_meta($post_id,"last_updated_news",0);
                echo " - updated ".$post_id;
              }
            }
            echo "<br>";
          }
        }
      }
    }
  }

  new TDF_Import;

}
