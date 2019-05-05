<?php
$item = apply_filters("tdf_get_single",get_the_ID());
?>
<div class="product_bg">
  <div class="container">
    <?php do_action( 'woocommerce_before_single_product' ); ?>
    <div class="row">
      <div class="col-md-7">
        <div class="product_top">
          <h1><?php echo $item["post_title"]; ?></h1>
          <h2>For heart health</h2>
          <div>
          <ul class="d-flex align-items-center good_for w-auto">

            <?php foreach($item["tax_array_topics"] as $topic){ ?>
              <li>
              <a href="<?php echo get_term_link($topic); ?>" target="_blank">
                <?php echo wp_get_attachment_image(get_term_meta($topic->term_id,"black",true)); echo $topic->name; ?>
              </a>
            </li>
            <?php } ?>
          </ul>
        </div>
        <div class="clearfix" style="clear:both;">
          <?php echo $item["post_excerpt"]; ?>
        </div>
        </div>
        <?php
        /**
        * Hook: woocommerce_single_product_summary.
        *
        * @hooked woocommerce_template_single_title - 5
        * @hooked woocommerce_template_single_rating - 10
        * @hooked woocommerce_template_single_price - 10
        * @hooked woocommerce_template_single_excerpt - 20
        * @hooked woocommerce_template_single_add_to_cart - 30
        * @hooked woocommerce_template_single_meta - 40
        * @hooked woocommerce_template_single_sharing - 50
        * @hooked WC_Structured_Data::generate_product_data() - 60
        */
        global $product;
        do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
        ?>

      </div>
      <div class="col-md-5">
        <?php
        /**
        * Hook: woocommerce_before_single_product_summary.
        *
        * @hooked woocommerce_show_product_sale_flash - 10
        * @hooked woocommerce_show_product_images - 20
        */
        do_action( 'woocommerce_before_single_product_summary' );
        ?>

      </div>
    </div>


  </div>
</div>
<section class=" about_product">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-5">
        <h2 class="fs225">about <?php echo $item["post_title"]; ?></h2>
      </div>
      <div class="col-lg-8 col-md-7">
        <ul class="about_product_tags">
          <?php foreach($item["tax_array_features"] as $topic){ ?>
            <li><a href="<?php echo get_term_link($topic); ?>" target="_blank"><?php echo wp_get_attachment_image(get_term_meta($topic->term_id,"white",true)); echo $topic->name; ?></a></li>
          <?php } ?>
                </ul>
        <?php echo $item["post_content"]; ?>
        <hr class="my-5">
        <div class="row">
          <div class="col-md-6">
            <h4>Ingredients</h4>
            <?php echo $item["meta_ingredients"]; ?>
          </div>
          <div class="col-md-6">
            <h4>does not contain</h4>
            <?php echo $item["meta_does_not_contain"]; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="product_faq">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-5">
        <h2 class="fs225">benefits of <?php echo $item["post_title"]; ?></h2>
      </div>
      <div class="col-lg-8 col-md-7">
        <ul class="faq_list">
          <?php for($i=0;$i<$item["meta_benefits"];$i++){
            $term_id = $item["meta_benefits_".$i."_topic"];
            $the_term = get_term_by("id",$term_id,"topics");
            ?>
            <li>
              <div class="faq_title">
                <div class="icon_black"><?php echo wp_get_attachment_image(get_term_meta($term_id,"black",true)); ?></div>
                <div class="icon_white"><?php echo wp_get_attachment_image(get_term_meta($term_id,"white",true)); ?></div>

                <div style="flex:1;"><?php echo $the_term->name; ?></div>
                <span></span>
              </div>
              <div class="faq_body"><?php echo $item["meta_benefits_".$i."_description"]; ?></div>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</section>
<script>
jQuery(document).ready(function () {
  jQuery(".faq_list li").click(function () {
    var th = jQuery(this);
    if (jQuery(this).is(".active")) {
      jQuery(this).find(".faq_body").slideUp(500, function () {
        th.removeClass("active");
      });
    } else {
      jQuery(this).find(".faq_body").slideDown(500, function () {
        th.addClass("active");
      });
    }
  })
});
</script>
