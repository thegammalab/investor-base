<section class="article_single">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="top_article">
          <?php if(isset($item["tax_array_category"][0])){ ?>
            <div class="post_category"><span><a href="<?=get_term_link($item["tax_array_category"][0]); ?>"><?=$item["tax_array_category"][0]->name;?></a></span></div>
          <?php } ?>
          <h1><?=$item["post_title"];?></h1>
          <ul class="writtenby">
            <li>Written by <a href="#"><?=$item["meta_source"];?></a></li>
            <li>Posted on <a href="#"><?=get_the_time("F j, Y, g:i a",$item["post_id"]); ?></a></li>
          </ul>
          <?=post_comment_counts($item["post_id"],""); ?>

        </div>
        <div class="main_image">
          <?=get_the_post_thumbnail($item["post_id"]);?>
        </div>
        <div class="article_content">
          <?php
          $body = str_replace("#ffffff","",file_get_contents($item["meta_storyurl"]));

          //$body = substr($body,strpos($body,'<body>')+6);
          $body = str_replace('<body>','',$body);
          $body = str_replace('<html>','',$body);
          $body = str_replace('</body>','',$body);
          $body = str_replace('</html>','',$body);

          echo $body;
          ?>
                </div>
        <div class="discussion" id="comments">
          <h4>Join the discussion</h4>
          <?php echo do_shortcode('[Fancy_Facebook_Comments]'); ?>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="widget_sidebar">
          <?php dynamic_sidebar("article_sidebar"); ?>
        </div>

      </div>
    </div>
  </div>
</section>
