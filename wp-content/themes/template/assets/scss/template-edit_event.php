<?php
/*
Template Name: Add Event
*/

$eid = $_GET["event_id"];
$item = apply_filters("tdf_get_single", $eid);
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= get_option("google_api"); ?>&libraries=places"></script>

<link rel="stylesheet" href="<?php echo get_bloginfo("template_directory"); ?>/assets/css/jquery-ui.min.css">
<script src="<?php echo get_bloginfo("template_directory"); ?>/assets/js/jquery-ui.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>

<section class="search_events mb-4">
  <div class="container">
    <h1 class="text-left">Edit Event - <?= $item["post_title"]; ?></h1>
    <ul class="breadcrumbs">
      <li><a href="<?php echo get_bloginfo("url"); ?>">Home</a></li>
      <li><a href="<?php echo PAGE_DASHBOARD; ?>">My Account</a></li>
      <li><a href="<?php echo PAGE_BROWSE_EVENTS; ?>">Manage Events</a></li>
      <li><a href="<?php echo PAGE_EVENT_DASHBOARD . "?event_id=" . $eid; ?>">Event Dashboard</a></li>
      <li><a href="<?php echo PAGE_EDIT_EVENT . "?event_id=" . $eid; ?>">Edit Event</a></li>
    </ul>
  </div>
</section>
<form class="add_event_form" id="add_listing_form" action="<?php echo admin_url("admin-post.php"); ?>" enctype="multipart/form-data" method="POST">

  <div class="container pt-6">
    <div class="alert alert-danger" style="display:none;"></div>
    <div class="row">
      <div class="col-md-5">
        <div class="mb-4">
          <h3 class="section_title d-block w-75 ">Details</h3>


        </div>
      </div>
      <div class="col-md-7">
        <div class="form-group">
          <label for="exampleInputEmail1">Event title: <i>*</i></label>
          <?php echo apply_filters("tdf_get_update_post_field", "title", $eid, "", array("classes" => "form-control required")); ?>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Event URL: <i>*</i></label>
          <?php echo apply_filters("tdf_get_update_post_field", "meta_slug", $eid, "", array("classes" => "form-control required")); ?>
          <div id="event_url"><?= get_bloginfo("url"); ?>/events/<b>[enter here]</b></div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Event type: <i>*</i> </label>
              <?php echo apply_filters("tdf_get_update_post_field", "tax_events_type", $eid, "select", array("classes" => "required required_selectize")); ?>

            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Event category: <i>*</i> </label>
              <?php $field = apply_filters("tdf_get_update_post_field", "tax_events_cat", $eid, "select", array("classes" => "required_selectize required", "args" => 'multiple="multiple"'));
              echo str_replace('name="post_tax_events_cat"', 'name="post_tax_events_cat[]"', $field); ?>

            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">main event image: <i>*</i></label>
          <?php echo apply_filters("tdf_get_update_post_field", "image_gallery", $eid); ?>
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">event description: <i>*</i></label>
          <?php echo apply_filters("tdf_get_update_post_field", "content", $eid, "", array("classes" => "form-control required")); ?>
        </div>

      </div>
    </div>
    <hr class=" mb-4">
    <div class="row">
      <div class="col-md-5">
        <div class="mb-4">
          <h3 class="section_title d-block w-75 ">Location</h3>
        </div>
      </div>
      <div class="col-md-7">
        <?php echo apply_filters("tdf_get_update_post_field", "meta_location_type", $eid, "radio", array("classes" => "radio_set", "field_classes" => "required")); ?>
        <div id="map_div" style="display: none;">
          <div class="search_input d-flex align-items-center">
            <input id="post_meta_location_address" name="post_meta_location_address" value="<?php echo $item["meta_location_address"]; ?>" class="form-control required" type="text" placeholder="e.g. 10 Downing Street, London SW1A 2AA, England" />
            <button class="" id="apply_to_map">
              <i class="fas fa-search"></i>
            </button>
          </div>
          <div id="map_container" style="display: none;">
            <p class="fs80 color_gray4 pt-3 mb-1">Make sure your event is located correctly. If it's not then simply drag the pointer to the correct location.</p>
            <div style="width:100%; height:500px;" id="register-form__map" class="register-form__map register-form__map--user"></div>
            <input name="post_meta_location_string" type="hidden" value="<?= $item["meta_location_address"]; ?>" id="post_meta_location_string">

            <input name="post_meta_location_lat" type="hidden" value="<?= $item["meta_location_lat"]; ?>" class="register-form__latitude-holder">
            <input name="post_meta_location_long" type="hidden" value="<?= $item["meta_location_long"]; ?>" class="register-form__longitude-holder">
          </div>
        </div>
      </div>
    </div>
    <hr class="mt-4 mb-4">
    <div class="row">
      <div class="col-md-5">
        <div class="mb-4">
          <h3 class="section_title d-block w-75 ">Date and Time</h3>
        </div>
      </div>
      <div class="col-md-7">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">starts: <i>*</i> </label>
              <input type="text" value="<?= date("m/d/Y", strtotime($item["meta_start_date"])); ?>" id="start_date" class="form-control required" />
              <?php echo apply_filters("tdf_get_update_post_field", "meta_start_date", $eid, "hidden", array("classes" => "form-control required", "placeholder" => "MM/DD/YY")); ?>
            </div>

          </div>
          <div class="col-md-6 mt-4">
            <div class="form-group">
              <?php echo apply_filters("tdf_get_update_post_field", "meta_start_time", $eid, "select", array("classes" => "form-control required")); ?>

            </div>

          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Ends: <i>*</i></label>
              <input type="text" value="<?= date("m/d/Y", strtotime($item["meta_end_date"])); ?>" id="end_date" class="form-control required" />
              <?php echo apply_filters("tdf_get_update_post_field", "meta_end_date", $eid, "hidden", array("classes" => "form-control required", "placeholder" => "MM/DD/YY")); ?>
            </div>

          </div>
          <div class="col-md-6 mt-4">
            <div class="form-group">
              <?php echo apply_filters("tdf_get_update_post_field", "meta_end_time", $eid, "select", array("classes" => "form-control required")); ?>

            </div>

          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Timezone: <i>*</i></label>
              <?php $field = apply_filters("tdf_get_update_post_field", "meta_timezone", $eid, "select", array("classes" => "required_selectize required"));
              echo str_replace(" = ", " - ", $field); ?>

            </div>

          </div>
        </div>

      </div>
    </div>
    <hr class="mt-4 mb-6">
    <div class="text-center mb-8">
      <input type="hidden" name="action" value="tdf_update_post" />
      <input type="hidden" name="post_id" value="<?= $eid; ?>" />

      <input type="hidden" name="success_url" value="<?php echo PAGE_EDIT_EVENT; ?>?action=update_success" />
      <input type="hidden" name="error_url" value="<?php echo PAGE_EDIT_EVENT; ?>" />
      <input type="hidden" id="post_status" name="post_core_status" value="publish" />
      <input type="hidden" id="post_core_name" name="post_core_name" value="" />

      <button type="button" id="save_draft" class="btn large_btn white_btn mr-3">Save Draft & Preview</button>
      <button class="btn large_btn save_btn">Update Event Information ></button>
    </div>
  </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/tinymce.min.js"></script>
<script>
  jQuery(document).ready(function() {
    jQuery("#save_draft").click(function() {
      jQuery("#post_status").val("draft");
      jQuery("#add_listing_form").submit();
    });

    jQuery("#post_meta_slug").keyup(function() {
      jQuery("#event_url b").html(jQuery("#post_meta_slug").val());
    });


    jQuery("#post_meta_slug").change(function() {
      jQuery("#post_core_name").val(jQuery("#post_meta_slug").val());
    })

    jQuery("#post_meta_slug").change().keyup();

    jQuery("#add_listing_form").submit(function() {
      jQuery("#post_core_content").val(jQuery("#post_core_content_ifr").contents().find('body').html());
      jQuery("#post_meta_about_the_location").val(jQuery("#post_meta_about_the_location_ifr").contents().find('body').html());
      if (validate_form(jQuery(this))) {
        return false;
      }
    });
    tinymce.init({
      selector: '#post_core_content',
      height: 150,
      content_css: '<?php echo get_stylesheet_directory_uri(); ?>/add_theme.css',
      object_resizing: false,

      menubar: false,
      plugins: [
        ' lists hr paste link', // "image" is a valid option in here, but removing it for now to simplify things
      ],
      paste_as_text: true,
      toolbar: 'bold italic link | styleselect hr | bullist numlist', // "image" goes between styleselect and hr if we ever re-include the plugin
      paste_remove_spans: true,
      image_dimensions: false,
      style_formats: [{
          title: 'Large Header',
          block: 'h2'
        },
        {
          title: 'Small Header',
          block: 'h3'
        }
      ],
      valid_children: "+p[]",
      inline_styles: true,
      verify_html: true,
      relative_urls: false,
      remove_script_host: false,
      convert_urls: true,
      // and here's our custom image picker
    });
    tinymce.init({
      selector: '#post_meta_about_the_location',
      height: 150,
      content_css: '<?php echo get_stylesheet_directory_uri(); ?>/add_theme.css',
      object_resizing: false,

      menubar: false,
      plugins: [
        ' lists hr paste link', // "image" is a valid option in here, but removing it for now to simplify things
      ],
      paste_as_text: true,
      toolbar: 'bold italic link | styleselect hr | bullist numlist', // "image" goes between styleselect and hr if we ever re-include the plugin
      paste_remove_spans: true,
      image_dimensions: false,
      style_formats: [{
          title: 'Large Header',
          block: 'h2'
        },
        {
          title: 'Small Header',
          block: 'h3'
        }
      ],
      valid_children: "+p[]",
      inline_styles: true,
      verify_html: true,
      relative_urls: false,
      remove_script_host: false,
      convert_urls: true,
      // and here's our custom image picker
    });
  });
</script>


<script>
  var country_code;
  var lat = jQuery('.register-form__latitude-holder').val();
  var long = jQuery('.register-form__longitude-holder').val();


  jQuery(document).ready(function(e) {
    // init map
    var autocomplete;

    setInterval(function() {
      if (jQuery('input[name="post_meta_location_type"][value="venue"]').is(":checked")) {
        jQuery("#map_div").show();
      } else {
        jQuery("#map_div").hide();
      }
    }, 500);

    jQuery("#apply_to_map").click(function() {
      jQuery("#map_container").show();
      place_pin();
    })

    function initMap(lat, long) {
      jQuery("#location .progress_button_next").hide();

      var center = new google.maps.LatLng(parseFloat(lat), long);
      var mapOptions = {
        center: center,
        zoom: 16,
        scrollwheel: false
      };
      map = new google.maps.Map(document.getElementById("register-form__map"), mapOptions);
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat, long),
        draggable: true,
        map: map,
        title: 'Test'
      });
      if (lat) {
        jQuery("#location .progress_button_next").show();
      }
      google.maps.event.addListener(marker, 'dragend', function(event) {
        var lat = this.getPosition().lat();
        var long = this.getPosition().lng();

        var latlng = {
          lat: lat,
          lng: long
        };
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
          'location': latlng
        }, function(results, status) {
          if (status === 'OK') {
            if (results[0]) {
              jQuery("#post_meta_location_string").val(JSON.stringify(results[0]));
              jQuery("#location .progress_button_next").show();
              jQuery("#post_meta_location_address").val(results[0].formatted_address);
            }
          }
        });

        initMap(lat, long);
        jQuery('.register-form__latitude-holder').val(lat);
        jQuery('.register-form__longitude-holder').val(long);
      });

      var card = document.getElementById('pac-card');
      var input = document.getElementById('post_meta_location_address');
      var types = document.getElementById('type-selector');
      var strictBounds = document.getElementById('strict-bounds-selector');
      var options = {};

      autocomplete = new google.maps.places.Autocomplete(input, options);
      autocomplete.bindTo('bounds', map);

      autocomplete.addListener('place_changed', function() {
        jQuery("#location .progress_button_next").show();
        jQuery("#map_container").show();
        place_pin();
      });


    }
    /**
     * Geocode when user location input changes
     */
    function place_pin() {
      var address = jQuery("#post_meta_location_address").val();
      var geocoder = new google.maps.Geocoder();
      if (geocoder) {
        geocoder.geocode({
          'address': address
        }, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            jQuery("#post_meta_location_string").val(JSON.stringify(results[0]));
            jQuery("#location .progress_button_next").show();
            var lat = results[0].geometry.location.lat();
            var long = results[0].geometry.location.lng();

            initMap(lat, long);
            jQuery('.register-form__latitude-holder').val(lat);
            jQuery('.register-form__longitude-holder').val(long);
          }
        });
      }
    }

    jQuery.widget("custom.combobox", {
      _create: function() {
        this.wrapper = jQuery("<span>").addClass("custom-combobox").insertAfter(this.element);
        this.element.hide();
        this._createAutocomplete();
      },

      _createAutocomplete: function() {
        var selected = this.element.children(":selected"),
          value = selected.val() ? selected.text() : "";
        this.input = jQuery("<input>").appendTo(this.wrapper).val(value).attr("title", "").attr("autocomplete", "new-type").addClass("form-control").autocomplete({
          delay: 0,
          minLength: 0,
          source: jQuery.proxy(this, "_source")
        }).tooltip({
          classes: {
            "ui-tooltip": "ui-state-highlight"
          }
        });
        this._on(this.input, {
          autocompleteselect: function(event, ui) {
            ui.item.option.selected = true;

            this._trigger("select", event, {
              item: ui.item.option
            });
            country_code = jQuery("#countries option:selected").attr("data-code");
            console.log(country_code);
            initMap(lat, long);
          },
          autocompletechange: "_removeIfInvalid"
        });
      },

      _source: function(request, response) {
        var matcher = new RegExp(jQuery.ui.autocomplete.escapeRegex(request.term), "i");
        response(this.element.children("option").map(function() {
          var text = jQuery(this).text();
          if (this.value && (!request.term || matcher.test(text)))
            return {
              label: text,
              value: text,
              option: this
            };
        }));
      },

      _removeIfInvalid: function(event, ui) {

        // Selected an item, nothing to do
        if (ui.item) {
          return;
        }

        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children("option").each(function() {
          if (jQuery(this).text().toLowerCase() === valueLowerCase) {
            this.selected = valid = true;
            return false;
          }
        });

        // Found a match, nothing to do
        if (valid) {

          return;
        }

        // Remove invalid value
        this.input.val("");
        this.element.val("");
        this._delay(function() {
          this.input.tooltip("close").attr("title", "");
        }, 2500);
        this.input.autocomplete("instance").term = "";
      },

      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });

    jQuery("#countries").combobox();
    jQuery(".ui-autocomplete-input").addClass("required").attr("autocomplete", "stop-it").prop("autocomplete", "stop-it");

    initMap(lat, long);

    // jQuery("#countries").change(function () {
    //   country_code = jQuery("#countries option:selected").attr("data-code");
    //   jQuery("#post_meta_location_address").val("");
    //   jQuery("#map_container").hide();
    //   initMap(lat, long);
    // })

    if (jQuery("#post_meta_location_address").val()) {
      jQuery("#map_container").show();
    }

    jQuery('#post_tax_events_type').selectize({

    });

    jQuery('#post_tax_events_cat').selectize({

    });

    jQuery('#post_meta_timezone').selectize({

    });
    jQuery("#end_date").datepicker({
      mindate: 0,
      dateFormat: "mm/dd/yy",
      altField: "#post_meta_end_date",
      altFormat: "yymmdd"
    });

    jQuery("#start_date").datepicker({
      mindate: 0,
      dateFormat: "mm/dd/yy",
      altField: "#post_meta_start_date",
      altFormat: "yymmdd"
    }).change(function() {
      jQuery("#end_date").datepicker({
        minDate: jQuery("#start_date").val()
      });
    })


  });
</script>
<style>
  #location .progress_button_next {
    display: none;
  }
</style>