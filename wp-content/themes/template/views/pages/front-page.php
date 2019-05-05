<section class="top_news">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div id="featured_post_slider" class="slide" data-ride="carousel">
          <div class="carousel-inner">
            <?php
            $featured_results = apply_filters('tdf_get_posts',"post",4,0,array("search"=>array("meta_has_unsplash_img"=>1)));

            foreach($featured_results["items"] as $i=>$item){ ?>
              <div id="<?="slide_".$i;?>" class="carousel-item  <?php if($i==0){ ?>active<?php } ?>">
                <div class="featured_post">
                  <?=get_the_post_thumbnail($item["post_id"],"medium_crop");?>
                  <div class="content_featured">
                    <?php if(count($item["tax_array_category"])){ ?>
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
      </div>
      <div class="col-lg-4">
        <div class="row pb-2" id="slider_side_posts">
          <?php foreach($featured_results["items"] as $i=>$item){ ?>
            <div class="col-6 col-lg-12 slider_side_post" data-count="<?=$i;?>" data-target="<?="slide_".$i;?>">
              <div class="small_post  <?php if($i==0){ ?>active<?php } ?>">
                <div class="row ml-0">
                  <div class="col-sm-4 d-none d-sm-block p-0" style=" border-right: 5px solid transparent;">
                    <?=get_the_post_thumbnail($item["post_id"],"thumbnail");?>
                  </div>
                  <div class="col-sm-8">
                    <h5><?=substr($item["post_title"],0,120);?></h5>

                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section>
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
<section class="latest_news">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="top_section_news d-flex align-items-center">
          <h2 style="flex:1;">Latest News</h2>
          <?php if(UID){ ?>
            <div id="settings_icon" class="d-flex justify-content-end"><a href="#" data-toggle="modal" data-target="#preferences_popup" class="pref_button">
              <i class="fa fa-sliders-h"></i> Customize feed
            </a></div>
          <?php } ?>
        </div>
        <?php
        $ignore = array();
        foreach($featured_results["items"] as $item){
          $ignore[]=$item["post_id"];
        }
        $search = array();
        $search["exclude"] = $ignore;
        if(UID){
          $preferences = get_user_meta(UID,"preferences",false);
          if(count($preferences)){
            $search["tax_category"] = $preferences;
          }
        }
        $args= array("search"=>$search);

        $results = apply_filters('tdf_get_posts',"post",10,0,$args);
        ?>
        <div id="news_results" class="border_bottom">
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
      <div class="col-lg-4">
        <div class="widget_sidebar">
          <?php
          dynamic_sidebar("homepage_sidebar");
          // include(get_stylesheet_directory()."/views/pieces/widgets/widget1.php");
          // include(get_stylesheet_directory()."/views/pieces/widgets/widget2.php");
          ?>
        </div>
      </div>
    </div>
  </div>
</section>
