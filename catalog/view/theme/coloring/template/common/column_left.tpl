<?php if ($modules) { ?>
<div id="column-left" class="col-sm-4 col-md-3">
	<div class="visible-xs col-show-button">
		<a class="btn btn-default btn-block " id="show-modules-col-left"><i class="fa fa-eye show-icon"></i><i class="fa fa-eye-slash hid-icon"></i> <?php echo $button_text; ?></a>
	</div>
	<div id="col-left-modules" class="hid-col-left">
		<?php foreach ($modules as $module) { ?>
		<?php echo $module; ?>
		<?php } ?>
	</div>
</div>
<script>
	$('#show-modules-col-left').click(function () {
		$('#col-left-modules').toggleClass('show');
		$(this).toggleClass('open');
	});
</script>
<?php } ?>