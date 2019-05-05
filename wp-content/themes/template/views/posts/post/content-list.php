<?php
$search = array();
$search["exclude"] = $ignore;
$args= array("search"=>$search);

$results = apply_filters('tdf_get_posts',"post",10,0,$args);
 ?>
<section class="latest_news">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="top_section_news d-flex align-items-center">
          <h2 style="flex:1;">Latest News</h2>
        </div>
        <div class="border_bottom" id="news_results">
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
          <?php dynamic_sidebar("article_page_sidebar"); ?>

        </div>
      </div>
    </div>
  </div>
</section>
