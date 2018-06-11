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
					<li><a href="#tab_export" data-toggle="tab" id="link_tab_export"><?php echo $tab_export; ?></a></li>
                    <li><a href="#tab_import" data-toggle="tab" id="link_tab_import"><?php echo $tab_import; ?></a></li>
					<li><a href="#tab_macros" data-toggle="tab" id="link_tab_macros"><?php echo $tab_macros; ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_export">
                        <form action="<?php echo $action_export; ?>" method="post" id="form_manufacturer_export" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label" data-prop_id="0"><?php echo $entry_file_encoding; ?></label>
                                        <div class="col-sm-7">
											<select name="csv_export[file_encoding]" class="form-control">
												<?php foreach ($charsets as $key => $item) { ?>
													<?php if($csv_export['file_encoding'] == $key) { ?>
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
                                                <option value=";"<?php if(isset($csv_export['csv_delimiter']) && $csv_export['csv_delimiter'] == ';') { ?> selected="selected"<?php } ?>> ; </option>
                                                <option value=","<?php if(isset($csv_export['csv_delimiter']) && $csv_export['csv_delimiter'] == ',') { ?> selected="selected"<?php } ?>> , </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label" data-prop_id="2"><?php echo $entry_languages; ?></label>
                                        <div class="col-sm-7">
                                            <select name="csv_export[language_id]" class="form-control">
												<?php foreach ($languages as $language) { ?>
													<?php if($csv_export['language_id'] == $language['language_id']) { ?>
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
										<label class="col-sm-5 control-label" data-prop_id="3"><?php echo $entry_store; ?></label>
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
                                        <label class="col-sm-5 control-label" data-prop_id="4"><?php echo $entry_manufacturer; ?></label>
                                        <div class="col-sm-7">
                                            <input type="text" name="input_manufacturer" value="" placeholder="" id="input_manufacturer" class="form-control" />
                                            <div id="manufacturer_list" class="well well-sm" style="height: 100px; overflow: auto;">
												<?php foreach ($csv_export['product_manufacturer'] as $manufacturer) { ?>
													<div id="manufacturer-id<?php echo $manufacturer['manufacturer_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $manufacturer['name']; ?>
														<input type="hidden" name="csv_export[product_manufacturer][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
													</div>
												<?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label" data-prop_id="32"><?php echo $entry_image_url; ?></label>
                                        <div class="col-sm-7">
                                            <select name="csv_export[image_url]" class="form-control">
												<?php if($csv_export['image_url'] == 1) { ?>
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
                                </div>
                            </div>
							<div class="row">
								<div class="col-sm-12">
									<hr />
									<div class="pull-right">
										<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_manufacturer_export').submit();"><?php echo $button_export; ?></button>
									</div>
								</div>
							</div>
                        </form>
                    </div>
                    <div class="tab-pane" id="tab_import">
                        <form action="<?php echo $action_import; ?>" method="post" id="form_manufacturer_import" enctype="multipart/form-data" class="form-horizontal">
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
												<option value=";"<?php if(isset($csv_import['csv_delimiter']) && $csv_import['csv_delimiter'] == ';') { ?> selected="selected"<?php } ?>> ; </option>
                                                <option value=","<?php if(isset($csv_import['csv_delimiter']) && $csv_import['csv_delimiter'] == ',') { ?> selected="selected"<?php } ?>> , </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label" data-prop_id="11"><?php echo $entry_languages; ?></label>
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
                                        <label class="col-sm-5 control-label" data-prop_id="5"><?php echo $entry_import_mode; ?></label>
                                        <div class="col-sm-7">
                                            <select name="csv_import[mode]" class="form-control">
                                                <option value="2" <?php if($csv_import['mode'] == 2) { ?> selected="selected" <?php } ?>><?php echo $text_import_mode_update; ?></option>
                                                <option value="3" <?php if($csv_import['mode'] == 3) { ?> selected="selected" <?php } ?>><?php echo $text_import_mode_insert; ?></option>
                                                <option value="1" <?php if($csv_import['mode'] == 1) { ?> selected="selected" <?php } ?>><?php echo $text_import_mode_both; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label" data-prop_id="6"><?php echo $entry_key_field; ?></label>
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
										<input type="hidden" name="csv_import[to_store]" value="0">
										<label class="col-sm-5 control-label" data-prop_id="10"><?php echo $entry_store; ?></label>
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
                                        <label class="col-sm-5 control-label" data-prop_id="7"><?php echo $entry_import_id; ?></label>
                                        <div class="col-sm-7">
                                            <select name="csv_import[import_id]" class="form-control">
												<?php if(isset($csv_import['import_id']) && $csv_import['import_id'] == 1) { ?>
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
                                        <label class="col-sm-5 control-label"><?php echo $entry_sort_order; ?></label>
                                        <div class="col-sm-7">
                                            <input class="form-control text-right" type="text" name="csv_import[sort_order]" value="<?php echo $csv_import['sort_order']; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label" data-prop_id="8"><?php echo $entry_import_img_download; ?></label>
                                        <div class="col-sm-7">
                                            <select name="csv_import[image_download]" class="form-control">
                                                <?php if(isset($csv_import['image_download']) && $csv_import['image_download'] == 1) { ?>
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
												<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_manufacturer_import').submit();"><?php echo $button_import; ?></button>
											</div>
                                        </div>
									</div>
                                </div>
                            </div>
                        </form>
                    </div>
					<div id="tab_macros" class="tab-pane fade">
						<form action="<?php echo $action; ?>" method="post" id="form_macros" enctype="multipart/form-data" class="form-horizontal">
							<input type="hidden" name="form_macros_status" value="1" />
							<table id="table-macros-field" class="table table-hover table-striped">
								<thead>
									<tr>
										<th><?php echo $entry_table; ?></th>
										<th><?php echo $entry_field_name; ?></th>
										<th><?php echo $entry_csv_name; ?></th>
										<th><?php echo $entry_caption; ?></th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<?php $field_row = 0; ?>
								<?php if(isset($manufacturer_macros) && !empty($manufacturer_macros)) { ?>
								<tbody id="tbody_data">
								<?php foreach ($manufacturer_macros as $fields) { ?>
									<tr id="macros-field-row<?php echo $field_row; ?>">
										<td class="text-left"><input type="hidden" name="manufacturer_macros[<?php echo $field_row; ?>][tbl_name]" value="<?php echo $fields['tbl_name']; ?>"/><?php echo $fields['tbl_name']; ?></td>
										<td class="text-left"><input type="hidden" name="manufacturer_macros[<?php echo $field_row; ?>][field_name]" value="<?php echo $fields['field_name']; ?>"/><?php echo $fields['field_name']; ?></td>
										<td class="text-left"><input type="hidden" name="manufacturer_macros[<?php echo $field_row; ?>][csv_name]" value="<?php echo $fields['csv_name']; ?>"/><?php echo $fields['csv_name']; ?></td>
										<td class="text-left"><input type="hidden" name="manufacturer_macros[<?php echo $field_row; ?>][csv_caption]" value="<?php echo $fields['csv_caption']; ?>"/><?php echo $fields['csv_caption']; ?></td>
										<td class="text-center"><a onclick="$('#macros-field-row<?php echo $field_row; ?>').remove();" data-toggle="tooltip" title="" class="btn btn-danger btn-sm" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></a></td>
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
										<select id="tbl_name" onchange="loadMacrosField();" class="form-control">
											<?php foreach ($db_table as $table_name) { ?>
											<option value="<?php echo $text_db_prefix; ?><?php echo $table_name; ?>"><?php echo $text_db_prefix; ?><?php echo $table_name; ?></option>
											<?php } ?>
										</select>
										</td>
										<td>
											<select id="tbl_field_name" onchange="selectMacrostFieldName();" class="form-control"></select>
										</td>
										<td>
											<input type="text" id="csv_name" value="" class="form-control" />
										</td>
										<td>
											<input type="text" id="csv_caption" value="" class="form-control" />
										</td>
										<td class="text-center">
											<a onclick="addMacrosRow();" data-toggle="tooltip" title="" class="btn btn-success btn-sm" data-original-title="<?php echo $button_insert; ?>"><i class="fa fa-plus-circle"></i></a>
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
<script type="text/javascript">
	var prop_descr = new Array();
    <?php if(isset($prop_descr)) echo $prop_descr; ?>
	//	Manufacturer
	$('input[name=\'input_manufacturer\']').autocomplete({
		'source': function (request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function (json) {
					response($.map(json, function (item) {return {label: item['name'],value: item['manufacturer_id']}}))
				}
			});
		},
		'select': function (item) {
			$('input[name=\'manufacturer_name\']').val('');
			$('#manufacturer_id' + item['value']).remove();
			$('#manufacturer_list').append('<div id="manufacturer_id' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="csv_export[product_manufacturer][]" value="' + item['value'] + '" /></div>');
		}
	});
	$('.csvprice_pro_container #manufacturer_list').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
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
	// Macros
	var field_row = <?php if(isset($field_row)){ echo $field_row;} else {echo '0';} ?>;
	function deleteMacrosRow(obj) {
	   $('#macros-field-row' + obj).remove();
	}
	function addMacrosRow() {
		var tbl_name = $('#table-macros-field #tbl_name option:selected').val();
		var field_name = $('#table-macros-field #tbl_field_name option:selected').val();
		var csv_name = $('#table-macros-field #csv_name').val();
		var csv_caption = $('#table-macros-field #csv_caption').val();

		if (field_name == -1 || csv_name == '' || csv_caption == '') {
			return;
		}
		var html = '<tr id="macros-field-row' + field_row + '">';
		html += '    <td class="text-left"><input type="hidden" name="manufacturer_macros[' + field_row + '][tbl_name]" value="' + tbl_name + '" size="1" />' + tbl_name + '</td>';
		html += '    <td class="text-left"><input type="hidden" name="manufacturer_macros[' + field_row + '][field_name]" value="' + field_name + '" size="1" />' + field_name + '</td>';
		html += '    <td class="text-left"><input type="hidden" name="manufacturer_macros[' + field_row + '][csv_name]" value="' + csv_name + '" size="1" />' + csv_name + '</td>';
		html += '    <td class="text-left"><input type="hidden" name="manufacturer_macros[' + field_row + '][csv_caption]" value="' + csv_caption + '" size="1" />' + csv_caption + '</td>';
		html += '    <td class="text-center"><a onclick="deleteMacrosRow(' + field_row + ');" data-toggle="tooltip" class="btn btn-danger btn-sm" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></a></td>';
		html += '  </tr>';
		if (field_row < 1) {
			$('#table-macros-field #tbody_foot').html('');
		}
		$('#table-macros-field #tbody_foot').append(html);
		resetFormMacros();
		$('#table-macros-field #tbl_name option').change();
		field_row++;
	}
	function resetFormMacros(){
	    $('#csv_name').val('');
	    $('#csv_caption').val('');
	}
	function loadMacrosField() {
		var table_index = {};
		<?php $i = 0; ?>
		<?php foreach ($db_table as $table_name) { ?>
		table_index['<?php echo $text_db_prefix; ?><?php echo $table_name; ?>'] = <?php echo $i; ?>;
		<?php $i++; ?>
		<?php } ?>
		var table = <?php echo $db_table_fields; ?>;
		var data = table[table_index[$('#tbl_name option:selected').val()]];
	    
	    $('#tbl_field_name').get(0).options.length = 0;
		$('#tbl_field_name').get(0).options[0] = new Option(" <?php echo $text_none; ?> ", "-1");
		$.each(data, function(index,text) {
			$("#tbl_field_name").get(0).options[$("#tbl_field_name").get(0).options.length] = new Option(text,text);
		});
	    resetFormMacros();
	    return false;
	}
	function selectMacrostFieldName(){
	   $('#csv_name').val('');
	   $('#csv_caption').val('');
	   
	   var field_name = $('#tbl_field_name option:selected').val();
	   if( field_name != -1 ) {
	       field_name = '_CUSTOM_' + field_name.toString().toUpperCase() + '_';
	       $('#csv_name').val(field_name);
	       $('#csv_caption').focus();
	   }
	}
	// Document Ready
	jQuery(document).ready(function ($) {
		$('.csvprice_pro_container .nav-tabs li.active').removeClass('active');
		$('.csvprice_pro_container .tab-content div.active').removeClass('active');
		$("#link_<?php echo $tab_selected; ?>").parent().addClass('active');
		$("#<?php echo $tab_selected; ?>").removeClass('fade').addClass('active');
		loadMacrosField();
		initFieldsSet();
		$('#tbl_field_set input[type=checkbox]').change(function () {
			setBackgroundColor(this);
		});
	});
</script>
</div>
<?php echo $app_footer; ?>
<?php echo $footer; ?>