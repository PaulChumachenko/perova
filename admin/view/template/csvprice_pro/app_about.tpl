<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
		<div class="container-fluid">
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
    </div>
    <div class="container-fluid csvprice_pro_container">
		<?php if (isset($warning) && !empty($warning)) { ?>
			<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
        <?php if (isset($success) && !empty($success)) { ?>
			<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
        <?php } ?>
		<?php echo $app_header; ?>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-6">
						<form action="<?php echo $action; ?>" method="post" id="form-license" enctype="multipart/form-data" class="form-horizontal">
							<div class="form-group">
								<?php if ($entry_license_key != false) { ?>
									<label for="license_key" class="col-sm-5 control-label"><?php echo $entry_license_key; ?></label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" class="form-control" name="license_key" id="license_key" placeholder="License Key">
											<span class="input-group-btn"> <button type="submit" form="form-license" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_save; ?>"><i class="fa fa-save"></i></button> </span>
										</div>
									</div>
								<?php } else { ?>
									<label for="license_key" class="col-sm-5 control-label"><?php echo $text_license_key; ?></label>
									<div class="col-sm-7">
										<p class="form-control-static"><a onclick="prompt('<?php echo $text_license_key; ?>', '<?php echo $license_key; ?>'); return false;"><?php echo $text_show; ?></a></p>
									</div>
								<?php } ?>
							</div>
							<div class="form-group">
								<label for="license_key" class="col-sm-5 control-label"><?php echo $text_app_name; ?></label>
								<div class="col-sm-7">
									<p class="form-control-static"><?php echo $app_name; ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="license_key" class="col-sm-5 control-label"><?php echo $text_app_version; ?></label>
								<div class="col-sm-7">
									<p class="form-control-static"><?php echo $app_version; ?></p>
								</div>
							</div>
							<div class="form-group">
								<label for="license_key" class="col-sm-5 control-label"><?php echo $text_home_page; ?></label>
								<div class="col-sm-7">
									<p class="form-control-static"><a href="<?php echo $home_page; ?>" target="_blank"><?php echo str_replace('http://', '', $home_page); ?></a></p>
								</div>
							</div>
							<div class="form-group">
								<label for="license_key" class="col-sm-5 control-label"><?php echo $text_support_email; ?></label>
								<div class="col-sm-7">
									<p class="form-control-static"><a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a></p>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

    </div>
</div>
<?php echo $app_footer; ?>
<?php echo $footer; ?>
