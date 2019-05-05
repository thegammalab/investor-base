<div class="col-md-4">
  <div class="blog_box blog_box_small">
    <div class="row m-0">
      <div class="col-4 col-md-5 p-0">
        <div class="blog_image">
          <a href="<?=$item["post_permalink"];?>">
            <?=get_the_post_thumbnail($item["post_id"],"thumbnail");?>
          </a>
        </div>
      </div>
      <div class="col-8 col-md-7">
        <h3><a href="<?=$item["post_permalink"];?>"><?=$item["post_title"];?></a></h3>
        <?=post_comment_counts($item["post_id"],""); ?>
      </div>
    </div>
  </div>
</div>
