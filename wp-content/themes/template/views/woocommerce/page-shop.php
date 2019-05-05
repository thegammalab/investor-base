<section class="browse_products">
  <div class="container">
    <div class="mb-6">
      <?php echo get_the_content(get_the_ID()); ?>
    </div>
    <?php
    $categories = get_terms("product_cat");
    foreach($categories as $cat){
      $results = apply_filters( 'tdf_get_posts',"product",10,0,array("search"=>array("tax_product_cat"=>$cat->term_id)));
      if(count($results["items"])){
        //do_action( 'woocommerce_before_shop_loop' );
        // ^ uncomment this line to show top count and sort

        woocommerce_product_loop_start();
        ?>
        <div class="col-md-6">
          <div class="product_category">
            <h3 class="fs225"><?php echo $cat->name; ?></h3>
            <?php echo $cat->description; ?>
          </div>
        </div>

        <?php
        echo $results["output"];
        woocommerce_product_loop_end();
        do_action( 'woocommerce_after_shop_loop' );
      }
    }
    ?>
  </div>
</section>
