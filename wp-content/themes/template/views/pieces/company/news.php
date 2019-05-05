<div class="company_content_space pt-4">
  <h2 class="mb-5">News Articles for <?=$item["post_title"]; ?></h2>


  <div class="news_about_company">

    <div class="border_bottom">
      <div class="row"  id="news_results">
        <?php foreach($news["items"] as $item){ ?>
          <div class="col-sm-6">
            <?php include(get_stylesheet_directory()."/views/posts/post/content-item.php"); ?>
          </div>
          <?php
        }
        $item = $comp_item;
        ?>
      </div>

    </div>
    <script>
    jQuery(document).ready(function(){
      var the_page = <?=max(1,get_query_var("paged"));?>;
      var query_args = "<?=http_build_query($news_args);?>"
      var the_current_page = "<?=$item["post_permalink"]."/".$exchange["code"]; ?>/";

      jQuery("#load_more").click(function(){
        if(!jQuery("#load_more").prop("disabled")){
          the_page++;
          var th = jQuery(this);

          jQuery("#load_more").prop("disabled",true);
          jQuery.ajax({
            method: "GET",
            url: bloginfo_url+"/wp-admin/admin-ajax.php?action=get_ajax_company_news&"+query_args+"&page_no="+the_page,
          }).done(function(data) {
            if(!data){
              th.slideUp(500,function(){
                th.parent().find("span").slideDown();
                setTimeout(function(){
                  th.parent().slideUp();
                },3000);
              });
            }
            // window.history.pushState(the_current_page+'page/'+the_page, 'Title', the_current_page+'page/'+the_page);
            jQuery("#news_results").append(data);
            jQuery("#load_more").prop("disabled",false);
          });
        }
      });
    });
    </script>
    <div class="button_border" style="display:flex;">
      <span class="alert alert-warning m-auto" style="display:none;">No more results</span>
      <button type="button" id="load_more" class="btn btn-primary m-auto">GET MORE NEWS</button>
    </div>
  </div>
</div>
