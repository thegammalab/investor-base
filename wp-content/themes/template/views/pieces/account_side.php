<div class="d-flex align-items-center mb-4">
  <div class="profile_image">
    <?=get_avatar(UID); ?>
  </div>
  <div class="top_profile">
    <h4>Hi <?=apply_filters('tdf_get_display_name',UID); ?>,</h4>
    <ul>
      <li><a href="<?=get_bloginfo("url")."/my-account/edit-account/"; ?>">Edit Account</a></li>
      <li><a href="<?=get_bloginfo("url")?>/?action=logout">Logout</a></li>
    </ul>
  </div>
</div>
<div class="profile_menu_left">
  <?php
  if (has_nav_menu('account_menu')) :
    wp_nav_menu(array('theme_location' => 'account_menu', 'walker' => new TDF_Nav_Walker(), 'menu_class' => '', "depth" => 1));
  endif;
  ?>
</div>
<div class="d-none d-lg-block">

<div class="widget_sidebar">
  <?php dynamic_sidebar("account_sidebar"); ?>
</div>
</div>
