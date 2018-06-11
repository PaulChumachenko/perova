<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-xds-coloring-custon-blocks" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-xds-coloring-custon-blocks" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
    
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
					
					<table id="items" class="table table-bordered table-hover">
						<thead>
							<tr>
								<td><?php echo $entry_image; ?></td>
								<td><?php echo $entry_title; ?></td>
								<td><?php echo $entry_description; ?></td>
								<td><?php echo $entry_link; ?></td>
								<td><?php echo $entry_sort_order; ?></td>
								<td></td>
							</tr>
						</thead>
						<tbody>
						<?php $item_row = 0; ?>
						<?php foreach ($cust_blocks_items as $cust_blocks_item) { ?>
							<tr id="item-row<?php echo $item_row; ?>">
								<td class="text-left">
									<a href="" id="thumb-image<?php echo $item_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $cust_blocks_item['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>"  /></a><input type="hidden" name="cust_blocks_item[<?php echo $item_row; ?>][image]" value="<?php echo $cust_blocks_item['image']; ?>" id="input-image<?php echo $item_row; ?>" />
								</td>
								<td class="text-left">
									<?php foreach ($languages as $language) { ?>
									<div class="input-group pull-left">
										<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
										<input class="form-control" type="text" name="cust_blocks_item[<?php echo $item_row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo $cust_blocks_item['title'][$language['language_id']]; ?>" />
									</div>
									<?php } ?>
								</td>
								<td class="text-left">
									<?php foreach ($languages as $language) { ?>
									<div class="input-group pull-left">
										<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
										<input class="form-control" type="text" name="cust_blocks_item[<?php echo $item_row; ?>][description][<?php echo $language['language_id']; ?>]" value="<?php echo $cust_blocks_item['description'][$language['language_id']]; ?>" />
									</div>
									<?php } ?>
								</td>
								<td class="text-left">
									<?php foreach ($languages as $language) { ?>
									<div class="input-group pull-left">
										<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
											<input class="form-control" type="text" name="cust_blocks_item[<?php echo $item_row; ?>][link][<?php echo $language['language_id']; ?>]" value="<?php echo $cust_blocks_item['link'][$language['language_id']]; ?>" />
									</div>
									<?php } ?>
								</td>
								<td class="text-left">
									<input  class="form-control" type="text" name="cust_blocks_item[<?php echo $item_row; ?>][sort]" value="<?php echo $cust_blocks_item['sort']; ?>" />
								</td>
								<td class="text-right">
									<a class="btn btn-danger" onclick="$('#item-row<?php echo $item_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a>
								</td>
							</tr>
						<?php $item_row++; ?>
						<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="5"></td>
								<td class="text-right"><a class="btn btn-primary" onclick="addItem();" data-toggle="tooltip" title="<?php echo $button_add; ?>"><i class="fa fa-plus-circle"></i></a></td>
							</tr>
						</tfoot>
					</table>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var item_row = <?php echo $item_row; ?>;

function addItem() {
  html  = '<tr id="item-row' + item_row + '">';
	html += '<td class="text-left"><a href="" id="thumb-image' + item_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>"  /></a><input type="hidden" name="cust_blocks_item[' + item_row + '][image]" value="" id="input-image' + item_row + '" /></td>';
  html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control" type="text" name="cust_blocks_item[' + item_row + '][title][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control" type="text" name="cust_blocks_item[' + item_row + '][description][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group pull-left"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>';
	html += '<input class="form-control"  type="text" name="cust_blocks_item[' + item_row + '][link][<?php echo $language['language_id']; ?>]" value="" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td class="text-left"><input class="form-control" type="text" name="cust_blocks_item[' + item_row + '][sort]" size="1" value="" /></td>';
	html += '<td class="text-right"><a class="btn btn-danger" onclick="$(\'#item-row' + item_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></a></td>';
	html += '</tr>'; 
	
	$('#items tbody').append(html);;
	
	item_row++;
}
</script>
<?php echo $footer; ?>