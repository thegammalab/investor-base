<?php
$elem = (get_queried_object());

$search = array();
if(isset($elem->term_id)){
  $the_cat = get_queried_object()->term_id;
  $act_all = "";
  $search["tax_".$elem->taxonomy]=array($the_cat);
  $the_cat_obj = get_term_by("id",$the_cat,$elem->taxonomy);
  if($the_cat_obj){
    $the_title = "Browse ".$the_cat_obj->name. " news";
  }
}
$featured_results = apply_filters('tdf_get_posts',"post",3,0,array("search"=>$search));

$ignore = array();
foreach($featured_results["items"] as $item){
  $ignore[]=$item["post_id"];
}
$search["exclude"] = $ignore;
$args= array("search"=>$search);

$results = apply_filters('tdf_get_posts',"post",10,0,$args);
?>
<section class="top_news">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="top_section_news d-flex align-items-center">
          <h1><?=$the_title; ?></h1>
        </div>
        <div id="featured_post_slider" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <?php for($i=0;$i<3;$i++){
              $item = $featured_results["items"][$i];

              ?>
              <div class="carousel-item  <?php if($i==0){ ?>active<?php } ?>">
                <div class="featured_post">
                  <?=get_the_post_thumbnail($item["post_id"],"large_crop");?>
                  <div class="content_featured">
                    <?php if(isset($item["tax_array_category"][0])){ ?>
                      <div class="post_category">
                        <span><a href="<?=get_term_link($item["tax_array_category"][0]); ?>"><?=$item["tax_array_category"][0]->name; ?></a></span>
                      </div>
                    <?php } ?>
                    <div class="post_details">
                      <h2><a href="<?=$item["post_permalink"];?>"><?=$item["post_title"];?></a></h2>
                      <div class="d-none d-md-block"><p><?=$item["post_excerpt"];?></p></div>
                      <?=post_comment_counts($item["post_id"],""); ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
        <div class="mb-2">
          <div class="row pt-3" id="slider_side_posts">
            <?php for($i=0;$i<3;$i++){
              $item = $featured_results["items"][$i]; ?>
              <div class="col-4 slider_side_post" data-count="<?=$i;?>" data-target="<?="slide_".$i;?>">
                <div class="small_post <?php if($i==0){ ?>active<?php } ?>">
                  <div class="row ml-0">
                    <div class="col-sm-4 d-none d-sm-block p-0">
                      <?=get_the_post_thumbnail($item["post_id"],"small_crop");?>
                    </div>
                    <div class="col-sm-8">
                      <h5 style="display: block; height:50px; overflow:hidden; text-overflow:ellipsis;"><?=$item["post_title"];?></h5>
                    </div>
                  </div>
                </div>
              </div>

            <?php } ?>
          </div>

        </div>
        <hr />
        <script>
        jQuery(document).ready(function(){
          jQuery('#featured_post_slider').carousel({
            interval: 4000
          }).on('slid.bs.carousel', function () {
            console.log("aaa");
            jQuery("#slider_side_posts .small_post").removeClass("active");
            jQuery('.slider_side_post[data-target="'+jQuery("#featured_post_slider .carousel-item.active").attr("id")+'"]').find(".small_post").addClass("active");
          });

          jQuery(".slider_side_post").click(function(){

            jQuery('#featured_post_slider').carousel(parseInt(jQuery(this).attr("data-count")));
            jQuery("#slider_side_posts .small_post").removeClass("active");
            jQuery(this).find(".small_post").addClass("active");
          });
        });
        </script>
        <div class="latest_news">

          <div class="border_bottom"  id="news_results">
            <?php
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
            ?>
          </div>
          <div class="button_border" style="display:flex;">
            <span class="alert alert-warning m-auto" style="display:none;">No more results</span>
            <button type="button" id="load_more" class="btn btn-primary m-auto">GET MORE NEWS</button>
          </div>
          <script>
          jQuery(document).ready(function(){
            var the_page = <?=max(1,get_query_var("paged"));?>;
            var query_args = "<?=http_build_query($args);?>"
            var the_current_page = "<?=get_bloginfo("url"); ?>/";

            jQuery("#load_more").click(function(){
              if(!jQuery("#load_more").prop("disabled")){
              the_page++;

              jQuery("#load_more").prop("disabled",true);
              jQuery.ajax({
                method: "GET",
                url: bloginfo_url+"/wp-admin/admin-ajax.php?action=get_ajax_news&"+query_args+"&page_no="+the_page,
              }).done(function(data) {
                if(!data){
                  th.slideUp(500,function(){
                    th.parent().find("span").slideDown();
                    setTimeout(function(){
                      th.parent().slideUp();
                    },3000);
                  });
                }
                window.history.pushState(the_current_page+'page/'+the_page, 'Title', the_current_page+'page/'+the_page);
                jQuery("#news_results").append(data);
                jQuery("#load_more").prop("disabled",false);
              });
            }
            });
          });
          </script>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="widget_sidebar">
          <?php dynamic_sidebar("article_page_sidebar"); ?>
        </div>
      </div>
    </div>
  </div>
</section>
