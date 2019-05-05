<?php

/* ==============================================================
REGISTER POST TYPES
============================================================== */

$post_types = array(
  array("events", array("name" => "Events", "singular_name" => "Event", "slug" => "events")),
  array("analysis", array("name" => "Analysis", "singular_name" => "Analysis", "slug" => "analysis")),
  array("companies", array("name" => "Companies", "singular_name" => "Company", "slug" => "company")),
);


/* ==============================================================
REGISTER TAXONOMIES
============================================================== */

$taxonomies = array(
  array("event_cat", array("events"), array("name" => "Categories", "singular_name" => "Category", "slug" => "event_cat")),
  array("analysis_cat", array("analysis"), array("name" => "Categories", "singular_name" => "Category", "slug" => "analysis_cat")),

  array("company_cat", array("companies"), array("name" => "Sectors", "singular_name" => "Sector", "slug" => "sector")),
  array("company_industry", array("companies"), array("name" => "Industries", "singular_name" => "Industry", "slug" => "industry")),
  array("company_sics", array("companies"), array("name" => "SICs", "singular_name" => "SIC", "slug" => "sic")),
  array("exchanges", array("companies"), array("name" => "Exchanges", "singular_name" => "Exchange", "slug" => "exchange")),

);



/* ==============================================================
REGISTER MENUS
============================================================== */

$menus = array(
  array("main_menu_header"),
  array("account_menu"),

  array("footer_menu1"),
  array("footer_menu2"),
  array("footer_menu3"),

  array("privacy_menu"),
);

/* ==============================================================
REGISTER SIDEBAR
============================================================== */

$sidebars = array(
  array("homepage_sidebar"),

  array("article_sidebar"),
  array("article_page_sidebar"),

  array("events_sidebar"),
  array("events_page_sidebar"),

  array("account_sidebar"),

  array("analysis_sidebar"),
  array("analysis_page_sidebar"),

  array("company_sidebar"),
  array("company_page_sidebar"),
);

/* ==============================================================
REGISTER IMAGE SIZES
============================================================== */

$image_sizes = array(

);

/* ==============================================================
REGISTER POST FIELDS
============================================================== */

$post_fields = array(
  /*EVENT INFO*/
  "event_settings" => array(
    "name"=>"Date Information",
    "post_types" => array("events"),

    "fields"=>array(
      "date"=>array(
        "name"=>  "Event Date",
        "type" => "date_picker",
      ),

    )
  ),
  /*COMPANY INFO*/
  "listing_settings" => array(
    "name"=>"Company Data",
    "post_types" => array("companies"),

    "fields"=>array(
      "pricing_test"=>array(
        'key'   => 'pricing_test',
        'id'   => 'pricing_test',
        "name"=>  "pricing_test",
        "label"=>  "Pricing",

        "type" => "repeater",
        'sub_fields'   => array(
          "date"=>array(
            'key'   => 'comp_price_date',
            'label' => 'DATE',
            'type'  => 'date_picker'
          ),
          "open"=>array(
            'key'   => 'comp_price_open',
            'label' => 'OPEN',
          ),
          array(
            'key'   => 'comp_price_low',
            'label' => 'LOW',
          ),
          array(
            'key'   => 'comp_price_high',
            'label' => 'HIGH',
          ),
          array(
            'key'   => 'comp_price_close',
            'label' => 'CLOSE',
          ),
          array(
            'key'   => 'comp_price_change',
            'label' => 'CHG',
          ),
          array(
            'key'   => 'comp_price_changepercent',
            'label' => '% CHG',
          ),
          array(
            'key'   => 'comp_price_vwap',
            'label' => 'VWAP',
          ),
          array(
            'key'   => 'comp_price_totaltrades',
            'label' => '# TRADES',
          ),
          array(
            'key'   => 'comp_price_avolume',
            'label' => 'ADJ. VOLUME',
          ),
          array(
            'key'   => 'comp_price_totalvalue',
            'label' => 'TRADE VAL',
          ),
          array(
            'key'   => 'comp_price_sharevolume',
            'label' => 'VOLUME',
          ),
        ),
        'button_label' => 'Add Pricing item',
      ),
      "sec_filings"=>array(
        "name"=>  "SEC Filings",
        "type" => "repeater",
        'sub_fields'   => array(
          array(
            'key'   => 'comp_sec_form_type',
            'label' => 'FORM TYPE',
          ),
          array(
            'key'   => 'comp_sec_form_description',
            'label' => 'FORM DESCRIPTION',
          ),
          array(
            'key'   => 'comp_sec_pages',
            'label' => 'PAGES',
            'type'  => 'number'
          ),
          array(
            'key'   => 'comp_sec_date_view',
            'label' => 'DATE VIEW',
            'type'  => 'date_picker'
          ),
          array(
            'key'   => 'comp_sec_xblr',
            'label' => 'XBLR',
            'type'  => 'file'
          ),
          array(
            'key'   => 'comp_sec_www',
            'label' => 'WWW',
            'type'  => 'url'
          ),
          array(
            'key'   => 'comp_sec_doc',
            'label' => 'DOC',
            'type'  => 'file'
          ),
          array(
            'key'   => 'comp_sec_pdf',
            'label' => 'PDF',
            'type'  => 'file'
          ),
          array(
            'key'   => 'comp_sec_xls',
            'label' => 'XLS',
            'type'  => 'file'
          ),
        ),
        'button_label' => 'Add SEC filing',
      ),
    )
  ),
  "basic_info" => array(
    "name"=>"Basic Info",
    "post_types" => array("companies"),

    "fields"=>array(
      "open"=>array(
        'label' => 'Open',
      ),
      "prev_close"=>array(
        'label' => 'Prev Close',
      ),
      "dividend"=>array(
        'label' => 'Dividend',
      ),
      "yield"=>array(
        'label' => 'Yield',
      ),
      "high"=>array(
        'label' => 'High',
      ),
      "low"=>array(
        'label' => 'Low',
      ),
      "div_freq"=>array(
        'label' => 'Div Frequency',
        'type' => 'select',
        'values' => array('monthly'=>"Monthly",'quarterly'=>"Quarterly",'yearly'=>"Yearly",)
      ),
      "ex_div_date"=>array(
        'label' => 'Ex-div date',
        'type' => 'date_picker'
      ),
      "bid"=>array(
        'label' => 'Bid',
      ),
      "ask"=>array(
        'label' => 'Ask',
      ),
      "shares_out"=>array(
        'label' => 'Shares Out',
      ),
      "market_cap"=>array(
        'label' => 'Market Cap',
      ),
    ),
  ),
  "company_overview" => array(
    "name"=>"Company Overview",
    "post_types" => array("companies"),

    "fields"=>array(
      "cik"=>array(
        'label' => 'CIK',
      ),
      "sic"=>array(
        'label' => 'SIC',
      ),
      "naics"=>array(
        'label' => 'NAICS',
      ),
      "address"=>array(
        'label' => 'Address',
      ),
      "address2"=>array(
        'label' => 'Address 2',
      ),
      "state"=>array(
        'label' => 'State',
      ),
      "postcode"=>array(
        'label' => 'Postcode',
      ),
      "country"=>array(
        'label' => 'Country',
      ),
      "phone"=>array(
        'label' => 'Phone',
      ),
      "web"=>array(
        'label' => 'Website',
      ),
      "facisimile"=>array(
        'label' => 'Facisimile',
      ),
      "email"=>array(
        'label' => 'Email',
      ),
    )
  ),
);

/* ==============================================================
REGISTER USER FIELDS
============================================================== */

$user_fields = array(

);


/* ==============================================================
REGISTER MISC VARIABLES
============================================================== */

$variables = array(
);

/* ==============================================================
REGISTER THEME VARIABLES
============================================================== */

$theme_variables = array(

);

/* ==============================================================
REGISTER EMAILS
============================================================== */

$email_variables = array(
  array("register",array("has_admin"=>true,"variables"=>array("user_login","confirm_link"))),
  array("forgot",array("has_admin"=>false,"variables"=>array("user_login","reset_link"))),
  array("resend_confirm",array("has_admin"=>false,"variables"=>array("user_login","confirm_link"))),
  array("send_invite",array("has_admin"=>false,"variables"=>array("user_login"))),
  array("alert_notif",array("has_admin"=>false,"variables"=>array("ticker","type","value"))),

  // array("test_email",array("has_admin"=>true,"variables"=>array("test"),"admin_variables"=>array("admin_test"),"custom_function"=>"test","custom_admin_function"=>"test_admin")),
);
