<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
		<?php $breadcount = count($breadcrumbs) - 1; ?>
    <?php foreach ($breadcrumbs as $key => $breadcrumb) { ?>
		<?php if ($key != $breadcount) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } else {?>
		<li class="active"><?php echo $breadcrumb['text']; ?></li>
		<?php } ?>
    <?php } ?>
  </ul>
	<h1><?php echo $user_name; ?></h1>
  <?php if ($success) { ?>
  <div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i class="fa fa-check-circle"></i> <?php echo $success; ?>
	</div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-4 col-md-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-8 col-md-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
			<?php echo $content_top; ?>
			<div class="row">
				
				<?php if ($reward) { ?>

				<?php } ?>
				<div class="col-md-3 col-sm-6">
					<div class="well">
						<div class="media">
							<div class="pull-left"><i class="fa fa-cube card-icon"></i></div>
							<div class="media-body">
								<?php echo $text_user_order_total; ?>
								<h4 class="media-heading"><a href="<?php echo $order; ?>"><?php echo $user_order_total; ?></a></h4>
							</div>
						</div>
					</div>
				</div>

			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading"><h3 class="panel-title"><?php echo $text_my_account; ?></h3></div>
						<div class="list-group">
							<a class="list-group-item" href="<?php echo $edit; ?>"><i class="fa fa-edit list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_edit; ?></a>
							<a class="list-group-item" href="<?php echo $password; ?>"><i class="fa fa-unlock list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_password; ?></a>
							<a class="list-group-item" href="<?php echo $address; ?>"><i class="fa fa-building-o list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_address; ?></a>
							
							<a class="list-group-item" href="<?php echo $newsletter; ?>"><i class="fa fa-envelope-o list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_newsletter; ?></a>
							<a class="list-group-item" href="<?php echo $logout; ?>"><i class="fa fa-sign-out list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_logout; ?></a>
						</div>
						<div class="panel-footer"></div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading"><h3 class="panel-title"><?php echo $text_my_orders; ?></h3></div>
						<div class="list-group">
							<a class="list-group-item" href="<?php echo $order; ?>"><i class="fa fa-history list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_order; ?></a>
							
							<?php if ($reward) { ?>
							<a class="list-group-item" href="<?php echo $reward; ?>"><i class="fa fa-diamond list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_reward; ?></a>
							<?php } ?>
							<a class="list-group-item" href="<?php echo $return; ?>"><i class="fa fa-exchange list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_return; ?></a>
							
							<a class="list-group-item" href="<?php echo $recurring; ?>"><i class="fa fa-clock-o list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_recurring; ?></a>
						</div>
						<div class="panel-footer"></div>
					</div>
				</div>
			</div>
			<?php echo $content_bottom; ?>
		</div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>