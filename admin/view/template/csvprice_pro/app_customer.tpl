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
				<form action="<?php echo $action_export; ?>" class="form-horizontal" method="post" id="form_customer_export" enctype="multipart/form-data">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_file_format; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[file_format]" class="form-control">
										<?php if($csv_export['file_format'] == 'csv') { ?>
										<option value="csv" selected="selected">&nbsp;CSV&nbsp;</option>
										<option value="vcf">&nbsp;vCard&nbsp;</option>
										<?php } else { ?>
										<option value="csv">&nbsp;CSV&nbsp;</option>
										<option value="vcf" selected="selected">&nbsp;vCard&nbsp;</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_file_encoding; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[file_encoding]" class="form-control">
										<?php foreach ($charsets as $key => $item ) { ?>
											<?php if ($csv_export['file_encoding'] == $key) { ?>
												<option value="<?php echo $key; ?>" selected="selected"><?php echo $item; ?></option>
											<?php } else { ?>
												<option value="<?php echo $key; ?>"><?php echo $item; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_csv_delimiter; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[csv_delimiter]" class="form-control">
										<option value=";"<?php if ($csv_export['csv_delimiter'] == ';'){echo ' selected="selected"';} ?>> ; </option>
										<option value=","<?php if ($csv_export['csv_delimiter'] == ','){echo ' selected="selected"';} ?>> , </option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_customer_group; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[customer_group_id]" class="form-control">
										<option value="0" <?php if($csv_export['customer_group_id'] == 0) { ?> selected="selected" <?php } ?>><?php echo $text_all; ?></option>
										<?php foreach ($customer_groups as $customer_group) { ?>
											<?php if($customer_group['customer_group_id'] == $csv_export['customer_group_id']) { ?>
											<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_newsletter; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[newsletter]" class="form-control">
										<option value="2" <?php if($csv_export['newsletter'] == 2) { ?> selected="selected" <?php } ?>> <?php echo $text_all; ?> </option>
										<option value="1" <?php if($csv_export['newsletter'] == 1) { ?> selected="selected" <?php } ?>> <?php echo $text_enabled; ?> </option>
										<option value="0" <?php if($csv_export['newsletter'] == 0) { ?> selected="selected" <?php } ?>> <?php echo $text_disabled; ?> </option>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_date_start; ?></label>
								<div class="col-sm-7">
									<div class="input-group datetime">
										<input type="text" name="csv_export[date_start]" value="<?php echo $csv_export['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
										<span class="input-group-btn"> <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button> <button class="btn btn-default btn-calendar-check-o " type="button" data-toggle="tooltip" title="<?php echo $text_cur_date; ?>"><i class="fa fa-refresh"></i></button> <button class="btn btn-default btn-calendar-eraser" type="button" data-toggle="tooltip" title="<?php echo $text_clear; ?>"><i class="fa fa-eraser"></i></button> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_date_end; ?></label>
								<div class="col-sm-7">
									<div class="input-group datetime">
										<input type="text" name="csv_export[date_end]" value="<?php echo $csv_export['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
										<span class="input-group-btn"> <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button> <button class="btn btn-default btn-calendar-check-o" type="button" data-toggle="tooltip" title="<?php echo $text_cur_date; ?>"><i class="fa fa-refresh"></i></button> <button class="btn btn-default btn-calendar-eraser" type="button" data-toggle="tooltip" title="<?php echo $text_clear; ?>"><i class="fa fa-eraser"></i></button> </span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_status; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[status]" class="form-control">
										<option value="2" <?php if($csv_export['status'] == 2) { ?> selected="selected" <?php } ?>> <?php echo $text_all; ?> </option>
										<option value="1" <?php if($csv_export['status'] == 1) { ?> selected="selected" <?php } ?>> <?php echo $text_enabled; ?> </option>
										<option value="0" <?php if($csv_export['status'] == 0) { ?> selected="selected" <?php } ?>> <?php echo $text_disabled; ?> </option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<table class="table table-hover csvpricepro-field_set" id="tbl_field_set">
								<tbody>
									<?php foreach ($csv_export['fields_set_data'] as $field) { ?>
									<tr id="row_<?php echo $field['uid']; ?>">
										<td>
											<label class="control-label" title="<?php echo $fields_set_help[$field['uid']]; ?> <?php echo $field['uid']; ?>">
												<input <?php if (array_key_exists($field['uid'], $csv_export['fields_set']) || $field['uid'] == '_ID_') { ?> checked="checked" <?php } ?> <?php if ($field['uid'] == '_ID_') { ?> disabled="disabled" class="field_id" <?php } ?> type="checkbox" id="<?php echo $field['uid']; ?>" name="csv_export[fields_set][<?php echo $field['uid']; ?>]" value="1" />
												<?php echo $fields_set_help[$field['uid']]; ?>
											</label>
										</td>
										<td><span><?php echo $field['uid']; ?></span></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							<a onclick="$(this).parent().find(':checkbox').prop('checked', true);initFieldsSet()"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false); initFieldsSet();"><?php echo $text_unselect_all; ?></a>
							<input type="hidden" name="csv_export[fields_set][_ID_]" value="1">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<hr />
							<div class="pull-right">
								<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_customer_export').submit();"><?php echo $button_export; ?></button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var prop_descr = new Array();
		<?php if(isset($prop_descr)) echo $prop_descr; ?>
			// Document Ready
			jQuery(document).ready(function ($) {
				initFieldsSet();
				$('.csvpricepro-field_set input[type=checkbox]').change(function () {
					setBackgroundColor(this);
				});
				$('.datetime').datetimepicker({
					pickDate: true,
					pickTime: false,
					language: '<?php echo $text_datepicker; ?>'
				});
				$(".btn-calendar-check-o").on("click", function () {
					event.preventDefault();
					$(this).parent().parent().data('DateTimePicker').setDate(new Date());
					return false;
				});
				$(".btn-calendar-eraser").on("click", function () {
					event.preventDefault();
					$(this).parent().parent().children("input").val('');
					return false;
				});
			});
			function setBackgroundColor(obj) {
				var row = '#row_' + $(obj).attr('id') + ' td';
				if ($(obj).prop('checked')) {
					$(row).addClass('active');
				} else {
					$(row).removeClass('active    ');
				}
			}
			function initFieldsSet() {
				$('.field_id').prop('checked', true);
				$('.csvpricepro-field_set input[type=checkbox]').each(function () {
					setBackgroundColor(this);
				});
			}
	</script>
</div>
<?php echo $app_footer; ?>
<?php echo $footer; ?>