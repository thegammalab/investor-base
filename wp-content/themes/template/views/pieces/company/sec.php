<script>
jQuery(document).ready(function() {
    jQuery('#filings_table').DataTable();
});
</script>
<div class="company_content_space pt-4">
  <h2 class="mb-5">SEC Filings for <?=$comp_item["post_title"];?></h2>
<!-- <select class="select mb-1" id="sec_filling">
  <option selected>all sec filling Types</option>
  <?php foreach($form_types as $form_type){ ?>
    <option value="<?=$form_type; ?>"><?=$form_type; ?></option>
  <?php } ?>
</select> -->
<table id="filings_table" class="table table-striped table-borderless sec_table mt-3">
  <thead>
    <tr>
      <th scope="col" width="120">Form Type	</th>
      <th scope="col">Form Description</th>
      <th scope="col" width="60" style="text-align: center;">Pages</th>
      <th scope="col" width="100" style="width: 180px; text-align: center;">Issue Date</th>
      <th scope="col" width="250" style="width: 290px; text-align: right;"> </th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($filings as $item){ ?>
      <tr>
        <td><?=$item["formtype"]; ?></td>
        <td><?=$item["formdescription"]; ?></td>
        <td style="text-align: center;"><?=$item["pages"]; ?></td>
        <td style="text-align: center;"><?=$item["datefiled"]; ?></td>
        <td style="width: 280px; text-align: right;">
          <ul class="file_format">
            <?php if($item["xbrllink"]){ ?>
              <li class="purple"><a href="<?=$item["xbrllink"]; ?>" target="_blank">xblr</a></li>
            <?php } ?>
            <?php if($item["htmllink"]){ ?>
              <li class="blue"><a href="<?=$item["htmllink"]; ?>" target="_blank">www</a></li>
            <?php } ?>
            <?php if($item["doclink"]){ ?>
              <li class="dark_blue"><a href="<?=$item["doclink"]; ?>" target="_blank">doc</a></li>
            <?php } ?>
            <?php if($item["pdflink"]){ ?>
              <li class="red"><a href="<?=$item["pdflink"]; ?>" target="_blank">pdf</a></li>
            <?php } ?>
            <?php if($item["xlslink"]){ ?>
              <li class="green"><a href="<?=$item["xlslink"]; ?>" target="_blank">xls</a></li>
            <?php } ?>
          </ul>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
</div>
