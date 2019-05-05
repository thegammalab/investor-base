<div class="row">
  <div class="col-md-8">
    <div class="company_content_space pt-4 mb-5">
      <h2 class="mb-5">About <?=$comp_item["post_title"];?></h2>
      <?php if($comp_item["post_content"]){ ?>
        <div class="about_company">
          <?=$comp_item["post_content"];?>
        </div>
      <?php } ?>
      <?php if($comp_item["tax_string_company_cat"] || $comp_item["meta_cik"] || $comp_item["tax_string_company_industry"] || $comp_item["meta_sic"]){ ?>

      <hr />
      <h3>Company overview</h3>
      <table class="table table-striped table-borderless company_overview_table mt-3">
        <tbody>
          <?php if($comp_item["tax_string_company_cat"] || $comp_item["meta_cik"]){ ?>
            <tr>
              <?php if($comp_item["tax_string_company_cat"]){ ?>
                <td>Sector:</td>
                <td class="font-weight-bold dark_blue"><?=$comp_item["tax_string_company_cat"];?></td>
              <?php }else{
                echo '<td></td><td></td>';
              } ?>
              <?php if($comp_item["meta_cik"]){ ?>
                <td>CIK:</td>
                <td class="font-weight-bold dark_blue"><?=$comp_item["meta_cik"];?></td>
              <?php }else{
                echo '<td></td><td></td>';
              } ?>
            </tr>
          <?php } ?>
          <?php if($comp_item["tax_string_company_industry"] || $comp_item["meta_sic"]){ ?>
            <tr>
              <?php if($comp_item["tax_string_company_industry"]){ ?>
                <td>Industry:</td>
                <td class="font-weight-bold dark_blue"><?=$comp_item["tax_string_company_industry"];?></td>
              <?php }else{
                echo '<td></td><td></td>';
              } ?>
              <?php if($comp_item["meta_sic"]){ ?>
                <td>SIC:</td>
                <td class="font-weight-bold dark_blue"><?=$comp_item["meta_sic"];?></td>
              <?php }else{
                echo '<td></td><td></td>';
              } ?>
            </tr>
          <?php } ?>
          <?php if($comp_item["meta_naics"]){ ?>
            <tr>
              <td>NAICS:</td>
              <td class="font-weight-bold dark_blue"><?=$comp_item["meta_naics"];?></td>
              <td></td>
              <td></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
<?php } ?>
    </div>
    <?php if(trim($the_add)){ ?>
      <iframe class="mb-5" width="100%" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC1G26tDMYAD00ZO7kdopfFxlBHXd4X6Tk&q=<?=$the_add;?>" allowfullscreen></iframe>
    <?php } ?>
  </div>
  <div class="col-md-4">
    <div class="widget_sidebar pt-0">
      <div class="widget_contact  pt-4">
        <h4>Similar companies</h4>
        <table class="table table-striped table-borderless contacttable ">
          <tbody>
            <?php if($comp_item["meta_address"]){ ?>
              <tr>
                <td>Address
                <div class="font-weight-bold dark_blue"><?=$comp_item["meta_address"]." ".$comp_item["meta_address2"]." ".$comp_item["meta_city"]." ".$comp_item["meta_postcode"]." ".$comp_item["meta_state"]." ".$comp_item["meta_country"];?></div>
                </td>
              </tr>
            <?php } if($comp_item["meta_phone"]){ ?>
              <tr>
                <td>Telephone:
                <div class="font-weight-bold dark_blue"><?=$comp_item["meta_phone"];?></div>
                </td>
              </tr>
            <?php } if($comp_item["meta_web"]){ ?>
              <tr>
                <td>Website:
                <div class="font-weight-bold dark_blue"><?=$comp_item["meta_web"];?></div>
                </td>
              </tr>
            <?php } if($comp_item["meta_facisimile"]){ ?>
              <tr>
                <td>Facsimile:
                <div class="font-weight-bold dark_blue"><?=$comp_item["meta_facisimile"];?></div>
                </td>
              </tr>
            <?php } if($comp_item["meta_email"]){ ?>
              <tr>
                <td>Email:
                <div class="font-weight-bold dark_blue"><?=$comp_item["meta_email"];?></div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
