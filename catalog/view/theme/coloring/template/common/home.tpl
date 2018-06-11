<?php echo $header; ?>
<div class="container">
  <div class="row">
		<div class="col-md-3"><div id="menu-home-helper"></div></div>
		<div class="col-md-9"><?php echo $content_top; ?></div>
	</div>
	<div class="row">
		<?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-4 col-md-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-8 col-md-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div class="<?php echo $class; ?>">
			<?php echo $content_bottom; ?>
		</div>
    <?php echo $column_right; ?>
	</div>
	<?php if ($new_modules_1 || $new_modules_2) { ?>
	<div class="row">
    <?php if ($new_modules_1) { ?>
    <?php $class1 = 'col-sm-8 col-md-9'; ?>
    <?php } else { ?>
    <?php $class1 = 'col-sm-12'; ?>
    <?php } ?>
		<?php if ($new_modules_1) { ?>
		<div class="col-sm-4 col-md-3" id="home_position_1">
			<?php foreach ($new_modules_1 as $new_module) { ?>
			<?php echo $new_module['module']; ?>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if ($new_modules_2) { ?>
		<div class="<?php echo $class1; ?>" id="home_position_2">
			<?php foreach ($new_modules_2 as $new_module) { ?>
			<?php echo $new_module['module']; ?>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	<?php if ($new_modules_3 || $new_modules_4) { ?>
	<div class="row">
		<?php if ($new_modules_4) { ?>
    <?php $class2 = 'col-sm-8 col-md-9'; ?>
    <?php } else { ?>
    <?php $class2 = 'col-sm-12'; ?>
    <?php } ?>
		<?php if ($new_modules_3) { ?>
		<div class="<?php echo $class2; ?>" id="home_position_3">
			<?php foreach ($new_modules_3 as $new_module) { ?>
			<?php echo $new_module['module']; ?>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if ($new_modules_4) { ?>
		<div class="col-sm-4 col-md-3" id="home_position_4">
			<?php foreach ($new_modules_4 as $new_module) { ?>
			<?php echo $new_module['module']; ?>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	<?php if ($new_modules_5) { ?>
	<div class="row">
		<div class="col-sm-12" id="home_position_5">
		<?php foreach ($new_modules_5 as $new_module) { ?>
		<?php echo $new_module['module']; ?>
		<?php } ?>
		</div>
	</div>
	<?php } ?>
</div>
<script type="text/javascript">$(function(){$('#menu-home-helper').css({'min-height': $('#menu-list').outerHeight() - 20});});</script>
<?php echo $footer; ?>