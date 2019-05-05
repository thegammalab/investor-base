<div class="event_box">
  <div class="row">
    <div class="col-4 col-md-2">
      <div class="blog_image">
        <a href="<?=$item["post_permalink"];?>">
          <?=get_the_post_thumbnail($item["post_id"],"thumbnail");?>
        </a>
      </div>
    </div>
    <div class="col-5 col-md-7 event_box_content">
      <h3><a href="<?=$item["post_permalink"];?>"><?=$item["post_title"];?></a></h3>
      <p><?=$item["post_excerpt"];?></p>
    </div>
    <div class="col-3 col-md-3 event_date_box">
      <div class="event_date text-center mb-3">
        <h2><?=date("j",strtotime($item["meta_date"])); ?></h2>
        <ul>
          <li><?=date("M",strtotime($item["meta_date"])); ?></li>
          <li class="fs112"><?=date("Y",strtotime($item["meta_date"])); ?></li>
        </ul>
      </div>
      <div class="d-flex align-items-center justify-content-center">
        <?=post_social_share($item["post_id"],"mb-1"); ?>
        <?=post_comment_counts($item["post_id"],"mb-0"); ?>
      </div>
    </div>
  </div>
</div>
