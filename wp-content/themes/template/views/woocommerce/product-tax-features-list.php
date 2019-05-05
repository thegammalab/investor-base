<section class="browse_products">
  <div class="container">
    <div class="mb-6">
      <?php $the_term = get_term_by('slug',get_query_var('term'),get_query_var('taxonomy')); ?>
      <div class="mb-6 text-center">
        <h1>Browse <?php echo $the_term->name; ?></h1>
        <?php echo apply_filters("the_content",$the_term->description); ?>
      </div>
    </div>
    <?php
    $categories = get_terms("product_cat");
    foreach($categories as $cat){
      $results = apply_filters( 'tdf_get_posts',"product",10,0,array("search"=>array("tax_product_cat"=>$cat->term_id, "tax_features"=>$the_term->term_id)));
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
