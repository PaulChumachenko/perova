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
		<?php if ($Template == 'form') { ?>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-6">
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
						
						<input type="hidden" name="job_id" value="<?php if (isset($job_id)) { ?><?php echo $job_id; ?><?php } else { ?>0<?php } ?>" />
						<input type="hidden" name="job_key" value="<?php if(isset($job_key)) echo $job_key; else echo date('U'); ?>" />
						
						<div class="form-group">
							<label class="col-sm-5 control-label"><?php echo $entry_status; ?></label>
							<div class="col-sm-7">
								<select class="form-control" name="status">
									<?php if ($job['status'] == 1) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"><?php echo $entry_job_type; ?></label>
							<div class="col-sm-7">
								<select id="job_type" name="job_type" class="form-control">
									<?php if ($job['job_type'] == 'import') { ?>
									<option value="import" selected="selected"><?php echo $text_job_type_import; ?></option>
									<option value="export"><?php echo $text_job_type_export; ?></option>
									<?php } else { ?>
									<option value="import"><?php echo $text_job_type_import; ?></option>
									<option value="export" selected="selected"><?php echo $text_job_type_export; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"><?php echo $entry_profile; ?></label>
							<div class="col-sm-7">
								<select id="profile_id" name="profile_id" class="form-control">
									<?php if ($job['job_type'] == 'import') { ?>
									<?php $options = $profile_import; ?>
									<?php } else { ?>
									<?php $options = $profile_export; ?>
									<?php } ?>
									<?php foreach ($options as $profile) { ?>
									<option value="<?php echo $profile['profile_id']; ?>"<?php if (isset($job['profile_id']) && $job['profile_id'] == $profile['profile_id']) { ?> selected="selected"<?php } ?>><?php echo $profile['name']; ?></option>	
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"><?php echo $entry_job_offline; ?></label>
							<div class="col-sm-7">
								<select class="form-control" name="job_offline">
									<?php if ($job['job_offline']) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"><?php echo $entry_time_start; ?></label>
							<div class="col-sm-7">
								<select id="job_time_start_h" name="job_time_start[H]" class="form-control" style="max-width:120px">
									<?php foreach ($datetime['H'] as $h) { ?>
									<?php if (isset($job['job_time_start']['H']) && $job['job_time_start']['H']  == $h) { ?>	
									<option value="<?php echo $h; ?>" selected="selected"><?php echo $h; ?></option>
									<?php } else { ?>
									<option value="<?php echo $h; ?>"><?php echo $h; ?></option>
									<?php } ?>
									<?php } ?>
								</select>
								<select id="job_time_start_i" name="job_time_start[i]" class="form-control" style="max-width:120px; margin-top:6px;">
									<?php foreach ($datetime['i'] as $i) { ?>
									<?php if (isset($job['job_time_start']['i']) && $job['job_time_start']['i']  == $i) { ?>	
									<option value="<?php echo $i; ?>" selected="selected"><?php echo $i; ?></option>
									<?php } else { ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
									<?php } ?>
								<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"><?php echo $entry_file_location; ?></label>
							<div class="col-sm-7">
								<select id="job_file_location" class="form-control" name="job_file_location">
									<?php if (isset($job['job_file_location']) && $job['job_file_location']  == 'web') { ?>
									<option value="web" selected="selected">Web</option>
									<option value="ftp">FTP</option>
									<option value="dir">Directory</option>
									<?php } elseif (isset($job['job_file_location']) && $job['job_file_location']  == 'ftp') { ?>
									<option value="web">Web</option>
									<option value="ftp" selected="selected">FTP</option>
									<option value="dir">Directory</option>
									<?php } else { ?>
									<option value="web">Web</option>
									<option value="ftp">FTP</option>
									<option value="dir" selected="selected">Directory</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group csvprice_pro_ftp_data">
							<label class="col-sm-5 control-label"><?php echo $entry_ftp_host; ?></label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="ftp_host" value="<?php if(isset($job['ftp_host'])) echo $job['ftp_host']; ?>" />
							</div>
						</div>
						<div class="form-group csvprice_pro_ftp_data">
							<label class="col-sm-5 control-label"><?php echo $entry_ftp_user; ?></label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="ftp_user" value="<?php if(isset($job['ftp_user'])) echo $job['ftp_user']; ?>" />
							</div>
						</div>
						<div class="form-group csvprice_pro_ftp_data">
							<label class="col-sm-5 control-label"><?php echo $entry_ftp_passwd; ?></label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="ftp_passwd" value="<?php if(isset($job['ftp_passwd'])) echo $job['ftp_passwd']; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"><?php echo $entry_file_path; ?></label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="file_path" value="<?php if(isset($job['file_path'])) echo $job['file_path']; ?>" />
							</div>
						</div>
					</form>
					<div class="row">
						<div class="form-group">
							<div class="col-sm-12">
								<div class="pull-right">
									<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form').submit()"><?php echo $button_save; ?></button>
									<button type="button" class="btn btn-default" style="min-width:120px" onclick="location.replace('<?php echo $cancel; ?>')"><?php echo $button_cancel; ?></button>
								</div>
							</div>
						</div>
					</div>
					<script type="text/javascript">
						jQuery(document).ready(function ($) {
							var s=0,i='',e='',
							profile_export='<?php foreach ($profile_export as $profile) { ?><option value="<?php echo $profile['profile_id']; ?>"><?php echo  $profile['name']; ?> </option><?php } ?>',
							profile_import='<?php foreach ($profile_import as $profile) { ?><option value="<?php echo $profile['profile_id']; ?>"><?php echo $profile['name']; ?> </option><?php } ?>';
							$("#job_type").change(function(){
								if( $("#job_type").val()=='import' ) {
									$("#profile_id").html(profile_import);
								} else {
									$("#profile_id").html(profile_export);
								}
							});
							f_file_location();
							$("#job_file_location").change(f_file_location);
						});
						function f_file_location(){
							if( $("#job_file_location").val()=='ftp' ) {
								$(".csvprice_pro_ftp_data").show();
							} else {
								$(".csvprice_pro_ftp_data").hide();
							}
						}
					</script>
					</div>
				</div>
			</div>
		</div>
		<?php } else { ?>
		<div class="panel panel-default">
			<div class="panel-body">
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
					<div class="text-right">
						<button type="button" data-toggle="tooltip" title="<?php echo $button_add; ?>" onclick="location.replace('<?php echo $insert; ?>')" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
						<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger btn-sm" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form').submit() : false;"><i class="fa fa-trash-o"></i></button>
					</div>
					<table class="table table-bordered table-hover" style="margin-top:10px;">
						<thead>
							<tr>
								<th width="1" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
								<th class="text-left"><?php echo $column_profile_name; ?></th>
								<th class="text-left"><?php echo $column_job_type; ?></th>
								<th class="text-center"><?php echo $column_job_time_start; ?></th>
								<th class="text-left"><?php echo $column_job_file_location; ?></th>
								<th class="text-left"><?php echo $column_status; ?></th>
								<th class="text-center"><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody>
							<?php if (isset($jobs) && !empty($jobs)) { ?>
							<?php foreach ($jobs as $job) { ?>
							<?php $action = $job['action']; ?>
							<tr id="row_job_<?php echo $job['job_id']; ?>">
								<td class="text-center">
									<?php if (isset($job['selected']) && $job['selected'] == 1) { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $job['job_id']; ?>" checked="checked" />
									<?php } else { ?>
									<input type="checkbox" name="selected[]" value="<?php echo $job['job_id']; ?>" />
									<?php } ?>
								</td>
								<td class="text-left"><?php echo $job['profile_name']; ?></td>
								<td class="text-left"><?php echo $job['job_type']; ?></td>
								<td class="text-center"><?php echo $job['job_time_start']; ?></td>
								<td class="text-left"><?php echo $job['job_file_location']; ?></td>
								<td class="text-left" style="color:<?php echo $job['color_status']; ?>"><?php echo $job['status']; ?></td>
								<td class="text-center">
									<a onclick="$('#job-view_<?php echo $job['job_id']; ?>').toggle()" data-toggle="tooltip" title="" class="btn btn-info btn-sm" data-original-title="<?php echo $button_view; ?>"><i class="fa fa-eye"></i></a>
									<a href="<?php echo $action['edit']['href']; ?>" data-toggle="tooltip" title="" class="btn btn-primary btn-sm" data-original-title="<?php echo $button_edit; ?>"><i class="fa fa-pencil"></i></a>
								</td>
							</tr>
							<tr id="job-view_<?php echo $job['job_id']; ?>" style="display: none">
								<td class="text-left" colspan="7">
									<?php if (isset($job['cron_cli']) && !empty($job['cron_cli'])) { ?>
									<p><b>cli cron job command:</b><br /><?php echo $job['cron_cli']; ?></p>
									<?php } ?>
									<?php if (isset($job['cron_wget']) && !empty($job['cron_wget'])) { ?>
									<p><b>wget cron job command:</b><br /><?php echo $job['cron_wget']; ?></p>
									<?php } ?>
									<?php if (isset($job['cron_curl']) && !empty($job['cron_curl'])) { ?>
									<p><b>curl cron job command:</b><br /><?php echo $job['cron_curl']; ?></p>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</form>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php echo $app_footer; ?>
<?php echo $footer; ?>