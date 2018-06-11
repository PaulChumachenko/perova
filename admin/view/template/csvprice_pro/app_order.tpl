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
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_setting" data-toggle="tab" id="link_tab_setting"><?php echo $tab_setting; ?></a></li>
                    <li><a href="#tab_export" data-toggle="tab" id="link_tab_export"><?php echo $tab_export; ?></a></li>
                    <li><a href="#tab_import" data-toggle="tab" id="link_tab_import"><?php echo $tab_import; ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_setting">
                        <form action="<?php echo $action; ?>" method="post" id="form_order_setting" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label"><?php echo $entry_file_encoding; ?></label>
                                        <div class="col-sm-7">
                                            <select name="csv_setting[file_encoding]" class="form-control">
                                                <?php foreach ($charsets as $key => $item ) { ?>
												<?php if($csv_setting['file_encoding'] == $key) { ?>
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
											<select name="csv_setting[delimiter]" class="form-control">
												<option value=";"<?php if($csv_setting['delimiter'] == ';') { ?> selected="selected"<?php } ?>> ; </option>
												<option value=","<?php if($csv_setting['delimiter'] == ',') { ?> selected="selected"<?php } ?>> , </option>
												<option value="|"<?php if($csv_setting['delimiter'] == '|') { ?> selected="selected"<?php } ?>> | </option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_include_csv_title; ?></label>
										<div class="col-sm-7">
											<select name="csv_setting[csv_title]" class="form-control">
												<?php if(isset($csv_setting['csv_title']) && $csv_setting['csv_title'] == 1) { ?>
												<option value="1" selected="selected"><?php echo $text_yes; ?></option>
												<option value="0"><?php echo $text_no; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_yes; ?></option>
												<option value="0" selected="selected"><?php echo $text_no; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<?php $k = $fields_count; ?>
								<?php $s = 1; ?>
								<?php $id = 1; ?>
								<?php foreach ($csv_setting['fields_set'] as $key => $item) { ?>
								<?php if ($s == 1) { ?>
								<div class="col-sm-6">
									<table class="table table-hover csvpricepro-field_set">
										<tbody><?php $s = 0; ?>
								<?php } ?>
								<?php if ($id == $k) { ?>
										</tbody>
									</table>
								</div>
								<div class="col-sm-6">
									<table class="table table-hover csvpricepro-field_set">
										<tbody>
								<?php } ?>
										<tr id="row_<?php echo $id; ?>">
											<td><input type="hidden" name="csv_setting[fields_set][<?php echo $key; ?>][status]" value="0"><label class="control-label checkbox-inline"><input id="<?php echo $id; ?>" <?php if ($item['status']) { ?>checked="checked"<?php } ?> type="checkbox" name="csv_setting[fields_set][<?php echo $key; ?>][status]" value="1"> <?php echo $fields_set_help[$key]; ?></label></td>
											<td><input class="form-control" type="text" name="csv_setting[fields_set][<?php echo $key; ?>][caption]" value="<?php echo $csv_setting['fields_set'][$key]['caption']; ?>"></td>
										</tr>
								<?php $id++; ?>
								<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<a onclick="$('.csvpricepro-field_set').find(':checkbox').prop('checked', true);initFieldsSet();"><?php echo $text_select_all; ?></a> / <a onclick="$('.csvpricepro-field_set').find(':checkbox').prop('checked', false);initFieldsSet();"><?php echo $text_unselect_all; ?></a>
									<hr />
									<div class="pull-right">
										<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_order_setting').submit();"><?php echo $button_save; ?></button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="tab_export">
						<div class="row">
							<div class="col-sm-6">
								<div id="wrap_profile_export" class="well well-sm">
									<form id="form_profile_export" class="form-horizontal form-control-sm">
										<div class="form-group form-group-sm">
											<label class="col-sm-4 control-label" data-prop_id="1"><?php echo $text_profile_load; ?></label>
											<div class="col-sm-8">
												<div class="input-group">
													<select name="profile_export_select" id="profile_export_select" class="form-control form-control-sm"></select>
													<span class="input-group-btn">
														<a onclick="loadProfile('profile_export');" data-toggle="tooltip" title="" class="btn btn-info btn-sm" data-original-title="<?php echo $button_load; ?>"><i class="fa fa-refresh"></i></a>
														<a onclick="updateProfile('profile_export');" data-toggle="tooltip" title="" class="btn btn-primary btn-sm" data-original-title="<?php echo $button_save; ?>"><i class="fa fa-save"></i></a>
														<a onclick="deleteProfile('profile_export');" data-toggle="tooltip" title="" class="btn btn-warning btn-sm" data-original-title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>
													</span>
												</div><!-- /input-group -->
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label"><?php echo $text_import_create_profile; ?></label>
											<div class="col-sm-8">
												<div class="input-group">
													<input type="text" name="profile_export_name" id="profile_export_name" value="" class="form-control input-sm">
													<span class="input-group-btn">
														<a onclick="createProfile('profile_export');" data-toggle="tooltip" title="" class="btn btn-success btn-sm" data-original-title="<?php echo $button_add; ?>"><i class="fa fa-plus-circle"></i></a>
														<span style="margin-right:57px;"></span>
													</span>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<form action="<?php echo $action_export; ?>" method="post" id="form_order_export" enctype="multipart/form-data" class="form-horizontal">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_order_id; ?></label>
										<div class="col-sm-7">
											<input class="form-control" type="text" name="csv_export[filter_order_id]" value="<?php echo $csv_export['filter_order_id']; ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_customer; ?></label>
										<div class="col-sm-7">
											<input class="form-control" type="text" id="input_filter_customer" name="csv_export[filter_customer]" value="<?php echo $csv_export['filter_customer']; ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_order_status; ?></label>
										<div class="col-sm-7">
											<select class="form-control" name="csv_export[filter_order_status_id]">
												<option value="*"<?php if (!isset($csv_export['filter_order_status_id']) || $csv_export['filter_order_status_id'] == '*') { ?> selected="selected"<?php } ?>>&nbsp;</option>
												<option value="0"<?php if ($csv_export['filter_order_status_id'] != '*' && $csv_export['filter_order_status_id'] == 0) { ?> selected="selected"<?php } ?>><?php echo $text_missing; ?></option>
												<?php foreach ($order_statuses as $order_status) { ?>
													<?php if ($order_status['order_status_id'] == $csv_export['filter_order_status_id']) { ?>
													<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
													<?php } else { ?>
													<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_time_interval; ?></label>
										<div class="col-sm-3">
											<select class="form-control" name="csv_export[filter_time_interval]" id="input_time_interval">
												<?php for ($i = 0; $i <= 48; $i++) { ?>
													<?php if (isset($csv_export['filter_time_interval']) && $csv_export['filter_time_interval'] == $i) { ?>
													<option value="<?php echo $i; ?>" selected="selected"> <?php echo $i; ?> </option>
													<?php } else { ?>
													<option value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_date_start; ?></label>
										<div class="col-sm-7">
											<div class="input-group datetime">
												<input type="text" name="csv_export[filter_date_added_start]" value="<?php if(isset($csv_export['filter_date_added_start'])) echo $csv_export['filter_date_added_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-added-start" class="form-control" />
												<span class="input-group-btn"> <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button> <button class="btn btn-default btn-calendar-check-o" type="button" data-toggle="tooltip" title="<?php echo $text_cur_date; ?>"><i class="fa fa-refresh"></i></button> <button class="btn btn-default btn-calendar-eraser" type="button" data-toggle="tooltip" title="<?php echo $text_clear; ?>"><i class="fa fa-eraser"></i></button> </span>
											</div>
											<div class="input-group datetime" style="margin-top: 8px;">
												<input type="text" name="csv_export[filter_date_added_end]" value="<?php if(isset($csv_export['filter_date_added_end'])) echo $csv_export['filter_date_added_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-added-end" class="form-control" />
												<span class="input-group-btn"> <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button> <button class="btn btn-default btn-calendar-check-o" type="button" data-toggle="tooltip" title="<?php echo $text_cur_date; ?>"><i class="fa fa-refresh"></i></button> <button class="btn btn-default btn-calendar-eraser" type="button" data-toggle="tooltip" title="<?php echo $text_clear; ?>"><i class="fa fa-eraser"></i></button> </span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_date_end; ?></label>
										<div class="col-sm-7">
											<div class="input-group datetime">
												<input type="text" name="csv_export[filter_date_modified_start]" value="<?php if(isset($csv_export['filter_date_modified_start'])) echo $csv_export['filter_date_modified_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-added-start" class="form-control" />
												<span class="input-group-btn"> <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button> <button class="btn btn-default btn-calendar-check-o" type="button" data-toggle="tooltip" title="<?php echo $text_cur_date; ?>"><i class="fa fa-refresh"></i></button> <button class="btn btn-default btn-calendar-eraser" type="button" data-toggle="tooltip" title="<?php echo $text_clear; ?>"><i class="fa fa-eraser"></i></button> </span>
											</div>
											<div class="input-group datetime" style="margin-top: 8px;">
												<input type="text" name="csv_export[filter_date_modified_end]" value="<?php if(isset($csv_export['filter_date_modified_end'])) echo $csv_export['filter_date_modified_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-added-end" class="form-control" />
												<span class="input-group-btn"> <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button> <button class="btn btn-default btn-calendar-check-o" type="button" data-toggle="tooltip" title="<?php echo $text_cur_date; ?>"><i class="fa fa-refresh"></i></button> <button class="btn btn-default btn-calendar-eraser" type="button" data-toggle="tooltip" title="<?php echo $text_clear; ?>"><i class="fa fa-eraser"></i></button> </span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_total_sum; ?></label>
										<div class="col-sm-2">
											<select class="form-control" name="csv_export[filter_total_prefix]">
												<option value="1"<?php if ($csv_export['filter_total_prefix'] == 1) { ?> selected="selected"<?php } ?>> = </option>
												<option value="2"<?php if ($csv_export['filter_total_prefix'] == 2) { ?> selected="selected"<?php } ?>> &rsaquo; = </option>
												<option value="3"<?php if ($csv_export['filter_total_prefix'] == 3) { ?> selected="selected"<?php } ?>> &lsaquo; = </option>
												<option value="4"<?php if ($csv_export['filter_total_prefix'] == 4) { ?> selected="selected"<?php } ?>> &lsaquo; &rsaquo; </option>
											</select>
										</div>
										<div class="col-sm-5">
											<input class="form-control" type="text" name="csv_export[filter_total_sum]" value="<?php echo $csv_export['filter_total_sum']; ?>" />
										</div>
									</div>
									<div class="form-group">
										<input type="hidden" name="csv_export[from_store]" value="0">
										<label class="col-sm-5 control-label"><?php echo $entry_store; ?></label>
										<div class="col-sm-7">
											<div class="well well-sm" style="height:100px;margin-bottom:4px;overflow:auto;">
												<?php foreach ($stores as $store) { ?>
												<div class="checkbox">
													<label>
													<?php if (isset($csv_export['from_store']) && is_array($csv_export['from_store']) && in_array($store['store_id'],$csv_export['from_store'])) { ?>
														<input type="checkbox" name="csv_export[from_store][]" value="<?php echo $store['store_id']; ?>" checked="checked" />
														<?php echo $store['name']; ?>
													<?php } else { ?>
														<input type="checkbox" name="csv_export[from_store][]" value="<?php echo $store['store_id']; ?>" />
														<?php echo $store['name']; ?>
													<?php } ?>
													</label>
												</div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label">&nbsp;</label>
										<div class="col-sm-7">
											<div class="text-right">
												<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_order_export').submit();"><?php echo $button_export; ?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="tab_import">
						<div class="row">
							<div class="col-sm-6">
								<div id="wrap_profile_export" class="well well-sm">
									<form id="form_profile_import" class="form-horizontal form-control-sm">
										<div class="form-group form-group-sm">
											<label class="col-sm-4 control-label" data-prop_id="1"><?php echo $text_profile_load; ?></label>
											<div class="col-sm-8">
												<div class="input-group">
													<select name="profile_import_select" id="profile_import_select" class="form-control input-sm"></select>
													<span class="input-group-btn">
														<a onclick="loadProfile('profile_import');" data-toggle="tooltip" title="" class="btn btn-info btn-sm" data-original-title="<?php echo $button_load; ?>"><i class="fa fa-refresh"></i></a>
														<a onclick="updateProfile('profile_import');" data-toggle="tooltip" title="" class="btn btn-primary btn-sm" data-original-title="<?php echo $button_save; ?>"><i class="fa fa-save"></i></a>
														<a onclick="deleteProfile('profile_import');" data-toggle="tooltip" title="" class="btn btn-warning btn-sm" data-original-title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>
													</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label"><?php echo $text_import_create_profile; ?></label>
											<div class="col-sm-8">
												<div class="input-group">
													<input type="text" name="profile_import_name" id="profile_import_name" value="" class="form-control input-sm">
													<span class="input-group-btn">
														<a onclick="createProfile('profile_import');" data-toggle="tooltip" title="" class="btn btn-success btn-sm" data-original-title="<?php echo $button_add; ?>"><i class="fa fa-plus-circle"></i></a>
														<span style="margin-right:57px;"></span>
													</span>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<form action="<?php echo $action_import; ?>" method="post" id="form_order_import" enctype="multipart/form-data" class="form-horizontal">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_file_encoding; ?></label>
										<div class="col-sm-7">
											<select name="csv_import[file_encoding]" class="form-control">
												<?php foreach ($charsets as $key => $item) { ?>
												<?php if ($csv_import['file_encoding'] == $key) { ?>
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
											<select name="csv_import[csv_delimiter]" class="form-control">
												<option value=";"<?php if (isset($csv_import['csv_delimiter']) && $csv_import['csv_delimiter'] == ';') { ?> selected="selected"<?php } ?>> ; </option>
												<option value=","<?php if (isset($csv_import['csv_delimiter']) && $csv_import['csv_delimiter'] == ',') { ?> selected="selected"<?php } ?>> , </option>
												<option value="|"<?php if (isset($csv_import['csv_delimiter']) && $csv_import['csv_delimiter'] == '|') { ?> selected="selected"<?php } ?>> | </option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_order_id; ?></label>
										<div class="col-sm-7">
											<input class="form-control" type="text" name="csv_import[field_id]" value="<?php echo (isset($csv_import['field_id'])) ? $csv_import['field_id'] : '_ID_'; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_order_status_id; ?></label>
										<div class="col-sm-7">
											<input class="form-control" type="text" name="csv_import[field_status_id]" value="<?php echo (isset($csv_import['field_status_id'])) ? $csv_import['field_status_id'] : '_STATUS_ID_'; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_order_status; ?></label>
										<div class="col-sm-7">
											<input class="form-control" type="text" name="csv_import[field_status]" value="<?php echo (isset($csv_import['field_status'])) ? $csv_import['field_status'] : '_STATUS_'; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_order_comment; ?></label>
										<div class="col-sm-7">
											<input class="form-control" type="text" name="csv_import[field_comment]" value="<?php echo (isset($csv_import['field_comment'])) ? $csv_import['field_comment'] : '_COMMENT_'; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label" data-prop_id="0"><?php echo $entry_order_notify; ?></label>
										<div class="col-sm-7">
											<input class="form-control" type="text" name="csv_import[field_notify]" value="<?php echo (isset($csv_import['field_notify'])) ? $csv_import['field_notify'] : '_NOTIFY_'; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_api; ?></label>
										<div class="col-sm-7">
											<select name="csv_import[api_id]" id="input-api" class="form-control">
												<option value="0"><?php echo $text_none; ?></option>
												<?php foreach ($apis as $api) { ?>
													<option value="<?php echo $api['api_id']; ?>"<?php if (isset($csv_import['api_id']) && $api['api_id'] == $csv_import['api_id']) { ?> selected="selected"<?php } ?>><?php echo $api['name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_import_file; ?></label>
										<div class="col-sm-7">
											<input  type="file" name="import" class="form-control" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label">&nbsp;</label>
										<div class="col-sm-7">
											<div class="text-right">
												<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_order_import').submit();"><?php echo $button_import; ?></button>
											</div>
										</div>
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
	var prop_descr=new Array();
	<?php if(isset($prop_descr)) echo $prop_descr; ?>

	// Document Ready
	jQuery(document).ready(function($) {
		$('.csvprice_pro_container .nav-tabs li.active').removeClass('active');
		$('.csvprice_pro_container .tab-content div.active').removeClass('active');
		$("#link_<?php echo $tab_selected; ?>").parent().addClass('active');
		$("#<?php echo $tab_selected; ?>").removeClass('fade').addClass('active');
		initFieldsSet();
		$('.csvpricepro-field_set input[type=checkbox]').change(function(){
			setBackgroundColor(this);
		});
		$('.datetime').datetimepicker({
			pickDate : true,
			pickTime : false,
			language: '<?php echo $text_datepicker; ?>'
		});
		$(".btn-calendar-check-o").on("click", function() {
			event.preventDefault();
			$(this).parent().parent().data('DateTimePicker').setDate(new Date());
			return false;
		});
		$(".btn-calendar-eraser").on("click", function() {
			event.preventDefault();
			$(this).parent().parent().children("input").val('');
			return false;
		});
		// Profile load
		getProfile('profile_export', <?php echo (isset($profile_id)) ? $profile_id : 0; ?>);
		getProfile('profile_import', <?php echo (isset($profile_id)) ? $profile_id : 0; ?>);
	});// END Document Ready

	function setBackgroundColor(obj) {
		var row = '#row_' + $(obj).attr('id') + ' td';
		if ($(obj).prop('checked')) {
			$(row).addClass('active');
		} else {
			$(row).removeClass('active');
		}
	}
	function initFieldsSet() {
		$('.csvpricepro-field_set input[type=checkbox]').each(function() {
			setBackgroundColor(this);
		});
	}
	$('#input_filter_customer').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['customer_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('#input_filter_customer').val(item['label']);
		}
	});
	// Profile
	function getProfile(key, id) {
		var url = '<?php echo $action_get_profile; ?>' + '&key=' + key;
		url = url.replace( /\&amp;/g, '&' );
		var obj = $('#' + key + '_select');
		$.ajax({
		       type:'POST',
		       url: url,
		       dataType:'json',
		       success:function(json){
		           obj.get(0).options.length = 0;
		           $.each(json, function(i,item) {
		               obj.get(0).options[obj.get(0).options.length] = new Option(item.name, item.id);
		           });
		           if (id) {
		           	$('#' + key + '_select option[value="' + id + '"]').attr("selected", "selected");
		           }
		       },
		       error:function(){
		       	alert("Failed to load profiles!");
		       }
		});
	}
	
	function deleteProfile(key) {
		if (!confirm('<?php echo $text_confirm_delete; ?>')) return false;
		var url = '<?php echo $action_del_profile; ?>';
		url = url.replace( /\&amp;/g, '&' );
		var s = '#' + key + '_select';
		if($(s).get(0).options.length == 0) {
			return;
		}
		var id = $( s +' option:selected').val();
		$.ajax({
			type: "POST",
			url: url,
			data: {profile_id: id},
			success: function(data){getProfile(key);}
		});
		return false;
	}
	function loadProfile(key, u = '') {
		var s = '#' + key + '_select';
		if($(s).get(0).options.length == 0) {
			return;
		}
		if(u != '') {
			u = '&update=1';
		}
		var url = '<?php echo $action; ?>&profile_id=' + $( '#' + key + '_select' + ' option:selected').val() + u;
		url = url.replace( /\&amp;/g, '&' );
		window.location.href = url;
	
	}

	function createProfile(key) {
		var url = '<?php echo $action_add_profile; ?>';
		url = url.replace( /\&amp;/g, '&' );
		if($('#' + key + '_name').val() == '') return false;
		var data;
		
		if( key == 'profile_import' ){
			data = $("#form_order_import, #form_profile_import").serialize();
		} else if( key == 'profile_export' ){
			data = $("#form_order_setting, #form_order_export, #form_profile_export").serialize();
		} else {
			return false;
		}

		$.ajax({
			type: "POST",
			url: url,
			data: data,
			datatype: 'json',
			success: function(json){
				$('#' + key + '_name').val('');
				var obj = jQuery.parseJSON(json);
				getProfile(key, obj.id);
			}
		});
		return false;
	}
	function updateProfile(key) {
		var url = '<?php echo $action_edit_profile; ?>',data;
		url = url.replace( /\&amp;/g, '&' );

		if( key == 'profile_import' ){
			data = $("#form_order_import, #form_profile_import").serialize();
		} else if( key == 'profile_export' ){
			data = $("#form_order_setting, #form_order_export, #form_profile_export").serialize();
		} else {
			return false;
		}

		$.ajax({
			type: "POST",
			url: url,
			data: data,
			datatype: 'json',
			success: function(json){
				$('#' + key + '_name').val('');
				var obj = jQuery.parseJSON(json);
				loadProfile(key, 'update');
			}
		});
		return false;
	}
</script>
<?php echo $app_footer; ?>
<?php echo $footer; ?>