function get_companies(order,page,the_current_page){
  if(!order){
    order = 'title_asc';
  }
  if(!page){
    page=1;
  }

  jQuery("#allmarkets_wrapper #allmarkets_load").css("opacity",1);
  jQuery("#allmarkets_wrapper #allmarkets").css("opacity",0);

  jQuery.ajax({
    method: "GET",
    url: bloginfo_url+"/wp-admin/admin-ajax.php?action=get_company_list&"+query_args+"&order="+order+"&page_no="+page,
  }).done(function(data) {
    window.history.pushState(the_current_page+'page/'+page, 'Title', the_current_page+'page/'+page);

    jQuery("#market_list tbody").html("");
    for(i=0;i<data.results.length;i++){
      var row = '<tr>';
      for(j=0;j<data.results[i].length;j++){
        row+='<td>'+data.results[i][j]+'</td>';
      }
      row+='</tr>';
      jQuery("#market_list tbody").append(row);
    }

    jQuery("#allmarkets_counts").html(data.counts);
    jQuery("#allmarkets_pagination").html(data.pagination);

    jQuery("#allmarkets_wrapper #allmarkets_load").css("opacity",0);
    jQuery("#allmarkets_wrapper #allmarkets").css("opacity",1);

    jQuery("#allmarkets_pagination a").click(function(e){
      e.preventDefault();
      comp_page = parseInt(jQuery(this).attr("href").substr(1));
      get_companies(comp_order,comp_page,the_current_page);

      jQuery([document.documentElement, document.body]).animate({
        scrollTop: jQuery("#allmarkets_wrapper").offset().top
      }, 500);


      return false;
    });

    apply_tooltips();
    apply_watchlist();

  });
}

function companies_sort(){
  jQuery("#market_list th").click(function(){
    if(jQuery(this).is(".sorting_asc")){
      var dir = 'desc';
      jQuery(this).removeClass("sorting_asc").addClass("sorting_desc");
    }else if(jQuery(this).is(".sorting_desc")){
      var dir = 'asc';
      jQuery(this).removeClass("sorting_desc").addClass("sorting_asc");
    }else{
      jQuery("#market_list th").removeClass("sorting_asc").removeClass("sorting_desc");
      var dir = 'asc';
      jQuery(this).addClass("sorting_asc");
    }
    comp_order = (jQuery(this).attr("data-order").replace("DIR", dir));
    get_companies(comp_order,comp_page,the_current_page);
  });
}





jQuery(document).ready( function () {
  jQuery('.events_tabs').slick({
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 1,
    variableWidth: true,
    focusOnSelect: true
  });

  setTimeout(function(){
    jQuery("#events .nav-link.active").parent().parent().click();
  },500);

});
