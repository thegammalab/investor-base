<?php
$uid = get_current_user_id();
session_start();
if(!isset($_SESSION["exchange"]) || !isset($_SESSION["exchange_code"])){
  $_SESSION["exchange"] = 9866;
  $_SESSION["exchange_info"] = get_term_by("id",$_SESSION["exchange"],"exchanges");
  $_SESSION["exchange_code"] = strtolower(get_term_meta($_SESSION["exchange"],"ex_code",true));
}

if(!$uid && (is_page(PAGE_ID_ACCOUNT_PORTFOLIO) || is_page(PAGE_ID_ACCOUNT_WATCHLIST) || is_page(PAGE_ID_ACCOUNT_EDIT) || is_page(PAGE_ID_ACCOUNT_DASHBOARD) || is_page(PAGE_ID_ACCOUNT_ALERTS))){
  header("Location:".get_bloginfo("url")."/?login=required&return_url=".get_the_permalink());
}
