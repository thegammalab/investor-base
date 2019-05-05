<?php
$results = apply_filters('tdf_get_posts',"analysis",10,0,array());
 ?>
<section class="latest_news">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="top_section_news d-flex align-items-center">
          <h2 style="flex:1;">Latest Analysis</h2>
        </div>
        <div class="border_bottom">
          <?php
          for($i=0;$i<2;$i++){
            $item = $results["items"][$i];
            if($item){
              include(get_stylesheet_directory()."/views/posts/analysis/content-item.php");
            }
          }
          ?>
          <div class="row">
            <?php
            for($i=2;$i<5;$i++){
              $item = $results["items"][$i];
              if($item){
                include(get_stylesheet_directory()."/views/posts/post/content-item-sm.php");
              }
            }
            ?>
          </div>
          <?php
          for($i=5;$i<10;$i++){
            $item = $results["items"][$i];
            if($item){
              include(get_stylesheet_directory()."/views/posts/post/content-item.php");
            }
          }
          ?>
        </div>
        <div class="button_border d-none">
          <button type="submit" class="btn btn-primary d-flex m-auto">GET MORE NEWS</button>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="widget_sidebar">
          <?php dynamic_sidebar("analysis_page_sidebar"); ?>
        </div>
      </div>
    </div>
  </div>
</section>
