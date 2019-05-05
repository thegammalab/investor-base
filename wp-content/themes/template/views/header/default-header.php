<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>

<header class="">
  <nav class="navbar navbar-expand-lg">
    <div class="container first_line">
      <a class="navbar-brand" href="<?php echo get_bloginfo("url"); ?>"><img src="<?php echo get_bloginfo("template_directory"); ?>/assets/images/logo.svg" alt="Investor Base"/></a>
      <form action="" class="search_form">
        <div class="grey_bg d-flex align-items-center">
          <input id="main_search" autocomplete="off" type="text" class="" placeholder="What are you searching for?">
          <button class="btn"><i class="fas fa-search"></i></button>
        </div>
        <div id="autocomplete_box">

        </div>
      </form>

      <div class="top_btn">
        <?php if(UID){ ?>
          <a href="<?=get_permalink(PAGE_ID_ACCOUNT_DASHBOARD);?>">
          <div class="btn-group logged_buttons" >
            <div class="header_avatar">
              <?=get_avatar(UID); ?>
            </div>
            <button class="btn btn-secondary">
              Account
            </button>
          </div>
        </a>
        <?php }else{ ?>
          <div class="btn-group" >
            <button class="btn btn-primary " data-toggle="modal" data-target="#login_popup">
              sign in
            </button>
            <button class="btn btn-secondary " data-toggle="modal" data-target="#register_popup">
              join free
            </button>
          </div>
        <?php } ?>
      </div>
      <div class="notification_icon <?php if(apply_filters('tdf_has_alerts',UID)){ echo 'active'; } ?>">
        <a href="<?=get_permalink(PAGE_ID_ACCOUNT_ALERTS);?>"><img src="<?php echo get_bloginfo("template_directory"); ?>/assets/images/bell.svg" alt="" /></a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_menu" aria-controls="main_menu" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-bars"></i>
      </button>
    </div>
    <div class="collapse navbar-collapse " id="main_menu">
      <div class="container">
        <?php
        if (has_nav_menu('main_menu_header')) :
          wp_nav_menu(array('theme_location' => 'main_menu_header', 'walker' => new TDF_Nav_Walker(), 'menu_class' => 'navbar-nav d-flex justify-content-center ', "depth" => 3));
        endif;
        ?>
      </div>
    </nav>
  </div>

</header>
<!-- <script src="<?php echo get_bloginfo("template_directory"); ?>/assets/js/plugins/owl/owl.carousel.min.js"></script>

<link rel="stylesheet" href="<?php echo get_bloginfo("template_directory"); ?>/assets/css/plugins/owl/owl.carousel.min.css">
<link rel="stylesheet" href="<?php echo get_bloginfo("template_directory"); ?>/assets/css/plugins/owl/owl.theme.default.min.css"> -->
