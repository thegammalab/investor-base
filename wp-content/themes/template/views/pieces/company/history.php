<div class="company_content_space pt-4">
<h2 class="mb-5">Price History for <?=$item["post_title"];?></h2>
<div class="row align-items-center d-none">
  <div class="col-md-4">
    <div class="input-group top_selects_history_page">
      <input type="text" class="form-control" placeholder="Enter date">
      <div class="input-group-append">
        <button class="btn btn-secondary" type="button">get price snapshot</button>
      </div>
    </div>
  </div>
  <div class="col-md-8 d-flex justify-content-end">
    <div class="show_products mb-0 d-flex justify-content-end">1-25 of <?=count($quotes);?> results</div>
  </div>
</div>
<script>
jQuery(document).ready(function() {
    jQuery('#history_table').DataTable();
});
</script>

<table id="history_table" class="table table-striped table-borderless sec_table mt-3">
  <thead>
    <tr>
      <th scope="col" class="">Date</th>
      <th scope="col" class="">Open</th>
      <th scope="col" class="">High</th>
      <th scope="col" class="">Low</th>
      <th scope="col" class="">Close</th>
      <th scope="col" class="">Volume</th>
      <th scope="col" class="">Chg</th>
      <th scope="col" class="">% Chg</th>
      <th scope="col" class="">Trade Val</th>
      <th scope="col" class=""># Trades</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($quotes as $item){ ?>
      <tr>
        <td><?=$item["date"];?></td>
        <td><?=$item["open"];?></td>
        <td><?=$item["high"];?></td>
        <td><?=$item["low"];?></td>
        <td><?=$item["close"];?></td>
        <td><?=$item["sharevolume"];?></td>
        <td><?=$item["change"];?></td>
        <td><?=$item["changepercent"];?></td>
        <td><?=$item["totalvalue"];?></td>
        <td><?=$item["totaltrades"];?></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
</div>
