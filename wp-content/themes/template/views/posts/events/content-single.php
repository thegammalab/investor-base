<section class="article_single">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="top_article pl-1">
          <div class="row">
            <div class="col-md-9">
              <div class=" pb-0" style="padding-left:80px;">
                <?php if(isset($item["tax_array_event_cat"][0])){ ?>
                  <div class="post_category"><span><a href="<?=get_term_link($item["tax_array_event_cat"][0]); ?>"><?=$item["tax_array_event_cat"][0]->name;?></a></span></div>
                <?php } ?>
                <h1 class="fs250"><?=$item["post_title"];?></h1>
                <div class="events pb-0">
                  <div class="row">
                    <div class="col-lg-12">
                      <ul class="writtenby">
                        <li>Written by <a href="#"><?=$item["author_display_name"];?></a></li>
                        <li>Posted on <a href="#"><?=get_the_time("F j, Y, g:i a",$item["post_id"]); ?></a></li>
                      </ul>
                    </div>
                    <div class="col-lg-12">
                      <?=post_comment_counts($item["post_id"],"float-left pt-0"); ?>
                    </div>
                  </div>


                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="event_date_box top_event_page text-center">
                <h4><i class="fas fa-calendar-alt"></i>Event Date:</h4>
                <div class="event_date text-center">
                  <h2><?=date("j",strtotime($item["meta_date"])); ?></h2>
                  <ul>
                    <li><?=date("M",strtotime($item["meta_date"])); ?></li>
                    <li class="fs112"><?=date("Y",strtotime($item["meta_date"])); ?></li>
                  </ul>
                </div>
              </div>

            </div>
            <div class="col-md-12">
            </div>
          </div>
        </div>
        <div class="main_image">
          <?=get_the_post_thumbnail($item["post_id"]);?>
        </div>
        <div class="article_content">
          <?=$item["post_content"];?>
        </div>
        <div class="discussion" id="comments">
          <h4>Join the discussion</h4>
          <?php echo do_shortcode('[Fancy_Facebook_Comments]'); ?>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="widget_sidebar">
          <?php dynamic_sidebar("events_sidebar"); ?>


        </div>

      </div>
    </div>
  </div>
</section>
