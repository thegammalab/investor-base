<?php
add_action('init', 'check_logout');

function check_logout(){
  if (isset($_GET["action"]) || isset($_GET["logout"])) {
    if($_GET["logout"] || $_GET["action"]=="logout"){
      wp_logout();
      header("Location:" . get_bloginfo("url"));
      die();
    }
  }
}

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
  .wrap{
    min-height: 0px !important;
  }
  </style>';
}

function get_events_count(){
  return 1;
}

function add_search_form($items, $args) {
  if(!is_home() && !is_category() && !is_singular("post")){
    $items = str_replace('active menu-news','menu-news',$items);
  }
  $pieces = explode('</li>',$items);
  foreach($pieces as $i=>$piece){
    if(strpos($piece,"events")){
      $pieces[$i]=str_replace('</a>','<span class="badge badge-light">'.get_events_count().'</span></a>',$piece);
    }
  }
  $items = implode('</li>',$pieces);
  return $items;
}
add_filter('wp_nav_menu_items', 'add_search_form', 10, 2);

add_filter('post_thumbnail_html', 'my_post_thumbnail_html',10,4);

function get_image_sizes() {
  global $_wp_additional_image_sizes;

  $sizes = array();

  foreach ( get_intermediate_image_sizes() as $_size ) {
    if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
      $sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
      $sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
      $sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
    } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
      $sizes[ $_size ] = array(
        'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
        'height' => $_wp_additional_image_sizes[ $_size ]['height'],
        'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
      );
    }
  }

  return $sizes;
}

function my_post_thumbnail_html($html,$post_id,$post_thumbnail_id,$size) {
  $sizes = get_image_sizes();
  //print_r($sizes[$size]);
  $ratio = $sizes[$size]["width"]/$sizes[$size]["height"];
  if (empty($html)){
    if($thumb_url = get_post_meta($post_id,"thumbnailurl",true)){
      $html = '<img src="' . $thumb_url. '" alt="" style="max-width:100%; height:auto;" />';
    }else{
      $rand = rand(intval($sizes[$size]["width"]*0.9),intval($sizes[$size]["width"]*1.5));
      //$html = '<img src="https://source.unsplash.com/collection/1966837/'.floor($rand*$ratio).'x'.$rand.'" alt="" style="max-width:100%; height:auto;" />';
    }
    // else{
    //   $html = '<img src="' . trailingslashit(get_stylesheet_directory_uri()) . '/assets/images/defaults/no_thumb.png' . '" alt="" style="max-width:100%; height:auto;" />';
    // }
  }
  return $html;
}

function post_social_share($post_id,$classes=""){
  ?>
  <ul class="post_social <?=$classes; ?>">
    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
  </ul>
  <?php
}

function post_comment_counts($post_id,$classes=""){
  global $wpdb;

  if((time()-get_post_meta($post_id,"last_count_check",true))>300){
    $cont = file_get_contents("https://graph.facebook.com/?fields=og_object{id},share&id=".get_the_permalink($post_id));
    $counts = json_decode($cont);
    update_post_meta($post_id,"comment_count",$counts->share->comment_count);
    update_post_meta($post_id,"share_count",$counts->share->share_count);
    update_post_meta($post_id,"last_count_check",time());
  }
  if(!get_post_meta($post_id,"like_count",true)){
    update_post_meta($post_id,"like_count",0);
  }
  if($uid = get_current_user_id()){
    if($wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->usermeta."` WHERE `user_id`='".$uid."' AND `meta_key`='liked_posts' AND `meta_value`='".$post_id."'")){
      $is_liked = 'active';
    }else{
      $is_liked = '';
    }
  }else{
    $is_liked = '';
  }
  ?>
  <ul class="post_comments <?=$classes; ?>">
    <li><a class="comment_item" href="<?=get_permalink($post_id);?>#comments">
      <?php //echo file_get_contents(get_bloginfo("template_directory")."/assets/images/comment_icon.svg"); ?>
      <b><?=get_post_meta($post_id,"comment_count",true); ?></b>
    </a></li>
    <li class="mr-0">
      <a class="fav_item <?=$is_liked;?>" data-pid="<?=$post_id; ?>" href="#">
        <span class="heart">
          <?php //echo file_get_contents(get_bloginfo("template_directory")."/assets/images/heart_icon.svg"); ?>
        </span>
        <b><?=get_post_meta($post_id,"like_count",true); ?></b>
      </a>
    </li>
  </ul>
  <?php
}

function get_my_watchlist(){
  global $wpdb;
  $watchlist = array();

  if($uid = get_current_user_id()){
    $results = $wpdb->get_results("SELECT * FROM `".$wpdb->usermeta."` WHERE `user_id`='".$uid."' AND `meta_key`='watchlist'");
    foreach($results as $item){
      $post_data = $wpdb->get_row("SELECT * FROM `".$wpdb->postmeta."` WHERE `meta_value`='".$item->meta_value."' AND `meta_key` LIKE '%_symbol'");
      $ex_pieces = explode("_",$post_data->meta_key);
      $watchlist[$post_data->post_id]=array(
        "code"=>$item->meta_value,
        "post_id"=>$post_data->post_id,
        "exchange"=>$ex_pieces[0],
      );
    }
  }

  return $watchlist;
}

function get_watchlist_button($post_id,$full=false){
  global $wpdb;
  if($uid = get_current_user_id()){
    $inactive_label = 'add to watchlist';
    $active_label = 'in watchlist';

    if($wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->usermeta."` WHERE `user_id`='".$uid."' AND `meta_key`='watchlist' AND `meta_value`='".$post_id."'")){
      $is_watchlist = 'active';
      $watchlist_text = 'remove from watchlist';
      $watchlist_text_label = $active_label;

      $i_class = 'fa-minus';
    }else{
      $is_watchlist = '';
      $watchlist_text = 'add to watchlist';
      $watchlist_text_label = $inactive_label;
      $i_class = 'fa-plus';
    }
  }else{
    $is_watchlist = 'not_active';
    $watchlist_text_label = 'add to watchlist';
    $watchlist_text = 'you need to be logged in';
    $i_class = 'fa-plus';
  }
  if($full){
    return '<button type="button" class="full_button add_to_watchlist '.$is_watchlist.'" data-toggle="tooltip" data-placement="top" title="'.$watchlist_text.'" data-pid="'.$post_id.'" data-inactive_label="'.$inactive_label.'" data-active_label="'.$active_label.'"><i class="fas '.$i_class.'"></i> '.$watchlist_text_label.'</button>';
  }else{
    return '<button type="button" class="add_to_watchlist '.$is_watchlist.'" data-toggle="tooltip" data-placement="top" title="'.$watchlist_text.'" data-pid="'.$post_id.'"><i class="fas '.$i_class.'"></i></button>';
  }
}

function custom_rewrite_basic() {
  add_rewrite_rule('^company/([^/]+)/([^/]+)/?', 'index.php?companies=$matches[1]&exchange_slug=$matches[2]', 'top');
  flush_rewrite_rules();
}
add_action('init', 'custom_rewrite_basic');

function add_custom_query_var( $vars ){
  $vars[] = "exchange_slug";
  return $vars;
}
add_filter( 'query_vars', 'add_custom_query_var' );
