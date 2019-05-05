<?php
$results = apply_filters('tdf_get_posts',"post",3,0,array("search"=>array(),"order"=>"rand"));
 ?>
<div class="other_news_widget">
  <h4><img src="<?=get_bloginfo("template_directory");?>/assets/images/other_news.png" alt="">other related news</h4>
  <div class="widget_content">
    <?php foreach($results["items"] as $i=>$item){ ?>
      <div class="row align-items-center">
        <div class="col-4 pr-0">
          <?=get_the_post_thumbnail($item["post_id"],"small_crop");?>
        </div>
        <div class="col-8">
          <h5><a href="<?=$item["post_permalink"];?>" class="fs90"><?=$item["post_title"];?></a></h5>
        </div>
      </div>
      <?php if($i!=2){ echo '<hr />';} ?>
    <?php } ?>
  </div>
</div>
