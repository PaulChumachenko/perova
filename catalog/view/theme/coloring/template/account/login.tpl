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
	<h1><?php echo $heading_title; ?></h1>
  <?php if ($success) { ?>
  <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-4 col-md-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-8 col-md-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row">
				<div class="col-sm-12 col-md-6">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
						<div class="panel panel-default">
							<div class="panel-heading"><h3 class="panel-title"><?php echo $text_returning_customer; ?></div>
							<div class="panel-body" id="login-body">
								<p><?php echo $text_i_am_returning_customer; ?></p>
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
										<input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										<input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
									</div>
								</div>
							</div>
							<div class="panel-footer">
								<div class="text-right">
									<a href="<?php echo $forgotten; ?>" class="btn btn-link"><?php echo $text_forgotten; ?></a>
									<a onclick="$(this).closest('form').submit()" class="btn btn-primary" /><i class="fa fa-key"></i> <?php echo $button_login; ?></a>
									<?php if ($redirect) { ?>
									<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
									<?php } ?>
								</div>
							</div>
						</div>
					</form>
        </div>
				<div class="col-sm-12 col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><?php echo $text_new_customer; ?></h3></div>
						<div class="panel-body" id="register-body">
							<p><?php echo $text_register; ?></p>
							<p><?php echo $text_register_account; ?></p>
						</div>
						<div class="panel-footer">
							<div class="text-right">
								<a href="<?php echo $register; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
							</div>
						</div>
					</div>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script>$(function(){$('#register-body').css({'min-height': $('#login-body').outerHeight()});});</script>
<?php echo $footer; ?>