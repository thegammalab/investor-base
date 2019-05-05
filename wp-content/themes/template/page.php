<?php
the_post();
if(!function_exists("is_woocommerce")){
  include("views/pages/default.php");
}elseif(is_woocommerce() || is_cart() || is_checkout() || is_account_page()){
  include("woocommerce.php");
}else{
  include("default.php");
 }
