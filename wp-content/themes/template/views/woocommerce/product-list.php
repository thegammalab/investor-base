<section class="browse_products">
  <div class="container">
    <?php
    $results = apply_filters( 'tdf_get_posts',"product",10,get_query_var("page"),array());
    if(count($results["items"])){
      //do_action( 'woocommerce_before_shop_loop' );
      // ^ uncomment this line to show top count and sort
      woocommerce_product_loop_start();
      $cat = get_term_by('slug',get_query_var('term'),get_query_var('taxonomy'));
      if($cat){
      ?>
      <div class="col-md-6">
        <div class="product_category">
          <h3 class="fs225"><?php echo $cat->name; ?></h3>
          <?php echo $cat->description; ?>
        </div>
      </div>
      <?php
    }
      echo $results["output"];
      woocommerce_product_loop_end();
      do_action( 'woocommerce_after_shop_loop' );
    }else{
      echo $results["output"];
    }
    ?>
  </div>
</section>
