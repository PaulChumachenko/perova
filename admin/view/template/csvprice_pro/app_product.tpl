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
        <div class="csv-progress-info">
			<div id="progress-import" class="progress progress-striped active">
				<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
		</div>
		<?php echo $app_header; ?>
        <div class="panel panel-default">
            <div class="panel-body">
				<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_setting" data-toggle="tab" id="link_tab_setting"><?php echo $tab_setting; ?></a></li>
				<li><a href="#tab_fields" data-toggle="tab" id="link_tab_fields"><?php echo $tab_fields; ?></a></li>
				<li><a href="#tab_export" data-toggle="tab" id="link_tab_export"><?php echo $tab_export; ?></a></li>
				<li><a href="#tab_import" data-toggle="tab" id="link_tab_import"><?php echo $tab_import; ?></a></li>
				<li><a href="#tab_macros" data-toggle="tab" id="link_tab_macros"><?php echo $tab_macros; ?></a></li>
			</ul>
			<div class="tab-content">
			<div id="tab_setting" class="tab-pane active">
				<form action="<?php echo $action; ?>" method="post" id="form_product_setting" enctype="multipart/form-data" class="form-horizontal">
					<div class="row">
						<div class="col-sm-6">
							<div class="alert alert-info"><i class="fa fa-info-circle"></i>
								<?php echo $text_default_product_setting; ?>
							</div>
							<input type="hidden" name="form_product_setting_status" id="setting_default" value="1" />
							<div class="form-group">
								<input type="hidden" name="csv_setting[to_store]" value="0">
								<label class="col-sm-5 control-label"><?php echo $entry_store; ?></label>
								<div class="col-sm-7">
									<div class="well well-sm" style="height: 100px; overflow: auto;">
										<?php foreach ($stores as $store) { ?>
											<div class="checkbox">
												<label>
													<?php if (isset($csv_setting['to_store']) && is_array($csv_setting['to_store']) && in_array($store['store_id'], $csv_setting['to_store'])) { ?>
													<input type="checkbox" name="csv_setting[to_store][]" value="<?php echo $store['store_id']; ?>" checked="checked" />
													<?php echo $store['name']; ?>
													<?php } else { ?>
													<input type="checkbox" name="csv_setting[to_store][]" value="<?php echo $store['store_id']; ?>" />
													<?php echo $store['name']; ?>
													<?php } ?>
												</label>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_tax_class; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[tax_class_id]" class="form-control">
										<option value="0"><?php echo $text_none; ?></option>
										<?php foreach ($tax_classes as $tax_class) { ?>
										<?php if ($tax_class['tax_class_id'] == $csv_setting['tax_class_id']) { ?>
										<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="0"><?php echo $entry_minimum; ?></label>
								<div class="col-sm-7">
									<input class="form-control text-right" type="text" name="csv_setting[minimum]" value="<?php echo $csv_setting['minimum']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_subtract; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[subtract]" class="form-control">
										<?php if ($csv_setting['subtract'] == 1) { ?>
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
								<label class="col-sm-5 control-label" data-prop_id="1"><?php echo $entry_stock_status; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[stock_status_id]" class="form-control">
										<?php foreach ($stock_statuses as $stock_status) { ?>
										<?php if ($stock_status['stock_status_id'] == $csv_setting['stock_status_id']) { ?>
										<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_shipping; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[shipping]" class="form-control">
										<?php if ($csv_setting['shipping'] == 1) { ?>
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
								<label class="col-sm-5 control-label"><?php echo $entry_length_class; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[length_class_id]" class="form-control">
										<?php foreach ($length_classes as $length_class) { ?>
										<?php if ($length_class['length_class_id'] == $csv_setting['length_class_id']) { ?>
										<option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_weight_class; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[weight_class_id]" class="form-control">
										<?php foreach ($weight_classes as $weight_class) { ?>
										<?php if ($weight_class['weight_class_id'] == $csv_setting['weight_class_id']) { ?>
										<option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
										<?php } else { ?>
										<option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_layout; ?></label>
								<div class="col-sm-7">
									<?php foreach ($stores as $store) { ?>
										<p class="form-control-static"><?php echo $store['name']; ?></p>
										<select name="csv_setting[layout][<?php echo $store['store_id']; ?>]" class="form-control">
											<?php foreach ($layouts as $layout) { ?>
											<?php if ($layout['layout_id'] == $csv_setting['layout'][$store['store_id']]) { ?>
											<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_sort_order; ?></label>
								<div class="col-sm-7">
									<input class="form-control text-right" type="text" name="csv_setting[sort_order]" value="<?php echo $csv_setting['sort_order']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_status; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[status]" class="form-control">
										<?php if ($csv_setting['status'] == 1) { ?>
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
							<div class="alert alert-info"><i class="fa fa-info-circle"></i>
								<?php echo $text_default_options_setting; ?>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_option_type; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[option_type]" class="form-control">
										<optgroup label="Choose">
											<option value="select"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'select') {echo ' selected="selected"';} ?>>Select</option>
											<option value="radio"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'radio') {echo ' selected="selected"';} ?>>Radio</option>
											<option value="checkbox"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'checkbox') {echo ' selected="selected"';} ?>>Checkbox</option>
											<option value="image"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'image') {echo ' selected="selected"';} ?>>Image</option>
										</optgroup>
										<optgroup label="Input">
											<option value="text"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'text') {echo ' selected="selected"';} ?>>Text</option>
											<option value="textarea"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'textarea') {echo ' selected="selected"';} ?>>Textarea</option>
										</optgroup>
										<optgroup label="File">
											<option value="file"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'file') {echo ' selected="selected"';} ?>>File</option>
										</optgroup>
										<optgroup label="Date">
											<option value="date"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'date') {echo ' selected="selected"';} ?>>Date</option>
											<option value="time"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'time') {echo ' selected="selected"';} ?>>Time</option>
											<option value="datetime"<?php if (isset($csv_setting['option_type']) && $csv_setting['option_type'] == 'datetime') {echo ' selected="selected"';} ?>>Date &amp; Time</option>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_option_required; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[option_required]" class="form-control">
										<?php if (isset($csv_setting['option_required']) && $csv_setting['option_required'] == 1) { ?>
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
								<label class="col-sm-5 control-label"><?php echo $entry_option_quantity; ?></label>
								<div class="col-sm-7">
									<input class="form-control text-right" type="text" name="csv_setting[option_quantity]" value="<?php if(!empty($csv_setting['option_quantity'])) echo $csv_setting['option_quantity']; else echo '100'; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_option_subtract_stock; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[option_subtract_stock]" class="form-control">
										<?php if (isset($csv_setting['option_subtract_stock']) && $csv_setting['option_subtract_stock'] == 1) { ?>
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
								<label class="col-sm-5 control-label"><?php echo $entry_option_price_prefix; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[option_price_prefix]" class="form-control">
										<?php if (isset($csv_setting['option_price_prefix']) && $csv_setting['option_price_prefix'] == '-') { ?>
										<option value="-" selected="selected">&nbsp;&nbsp;-&nbsp;&nbsp;</option>
										<option value="+">&nbsp;&nbsp;+&nbsp;&nbsp;</option>
										<?php } else { ?>
										<option value="-">&nbsp;&nbsp;-&nbsp;&nbsp;</option>
										<option value="+" selected="selected">&nbsp;&nbsp;+&nbsp;&nbsp;</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_option_points_prefix; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[option_points_prefix]" class="form-control">
										<?php if (isset($csv_setting['option_points_prefix']) && $csv_setting['option_points_prefix'] == '-') { ?>
										<option value="-" selected="selected">&nbsp;&nbsp;-&nbsp;&nbsp;</option>
										<option value="+">&nbsp;&nbsp;+&nbsp;&nbsp;</option>
										<?php } else { ?>
										<option value="-">&nbsp;&nbsp;-&nbsp;&nbsp;</option>
										<option value="+" selected="selected">&nbsp;&nbsp;+&nbsp;&nbsp;</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_option_points_default; ?></label>
								<div class="col-sm-7">
									<input class="form-control text-right" type="text" name="csv_setting[option_points_default]" value="<?php if (isset($csv_setting['option_points_default'])) echo $csv_setting['option_points_default']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_option_price_prefix; ?></label>
								<div class="col-sm-7">
									<select name="csv_setting[option_weight_prefix]" class="form-control">
										<?php if (isset($csv_setting['option_weight_prefix']) && $csv_setting['option_weight_prefix'] == '-') { ?>
										<option value="-" selected="selected">&nbsp;&nbsp;-&nbsp;&nbsp;</option>
										<option value="+">&nbsp;&nbsp;+&nbsp;&nbsp;</option>
										<?php } else { ?>
										<option value="-">&nbsp;&nbsp;-&nbsp;&nbsp;</option>
										<option value="+" selected="selected">&nbsp;&nbsp;+&nbsp;&nbsp;</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_option_weight_default; ?></label>
								<div class="col-sm-7">
									<input class="form-control text-right" type="text" name="csv_setting[option_weight_default]" value="<?php if(isset($csv_setting['option_weight_default'])) echo $csv_setting['option_weight_default']; ?>" />
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<hr />
							<div class="pull-right">
								<button type="button" style="min-width:120px" class="btn btn-primary" onclick="$('#form_product_setting').submit();"><?php echo $button_save; ?></button>
								<button type="button" style="min-width:120px" class="btn btn-secondary" onclick="$('#setting_default').val(0);$('#form_product_setting').submit();"><?php echo $button_default ?></button>
							</div>
						</div>
					</div>
				</form>
			</div><!-- END tab_setting -->
			
			<div id="tab_fields" class="tab-pane fade">
				<form action="<?php echo $action; ?>" method="post" id="form_product_fields" enctype="multipart/form-data" class="form-horizontal">
					<div class="alert alert-info"><i class="fa fa-info-circle"></i>
						<?php if(isset($text_help_csv_column_names)) echo $text_help_csv_column_names; ?>
					</div>
					<input type="hidden" name="form_product_field_status" id="product_field_default" value="1" />
					<table id="table-product-field" class="table table-hover table-striped">
							<thead>
								<tr>
									<th><?php echo $entry_field_name; ?></th>
									<th><?php echo $entry_csv_name; ?></th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<?php $product_field_row = 0; ?>
							<?php if (!empty($csv_product_field)) { ?>
							<tbody id="tbody_data">
								<?php foreach ($csv_product_field as $field_key => $filed_name) {?>
								<tr id="product-field-row<?php echo $product_field_row; ?>">
									<td class="text-left"><input type="hidden" name="csv_product_field[<?php echo $field_key; ?>]" value="<?php echo $filed_name; ?>" /><?php echo $field_key; ?></td>
									<td class="text-left"><?php echo $filed_name; ?></td>
									<td class="text-center"><a onclick="$('#product-field-row<?php echo $product_field_row; ?>').remove();" data-toggle="tooltip" title="" class="btn btn-danger btn-sm" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></a></td>
								</tr>
								<?php $product_field_row++; ?>
								<?php } ?>
							</tbody>
							<tbody id="tbody_foot"></tbody>
							<?php } else { ?>
							<tbody id="tbody_foot">
								<tr>
									<td colspan="3">
										<?php echo $text_no_results; ?>
									</td>
								</tr>
							</tbody>
							<?php } ?>
							<tbody id="tbody_form" class="csvpricepro_tbody-form">
								<tr>
									<td>
										<select id="product_field_select" class="form-control">
										<?php foreach ($product_field as $field_caption) { ?>
											<option value="<?php echo $field_caption; ?>"><?php echo $field_caption; ?></option>
										<?php } ?>
										</select>
									</td>
									<td>
										<input type="text" id="product_field_input" value="" class="form-control" />
									</td>
									<td class="text-center">
										<a onclick="addProductFieldRow();" data-toggle="tooltip" title="" class="btn btn-success btn-sm" data-original-title="<?php echo $button_insert; ?>"><i class="fa fa-plus-circle"></i></a>
									</td>
								</tr>
							</tbody>
						</table>
				</form>
				<div class="row">
					<div class="col-sm-12">
						<hr />
						<div class="pull-right">
							<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_product_fields').submit();"><?php echo $button_save; ?></button>
						</div>
					</div>
				</div>
			</div><!-- END tab_fields -->

			<div id="tab_export" class="tab-pane fade">
				<div class="row">
					<div class="col-sm-8 col-md-offset-2">
						<div id="wrap_profile_export" class="well well-sm">
							<form id="form_profile_export" class="form-horizontal">
								<div class="form-group form-group-sm">
									<label class="col-sm-4 control-label" data-prop_id="33"><?php echo $text_profile_load; ?></label>
									<div class="col-sm-8">
										<div class="input-group">
											<select name="profile_export_select" id="profile_export_select" class="form-control input-sm"></select>
											<span class="input-group-btn">
												<a onclick="loadProfile('profile_export');" data-toggle="tooltip" title="" class="btn btn-info btn-sm" data-original-title="<?php echo $button_load; ?>"><i class="fa fa-refresh"></i></a>
												<a onclick="updateProfile('profile_export');" data-toggle="tooltip" title="" class="btn btn-primary btn-sm" data-original-title="<?php echo $button_save; ?>"><i class="fa fa-save"></i></a>
												<a onclick="deleteProfile('profile_export');" data-toggle="tooltip" title="" class="btn btn-warning btn-sm" data-original-title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group form-group-sm">
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
				<form action="<?php echo $action_export; ?>" method="post" id="form_product_export" enctype="multipart/form-data" class="form-horizontal">
				<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="2"><?php echo $entry_file_encoding; ?></label>
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
								<label class="col-sm-5 control-label" data-prop_id="3"><?php echo $entry_csv_delimiter; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[csv_delimiter]" class="form-control">
										<option value=";"<?php if (isset($csv_export['csv_delimiter']) && $csv_export['csv_delimiter'] == ';') { ?> selected="selected"<?php } ?>> ; </option>
										<option value=","<?php if (isset($csv_export['csv_delimiter']) && $csv_export['csv_delimiter'] == ',') { ?> selected="selected"<?php } ?>> , </option>
										<option value=","<?php if (isset($csv_export['csv_delimiter']) && $csv_export['csv_delimiter'] == '^') { ?> selected="selected"<?php } ?>> ^ </option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="28"><?php echo $entry_languages; ?></label>
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
								<label class="col-sm-5 control-label" data-prop_id="27"><?php echo $entry_store; ?></label>
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
								<label class="col-sm-5 control-label" data-prop_id="4"><?php echo $entry_category; ?></label>
								<div class="col-sm-7">
									<input type="text" id="export_input_category" name="export_input_category" data-profile_type="export" class="csv-input-category form-control" />
									<div id="export_category_list" class="container_fa-minus-circle well well-sm" style="height: 100px; overflow: auto;">
										<?php foreach ($csv_export['from_category'] as $category) { ?>
											<div id="category-id<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
												<input type="hidden" name="csv_export[from_category][]" value="<?php echo $category['category_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div><!-- END Select From Category -->
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="5"><?php echo $entry_export_category; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[export_category]" class="form-control">
										<option value="0"<?php if (!isset($csv_export['export_category']) || $csv_export['export_category'] == 0) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
										<option value="1"<?php if (isset($csv_export['export_category']) && $csv_export['export_category'] == 1) { ?> selected="selected"<?php } ?>><?php echo $text_as . '_CATEGORY_ID_'; ?></option>
										<option value="2"<?php if (isset($csv_export['export_category']) && $csv_export['export_category'] == 2) { ?> selected="selected"<?php } ?>><?php echo $text_as . '_CATEGORY_'; ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="6"><?php echo $entry_category_delimiter; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[delimiter_category]" class="form-control">
										<option value="|"<?php if ($csv_export['delimiter_category'] == '|') { ?> selected="selected" <?php } ?>> | </option>
										<option value="/"<?php if ($csv_export['delimiter_category'] == '/') { ?> selected="selected" <?php } ?>> / </option>
										<option value=">"<?php if ($csv_export['delimiter_category'] == '>') { ?> selected="selected" <?php } ?>> / </option>
										<option value=","<?php if ($csv_export['delimiter_category'] == ',') { ?> selected="selected" <?php } ?>> , </option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="10"><?php echo $entry_category_parent; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[category_parent]" class="form-control">
										<option value="1"<?php if (!isset($csv_export['category_parent']) || $csv_export['category_parent'] == 1) { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
										<option value="0"<?php if (isset($csv_export['category_parent']) && $csv_export['category_parent'] == 0) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="7"><?php echo $entry_manufacturer; ?></label>
								<div class="col-sm-7">
									
									<input type="text" id="export_input_manufacturer" name="export_input_manufacturer" value="" data-profile_type="export" class="csv-input-manufacturer form-control" />
									<div id="export_manufacturer_list" class="container_fa-minus-circle well well-sm" style="height: 100px; overflow: auto;">
										<?php foreach ($csv_export['from_manufacturer'] as $manufacturer) { ?>
											<div id="manufacturer-id<?php echo $manufacturer['manufacturer_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $manufacturer['name']; ?>
												<input type="hidden" name="csv_export[from_manufacturer][]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_export_related; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[export_related]" class="form-control">
										<option value="0"<?php if (!isset($csv_export['export_related']) || $csv_export['export_related'] == 0) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
										<?php foreach($csv_export_key_related as $key => $name) { ?>
											<?php if ($csv_export['export_related'] != 0 && $csv_export['export_related'] == $key) { ?>
											<option value="<?php echo $key; ?>" selected="selected"><?php echo $text_as . $name; ?></option>
											<?php } else { ?>
											<option value="<?php echo $key; ?>"><?php echo $text_as . $name; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="32"><?php echo $entry_image_url; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[image_url]" class="form-control">
										<option value="1"<?php if (isset($csv_export['image_url']) && $csv_export['image_url'] == 1) { ?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
										<option value="0"<?php if (!isset($csv_export['image_url']) || $csv_export['image_url'] == 0) { ?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="8"><?php echo $entry_export_limit; ?></label>
								<div class="col-sm-7">
									<div class="row">
									<div class="col-sm-6">
									<input type="text" class="form-control text-right" name="csv_export[limit_start]" value="<?php echo $csv_export['limit_start']; ?>">
									</div>
									<div class="col-sm-6">
									<input type="text" class="form-control text-right" name="csv_export[limit_end]" value="<?php echo $csv_export['limit_end']; ?>">
									</div>
									</div>
								</div>
							</div>

							<!-- BEGIN FIlter -->
							<div class="form-group">
								<label class="col-sm-5 control-label" for="w_product_filter"><?php echo $entry_product_filter; ?></label>
								<div class="col-sm-7">
									<select name="csv_export[product_filter]" id="w_product_filter" onchange="showProductFilter();" class="form-control">
										<?php if (isset($csv_export['product_filter']) && $csv_export['product_filter'] == 1) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div id="wrap_product_filter" class="well well-sm">
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_filter_name; ?></label>
									<div class="col-sm-7">
										<input type="text" name="csv_export[filter_name]" class="form-control" value="<?php if (isset($csv_export['filter_name'])) { ?><?php echo $csv_export['filter_name']; ?><?php } ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_filter_sku; ?></label>
									<div class="col-sm-7">
										<input type="text" name="csv_export[filter_sku]" class="form-control" value="<?php if (isset($csv_export['filter_sku'])) { ?><?php echo $csv_export['filter_sku']; ?><?php } ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_filter_location; ?></label>
									<div class="col-sm-7">
										<input type="text" name="csv_export[filter_location]" class="form-control" value="<?php if (isset($csv_export['filter_location'])) { ?><?php echo $csv_export['filter_location']; ?><?php } ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_filter_price; ?></label>
									<div class="col-sm-7">
										<div class="row">
										<div class="col-sm-6">
										<select name="csv_export[filter_price_prefix]" class="form-control">
											<option value="1"<?php if (isset($csv_export['filter_price_prefix']) && $csv_export['filter_price_prefix'] == 1) { ?> selected="selected"<?php } ?>>&nbsp;&nbsp;=&nbsp;&nbsp;</option>
											<option value="1"<?php if (isset($csv_export['filter_price_prefix']) && $csv_export['filter_price_prefix'] == 2) { ?> selected="selected"<?php } ?>>&nbsp;&nbsp;&gt;=&nbsp;&nbsp;</option>
											<option value="1"<?php if (isset($csv_export['filter_price_prefix']) && $csv_export['filter_price_prefix'] == 3) { ?> selected="selected"<?php } ?>>&nbsp;&nbsp;&lt;=&nbsp;&nbsp;</option>
											<option value="1"<?php if (isset($csv_export['filter_price_prefix']) && $csv_export['filter_price_prefix'] == 4) { ?> selected="selected"<?php } ?>>&nbsp;&nbsp;&lt;&gt;&nbsp;&nbsp;</option>
										</select>
										</div>
										<div class="col-sm-6">
											<input type="text" class="form-control text-right" name="csv_export[filter_price]" value="<?php if (isset($csv_export['filter_price'])) { ?><?php echo $csv_export['filter_price']; ?><?php } ?>" />
										</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_filter_price_range; ?></label>
									<div class="col-sm-7">
										<div class="row">
										<div class="col-sm-6">
										<input type="text" class="form-control text-right" name="csv_export[filter_price_start]" value="<?php if (isset($csv_export['filter_price_start'])) { ?><?php echo $csv_export['filter_price_start']; ?><?php } ?>" />
										</div>
										<div class="col-sm-6">
										<input type="text" class="form-control text-right" name="csv_export[filter_price_end]" value="<?php if (isset($csv_export['filter_price_end'])) { ?><?php echo $csv_export['filter_price_end']; ?><?php } ?>" />
										</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_filter_quantity; ?></label>
									<div class="col-sm-7">
										<div class="row">
											<div class="col-sm-6">
											<select name="csv_export[filter_quantity_prefix]" class="form-control">
												<option value="1"<?php if (isset($csv_export['filter_quantity_prefix']) && $csv_export['filter_quantity_prefix'] == 1) { ?> selected="selected"<?php } ?>>&nbsp;&nbsp;=&nbsp;&nbsp;</option>
												<option value="1"<?php if (isset($csv_export['filter_quantity_prefix']) && $csv_export['filter_quantity_prefix'] == 2) { ?> selected="selected"<?php } ?>>&nbsp;&nbsp;&gt;=&nbsp;&nbsp;</option>
												<option value="1"<?php if (isset($csv_export['filter_quantity_prefix']) && $csv_export['filter_quantity_prefix'] == 3) { ?> selected="selected"<?php } ?>>&nbsp;&nbsp;&lt;=&nbsp;&nbsp;</option>
												<option value="1"<?php if (isset($csv_export['filter_quantity_prefix']) && $csv_export['filter_quantity_prefix'] == 4) { ?> selected="selected"<?php } ?>>&nbsp;&nbsp;&lt;&gt;&nbsp;&nbsp;</option>

											</select>
											</div>
											<div class="col-sm-6">
												<input type="text" class="form-control text-right" name="csv_export[filter_quantity]" value="<?php if (isset($csv_export['filter_price'])) { ?><?php echo $csv_export['filter_quantity']; ?><?php } ?>" />
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_stock_status; ?></label>
									<div class="col-sm-7">
										<select name="csv_export[filter_stock_status]" class="form-control">
											<option value="0" selected="selected">&nbsp;</option>
											<?php foreach ($stock_statuses as $stock_status) { ?>
											<option value="<?php echo $stock_status['stock_status_id']; ?>"<?php if (isset($csv_export['filter_stock_status']) && $csv_export['filter_stock_status'] == $stock_status['stock_status_id']) { ?> selected="selected"<?php } ?>><?php echo $stock_status['name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_filter_status; ?></label>
									<div class="col-sm-7">
										<select name="csv_export[filter_status]" class="form-control">
											<option value="3"<?php if( isset($csv_export['filter_status']) && $csv_export['filter_status'] == 3 ){echo ' selected="selected"';}?>>&nbsp;</option>
											<option value="1"<?php if( isset($csv_export['filter_status']) && $csv_export['filter_status'] == 1 ){echo ' selected="selected"';}?>><?php echo $text_enabled; ?></option>
											<option value="0"<?php if( isset($csv_export['filter_status']) && $csv_export['filter_status'] == 0 ){echo ' selected="selected"';}?>><?php echo $text_disabled; ?></option>
										</select>
									</div>
								</div>
							</div>
							<!-- END Filter -->
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
				</form>
				<div class="row">
					<div class="col-sm-12">
						<hr />
						<div class="pull-right">
							<button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_product_export').submit();"><?php echo $button_export; ?></button>
						</div>
					</div>
				</div>
			</div><!-- END tab_export -->

			<div id="tab_import" class="tab-pane fade">
				<div class="row">
					<div class="col-sm-8 col-md-offset-2">
						<div id="wrap_profile_export" class="well well-sm">
							<form id="form_profile_import" class="form-horizontal">
								<div class="form-group form-group-sm">
									<label class="col-sm-4 control-label" data-prop_id="33"><?php echo $text_profile_load; ?></label>
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
								<div class="form-group form-group-sm">
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
				<form action="<?php echo $action_import; ?>" method="post" id="form_product_import" enctype="multipart/form-data" class="form-horizontal">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="2"><?php echo $entry_file_encoding; ?></label>
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
								<label class="col-sm-5 control-label" data-prop_id="3"><?php echo $entry_csv_delimiter; ?></label>
								<div class="col-sm-7">
									<select name="csv_import[csv_delimiter]" class="form-control">
										<option value=";"<?php if ($csv_import['csv_delimiter'] == ';') { ?> selected="selected"<?php } ?>> ; </option>
										<option value=","<?php if ($csv_import['csv_delimiter'] == ',') { ?> selected="selected"<?php } ?>> , </option>
										<option value="^"<?php if ($csv_import['csv_delimiter'] == '^') { ?> selected="selected"<?php } ?>> ^ </option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="9"><?php echo $entry_languages; ?></label>
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
								<label class="col-sm-5 control-label" data-prop_id="11"><?php echo $entry_import_mode; ?></label>
								<div class="col-sm-7">
									<select name="csv_import[mode]" class="form-control">
										<option value="2" <?php if ( isset($csv_import['mode']) && $csv_import['mode'] == 2 ) echo 'selected'; ?>> <?php echo $text_import_mode_update; ?></option>
										<option value="3" <?php if ( isset($csv_import['mode']) && $csv_import['mode'] == 3 ) echo 'selected'; ?>> <?php echo $text_import_mode_insert; ?></option>
										<option value="1" <?php if ( isset($csv_import['mode']) && $csv_import['mode'] == 1 ) echo 'selected'; ?>> <?php echo $text_import_mode_both; ?></option>
										<option value="4" <?php if ( isset($csv_import['mode']) && $csv_import['mode'] == 4 ) echo 'selected'; ?>> <?php echo $text_import_mode_supplement; ?></option>
										<option value="10"><?php echo $text_import_mode_delete; ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="12"><?php echo $entry_key_field; ?></label>
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
								<label class="col-sm-5 control-label"><?php echo $entry_import_manufacturer; ?> <?php echo $entry_key_field; ?></label>
								<div class="col-sm-7">
									<input type="text" name="key_field_manufacturer_input" placeholder="<?php echo $entry_import_manufacturer; ?>" value="<?php if(isset($csv_import['key_field_manufacturer_name'])) echo $csv_import['key_field_manufacturer_name']; ?>" id="key_field_manufacturer_input" class="form-control" />
									<input type="hidden" name="csv_import[key_field_manufacturer]" id="key_field_manufacturer_id" value="<?php if(isset($csv_import['key_field_manufacturer'])) echo $csv_import['key_field_manufacturer']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="13"><?php echo $entry_import_id; ?></label>
								<div class="col-sm-7">
									<select name="csv_import[import_id]" class="form-control">
										<?php if (isset($csv_import['import_id']) && $csv_import['import_id'] == 1) { ?>
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
								<label class="col-sm-5 control-label" data-prop_id="29"><?php echo $entry_import_delimiter_category; ?></label>
								<div class="col-sm-7">
									<select name="csv_import[delimiter_category]" class="form-control">
										<option value="|"<?php if (isset($csv_import['delimiter_category']) && $csv_import['delimiter_category'] == '|') { ?> selected="selected" <?php } ?>>&nbsp;&nbsp;|&nbsp;&nbsp;</option>
										<option value="/"<?php if (isset($csv_import['delimiter_category']) && $csv_import['delimiter_category'] == '/') { ?> selected="selected" <?php } ?>>&nbsp;&nbsp;/&nbsp;&nbsp;</option>
										<option value=">"<?php if (isset($csv_import['delimiter_category']) && $csv_import['delimiter_category'] == '>') { ?> selected="selected" <?php } ?>>&nbsp;&nbsp;&gt;&nbsp;&nbsp;</option>
										<option value=","<?php if (isset($csv_import['delimiter_category']) && $csv_import['delimiter_category'] == ',') { ?> selected="selected" <?php } ?>>&nbsp;&nbsp;,&nbsp;&nbsp;</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="14"><?php echo $entry_import_fill_category; ?></label>
								<div class="col-sm-7">
									<select name="csv_import[fill_category]" class="form-control">
										<?php if (isset($csv_import['fill_category']) && $csv_import['fill_category'] == 1) { ?>
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
								<label class="col-sm-5 control-label" data-prop_id="15"><?php echo $entry_import_category_top; ?></label>
								<div class="col-sm-7">
									<select name="csv_import[top]" class="form-control">
										<?php if (isset($csv_import['top']) && $csv_import['top'] == 1) { ?>
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
								<label class="col-sm-5 control-label" data-prop_id="16"><?php echo $entry_import_category_column; ?></label>
								<div class="col-sm-7">
									<input class="form-control text-right" type="text" name="csv_import[column]" value="<?php echo !empty($csv_import['column']) ? $csv_import['column'] : 0 ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="17"><?php echo $entry_import_img_download; ?></label>
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
								<label class="col-sm-5 control-label"><?php echo $entry_import_calc_mode; ?></label>
								<div class="col-sm-7">
									<select name="csv_import[calc_mode]" class="form-control">
										<option value="0" <?php if ( $csv_import['calc_mode'] == 0 ) echo 'selected'; ?>><?php echo $text_import_calc_mode_off; ?></option>
										<option value="1" <?php if ( $csv_import['calc_mode'] == 1 ) echo 'selected'; ?>><?php echo $text_import_calc_mode_multiply; ?></option>
										<option value="2" <?php if ( $csv_import['calc_mode'] == 2 ) echo 'selected'; ?>><?php echo $text_import_calc_mode_divide; ?></option>
										<option value="3" <?php if ( $csv_import['calc_mode'] == 3 ) echo 'selected'; ?>><?php echo $text_import_calc_mode_pluse; ?></option>
										<option value="4" <?php if ( $csv_import['calc_mode'] == 4 ) echo 'selected'; ?>><?php echo $text_import_calc_mode_minus; ?></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="18"><?php echo $entry_import_calc_value; ?></label>
								<div class="col-sm-7">
									<div class="row">
										<div class="col-sm-6"><input class="form-control text-right" type="text" name="csv_import[calc_value][]" value="<?php if(isset($csv_import['calc_value'][0])) echo $csv_import['calc_value'][0]; ?>" /></div>
										<div class="col-sm-6"><input class="form-control text-right" type="text" name="csv_import[calc_value][]" value="<?php if(isset($csv_import['calc_value'][1])) echo $csv_import['calc_value'][1]; ?>" /></div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="19"><?php echo $entry_import_empty_field; ?></label>
								<div class="col-sm-7">
									<select name="csv_import[empty_field]" class="form-control">
										<?php if (isset($csv_import['empty_field']) && $csv_import['empty_field'] == 1) { ?>
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
								<label class="col-sm-5 control-label"><?php echo $entry_import_product_disable; ?></label>
								<div class="col-sm-7">
									<select name="csv_import[product_disable]" class="form-control">
										<?php if (isset($csv_import['product_disable']) && $csv_import['product_disable'] == 1) { ?>
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
								<label class="col-sm-5 control-label" data-prop_id="30"><?php echo $entry_import_quantity_reset; ?></label>
								<div class="col-sm-7">
									<select name="csv_import[quantity_reset]" class="form-control">
										<?php if (isset($csv_import['quantity_reset']) && $csv_import['quantity_reset'] == 1) { ?>
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
								<label class="col-sm-5 control-label" data-prop_id="20"><?php echo $entry_import_iter_limit; ?></label>
								<div class="col-sm-7">
									<input class="form-control text-right" type="text" name="csv_import[iter_limit]" value="<?php if(isset($csv_import['iter_limit'])) { echo $csv_import['iter_limit']; } else { echo '0';} ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label"><?php echo $entry_import_file; ?></label>
								<div class="col-sm-7">
									<input type="file" name="import" />
								</div>
							</div>
						</div>
						<!-- END col-sm-6 -->
						<div class="col-sm-6">
							
							<div class="form-group">
								<input type="hidden" name="csv_import[skip_import_store]" value="0">
								<label class="col-sm-5 control-label" data-prop_id="21"><?php echo $entry_store; ?></label>
								<div class="col-sm-7">
									<label class="csvpricepro_label_checkbox"><input class="im_checkbox_skip" type="checkbox" value="1" name="csv_import[skip_import_store]"<?php if (isset($csv_import['skip_import_store']) && $csv_import['skip_import_store'] == 1) { ?> checked="checked"<?php } ?> /> <?php echo $text_import_skip; ?></label>
									<div class="well well-sm" style="height: 100px; overflow: auto;">
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
								<input type="hidden" value="0" name="csv_import[skip_manufacturer]" />
								<label class="col-sm-5 control-label" data-prop_id="24"><?php echo $entry_import_manufacturer; ?></label>
								<div class="col-sm-7">
									<label class="csvpricepro_label_checkbox"><input class="im_checkbox_skip" type="checkbox" value="1" name="csv_import[skip_manufacturer]"<?php if (isset($csv_import['skip_manufacturer']) && $csv_import['skip_manufacturer'] == 1) { ?> checked="checked"<?php } ?> /> <?php echo $text_import_skip; ?></label>
									<input type="text" name="to_manufacturer" value="<?php if(isset($csv_import['to_manufacturer_name'])) echo $csv_import['to_manufacturer_name']; ?>" placeholder="" id="to_manufacturer" class="form-control" />
									<input type="hidden" name="csv_import[to_manufacturer]" id="to_manufacturer_id" value="<?php if(isset($csv_import['to_manufacturer'])) echo $csv_import['to_manufacturer']; ?>" />
								</div>
							</div>
							<?php if ($core_type['MAIN_CATEGORY'] == 1) { ?>
							<div class="form-group">
								<input type="hidden" value="0" name="csv_import[skip_main_category]">
								<label class="col-sm-5 control-label" data-prop_id="22"><?php echo $entry_import_main_category; ?></label>
								<div class="col-sm-7">
									<label class="csvpricepro_label_checkbox"><input class="im_checkbox_skip" type="checkbox" value="1" name="csv_import[skip_main_category]"<?php if (isset($csv_import['skip_main_category']) && $csv_import['skip_main_category'] == 1) { ?> checked="checked"<?php } ?> /> <?php echo $text_import_skip; ?></label>
									<input type="text" name="import_main_category" value="<?php if (isset($csv_import['main_category']))echo $csv_import['main_category']; ?>" placeholder="" id="import_main_category" class="form-control" />
									<input type="hidden" name="csv_import[main_category_id]" id="import_main_category_id" value="<?php if (isset($csv_import['main_category_id']))echo $csv_import['main_category_id']; ?>" />
								</div>
							</div>
							<?php } ?>
							<div class="form-group">
								<input type="hidden" value="0" name="csv_import[skip_category]">
								<label class="col-sm-5 control-label" data-prop_id="23"><?php echo $entry_import_category; ?></label>
								<div class="col-sm-7">
									<label class="csvpricepro_label_checkbox"><input class="im_checkbox_skip" type="checkbox" value="1" name="csv_import[skip_category]"<?php if (isset($csv_import['skip_category']) && $csv_import['skip_category'] == 1) { ?> checked="checked"<?php } ?> /> <?php echo $text_import_skip; ?></label>
									<input type="hidden" value="0" name="csv_import[product_category]" />
									<input type="text" id="import_input_category" name="import_input_category" data-profile_type="import" class="csv-input-category form-control" />
									<div id="import_category_list" class="container_fa-minus-circle well well-sm" style="height: 100px; overflow: auto;">
										<?php foreach ($csv_import['to_category'] as $category) { ?>
											<div id="category-id<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
												<input type="hidden" name="csv_import[to_category][]" value="<?php echo $category['category_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="25"><?php echo $entry_import_image_path; ?></label>
								<div class="col-sm-7">
									<input type="text" name="csv_import[img_path]" class="form-control" value="<?php if(isset($csv_import['img_path'])) echo $csv_import['img_path']; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-5 control-label" data-prop_id="26"><?php echo $entry_exclude_if; ?></label>
								<div class="col-sm-7">
									<input type="hidden" value="0" name="csv_import[exclude_filter]" />
									<label class="csvpricepro_label_checkbox"><input type="checkbox" name="csv_import[exclude_filter]" value="1" <?php if (isset($csv_import['exclude_filter']) && $csv_import['exclude_filter'] == 1) { ?> checked="checked"<?php } ?> /><?php echo $entry_import_exclude_filter; ?></label>
								</div>
							</div>
							<div class="well well-sm">
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_import_exclude_filter_name; ?></label>
									<div class="col-sm-7">
										<input type="text" name="csv_import[exclude_filter_name]" class="form-control" value="<?php if (isset($csv_import['exclude_filter_name'])) echo $csv_import['exclude_filter_name']; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_import_exclude_filter_desc; ?></label>
									<div class="col-sm-7">
										<input type="text" name="csv_import[exclude_filter_desc]" class="form-control" value="<?php if (isset($csv_import['exclude_filter_desc'])) echo $csv_import['exclude_filter_desc']; ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-5 control-label"><?php echo $entry_import_exclude_filter_attr; ?></label>
									<div class="col-sm-7">
										<input type="text" name="csv_import[exclude_filter_attr]" class="form-control" value="<?php if (isset($csv_import['exclude_filter_attr'])) echo $csv_import['exclude_filter_attr']; ?>" />
									</div>
								</div>
							</div>
						</div>
						<!-- END col-sm-6 -->
					</div>
					<div class="row">
						<div class="col-sm-12">
							<hr />
							<div class="pull-right">
                                <button type="button" class="btn btn-primary" style="min-width:120px" onclick="flushProducts()">Очистить товары</button>
                                <button type="button" class="btn btn-primary" style="min-width:120px" onclick="$('#form_product_import').submit();"><?php echo $button_import; ?></button>
							</div>
						</div>
					</div>
				</form>
			</div><!-- END tab_import -->
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
									<?php if (!empty($product_macros)) { ?>
									<tbody id="tbody_data">
									<?php foreach ($product_macros as $fields) { ?>
										<tr id="macros-field-row<?php echo $field_row; ?>">
											<td class="text-left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][tbl_name]" value="<?php echo $fields['tbl_name']; ?>"/><?php echo $fields['tbl_name']; ?></td>
											<td class="text-left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][field_name]" value="<?php echo $fields['field_name']; ?>"/><?php echo $fields['field_name']; ?></td>
											<td class="text-left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][csv_name]" value="<?php echo $fields['csv_name']; ?>"/><?php echo $fields['csv_name']; ?></td>
											<td class="text-left"><input type="hidden" name="category_macros[<?php echo $field_row; ?>][csv_caption]" value="<?php echo $fields['csv_caption']; ?>"/><?php echo $fields['csv_caption']; ?></td>
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
												<?php foreach ($db_table as $table_name) {  ?>
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
				</div><!-- END tab_macros -->
			</div><!-- END tab-content -->
		</div>
	</div>
</div>
<script type="text/javascript">
	var prop_descr = new Array();
    <?php if(isset($prop_descr)) echo $prop_descr; ?>
	
	var h, n, i, e;
	//	Categories autocomplete
	$('.csv-input-category').autocomplete({
		'source': function (request, response) {
			e = $(this);
			h = e.attr('data-profile_type');
			
			if (h == 'export') {
				i = 'csv_export';
				n = 'csv_export[from_category][]';
			} else {
				i = 'csv_import';
				n = 'csv_import[to_category][]';
			}
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
			e.val('');
			$('#' + i + '_id' + item['value']).remove();
			$('#' + h + "_category_list").append('<div id="' + i + '_id' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="' + n + '" value="' + item['value'] + '" /></div>');
		}
	});
	// Manufacturer Key
	$('input[name=\'key_field_manufacturer_input\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					json.unshift({
						manufacturer_id: 0,
						name: '<?php echo $text_none; ?>'
					});

					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['manufacturer_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('#key_field_manufacturer_input').val(item['label']);
			$('#key_field_manufacturer_id').val(item['value']);
		}
	});
	//	Manufacturer autocomplete
	$('.csv-input-manufacturer').autocomplete({
		'source': function (request, response) {
			e = $(this);
			h = e.attr('data-profile_type');
			
			if (h == 'export') {
				i = 'csv_export';
				n = 'csv_export[from_manufacturer][]';
			} else {
				i = 'csv_import';
				n = 'csv_import[to_manufacturer][]';
			}
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function (json) {
					response($.map(json, function (item) {
						return {
							label: item['name'],
							value: item['manufacturer_id']
						}
					}));
				}
			});
		},
		'select': function (item) {
			e.val('');
			$('#' + i + '_id' + item['value']).remove();
			$('#' + h + "_manufacturer_list").append('<div id="' + i + '_id' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="' + n + '" value="' + item['value'] + '" /></div>');
		}
	});
	$('.container_fa-minus-circle').delegate('.fa-minus-circle', 'click', function () {
		$(this).parent().remove();
	});
	
	// Main category import
	$('input[name=\'import_main_category\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					json.unshift({
						category_id: 0,
						name: '<?php echo $text_none; ?>'
					});

					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('#import_main_category').val(item['label']);
			$('#import_main_category_id').val(item['value']);
		}
	});

	// Manufacturer import
	$('input[name=\'to_manufacturer\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					json.unshift({
						manufacturer_id: 0,
						name: '<?php echo $text_none; ?>'
					});

					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['manufacturer_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('#to_manufacturer').val(item['label']);
			$('#to_manufacturer_id').val(item['value']);
		}
	});
	// Macros
	var field_row = <?php if(isset($field_row)) echo $field_row; else echo '0'; ?>;
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
			html += '    <td class="text-left"><input type="hidden" name="product_macros[' + field_row + '][tbl_name]" value="' + tbl_name + '" size="1" />' + tbl_name + '</td>';
			html += '    <td class="text-left"><input type="hidden" name="product_macros[' + field_row + '][field_name]" value="' + field_name + '" size="1" />' + field_name + '</td>';
			html += '    <td class="text-left"><input type="hidden" name="product_macros[' + field_row + '][csv_name]" value="' + csv_name + '" size="1" />' + csv_name + '</td>';
			html += '    <td class="text-left"><input type="hidden" name="product_macros[' + field_row + '][csv_caption]" value="' + csv_caption + '" size="1" />' + csv_caption + '</td>';
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
	// CSV Custom Field
	var product_field_row = <?php if(isset($product_field_row)) echo $product_field_row; else echo '0'; ?>;
	function deleteProductFieldRow(obj) {
	   $('#product-field-row' + obj).remove();
	}
	function addProductFieldRow() {
		var field_key = $('#product_field_select option:selected').val();
		var field_name = $('#product_field_input').val();
		if( field_name == '' || $('input[name="csv_product_field[' + field_key + ']"]').length > 0) {
			return;
		}
		var html  = '<tr id="product-field-row' + product_field_row + '">';
		html += '    <td class="text-left"><input type="hidden" name="csv_product_field[' + field_key + ']" value="' + field_name + '" size="1" />' + field_key + '</td>';
		html += '    <td class="text-left">' + field_name + '</td>';
		html += '    <td class="text-center"><a onclick="$(\'#product-field-row' + product_field_row + '\').remove();" data-toggle="tooltip" title="" class="btn btn-danger btn-sm" data-original-title="<?php echo $button_remove; ?>"><i class="fa fa-minus-circle"></i></a>';
		html += '  </tr>';
		if (product_field_row < 1) {
			$('#table-product-field #tbody_foot').html('');
		}
		$('#table-product-field #tbody_foot').append(html);
		product_field_row++;
		$('#product_field_input').val('');
	}
	$('#product_field_select').change(function() {
		$('#product_field_input').val('');
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
	function showProductFilter() {
		if($('#w_product_filter').val() == 1) {
			$('#wrap_product_filter').show();
		} else {
			$('#wrap_product_filter').hide();
		}
	}
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
	function loadProfile(key) {
		var s = '#' + key + '_select';
		if($(s).get(0).options.length == 0) {
			return;
		}
		var url = '<?php echo $action; ?>&profile_id=' + $( '#' + key + '_select' + ' option:selected').val();
		url = url.replace( /\&amp;/g, '&' );
		window.location.href = url;
	
	}

	function createProfile(key) {
		var url = '<?php echo $action_add_profile; ?>';
		url = url.replace( /\&amp;/g, '&' );
		if($('#' + key + '_name').val() == '') return false;
		var data;
		
		if( key == 'profile_import' ){
			data = $("#form_product_import, #form_product_setting, #form_profile_import, #form_product_fields").serialize();
		} else if( key == 'profile_export' ){
			data = $("#form_product_export, #form_profile_export").serialize();
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
			data = $("#form_product_import, #form_product_setting, #form_profile_import, #form_product_fields").serialize();
		} else if( key == 'profile_export' ){
			data = $("#form_product_export, #form_profile_export").serialize();
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
				loadProfile(key);
			}
		});
		return false;
	}
	// Document Ready
	jQuery(document).ready(function ($) {
		$('.csvprice_pro_container .nav-tabs li.active').removeClass('active');
		$('.csvprice_pro_container .tab-content div.active').removeClass('active');
		$("#link_<?php echo $tab_selected; ?>").parent().addClass('active');
		$("#<?php echo $tab_selected; ?>").removeClass('fade').addClass('active');
		initFieldsSet();
		$('#tbl_field_set input[type=checkbox]').change(function () {
			setBackgroundColor(this);
		});
		loadMacrosField();
		showProductFilter();
		getProfile('profile_export', <?php echo (isset($profile_id)) ? $profile_id : 0; ?>);
		getProfile('profile_import', <?php echo (isset($profile_id)) ? $profile_id : 0; ?>);
	});
</script>
<script>
	jQuery(document).ready(function ($) {
		var s = <?php echo $processing_import; ?>;
		if(s) {
			$('.alert').remove();
			$('#progress-import').parent().hide();
			$('#progress-import .progress-bar').attr('aria-valuenow', 1);
			$('#progress-import .progress-bar').css('width', '1%');
			$('#progress-import').parent().after('<div class="alert alert-info alert-dismissible" style="color: #333"><i class="fa fa-check-circle"></i> <?php echo $text_begin_auto_import; ?> </div>');
			$('#progress-import').parent().show();
			_processing(0);
		} else {
			$('#progress-import').parent().hide();
		}
	});
function _processing(p, f = 0){
	$.ajax({
		type:'POST',
		url: 'index.php?route=csvprice_pro/app_product/processing_import&token=<?php echo $token; ?>&p=' + p + '&f=' + f,
       		data: {
			'progress': p
		},
		dataType:'json',
		success: function(json) {
			if(json.processed == 0 ) {
				$('.alert').remove();
				$('#progress-import').parent().hide();
				$('#progress-import').parent().after('<div class="alert alert-danger alert-dismissible" style="color: #333"><i class="fa fa-exclamation-circle"></i> ' + json.message + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				return false;
			}
			if(json.status == 100) {
				$('.alert').remove();
				$('#progress-import .progress-bar').css('width', '100%').attr('aria-valuenow', 100);
				$('#progress-import').parent().after('<div class="alert alert-success alert-dismissible" style="color: #333"><i class="fa fa-check-circle"></i> ' + json.message + '</div>');
				$('#progress-import').removeClass( "active" );
				//$('#progress-import .progress-bar span').text('100%');
			} else {
				$('.alert').remove();
				$('#progress-import .progress-bar').css('width', json.status + '%').attr('aria-valuenow', json.status);
				$('#progress-import').parent().after('<div class="alert alert-info alert-dismissible" style="color: #333"><i class="fa fa-check-circle"></i> ' + json.message + '</div>');
				_processing(json.status);
			}
        },
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
    function flushProducts(){
        var ask = window.confirm("Вы уверены, что хотите удалить все товары?");
        if (ask) {
            var url = new URL(window.location.href);
            url = "/admin/index.php?route=catalog/product/flushAll&token=" + url.searchParams.get("token");
            $.post(url, {}).then(function(response){
                var msg = response.result ? 'Данные успешно очищены' : 'Упс! Что-то пошло не так. Попробуйте еще раз';
                alert(msg);
            });
        }
    }
</script>
</div>
<?php echo $app_footer; ?>
<?php echo $footer; ?>