<ul id="footer_alert"></ul>
<script>
setInterval(function(){
  $.ajax({
    url: "<?=get_bloginfo("url")?>/wp-admin/admin-ajax.php?action=get_alert_notif",
    context: document.body
  }).done(function(data) {
    if(data){
      jQuery(".notification_icon").addClass("active");
      jQuery("#footer_alert").html(data);
      jQuery("#footer_alert").addClass("active");
    }
  });
},5000);
</script>
<footer>
  <div class="first_footer">
    <div class="container">
      <div class="row ">
        <div class="col-md-3 col-6">
          <div class="footer_menu_title">
            Stay Connected
          </div>
          <ul class="footer_social">
            <li><a href="#"><i class="fab fa-facebook-f"></i> Facebook</a></li>
            <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
          </ul>
        </div>
        <div class="col-md-3 col-6">
          <div class="footer_menu_title">
            News
          </div>
          <?php
          if (has_nav_menu('footer_menu1')) :
            wp_nav_menu(array('theme_location' => 'footer_menu1', 'walker' => new TDF_Nav_Walker(), 'menu_class' => 'footer_menu ', "depth" => 2));
          endif;
          ?>

        </div>
        <div class="col-md-6 col-12">
          <div class="footer_menu_title">
            Markets
          </div>
          <?php
          if (has_nav_menu('footer_menu2')) :
            wp_nav_menu(array('theme_location' => 'footer_menu2', 'walker' => new TDF_Nav_Walker(), 'menu_class' => 'footer_menu footer_2col', "depth" => 2));
          endif;
          ?>

        </div>


      </div>
    </div>
  </div>
  <div class="copyright_section text-center">
    <div class="container">
      <?php
      if (has_nav_menu('privacy_menu')) :
        wp_nav_menu(array('theme_location' => 'privacy_menu', 'walker' => new TDF_Nav_Walker(), 'menu_class' => 'privacy_menu ', "depth" => 2));
      endif;
      ?>

      <p class="mb-0">© 2018 investorbase All Rights Reserved.</p>
    </div>

  </div>
</footer>
<div class="modal fade" id="login_popup" tabindex="-1" role="dialog" aria-labelledby="signupLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg signup_modal" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="signupLabel">Sign In</h2>

      </div>
      <div class="modal-body">
        <?php do_action( 'wordpress_social_login' ); ?>

        <h4>login to your account</h4>
        <div class="signup_form">
          <?php
          if ($_GET["login"] == "required") {
            ?>
            <script>jQuery(document).ready(function(){jQuery("#login_popup").modal();});</script>
            <div class="alert alert-danger">
              You have to be logged in to be able to see this page
            </div>
            <?php
          }
          if ($_GET["confirm_email"]) {
            $mylink = $wpdb->get_row("SELECT * FROM `wp_usermeta` WHERE `meta_key` = 'confirmation_code' AND `meta_value`='" . $_GET["confirm_email"] . "'");
            if ($mylink->user_id) {
              update_user_meta($mylink->user_id, "confirmed_email", 1);
              ?>
              <div class="alert alert-success">
                You have successfully confirmed your email address.
              </div>
            <?php } else { ?>
              <div class="alert alert-danger">
                The confirmation code provided is invalid.
              </div>
              <?php
            }
            ?>
            <script>jQuery(document).ready(function(){jQuery("#login_popup").modal();});</script>
            <?php
          }
          if ($_GET["action"] == "reset_success") {
            ?>
            <script>jQuery(document).ready(function(){jQuery("#login_popup").modal();});</script>

            <div class="alert alert-success">
              You have successfully reset your password, your new password has been emailed to you.
            </div>
            <?php
          }
          if (isset($_GET['login']) && $_GET['login'] == 'failed') {
            ?>
            <script>jQuery(document).ready(function(){jQuery("#login_popup").modal();});</script>

            <div class="alert alert-danger">
              Login failed: You have entered an incorrect Username or password, please try again.
            </div>
            <?php
          }
          $actual_link = get_the_permalink(PAGE_ID_ACCOUNT_DASHBOARD);
          $args = array();
          if ($_GET["return_url"]) {
            $args["redirect"] = urldecode($_GET["return_url"]);
          } else {
            $args["redirect"] = $actual_link;
          }
          wp_login_form($args);
          ?>
          <div class="forgot-pass-link">
            <a href="#" id="forgot_pass_link" class="forgot_pass">Forgot your Password?</a>
          </div>
          <script>
          jQuery(document).ready(function(){
            jQuery("#forgot_pass_link").click(function(){
              jQuery("#login_popup").modal('toggle');
              jQuery("#forgot_popup").modal();
            })
            jQuery("#user_login").addClass("form-control").attr("placeholder","Email address...");
            jQuery("#user_pass").addClass("form-control").attr("placeholder","Password...");
          });
          </script>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="preferences_popup" tabindex="-1" role="dialog" aria-labelledby="registerLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg signup_modal" role="document">
    <div class="modal-content">

      <div class="modal-body pt-5">
        <form action="<?=get_bloginfo("url");?>/wp-admin/admin-post.php" method="post" enctype="multipart/form-data" id="register_form" class="mx-0 text-left signup_form">
          <?php $preferences = get_user_meta(UID,"preferences",false);  ?>
          <?php if ($error_msg) { ?>
            <div class="alert alert-danger">
              <?php echo $error_msg; ?>
            </div>
          <?php } else { ?>
            <div class="alert alert-danger" style="display: none;"></div>
          <?php } ?>
          <div class="row" id="pref_list">
            <?php $terms = get_terms("category","hide_empty=0");
            foreach($terms as $term){
              ?>
              <div class="col-md-6">
                <div class="input-group mb-3 ">
                  <div class="input-group-text">
                    <label class="pref_item">
                      <?php if(in_array($term->term_id,$preferences)){
                        $check = 'checked="checked"';
                      }else{
                        $check = '';
                      }
                      ?>
                      <input <?=$check; ?> type="checkbox" name="user_meta_preferences[]" value="<?=$term->term_id; ?>" aria-label="Checkbox for following text input" >
                      <span><?=$term->name; ?></span>
                    </label>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
          <hr />
          <div class="row">
            <div class="col-md-12">
              <input type="hidden" name="action" value="tdf_save_user" />
              <input type="hidden" name="user_core_id" value="<?=UID;?>" />

              <input type="hidden" name="success_url" value="<?=get_bloginfo("url"); ?>" />
              <input type="hidden" name="error_url" value="<?=get_bloginfo("url"); ?>" />

              <button type="submit" class="btn btn-primary w-100">Update your preferences</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="forgot_popup" tabindex="-1" role="dialog" aria-labelledby="registerLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg signup_modal" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="registerLabel">Forgot Pass</h2>

      </div>
      <div class="modal-body">
        <h4>Enter E-mail or Username:</h4>
        <div class="signup_form">
        <?php
        if (isset($_GET["action"])){
          if ($_GET["action"] == "reset_password") { ?>
            <div class="alert alert-success">
              Password Reset email has been sent.
            </div>
          <?php }
          if ($_GET["action"] == "invalid_reset_key") { ?>
            <div class="alert alert-danger">
              Sorry, it seems the reset password key you are using is invalid
            </div>
          <?php }
        } ?>
        <div id="result">

        </div> <!-- To hold validation results -->
        <form class="user_form" id="wp_pass_reset" action="" method="post" >
          <div class="row">
<div class="col-sm-8">
  <input type="text" class="form-control" id="reset_username" name="user_input" value="" placeholder="Your E-mail" />
  <input type="hidden" name="action" value="tdf_send_reset_password" />

</div>
<div class="col-sm-4">
  <input type="submit" id="submitbtn" class="btn btn-primary reset_password button-start " name="submit" value="Validate" />

</div>
          </div>
        </form>

        <script type="text/javascript">
        jQuery("#wp_pass_reset").submit(function () {
          jQuery('#result').html('<div class="alert alert-warning loading">Validating...</div>').fadeIn();
          var input_data = jQuery('#wp_pass_reset').serialize();
          jQuery.ajax({
            type: "POST",
            url: "<?php echo admin_url("admin-ajax.php"); ?>",
            data: input_data,
            success: function (msg) {
              jQuery('.loading').remove();
              jQuery("#reset_username").val("");
              jQuery('div#result').hide().html(msg).fadeIn('slow');
            }
          });
          return false;
        });
        </script>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="register_popup" tabindex="-1" role="dialog" aria-labelledby="registerLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg signup_modal" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="registerLabel">Register</h2>

      </div>
      <div class="modal-body">
        <?php if(!get_current_user_id()){ ?>
          <div class="register_step_1">
            <div class="signup_form">
            <ul class="nav-justified d-flex align-items-center" id="register_form" role="tablist">
              <li>
                <a class="active" href="#" >basic info </a>
              </li>
              <li >
                <a class="" href="#">profile info  </a>
              </li>
              <li class="">
                <a class="" href="#">watchlist </a>
              </li>
              <li class="">
                <a class="" href="#">my interests </a>
              </li>
              <li class="">
                <a class="" href="#"> invite friends</a>
              </li>
            </ul>
</div>
            <?php do_action( 'wordpress_social_login' ); ?>

            <hr class="mt-0 mb-4">
            <h4>signup for your account</h4>
            <form action="<?=get_bloginfo("url");?>/wp-admin/admin-post.php" method="post" enctype="multipart/form-data" class="mx-0 text-left signup_form">

              <?php if ($error_msg) { ?>
                <div class="alert alert-danger">
                  <?php echo $error_msg; ?>
                </div>
              <?php } else { ?>
                <div class="alert alert-danger" style="display: none;"></div>
              <?php } ?>
              <div class="row">
                <div class="col-md-6">
                  <div class=" form-group">
                    <!-- <label>First Name: <span class="red">*</span></label> -->
                    <?php echo apply_filters('tdf_get_add_user_field',"first_name", "", array("placeholder" => "First Name", "classes" => "required form-control", "value"=>$_REQUEST["user_meta_first_name"])); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <!-- <label>Last Name: <span class="red">*</span></label> -->
                    <?php echo apply_filters('tdf_get_add_user_field',"last_name", "", array("placeholder" => "Last Name", "classes" => "required form-control", "value"=>$_REQUEST["user_meta_last_name"])); ?>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class=" form-group">
                    <!-- <label>Password: <span class="red">*</span></label> -->
                    <?php echo apply_filters('tdf_get_add_user_field',"password", "", array("placeholder" => "Password", "classes" => "required form-control", "value"=>$_REQUEST["user_core_password"])); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class=" form-group">
                    <!-- <label>Email: <span class="red">*</span></label> -->
                    <?php echo apply_filters('tdf_get_add_user_field',"password_confirm", "", array("placeholder" => "Confirm Password", "classes" => "check_email required form-control", "value"=>$_REQUEST["user_core_email"])); ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class=" form-group">
                    <!-- <label>Email: <span class="red">*</span></label> -->
                    <?php echo apply_filters('tdf_get_add_user_field',"email", "", array("placeholder" => "Email", "classes" => "check_email required form-control", "value"=>$_REQUEST["user_core_email"])); ?>
                  </div>
                </div>
                <div class="col-md-6">
                    <input type="hidden" name="action" value="tdf_save_user" />
                  <input type="hidden" name="user_core_role" value="subscriber" />
                  <input type="hidden" name="autologin" value="1" />
                  <?php if(isset($_GET["friend_id"])){ ?>
                    <input type="hidden" name="user_meta_ref" value="<?=$_GET["friend_id"]; ?>" />
                  <?php
                }

                  if ($_GET["return_url"]) {
                    $redir = urldecode($_GET["return_url"]);
                  } else {
                    $redir = get_bloginfo("url") . "/?action=finish_signup";
                  }
                  ?>
                  <input type="hidden" name="success_url" value="<?php echo $redir; ?>" />
                  <input type="hidden" name="error_url" value="<?=get_bloginfo("url"); ?>" />

                  <button type="submit" class="btn btn-primary w-100">Register your account</button>
                </div>
                <div class="col-sm-12">
                  <div class="form-check">
                    <input style="height:auto; margin-top:7px;" class="form-check-input" type="checkbox" name="join_newsletter" value="1" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                      Join the InvestorBase newsletter
                    </label>
                  </div>
                </div>
              </div>

            </form>


          </div>
        <?php }elseif($uid=get_current_user_id() && isset($_GET["action"]) && ($_GET["action"]=="finish_social" || $_GET["action"]=="finish_signup")){ ?>
          <script>jQuery(document).ready(function(){jQuery("#register_popup").modal();});</script>
          <form action="<?=get_bloginfo("url");?>/wp-admin/admin-post.php" method="POST" enctype="multipart/form-data" class="mx-0 text-left signup_form">

            <div class="register_step_2">
              <ul class=" nav-justified d-flex align-items-center" id="register_form" role="tablist">
                <li class="">
                  <a class=" active" href="#">basic info </a>
                </li>
                <li class="" href="#">
                  <a class=" active" >profile info  </a>
                </li>
                <li class="">
                  <a class="" href="#">watchlist </a>
                </li>
                <li class="">
                  <a class="" href="#">my interests </a>
                </li>
                <li class="">
                  <a class="" href="#"> invite friends</a>
                </li>
              </ul>

              <h4>we would like to know a bit more</h4>
              <div class="row ">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Tell us a bit about yourself</label>
                    <?php echo apply_filters('tdf_get_update_user_field',"description",$uid, "", array("placeholder" => "", "classes" => "required form-control", "value"=>$_REQUEST["user_core_password"])); ?>
                  </div>
                </div>
                <hr />
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Upload an image as yourself</label>
                    <div class="image_upload">
                      <?php echo apply_filters('tdf_get_update_user_field',"avatar",$uid, "", array("placeholder" => "", "classes" => "required form-control", "value"=>$_REQUEST["user_core_password"])); ?>
                    </div>
                  </div>
                </div>


              </div>
              <div class="row align-items-center">
                <div class="col-md-6">
                  <a href="#" class="forgot_pass next_step">Skip this step</a>
                </div>
                <div class="col-md-6">
                  <button type="button" class="btn btn-primary w-100 next_step">save my profile information</button>
                </div>
              </div>
            </div>
            <div class="register_step_3">
              <ul class=" nav-justified d-flex align-items-center" id="register_form" role="tablist">
                <li class="">
                  <a class=" active" href="#">basic info </a>
                </li>
                <li class="">
                  <a class=" active" href="#">profile info  </a>
                </li>
                <li class="">
                  <a class=" active" href="#">watchlist </a>
                </li>
                <li class="">
                  <a class="" href="#">my interests </a>
                </li>
                <li class="">
                  <a class="" href="#"> invite friends</a>
                </li>
              </ul>

              <h4>start building your watchlist</h4>
              <div class="row mb-4">
                <div class="col-md-12">
                  <div id="watchlist_search">
                    <div class="search_input">
                      <input id="watchlist_input" type="text" class="form-control" placeholder="Search for companies...">
                      <button class="btn w-25 text-right">Search</button>
                    </div>
                    <script>
                    jQuery(document).ready(function(){
                      var watchlist_autocomplete;
                      jQuery("body").click(function(){
                        jQuery("#watchlist_input").val("");
                        jQuery('#watchlist_autocomplete').css("height","0").html("");

                      })

                      jQuery("#watchlist_input").keyup(function(){
                        var text = jQuery(this).val();
                        watchlist_autocomplete = jQuery.ajax({
                          url: bloginfo_url+"/wp-admin/admin-ajax.php?action=get_watchlist_autocomplete&key="+jQuery("#watchlist_input").val(),
                          beforeSend : function()    {
                            if(watchlist_autocomplete != null) {
                              watchlist_autocomplete.abort();
                            }
                          },
                          success: function(data) {
                            if(data){
                              jQuery('#watchlist_autocomplete').css("height","auto").html(data).show();
                            }else{
                              jQuery('#watchlist_autocomplete').css("height","0").html("");
                            }
                          }
                        });
                      });
                    });
                    </script>
                    <div id="watchlist_autocomplete">

                    </div>
                  </div>
                </div>
              </div>
              <table id="watchlist_results" style="display:none;" class="table watchlist_tab">
                <thead>
                  <tr>
                    <th scope="col">Symbol</th>
                    <th scope="col"></th>
                    <th scope="col">Last price</th>
                    <th scope="col">change</th>
                    <th scope="col">% change</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
              <div class="row align-items-center">
                <div class="col-md-6">
                  <a href="#" class="forgot_pass next_step">Skip this step</a>
                </div>
                <div class="col-md-6">
                  <button type="button" class="btn btn-primary w-100 next_step">save my watchlist</button>
                </div>
              </div>
            </div>
            <div class="register_step_4">
              <ul class=" nav-justified d-flex align-items-center" id="register_form" role="tablist">
                <li class="">
                  <a class=" active" href="#">basic info </a>
                </li>
                <li class="">
                  <a class=" active" href="#">profile info  </a>
                </li>
                <li class="">
                  <a class=" active" href="#">watchlist </a>
                </li>
                <li class="">
                  <a class=" active" href="#">my interests </a>
                </li>
                <li class="">
                  <a class="" href="#"> invite friends</a>
                </li>
              </ul>

              <h4>tell us about what you are interested in</h4>
              <div class="row" id="pref_list">
                <?php $terms = get_terms("company_cat","hide_empty=0");
                foreach($terms as $term){
                  ?>
                  <div class="col-md-6">
                    <div class="input-group mb-3 ">
                      <div class="input-group-text">
                        <label class="pref_item">
                          <input type="checkbox" name="user_meta_preferences[]" value="<?=$term->term_id; ?>" aria-label="Checkbox for following text input" >
                          <span><?=$term->name; ?></span>
                        </label>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
              <div class="row align-items-center">
                <div class="col-md-6">
                  <a href="#" class="forgot_pass next_step">Skip this step</a>
                </div>
                <div class="col-md-6">
                  <button type="button" class="btn btn-primary w-100 next_step">save my interests</button>
                </div>
              </div>
            </div>
            <div class="register_step_5">
              <ul class=" nav-justified d-flex align-items-center" id="register_form" role="tablist">
                <li class="">
                  <a class=" active" href="#">basic info </a>
                </li>
                <li class="">
                  <a class=" active" href="#">profile info  </a>
                </li>
                <li class="">
                  <a class=" active" href="#">watchlist </a>
                </li>
                <li class="">
                  <a class=" active" href="#">my interests </a>
                </li>
                <li class="">
                  <a class="active" href="#"> invite friends</a>
                </li>
              </ul>

              <h4>tell your friends about it</h4>
              <div class="row">
                <div class="col-md-12 mb-4">
                  <div class="search_input">
                    <input type="text" id="invite_send_input" class="form-control" placeholder="Friend's email...">
                    <button id="invite_send" type="button" class="btn w-25 text-right">Send invite</button>
                  </div>
                </div>
              </div>
              <script>
              jQuery(document).ready(function(){
                jQuery("#invite_send").click(function(){
                  if(jQuery("#invite_send_input").val()){
                    jQuery.ajax({
                      url: bloginfo_url+"/wp-admin/admin-ajax.php?action=send_invite&friend_id=<?=get_current_user_id();?>&invite_email="+jQuery("#invite_send_input").val(),
                      success: function(data) {
                        jQuery(".invites_sent").show().delay(800).append('<li>'+jQuery("#invite_send_input").val()+'<span>(sent)</span></li>');
                        jQuery("#invite_send_input").val("");
                      }
                    });

                  }
                });
              });
              </script>
              <ul class="invites_sent  mb-4" style="display:none;">

              </ul>

              <div class="row align-items-center">
                <div class="col-md-6">
                  <a href="#" class="forgot_pass next_step">Skip this step</a>
                </div>
                <div class="col-md-6">
                  <input type="hidden" name="action" value="tdf_update_user" />
                  <input type="hidden" name="user_core_id" value="<?=get_current_user_id();?>" />

                  <?php

                  if ($_GET["return_url"]) {
                    $redir = urldecode($_GET["return_url"]);
                  } else {
                    $redir = get_permalink(PAGE_ID_ACCOUNT_DASHBOARD);
                  }
                  ?>
                  <input type="hidden" name="success_url" value="<?php echo $redir; ?>" />
                  <input type="hidden" name="error_url" value="<?=get_bloginfo("url"); ?>" />

                  <button type="submit" class="btn btn-primary w-100 form-control" style="border-radius:0;">finish signup</button>
                </div>
              </div>
            </div>
          </form>
        <?php } ?>
      </div>

    </div>
  </div>
</div>
<style>
.register_step_3,.register_step_4,.register_step_5{
  display: none;
}
</style>
<script>
jQuery(document).ready(function(){
  jQuery(".register_step_1 .next_step").click(function(){
    jQuery(".register_step_1").hide();
    jQuery(".register_step_2").show();
  });
  jQuery(".register_step_2 .next_step").click(function(){
    jQuery(".register_step_2").hide();
    jQuery(".register_step_3").show();
  });
  jQuery(".register_step_3 .next_step").click(function(){
    jQuery(".register_step_3").hide();
    jQuery(".register_step_4").show();
  });
  jQuery(".register_step_4 .next_step").click(function(){
    jQuery(".register_step_4").hide();
    jQuery(".register_step_5").show();
  });
  <?php if (isset($_GET["action"]) && $_GET["action"] == "invite_signup") {?>
    jQuery("#register_popup").modal();
  <?php } ?>
})
</script>
