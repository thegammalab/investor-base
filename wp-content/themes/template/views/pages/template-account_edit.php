<?php
/*
Template Name: Account Edit
*/
?>
<section class="my_account mb-6">
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <?php include(get_stylesheet_directory()."/views/pieces/account_side.php"); ?>
      </div>
      <div class="col-lg-9">
        <div class="d-flex align-items-center mb-4">
          <div class="page_title d-flex flex-grow-1">
            <h1 class="mb-0 ">Welcome, <?=apply_filters('tdf_get_display_name',UID); ?></h1>
          </div>
        </div>
        <div class="white_bg mb-5">
          <h2>Profile Information</h2>
          <form action="<?=get_bloginfo("url");?>/wp-admin/admin-post.php" method="post" enctype="multipart/form-data" id="register_form" class="mx-0 text-left signup_form">

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
                  <label>First Name: <span class="red">*</span></label>
                  <?php echo apply_filters('tdf_get_update_user_field',"first_name",UID, "", array("placeholder" => "First Name", "classes" => "required form-control", "value"=>$_REQUEST["user_meta_first_name"])); ?>
                </div>
                <div class="form-group">
                  <label>Last Name: <span class="red">*</span></label>
                  <?php echo apply_filters('tdf_get_update_user_field',"last_name",UID, "", array("placeholder" => "Last Name", "classes" => "required form-control", "value"=>$_REQUEST["user_meta_last_name"])); ?>
                </div>
                <div class=" form-group">
                  <label>Email: <span class="red">*</span></label>
                  <?php echo apply_filters('tdf_get_update_user_field',"email",UID, "", array("placeholder" => "Email", "classes" => "check_email required form-control", "value"=>$_REQUEST["user_core_email"])); ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class=" form-group">
                  <label>About me: <span class="red">*</span></label>
                  <?php echo apply_filters('tdf_get_update_user_field',"description",UID, "", array("placeholder" => "About you", "classes" => "min-hei required form-control", "value"=>$_REQUEST["user_core_email"])); ?>
                </div>
                <div class="form-group">
                  <label>Upload an image as yourself</label>
                  <div class="image_upload">
                    <?php echo apply_filters('tdf_get_update_user_field',"avatar",UID, "", array("placeholder" => "", "classes" => "required form-control", "value"=>$_REQUEST["user_core_password"])); ?>
                  </div>
                </div>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-md-12">
                <input type="hidden" name="action" value="tdf_save_user" />
                <input type="hidden" name="user_core_id" value="<?=UID;?>" />

                <input type="hidden" name="success_url" value="<?=get_permalink(PAGE_ID_ACCOUNT_EDIT); ?>" />
                <input type="hidden" name="error_url" value="<?=get_permalink(PAGE_ID_ACCOUNT_EDIT); ?>" />

                <button type="submit" class="btn btn-primary w-100">Update your info</button>
              </div>
            </div>

          </form>



        </div>
        <div class="white_bg mb-5">
          <h2>News preferences</h2>
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

                <input type="hidden" name="success_url" value="<?=get_permalink(PAGE_ID_ACCOUNT_EDIT); ?>" />
                <input type="hidden" name="error_url" value="<?=get_permalink(PAGE_ID_ACCOUNT_EDIT); ?>" />

                <button type="submit" class="btn btn-primary w-100">Update your preferences</button>
              </div>
            </div>
          </form>



        </div>
        <div class="white_bg mb-5">
          <h2>Update Password</h2>
          <form action="<?=get_bloginfo("url");?>/wp-admin/admin-post.php" method="post" enctype="multipart/form-data" id="update_password" class="mx-0 text-left signup_form">
            <?php if($_GET["action"]=="reset_pass"){ ?>
            <script>
            jQuery(document).ready(function(){
              jQuery([document.documentElement, document.body]).animate({
                scrollTop: jQuery("#update_password").offset().top
              }, 1000);
            })
            </script>
            <div class="alert alert-warning">Please update your password</div>
            <?php } ?>
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
                  <label>Password: <span class="red">*</span></label>
                  <?php echo apply_filters('tdf_get_update_user_field',"password",UID, "", array("placeholder" => "Password", "classes" => "required form-control", "value"=>$_REQUEST["user_core_email"])); ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class=" form-group">
                  <label>Confirm Password: <span class="red">*</span></label>
                  <?php echo apply_filters('tdf_get_update_user_field',"password_confirm",UID, "", array("placeholder" => "Password again", "classes" => "required form-control", "value"=>$_REQUEST["user_core_email"])); ?>
                </div>

              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-md-12">
                <input type="hidden" name="action" value="tdf_save_user" />
                <input type="hidden" name="user_core_id" value="<?=UID;?>" />

                <input type="hidden" name="success_url" value="<?=get_permalink(PAGE_ID_ACCOUNT_EDIT); ?>" />
                <input type="hidden" name="error_url" value="<?=get_permalink(PAGE_ID_ACCOUNT_EDIT); ?>" />

                <button type="submit" class="btn btn-primary w-100">Update your password</button>
              </div>
            </div>

          </form>



        </div>


      </div>
    </div>
  </div>
</section>
