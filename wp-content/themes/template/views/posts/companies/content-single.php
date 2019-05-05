<?php
$comp_item = $item;
apply_filters('tdf_get_snap_price',$comp_item["post_id"]);
// if(!get_post_meta($comp_item["post_id"],"exchanges",true)){
//   apply_filters('tdf_get_snap_price',$comp_item["post_id"]);
// }

$the_add = $comp_item["meta_address"]." ".$comp_item["meta_address2"]." ".$comp_item["meta_city"]." ".$comp_item["meta_postcode"]." ".$comp_item["meta_state"]." ".$comp_item["meta_country"];

$exchange = apply_filters('tdf_get_company_exchanges',$comp_item["post_id"]);
$the_price = apply_filters('tdf_get_company_latest_price',$comp_item["post_id"],$exchange["code"]);
$quotes = apply_filters('tdf_get_company_quotes',$the_price["symbol"]);
$price_info = apply_filters('tdf_get_company_enh_quotes',$the_price["symbol"]);

for($f=0;$f<30;$f++){
  if(!$day_price && $quotes[date("Y-m-d",strtotime("-".$f." day"))]){
    $day_price = $quotes[date("Y-m-d",strtotime("-".$f." day"))];
  }
}

$filings = apply_filters('tdf_get_company_filings',$the_price["symbol"]);
$financials = apply_filters('tdf_get_company_financials',$the_price["symbol"]);

$quotes_chart = array();
$chart_labels = array();
foreach($quotes as $i=>$the_item){
  $chart_labels[]='"'.date("M j",strtotime($the_item["date"])).'"';
  $quotes_chart[]=$the_item["close"];
}
$volumes_chart = array();
foreach($quotes as $the_item){
  $volumes_chart[]=$the_item["sharevolume"];
}
$chart_labels = array_slice($chart_labels,0,15);
$quotes_chart = array_slice($quotes_chart,0,15);
$volumes_chart = array_slice($volumes_chart,0,15);

$chart_labels = array_reverse($chart_labels);
$quotes_chart = array_reverse($quotes_chart);
$volumes_chart = array_reverse($volumes_chart);

$search = array();
$search["tax_slug_post_tag"] = sanitize_title($the_price["symbol"]);
$news_args= array("search"=>$search);

$news = apply_filters('tdf_get_posts',"post",10,0,$news_args);

$form_types = array();
foreach($filings as $the_item){
  $form_types[]=$the_item["formtype"];
}
$form_types = array_unique($form_types);

if($the_price["change"]>0){
  $val_class= "green";
}else{
  $val_class= "red";
}
?>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?php echo get_bloginfo("template_directory"); ?>/assets/js/charts.js"></script>

<section class="company_page">
  <div class="container">
    <div class="row">
      <div class="col-md-9">
        <div class="page_title d-flex align-items-center">
          <h1><?=$comp_item["post_title"];?> (<?=$the_price["symbol"];?>)</h1>
        </div>
      </div>
      <div class="col-md-3 ">
        <div class="company_title_right text-md-right mb-4">
          <h2><?=number_format($the_price["last"],2); ?></h2>
          <h3 class="<?=$val_class; ?>"><?=number_format($the_price["change"],2); ?> (<?=round($the_price["changepercent"],3); ?>%)</h3>
          <h4>As of <?=date("D M j G:i:s T Y",strtotime($the_price["datetime"])); ?></h4>
        </div>
      </div>
    </div>
    <div class="mb-4 d-flex">
      <div class="organize_month">
        <div class="input-group ">
          <div class="input-group-prepend d-none d-md-block">
            <label class="input-group-text" for="inputGroupSelect01">exchange</label>
          </div>
          <div id="exchange_select" class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?=apply_filters('tdf_get_exchange_flag',$exchange["id"]); ?>
              <?=$exchange["object"]->name; ?>
            </button>
            <ul class="dropdown-menu">
              <?php foreach($exchange["exchanges"] as $ex){ ?>
                <li>
                  <a href="<?=$comp_item["post_permalink"].strtolower(get_term_meta($ex->term_id,"ex_code",true)); ?>" title="<?=$ex->name; ?>">
                    <?=apply_filters('tdf_get_exchange_flag',$ex->term_id); ?>
                    <?=$ex->name; ?>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
      <?=get_watchlist_button($the_price["symbol"],true);?>
    </div>
    <div class="top_company">
      <ul class="nav nav-pills company_tabs d-flex align-content-stretch justify-content-center" id="pills-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="pills-quote-tab" data-toggle="pill" href="#pills-quote" role="tab" aria-controls="pills-quote" aria-selected="true">QUOTE</a>
        </li>
        <?php if(count($quotes)){ ?>
          <li class="nav-item">
            <a class="nav-link" id="pills-thechart-tab" data-toggle="pill" href="#pills-thechart" role="tab" aria-controls="pills-thechart" aria-selected="false">CHARTING</a>
          </li>
        <?php } ?>
        <?php if(count($news["items"])){ ?>
          <li class="nav-item">
            <a class="nav-link" id="pills-newslist-tab" data-toggle="pill" href="#pills-newslist" role="tab" aria-controls="pills-newslist" aria-selected="false">NEWS</a>
          </li>
        <?php } ?>
        <?php if($comp_item["post_content"] || trim($the_add)){ ?>
          <li class="nav-item">
            <a class="nav-link" id="pills-company-tab" data-toggle="pill" href="#pills-company" role="tab" aria-controls="pills-company" aria-selected="false">COMPANY</a>
          </li>
        <?php } ?>
        <?php if(count($financials)){ ?>
          <li class="nav-item">
            <a class="nav-link" id="pills-financials-tab" data-toggle="pill" href="#pills-financials" role="tab" aria-controls="pills-financials" aria-selected="false">FINANCIALS</a>
          </li>
        <?php } ?>
        <?php if(count($quotes)){ ?>
          <li class="nav-item">
            <a class="nav-link" id="pills-history-tab" data-toggle="pill" href="#pills-history" role="tab" aria-controls="pills-history" aria-selected="false">PRICE HISTORY</a>
          </li>
        <?php } ?>
        <?php if(count($filings)){ ?>
          <li class="nav-item">
            <a class="nav-link" id="pills-sec-tab" data-toggle="pill" href="#pills-sec" role="tab" aria-controls="pills-sec" aria-selected="false">sec filings</a>
          </li>
        <?php } ?>
      </ul>
    </div>
    <div class="tab-content pb-6" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-quote" role="tabpanel" aria-labelledby="pills-quote-tab">
        <?php include(get_stylesheet_directory()."/views/pieces/company/quote.php"); ?>
      </div>
      <?php if(count($quotes)){ ?>
        <div class="tab-pane fade show active" id="pills-thechart" role="tabpanel" aria-labelledby="pills-thechart-tab">
          <?php include(get_stylesheet_directory()."/views/pieces/company/chart.php"); ?>
        </div>
      <?php } ?>
      <?php if(count($news["items"])){ ?>
        <div class="tab-pane fade" id="pills-newslist" role="tabpanel" aria-labelledby="pills-newslist-tab">
          <?php include(get_stylesheet_directory()."/views/pieces/company/news.php"); ?>
        </div>
      <?php } ?>
      <?php if($comp_item["post_content"] || trim($the_add)){ ?>
        <div class="tab-pane fade" id="pills-company" role="tabpanel" aria-labelledby="pills-company-tab">
          <?php include(get_stylesheet_directory()."/views/pieces/company/company.php"); ?>
        </div>
      <?php } ?>
      <?php if(count($financials)){ ?>
        <div class="tab-pane fade" id="pills-financials" role="tabpanel" aria-labelledby="pills-financials-tab">
          <?php include(get_stylesheet_directory()."/views/pieces/company/financials.php"); ?>
        </div>
      <?php } ?>
      <?php if(count($quotes)){ ?>
        <div class="tab-pane fade" id="pills-history" role="tabpanel" aria-labelledby="pills-history-tab">
          <?php include(get_stylesheet_directory()."/views/pieces/company/history.php"); ?>
        </div>
      <?php } ?>
      <?php if(count($filings)){ ?>
        <div class="tab-pane fade" id="pills-sec" role="tabpanel" aria-labelledby="pills-sec-tab">
          <?php include(get_stylesheet_directory()."/views/pieces/company/sec.php"); ?>
        </div>
      <?php } ?>
    </div>
  </div>
</section>
