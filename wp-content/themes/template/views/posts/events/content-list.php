<?php
$the_title = "Browse Events";
$act_all = "active";
$elem = (get_queried_object());

$search = array();
if(isset($elem->term_id)){
  $the_cat = get_queried_object()->term_id;
  $act_all = "";
  $search["tax_".$elem->taxonomy]=array($the_cat);
  $the_cat_obj = get_term_by("id",$the_cat,$elem->taxonomy);
  if($the_cat_obj){
    $the_title = "Browse ".$the_cat_obj->name;
  }
}


?>

<section class="events_list">
  <div class="container">
    <div class="page_title d-flex align-items-center">
      <h1><?=$the_title; ?></h1>
    </div>
    <div class="top_events">
      <div class="row">
        <div class="col-md-9">
          <ul class="nav nav-pills  events_tabs" id="events" role="tablist">
            <li class="nav-item">
              <a class="nav-link <?=$act_all;?>" href="<?=get_post_type_archive_link("events");?>" id="allevents-tab" >All Events </a>
            </li>
            <?php
            $ev_cats = get_terms("event_cat","hide_empty=0");
            foreach($ev_cats as $ev_cat){
              if(isset($the_cat) && $the_cat==$ev_cat->term_id){
                $act = 'active';
              }else{
                $act = '';
              }
              ?>
              <li class="nav-item">
                <a class="nav-link <?=$act; ?>" href="<?=get_term_link($ev_cat); ?>" id="earnings-tab" ><?=$ev_cat->name;?></a>
              </li>
            <?php } ?>
          </ul>
        </div>
        <div class="col-md-3">
          <div class="organize_month">
            <div class="input-group ">
              <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">By Month</label>
              </div>
              <select class="select" id="inputGroupSelect01">
                <option selected>Choose...</option>
                <option value="01-2019">January 2019</option>

              </select>
            </div>
          </div>
        </div>
      </div>

    </div>
    <?php
    $results = apply_filters('tdf_get_posts',"events",10,0,array("search"=>$search));

     ?>
    <div class="row">
      <div class="col-lg-9">
        <div class="show_products d-flex justify-content-end">1-<?=$results["total_posts"]; ?> of <?=$results["total_posts"]; ?> result(s)</div>
        <div class="tab-content" id="eventsContent">
          <div class="tab-pane fade active show" id="allevents" role="tabpanel" aria-labelledby="allevents-tab">
            <?php
            echo $results["output"];
            ?>
          </div>

        </div>

      </div>
      <div class="col-lg-3">
        <div class="widget_sidebar">
          <?php dynamic_sidebar("events_page_sidebar"); ?>
        </div>

      </div>
    </div>
  </div>
</section>
