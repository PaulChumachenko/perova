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
					<li class="active"><a href="#tab_export" data-toggle="tab" id="link_tab_export"><?php echo $tab_export; ?></a></li>
					<li><a href="#tab_import" data-toggle="tab" id="link_tab_import"><?php echo $tab_import; ?></a></li>
					<li><a href="#tab_macros" data-toggle="tab" id="link_tab_macros"><?php echo $tab_macros; ?></a></li>
				</ul>
				<div class="tab-content">
					<div id="tab_export" class="tab-pane fade">
						<form action="<?php echo $action_export; ?>" method="post" id="form_category_export" enctype="multipart/form-data" class="form-horizontal">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="col-sm-5 control-label" data-prop_id="0"><?php echo $entry_file_encoding; ?></label>
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
										<label class="col-sm-5 control-label" data-prop_id="1"><?php echo $entry_csv_delimiter; ?></label>
										<div class="col-sm-7">
											<select name="csv_export[csv_delimiter]" class="form-control">
												<option value=";"<?php if ($csv_export['csv_delimiter'] == ';'){echo ' selected="selected"';} ?>> ; </option>
												<option value=","<?php if ($csv_export['csv_delimiter'] == ','){echo ' selected="selected"';} ?>> , </option>
                                            </select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label" data-prop_id="2"><?php echo $entry_languages; ?></label>
										<div class="col-sm-7">
											<select name="csv_export[language_id]" class="form-control">
											<?php foreach ($languages as $language) { ?>
												<?php if ($csv_export['language_id'] == $language['language_id']) {  ?>
												<option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
												<?php } ?>
											<?php } ?>
                                            </select>
										</div>
									</div>
									<div class="form-group">
										<input type="hidden" name="csv_export[from_store]" value="0">
										<label class="col-sm-5 control-label" data-prop_id="12"><?php echo $entry_store; ?></label>
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
									<!-- Select From Category -->
									<div class="form-group">
                                        <label class="col-sm-5 control-label" data-prop_id="3"><?php echo $entry_category; ?></label>
                                        <div class="col-sm-7">
                                            <input type="text" name="input_category" id="input_category" class="form-control" />
                                            <div id="category_list" class="well well-sm" style="height: 100px; overflow: auto;">
												<?php foreach ( $csv_export['from_category'] as $category) { ?>
													<div id="category-id<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
														<input type="hidden" name="csv_export[from_category][]" value="<?php echo $category['category_id']; ?>" />
													</div>
												<?php } ?>
                                            </div>
                                        </div>
                                    </div><!-- END Select From Category -->
									<div class="form-group">
										<label class="col-sm-5 control-label" data-prop_id="4"><?php echo $entry_category_delimiter; ?></label>
										<div class="col-sm-7">
											<select name="csv_export[delimiter_category]" class="form-control">
												<option value="|"<?php if ($csv_export['delimiter_category'] == '|') { ?> selected="selected" <?php } ?>> | </option>
												<option value="/"<?php if ($csv_export['delimiter_category'] == '/') { ?> selected="selected" <?php } ?>> / </option>
												<option value=","<?php if ($csv_export['delimiter_category'] == ',') { ?> selected="selected" <?php } ?>> , </option>
											</select>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="form-group">
										<label class="col-sm-5 control-label" data-prop_id="5"><?php echo $entry_category_parent; ?></label>
										<div class="col-sm-7">
											<select name="csv_export[category_parent]" class="form-control">
												<?php if ($csv_export['category_parent'] == 1) { ?>
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
                                        <label class="col-sm-5 control-label" data-prop_id="32"><?php echo $entry_image_url; ?></label>
                                        <div class="col-sm-7">
                                            <select name="csv_export[image_url]" class="form-control">
												<?php if ($csv_export['image_url'] == 1) { ?>
												<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
												<?php } ?>
                                            </select>
                                        </div>
                                    </div>
								</div>
								<!-- Fields Set Data -->
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
                                    <input type="hidden" name="csv_export[fields_set][_ID_]" value="1">
                                    <a onclick="$(this).parent().find(':checkbox').prop('checked', true);initFieldsSet()"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false); initFieldsSet();"><?php echo $text_unselect_all; ?></a>
                                </div><!-- END Fields Set Data -->
							</div>
							<div class="row">
                                <div class="col-sm-12">
                                    <hr />
									<div class="pull-right">
										<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_category_export').submit();"><?php echo $button_export; ?></button>
									</div>
                                </div>
                            </div>
						</form>
					</div>
					<!-- end tab_export -->
					<div id="tab_import" class="tab-pane fade">
						<form action="<?php echo $action_import; ?>" method="post" id="form_category_import" enctype="multipart/form-data" class="form-horizontal">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="col-sm-5 control-label" data-prop_id="0"><?php echo $entry_file_encoding; ?></label>
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
										<label class="col-sm-5 control-label" data-prop_id="1"><?php echo $entry_csv_delimiter; ?></label>
										<div class="col-sm-7">
											<select name="csv_import[csv_delimiter]" class="form-control">
												<option value=";"<?php if ($csv_import['csv_delimiter'] == ';') { ?> selected="selected"<?php } ?>> ; </option>
                                                <option value=","<?php if ($csv_import['csv_delimiter'] == ',') { ?> selected="selected"<?php } ?>> , </option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label" data-prop_id="6"><?php echo $entry_languages; ?></label>
										<div class="col-sm-7">
											<select name="csv_import[language_id]" class="form-control">
												<?php foreach ($languages as $language) { ?>
												<?php if ($csv_import['language_id'] == $language['language_id']) { ?>
												<option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
												<?php } ?>
												<?php } ?>
                                            </select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label" data-prop_id="7"><?php echo $entry_import_mode; ?></label>
										<div class="col-sm-7">
											<select name="csv_import[mode]" class="form-control">
                                                <option value="2" <?php if ($csv_import['mode'] == 2) { ?> selected="selected" <?php } ?>><?php echo $text_import_mode_update; ?></option>
                                                <option value="3" <?php if ($csv_import['mode'] == 3) { ?> selected="selected" <?php } ?>><?php echo $text_import_mode_insert; ?></option>
                                                <option value="1" <?php if ($csv_import['mode'] == 1) { ?> selected="selected" <?php } ?>><?php echo $text_import_mode_both; ?></option>
                                            </select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label" data-prop_id="8"><?php echo $entry_key_field; ?></label>
										<div class="col-sm-7">
											<select name="csv_import[key_field]" class="form-control">
												<?php foreach ($csv_import_key_fields as $key => $item) { ?>
												<?php if ($csv_import['key_field'] == $key) { ?>
												<option value="<?php echo $key; ?>" selected="selected"><?php echo $item; ?></option>
												<?php } else { ?>
												<option value="<?php echo $key; ?>"><?php echo $item; ?></option>
												<?php } ?>
												<?php } ?>
                                            </select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label" data-prop_id="9"><?php echo $entry_category_delimiter; ?></label>
										<div class="col-sm-7">
											<select name="csv_import[delimiter_category]" class="form-control">
												<option value="|"<?php if ($csv_export['delimiter_category'] == '|') { ?> selected="selected" <?php } ?>> | </option>
												<option value="/"<?php if ($csv_export['delimiter_category'] == '/') { ?> selected="selected" <?php } ?>> / </option>
												<option value=","<?php if ($csv_export['delimiter_category'] == ',') { ?> selected="selected" <?php } ?>> , </option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<input type="hidden" name="csv_import[to_store]" value="0">
										<label class="col-sm-5 control-label" data-prop_id="13"><?php echo $entry_store; ?></label>
										<div class="col-sm-7">
											<div class="well well-sm" style="height: 150px; overflow: auto;">
												<?php foreach ($stores as $store) { ?>
													<div class="checkbox">
														<label>
															<?php if (isset($csv_import['to_store']) && is_array($csv_import['to_store']) && in_array($store['store_id'], $csv_import['to_store'])) { ?>
															<input type="checkbox" name="csv_import[to_store][]" value="<?php echo $store['store_id']; ?>" checked="checked" />
															<?php echo $store['name']; ?>
															<?php } else { ?>
															<input type="checkbox" name="csv_import[to_store][]" value="<?php echo $store['store_id']; ?>" />
															<?php echo $store['name']; ?>
															<?php } ?>
														</label>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_sort_order; ?></label>
										<div class="col-sm-7">
											<input class="form-control text-right" type="text" name="csv_import[sort_order]" value="<?php echo $csv_import['sort_order']; ?>" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_status; ?></label>
										<div class="col-sm-7">
											<select name="csv_import[status]" class="form-control">
												<?php if ($csv_import['status'] == 1) { ?>
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
										<label class="col-sm-5 control-label"><?php echo $entry_import_category_disable; ?></label>
										<div class="col-sm-7">
											<select name="csv_import[category_disable]" class="form-control">
												<?php if ($csv_import['category_disable'] == 1) { ?>
												<option value="1" selected="selected"><?php echo $text_yes; ?></option>
												<option value="0"><?php echo $text_no; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_yes; ?></option>
												<option value="0" selected="selected"><?php echo $text_no; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group">
                                        <label class="col-sm-5 control-label" data-prop_id="10"><?php echo $entry_import_img_download; ?></label>
                                        <div class="col-sm-7">
                                            <select name="csv_import[image_download]" class="form-control">
                                                <?php if (isset($csv_import['image_download']) && $csv_import['image_download'] == 1) { ?>
												<option value="1" selected="selected"><?php echo $text_yes; ?></option>
												<option value="0"><?php echo $text_no; ?></option>
                                                <?php } else { ?>
												<option value="1"><?php echo $text_yes; ?></option>
												<option value="0" selected="selected"><?php echo $text_no; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
									<div class="form-group">
										<label class="col-sm-5 control-label"><?php echo $entry_import_file; ?></label>
										<div class="col-sm-7">
											<input  type="file" name="import" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label">&nbsp;</label>
                                        <div class="col-sm-7">
											<div class="pull-right">
												<button type="button" class="btn btn-primary" style="min-width:120px" style="max-width:200px" onclick="$('#form_category_import').submit()"><?php echo $button_import; ?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<!-- end tab_import -->
					<div id="tab_macros" class="tab-pane fade">
						
							<form action="<?php echo $action; ?>" method="post" id="form_macros" enctype="multipart/form-data" class="form-horizontal">
								<input type="hidden" name="form_macros_status" value="1" />
								<table id="table-custom-fields" class="table table-hover table-striped">
									<thead>
										<tr>
											<th><?php echo $entry_table; ?></th>
											<th><?php echo $entry_field_name; ?></th>
											<th><?php echo $entry_csv_name; ?></th>
											<th><?php echo $entry_caption; ?></th>
											<th>&nbsp;</th>
										</tr>
									</thead>
									<?php $field_row = 0 ?>
									<?php if (!empty($category_macros)) { ?>
									<tbody id="tbody_data">
									<?php foreach ($category_macros as $fields) { ?>
										<tr id="custom-fields-row<?php echo $field_row; ?>">
											<td class="text-left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][tbl_name]" value="<?php echo $fields['tbl_name']; ?>"/><?php echo $fields['tbl_name']; ?></td>
											<td class="text-left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][field_name]" value="<?php echo $fields['field_name']; ?>"/><?php echo $fields['field_name']; ?></td>
											<td class="text-left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][csv_name]" value="<?php echo $fields['csv_name']; ?>"/><?php echo $fields['csv_name']; ?></td>
											<td class="text-left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][csv_caption]" value="<?php echo $fields['csv_caption']; ?>"/><?php echo $fields['csv_caption']; ?></td>
											<td class="text-center"><a onclick="$('#custom-fields-row<?php echo $field_row; ?>').remove();" data-toggle="tooltip" title="" class="btn btn-danger btn-sm" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></a></td>
										</tr>
									<?php $field_row++; ?>
									<?php } ?>
									</tbody>
									<tbody id="tbody_foot"></tbody>
									<?php } else { ?>
									<tbody id="tbody_foot">
										<tr>
											<td colspan="5">
												<?php echo $text_no_results; ?>
											</td>
										</tr>
									</tbody>
									<?php } ?>
									<tbody id="tbody_form" class="csvpricepro_tbody-form">
										<tr>
											<td>
											<select id="tbl_name" onchange="loadFields();" class="form-control">
												<?php foreach ($db_table as $table_name) { ?>
												<option value="<?php echo $text_db_prefix; ?><?php echo $table_name; ?>"><?php echo $text_db_prefix; ?><?php echo $table_name; ?></option>
												<?php } ?>
											</select>
											</td>
											<td>
												<select id="custom_fields" onchange="selectFieldName();" class="form-control"></select>
											</td>
											<td>
												<input type="text" id="csv_name" value="" class="form-control" />
											</td>
											<td>
												<input type="text" id="csv_caption" value="" class="form-control" />
											</td>
											<td class="text-center">
												<a onclick="addFieldRow();" data-toggle="tooltip" title="" class="btn btn-success btn-sm" data-original-title="<?php echo $button_insert; ?>"><i class="fa fa-plus-circle"></i></a>
											</td>
										</tr>
									</tbody>
								</table>
							</form>
						<div class="row">
							<div class="col-sm-12">
								<hr />
								<div class="pull-right">
									<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_macros').submit();"><?php echo $button_save; ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var prop_descr = new Array();
    <?php if(isset($prop_descr)) echo $prop_descr; ?>
		
	//	Categories
	$('input[name=\'input_category\']').autocomplete({
		'source': function (request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function (json) {
					response($.map(json, function (item) {
						return {
							label: item['name'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		'select': function (item) {
			$('input[name=\'category_name\']').val('');
			$('#category_id' + item['value']).remove();
			$('#category_list').append('<div id="category_id' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="csv_export[from_category][]" value="' + item['value'] + '" /></div>');
		}
	});
	$('.csvprice_pro_container #category_list').delegate('.fa-minus-circle', 'click', function () {
		$(this).parent().remove();
	});

		function resetFormMacros() {
			$('#csv_name').val('');
			$('#csv_caption').val('');
		}
		function loadFields() {
			var table_index = {};
			<?php $i = 0; ?>
			<?php foreach ($db_table as $table_name) { ?>
			table_index['<?php echo $text_db_prefix; ?><?php echo $table_name; ?>'] = <?php echo $i; ?>;
			<?php $i++; ?>
			<?php } ?>
			var table = <?php echo $db_table_fields; ?>;
			var data = table[table_index[$('#tbl_name option:selected').val()]];

			$('#custom_fields').get(0).options.length = 0;
			$('#custom_fields').get(0).options[0] = new Option(" <?php echo $text_none; ?> ", "-1");
			$.each(data, function (index, text) {
				$("#custom_fields").get(0).options[$("#custom_fields").get(0).options.length] = new Option(text, text);
			});
			resetFormMacros();
			return false;
		}
		function selectFieldName() {
			$('#csv_name').val('');
			$('#csv_caption').val('');

			var field_name = $('#custom_fields option:selected').val();
			if (field_name != -1) {
				field_name = '_CUSTOM_' + field_name.toString().toUpperCase() + '_';
				$('#csv_name').val(field_name);
				$('#csv_caption').focus();
			}
		}

		var field_row = <?php echo $field_row; ?>;

		function deleteFieldRow(obj) {
			$('#custom-fields-row' + obj).remove();
		}

		function addFieldRow() {
			var tbl_name = $('#tbl_name option:selected').val();
			var field_name = $('#custom_fields option:selected').val();
			var csv_name = $('#csv_name').val();
			var csv_caption = $('#csv_caption').val();

			if (field_name == -1 || csv_name == '' || csv_caption == '') {
				return;
			}
			var html = '<tr id="custom-fields-row' + field_row + '">';
			html += '    <td class="text-left"><input type="hidden" name="category_macros[' + field_row + '][tbl_name]" value="' + tbl_name + '" size="1" />' + tbl_name + '</td>';
			html += '    <td class="text-left"><input type="hidden" name="category_macros[' + field_row + '][field_name]" value="' + field_name + '" size="1" />' + field_name + '</td>';
			html += '    <td class="text-left"><input type="hidden" name="category_macros[' + field_row + '][csv_name]" value="' + csv_name + '" size="1" />' + csv_name + '</td>';
			html += '    <td class="text-left"><input type="hidden" name="category_macros[' + field_row + '][csv_caption]" value="' + csv_caption + '" size="1" />' + csv_caption + '</td>';
			html += '    <td class="text-center"><a onclick="deleteFieldRow(' + field_row + ');" data-toggle="tooltip" class="btn btn-danger btn-sm" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></a></td>';
			html += '  </tr>';
			if (field_row < 1) {
				$('#tbody_foot').html('');
			}
			$('#tbody_foot').append(html);
			resetFormMacros();
			$('#tbl_name option').change();
			field_row++;
		}
		// Document Ready
		jQuery(document).ready(function ($) {
			$('.csvprice_pro_container .nav-tabs li.active').removeClass('active');
			$('.csvprice_pro_container .tab-content div.active').removeClass('active');
			$("#link_<?php echo $tab_selected; ?>").parent().addClass('active');
			$("#<?php echo $tab_selected; ?>").removeClass('fade').addClass('active');
			loadFields();
			initFieldsSet();
			$('#tbl_field_set input[type=checkbox]').change(function () {
				setBackgroundColor(this);
			});
		});
		function setBackgroundColor(obj) {
			var row = '#row_' + $(obj).attr('id') + ' td';
			if ($(obj).prop('checked')) {
				$(row).addClass('active');
			} else {
				$(row).removeClass('active');
			}
		}
		function initFieldsSet() {
			$('.field_id').prop('checked', true);
			$('#tbl_field_set input[type=checkbox]').each(function () {
				setBackgroundColor(this);
			});
		}
</script>
<?php echo $app_footer; ?>
<?php echo $footer; ?>