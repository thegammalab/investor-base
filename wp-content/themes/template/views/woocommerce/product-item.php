<?php
if(!isset($item) && get_the_ID()){
$item = apply_filters("tdf_get_single",get_the_ID());
} ?>
<div class="col-sm-3">
  <div class="product_box">
    <div class="product_name">
      <h3><a href="<?php echo $item["post_permalink"]; ?>"><?php echo $item["post_title"]; ?></a></h3>
      <a href="<?php echo $item["post_permalink"]; ?>"><?php echo $item["featured_img_tiny_crop"]; ?></a>
    </div>
    <div class="product_topics">
      <ul>
        <?php foreach($item["tax_array_topics"] as $topic){ ?>
          <li><a href="<?php echo get_term_link($topic); ?>" target="_blank">
            <?php echo wp_get_attachment_image(get_term_meta($topic->term_id,"black",true)); ?>
          </a></li>
        <?php } ?>
      </ul>
    </div>
    <div class="product_excerpt">
      <?php echo $item["post_excerpt"]; ?>
    </div>
    <div class="product_bottom">
    <div class="product_research">
      <?php foreach($item["tax_array_research"] as $research){ ?>
        <a href="<?php echo get_term_link($research); ?>" target="_blank" class="emerging_button btn-secondary"><?php echo $research->name; ?></a>
      <?php } ?>
    </div>
    <?php echo apply_filters( 'tdf_woocommerce_item_buy_button',$item["post_id"],"Add to Pack",array("classes"=>"product_add_to_cart btn-secondary")); ?>
  </div>
  </div>
</div>
