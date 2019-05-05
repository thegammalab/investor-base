<section class="top_list_page">
  <div class="container">
    <?php
    if ( function_exists('yoast_breadcrumb') ) {
      yoast_breadcrumb( '<div class="breadcrumb align-items-center justify-content-center">','</div>' );
    }
    ?>
    <h1 class="text-center"><?php the_title(); ?></h1>
  </div>
</section>
<section class="top_list_page">
  <div class="container">
<?php the_content(); ?>
  </div>
</div>
