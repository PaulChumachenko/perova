<?php
// Heading
$_['heading_title'] = 'Manufacturer';
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
$_['text_import_mode_insert'] = 'Insert only';
$_['text_import_mode_update'] = 'Update only';
$_['text_success_import'] = 'Data import has been completed!<br />Processed <b>%s</b> strings totally!<br /><br /> Updated: <b>%s</b><br />Added: <b>%s</b></b><br />Missing: <b>%s</b>';

// Tabs
$_['tab_export'] = 'Export';
$_['tab_import'] = 'Import';
$_['tab_macros'] = 'Macros';

// button
$_['button_export'] = 'Export DATA';
$_['button_import'] = 'Import DATA';
$_['button_insert'] = 'Add';
$_['button_remove'] = 'Delete';

// Entry
$_['entry_table'] = 'Table';
$_['entry_field_name'] = 'Field name';
$_['entry_csv_name'] = 'CSV title';
$_['entry_caption'] = 'Name';
$_['entry_manufacturer'] = 'Manufacturers';
$_['entry_file_encoding'] = 'File encoding';
$_['entry_languages'] = 'Localisation';
$_['entry_csv_delimiter'] = 'CSV field delimiter';
$_['entry_csv_text_delimiter'] = 'Text delimiter';
$_['entry_store'] = 'Stores';
$_['entry_import_mode'] = 'Import mode';
$_['entry_key_field'] = 'Key field for update';
$_['entry_sort_order'] = 'Sort order';
$_['entry_status'] = 'Status';
$_['entry_import_file'] = 'Data import from file';
$_['entry_import_img_download'] = 'Download images by URL';
$_['entry_import_id'] = 'Import ID manufacturer from file';
$_['entry_image_url'] = 'Image as URL';

// Helper
$_['help_export_file_encoding'] = 'File encoding. Your store uses UTF-8';
$_['help_export_csv_delimiter'] = 'CSV field delimiter';
$_['help_import_mode'] = 'In Update only, Update and Insert modes key field is searched in database, in case of matching Update mode is chosen';
$_['help_import_key_field'] = 'Key field for the matching position search in the database, update is performed in case of position is detected';
$_['help_import_img_download'] = 'Field with image _IMAGE_ should have the URL download link';
$_['help_export_all_manufacturer'] = 'If no manufacturers selected - exports all manufacturers';
$_['help_import_id'] = '_ID_ field will be imported on the new position creation of manufacturer as manufacturer_id. manufacturer_id can be imported only if the same id is not in base.';

$_['prop_descr'] = ' 
prop_descr[0]="<p><b>CSV file encoding</b></p><p>Your store uses UTF-8. Use the UTF-8 encoding to avoid problems with import and export.</p>";
prop_descr[1]="<p><b>CSV field delimiter</b></p><p>Character to be used as a delimiter for separate columns (values) in the CSV file.</p>";
prop_descr[2]="<p><b>Localisation</b></p><p>In what language the data will be exported, for example the manufacturer name or description</p>";
prop_descr[3]="<p><b>Stores</b></p><p>If the stores are not chosen, the manufacturer of all stores will be exported (by default).</p>";
prop_descr[4]="<p><b>Manufacturers</b></p><p>If the manufacturer are not chosen, the manufacturers will be exported (by default).</p>";
prop_descr[5]="<p><b>Import mode</p></b><p><i>Update only</i> - in this mode the key field of a manufacturer is searched, in case of matching the manufacturer will be updated from the CSV file.</p><p><i>Insert only</i> - in this mode all categories will be added as the new from the CSV file independently there are these categories in database or not.</p><p><i>Update and Insert</i> - in this mode the key field of a product is searched, in case of matching the category will be updated from the CSV file, if the coincidence is not found then the category will be added as a new.</p>";
prop_descr[6]="<p><b>Key field</b></p><p>Key field, on which the coincidence of category in database is looked for, is used in the modes <i>Update only</i>, <i>Update and Insert</i>.</p>";
prop_descr[7]="<p><b>Import ID manufacturer from file</b></p><p>Adding a new manufacturer the _ID_ field will be imported as manufacturer_id, import is carried out providing that there is not such manufacturer_id in database and the number does not exceed the maximum permitted value for manufacturer_id.</p>";
prop_descr[8]="<p><b>Download images by URL</b></p><p>Download the images by url in the _IMAGE_ and _IMAGES_ fields.</p><p>URL should be as:<br /><br /> http://www.example.com/dir/image_name.jpg</p>";
prop_descr[9]="<p><b>Text Delimiter</b></p><p>CSV file that also includes quotes as delimiters.<br />E.g.: \"Smith\",\"Pete\",\"Canada\", quote all text cells.</p>";
prop_descr[10]="<p><b>Stores</b></p><p>If the stores are not chosen, the manufacturer will be imported to all stores (by default).</p>";
prop_descr[11]="<p><b>Localisation</b></p><p>In what language the data will be imported, for example the manufacturer name or description</p>";
prop_descr[32]="<p><b>Image as URL</b></p><p>Exports value _IMAGE_ and _IMAGES_ as URL.</p><p>E.g.: http://www.example.com/dir/image_name.jpg</p>";
';

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
$_['_SEO_KEYWORD_'] = 'SEO Keyword';
$_['_META_H1_'] = 'HTML Tag H1';
$_['_META_TITLE_'] = 'Meta Tag Title';
$_['_META_KEYWORDS_'] = 'Meta Tag Keywords';
$_['_META_DESCRIPTION_'] = 'Meta Tag Description';
$_['_DESCRIPTION_'] = 'Description';
$_['_IMAGE_'] = 'Image';
$_['_SORT_ORDER_'] = 'Sort Order';
$_['_STATUS_'] = 'Status';
$_['_STORE_ID_'] = 'Store ID';
$_['_URL_'] = 'URL Manufacturer';
