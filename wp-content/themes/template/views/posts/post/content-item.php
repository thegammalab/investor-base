<div class="blog_box">
  <div class="row m-0">
    <div class="col-4 col-md-3 p-0">
      <div class="blog_image">
        <a href="<?=$item["post_permalink"];?>">
          <?=get_the_post_thumbnail($item["post_id"],"thumbnail");?>
        </a>
            </div>

    </div>
    <div class="col-8 col-md-9">
      <h3><a href="<?=$item["post_permalink"];?>"><?=$item["post_title"];?></a></h3>
      <p><?=$item["post_excerpt"];?></p>
      <div class="row d-flex align-items-center">
        <div class="col-md-6">
          <div class="posting_date"><?=$item["meta_source"];?> - <?=human_time_diff($item["post_date"]);?> ago</div>
        </div>

        <div class="col-md-6 d-flex justify-content-end">
          <?=post_social_share($item["post_id"],""); ?>
          <?=post_comment_counts($item["post_id"],""); ?>
        </div>

      </div>
    </div>
  </div>
</div>
