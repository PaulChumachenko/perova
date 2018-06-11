<?php
// Heading
$_['heading_title'] = 'Category';
$_['heading_title_normal'] = 'CSV Price Pro import/export OC2';

// Text global
$_['text_module'] = 'Modules';
$_['text_extension'] = 'Extensions';
$_['text_default'] = ' <b>(Default)</b>';
$_['text_yes'] = 'Yes';
$_['text_no'] = 'No';
$_['text_enabled'] = 'Enabled';
$_['text_disabled'] = 'Disabled';
$_['text_select_all'] = 'Select All';
$_['text_unselect_all'] = 'Unselect All';
$_['text_select'] = 'Select';
$_['text_show_all'] = 'Show all';
$_['text_hide_all'] = 'Hide unchecked';
$_['text_all'] = 'All';
$_['text_no_results'] = 'No results!';
$_['text_none'] = ' --- None --- ';
$_['text_as'] = 'As %s';
$_['text_confirm_delete'] = 'Deleting cannot be cancelled! Are you sure that you want to do it?';

// Text
$_['text_success_macros'] = 'Settings of macros has been updated successfully!';
$_['text_import_mode_both'] = 'Update and Insert';
$_['text_import_mode_delete'] = '* Delete forever *';
$_['text_import_mode_insert'] = 'Insert only';
$_['text_import_mode_update'] = 'Update only';
$_['text_success_import'] = 'Data import has been completed!<br />Processed <b>%s</b> strings totally!<br /><br /> Updated: <b>%s</b><br />Added: <b>%s</b></b><br />Missing: <b>%s</b>';

// Tabs
$_['tab_export'] = 'Export';
$_['tab_import'] = 'Import';
$_['tab_macros'] = 'Macros';
$_['tab_setting'] = 'Settings';

// Button
$_['button_export'] = 'Export DATA';
$_['button_import'] = 'Import DATA';
$_['button_insert'] = 'Add';
$_['button_remove'] = 'Remove';
$_['button_save'] = 'Save';

// Entry
$_['entry_file_encoding'] = 'File encoding';
$_['entry_languages'] = 'Localisation';
$_['entry_category'] = 'Category';
$_['entry_category_delimiter'] = 'Category delimiter';
$_['entry_csv_delimiter'] = 'CSV field delimiter';
$_['entry_csv_text_delimiter'] = 'Text delimiter';
$_['entry_store'] = 'Stores';
$_['entry_category_parent'] = 'Parent categories';
$_['entry_table'] = 'Table';
$_['entry_field_name'] = 'Field name';
$_['entry_csv_name'] = 'CSV title';
$_['entry_caption'] = 'Name';
$_['entry_import_mode'] = 'Import mode';
$_['entry_key_field'] = 'Key field for update';
//$_['entry_key_field'] = 'Identify by field';
$_['entry_sort_order'] = 'Sort order';
$_['entry_status'] = 'Status';
$_['entry_import_category_disable'] = 'Disable all categories before import';
$_['entry_import_file'] = 'Data import from file';
$_['entry_import_img_download'] = 'Download images by url';
$_['entry_image_url'] = 'Image as URL';

// Error
$_['error_permission'] = 'Warning: You do not have permission to modify CSV Price Pro import/export!';
$_['error_directory_not_available'] = 'The working directory <b>csvprice_pro</b> is not writable or does not exist!';
$_['error_move_uploaded_file'] = 'File copying error!';
$_['error_uploaded_file'] = 'File is not uploaded!';
$_['error_copy_uploaded_file'] = 'Failed to copy file!';
$_['error_export_empty_rows'] = 'No data for export!';

// Fields
$_['_ID_'] = 'ID';
$_['_NAME_'] = 'Name';
$_['_FILTERS_'] = 'Filters';
$_['_SEO_KEYWORD_'] = 'SEO Keyword';
$_['_META_H1_'] = 'HTML Tag H1';
$_['_META_TITLE_'] = 'Meta Tag Title';
$_['_META_KEYWORDS_'] = 'Meta Tag Keywords';
$_['_META_DESCRIPTION_'] = 'Meta Tag Description';
$_['_DESCRIPTION_'] = 'Description';
$_['_IMAGE_'] = 'Image';
$_['_SORT_ORDER_'] = 'Sort Order';
$_['_STATUS_'] = 'Status';
$_['_COLUMN_'] = 'Columns';
$_['_TOP_'] = 'Top menu';
$_['_PARENT_ID_'] = 'Parent category ID';
$_['_STORE_ID_'] = 'Stores ID';
$_['_URL_'] = 'URL';

$_['prop_descr'] = ' 
prop_descr[0]="<p><b>CSV file encoding</b></p><p>Your store uses UTF-8. Use the UTF-8 encoding to avoid problems with import and export.</p>";
prop_descr[1]="<p><b>CSV field delimiter</b></p><p>Character to be used as a delimiter for separate columns (values) in the CSV file.</p>";
prop_descr[2]="<p><b>Localisation</b></p><p>In what language the data will be exported, for example the product name or description</p>";
prop_descr[3]="<p><b>Category</b></p><p>If the category is not chosen, all categories will be exported (by default).</p>";
prop_descr[4]="<p><b>Category delimiter</b></p><p>Delimiter between the category names (category nesting), for example:<br /><br /><i>Main category</i>|<i>Subcategory</i></p><p>If the product has several categories, then the categories will be written in the form of a list of category names, each category will be written on a new line (multiline field), for example: <br /><br /><i>Main category 1</i>|<i>Subcategory 2</i><br /><i>Main category 3</i>|<i>Subcategory 4</i>.</p>";
prop_descr[5]="<p><b>Parent categories</b></p><p>All parent categories will be included in the category name, for example:<br /><br /><i>Main category</i>|<i>Subcategory|Category</i>.</p>";
prop_descr[6]="<p><b>Localisation</b></p><p>In what language the data will be exported, for example the product name or description</p>";
prop_descr[7]="<p><b>Import mode</p></b><p><i>Update only</i> - in this mode the key field of a category is searched, in case of matching the category will be updated from the CSV file.</p><p><i>Insert only</i> - in this mode all categories will be added as the new from the CSV file independently there are these categories in database or not.</p><p><i>Update and Insert</i> - in this mode the key field of a product is searched, in case of matching the category will be updated from the CSV file, if the coincidence is not found then the category will be added as a new.</p>";
prop_descr[8]="<p><b>Key field</b></p><p>Key field, on which the coincidence of category in database is looked for, is used in the modes <i>Update only</i>, <i>Update and Insert</i>.</p>";
prop_descr[9]="<p><b>_CATEGORY_field delimiter</b></p><p>Delimiter between the category names (category nesting), for example:<br /><br /><i>Main category</i>|<i>Subcategory</i>.</p>";
prop_descr[10]="<p><b>Download images by URL</b></p><p>Download the images by url in the _IMAGE_ and _IMAGES_ fields.</p><p>URL should be as:<br /><br /> http://www.example.com/dir/image_name.jpg</p>";
prop_descr[11]="<p><b>Text Delimiter</b></p><p>CSV file that also includes quotes as delimiters.<br />E.g.: \"Smith\",\"Pete\",\"Canada\", quote all text cells.</p>";
prop_descr[12]="<p><b>Stores</b></p><p>If the stores are not chosen, the categories of all stores will be exported (by default).</p>";
prop_descr[13]="<p><b>Stores</b></p><p>If the stores are not chosen, the categories will be imported to all stores (by default).</p>";
prop_descr[32]="<p><b>Image as URL</b></p><p>Exports value _IMAGE_ and _IMAGES_ as URL.</p><p>E.g.: http://www.example.com/dir/image_name.jpg</p>";
';