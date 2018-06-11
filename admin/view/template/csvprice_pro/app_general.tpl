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
						<form action="<?php echo $action; ?>" method="post" id="form_general" enctype="multipart/form-data" class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_csv_import_mod; ?></label>
								<div class="col-sm-7">
									<select name="csvprice_pro_csv_import_mod" class="form-control">
										<?php if ($csvprice_pro_csv_import_mod == 1) { ?>
										<option value="1" selected="selected"><?php echo $text_manual; ?></option>
										<option value="2"><?php echo $text_auto; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_manual; ?></option>
										<option value="2" selected="selected"><?php echo $text_auto; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="1"><?php echo $entry_image_download_mod; ?></label>
								<div class="col-sm-7">
									<select name="csvprice_pro_image_download_mod" class="form-control">
										<?php if ($csvprice_pro_image_download_mod == 2) { ?>
										<option value="1"><?php echo $text_auto; ?></option>
										<option value="2" selected="selected"><?php echo $text_mirror; ?></option>
										<?php } else { ?>
										<option value="1" selected="selected"><?php echo $text_auto; ?></option>
										<option value="2"><?php echo $text_mirror; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="2"><?php echo $entry_save_img_table; ?></label>
								<div class="col-sm-7">
									<select name="csvprice_pro_save_image_table" class="form-control">
										<?php if (isset($csvprice_pro_save_image_table) && $csvprice_pro_save_image_table == 0) { ?>
										<option value="1"> <?php echo $text_yes; ?> </option>
										<option value="0" selected="selected"> <?php echo $text_no; ?> </option>
										<?php } else { ?>
										<option value="1" selected="selected"> <?php echo $text_yes; ?> </option>
										<option value="0"> <?php echo $text_no; ?> </option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="3"><?php echo $entry_work_directory; ?></label>
								<div class="col-sm-7">
									<input name="csvprice_pro_work_directory"  class="form-control" value="<?php echo $csvprice_pro_work_directory; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label">&nbsp;</label>
								<div class="col-sm-7">
									<div class="pull-right">
										<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_general').submit();"><?php echo $button_save; ?></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

    </div>
</div>
<script type="text/javascript">
	var prop_descr = new Array();
    <?php if(isset($prop_descr)) echo $prop_descr; ?>
</script>
<?php echo $app_footer; ?>
<?php echo $footer; ?>
