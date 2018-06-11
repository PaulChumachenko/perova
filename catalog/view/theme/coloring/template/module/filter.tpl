<div class="panel panel-default module-filter">
  <div class="panel-heading"><i class="fa fa-filter"></i>&nbsp;&nbsp;<?php echo $heading_title; ?></div>
  <div class="list-group">
    <?php foreach ($filter_groups as $filter_group) { ?>
    <div class="list-group-item">
			<div class="list-group-item-heading" >
				<span class="filter-toggle pull-right" data-toggle="collapse" data-target="#filter-group<?php echo $filter_group['filter_group_id']; ?>">
					<i class="fa fa-toggle-on on"></i>
					<i class="fa fa-toggle-off off"></i>
				</span>
				<strong><?php echo $filter_group['name']; ?></strong>
			</div>
      <div class="list-group-item-text collapse in" id="filter-group<?php echo $filter_group['filter_group_id']; ?>">
        <?php foreach ($filter_group['filter'] as $filter) { ?>
        <?php if (in_array($filter['filter_id'], $filter_category)) { ?>
          <div class="filter-item">
						<input id="filt<?php echo $filter['filter_id']; ?>" name="filter[]" type="checkbox" value="<?php echo $filter['filter_id']; ?>" checked="checked" />
						<label for="filt<?php echo $filter['filter_id']; ?>">
							<i class="fa fa-check-square check-icon check"></i>
							<i class="fa fa-square-o check-icon uncheck"></i>
							<div class="filter-name"><?php echo $filter['name']; ?>&nbsp;<span class="filter-total"><?php echo $filter['total']; ?></span></div>
						</label>
					</div>
        <?php } else { ?>
          <div class="filter-item">
						<input id="filt<?php echo $filter['filter_id']; ?>" name="filter[]" type="checkbox" value="<?php echo $filter['filter_id']; ?>" />
						<label for="filt<?php echo $filter['filter_id']; ?>">
							<i class="fa fa-check-square check-icon check"></i>
							<i class="fa fa-square-o check-icon uncheck"></i>
							<div class="filter-name"><?php echo $filter['name']; ?>&nbsp;<span class="filter-total"><?php echo $filter['total']; ?></span></div>
						</label>
					</div>
        <?php } ?>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="panel-footer text-right">
		<button type="button" style="display: none;" id="button-clear" class="btn btn-default">Сбросить</button>
    <button type="button" id="button-filter" class="btn btn-primary"><?php echo $button_filter; ?></button>
  </div>
</div>
<script type="text/javascript">
$('#button-filter').on('click', function() {
	filter = [];
	
	$('input[name^=\'filter\']:checked').each(function(element) {
		filter.push(this.value);
	});
	
	location = '<?php echo $action; ?>&filter=' + filter.join(',');
});
if ($('input[name^=\'filter\']').is('input[name^=\'filter\']:checked')) {
	$('#button-clear').css({'display':'inline'});
}
$('#button-clear').on('click', function() {
	location = '<?php echo $action; ?>';
});
</script> 
