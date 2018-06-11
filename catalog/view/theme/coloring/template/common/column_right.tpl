<?php if ($modules) { ?>
<div id="column-right" class="col-sm-4 col-md-3">
	<div class="visible-xs text-right col-show-button">
		<a class="btn btn-default btn-block" id="show-modules-col-right"><i class="fa fa-eye show-icon"></i><i class="fa fa-eye-slash hid-icon"></i> <?php echo $button_text; ?></a>
	</div>
	<div id="col-right-modules" class="hid-col-right">
		<?php foreach ($modules as $module) { ?>
		<?php echo $module; ?>
		<?php } ?>
	</div>
</div>
<script>
	$('#show-modules-col-right').click(function () {
		$('#col-right-modules').toggleClass('show');
		$(this).toggleClass('open');
	});
</script>
<?php } ?>