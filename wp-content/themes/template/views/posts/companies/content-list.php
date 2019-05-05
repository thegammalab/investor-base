<?php
$the_title = "Browse the markets";
$act_all = "active";
$elem = (get_queried_object());
$the_page_url = "https://investorbase.thedesignfactory.ro/company/";

$search = array();
if(isset($elem->term_id)){
  $the_cat = $elem->term_id;
  $act_all = "";
  $search["tax_".$elem->taxonomy]=array($the_cat);
  $the_cat_obj = get_term_by("id",$the_cat,$elem->taxonomy);
  $the_page_url = get_term_link($the_cat_obj);

  if($the_cat_obj){
    $the_title = "Browse ".$the_cat_obj->name;
  }

  if($elem->taxonomy=="exchanges"){
    $act_all = "active";

    $_SESSION["exchange"] = $the_cat;
    $_SESSION["exchange_info"] = $the_cat_obj;
    $_SESSION["exchange_code"] = strtolower(get_term_meta($_SESSION["exchange"],"ex_code",true));
  }
}else{
  $the_page_url = "https://www.investorbase.ca/company/";
}
$args= array("search"=>$search,"order"=>"title_asc","get_ids_only"=>true);
?>
<section class="events_list">
  <div class="container">
    <div class="page_title d-flex align-items-center">
      <h1><?=$the_title; ?></h1>
    </div>
    <div class="top_events">
      <div class="row">
        <div class="col-md-9">
          <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" />

          <ul class="nav nav-pills events_tabs" style="display:flex; flex-wrap:nowrap; overflow:hidden;" id="events" role="tablist">
            <li class="nav-item">
              <a style="white-space: nowrap;" class="nav-link <?=$act_all;?>" href="<?=get_post_type_archive_link("companies");?>" id="allevents-tab" >All Markets</a>
            </li>
            <?php
            $ev_cats = get_terms("company_cat","hide_empty=0");
            foreach($ev_cats as $ev_cat){
              if(isset($the_cat) && $the_cat==$ev_cat->term_id){
                $act = 'active';
              }else{
                $act = '';
              }
              ?>
              <li class="nav-item">
                <a style="white-space: nowrap;" class="nav-link <?=$act; ?>" href="<?=get_term_link($ev_cat); ?>" id="earnings-tab" ><?=$ev_cat->name;?></a>
              </li>
            <?php } ?>
          </ul>

        </div>
        <div class="col-md-3">

          <div id="exchange_select" class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?=apply_filters('tdf_get_exchange_flag',$_SESSION["exchange"]); ?>
              <?=$_SESSION["exchange_info"]->name; ?>
            </button>

            <ul class="dropdown-menu">
              <?php
              $exchanges = get_terms("exchanges","hide_empty=0&orderby=count&order=desc");
              foreach($exchanges as $ex){
                if(get_term_meta($ex->term_id,"show_in_dropdown",true)){
              ?>
              <li>
                <a href="<?=get_term_link($ex); ?>" title="<?=$ex->name; ?>">
                  <?=apply_filters('tdf_get_exchange_flag',$ex->term_id); ?>
                  <?=$ex->name; ?>
                </a>
              </li>
            <?php }
          } ?>
            </ul>
          </div>

        </div>
      </div>

    </div>
    <div class="row">
      <div class="col-lg-9 pt-2">
        <script src="<?php echo get_bloginfo("template_directory"); ?>/assets/js/company_list.js"></script>

        <script>
        var comp_order = "title_asc";
        var comp_page = <?=get_query_var("paged");?>;
        var query_args = "<?=http_build_query($args);?>"
        var the_current_page = "<?=$the_page_url; ?>";

        jQuery(document).ready( function () {
          get_companies(comp_order,comp_page,the_current_page);
          companies_sort();
        });
        </script>

        <div id="allmarkets_wrapper">
          <div id="allmarkets_load">
            <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
          </div>
          <div id="allmarkets">
            <div class="pt-0 pb-3">
              <div class="show_products d-flex justify-content-end" id="allmarkets_counts">

              </div>
            </div>
            <div class="table-responsive">

            <table id="market_list" class="table table-striped table-borderless browse_table">
              <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col" data-order='meta_DIR_company_code'><div>Symbol</div></th>
                  <th scope="col" class="sorting_asc" data-order='title_DIR'><div>Name</div></th>
                  <th scope="col" data-order='meta_DIR_num_<?=$_SESSION["exchange_code"];?>_last'><div>Price</div></th>
                  <th scope="col" data-order='meta_DIR_num_<?=$_SESSION["exchange_code"];?>_change'><div>Change</div></th>
                  <th scope="col" data-order='meta_DIR_num_<?=$_SESSION["exchange_code"];?>_changepercent'><div>%Change</div></th>
                  <th scope="col" data-order='meta_DIR_num_<?=$_SESSION["exchange_code"];?>_sharevolume'><div>Volume</div></th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
            <div class="pt-6 pb-6">
              <div id="allmarkets_pagination" class="pagination_links">

              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="widget_sidebar">
          <?php dynamic_sidebar("company_page_sidebar"); ?>
        </div>
      </div>
    </div>
  </div>
</section>
