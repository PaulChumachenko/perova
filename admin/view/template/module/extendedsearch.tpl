<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<span style="padding-right:20px;">
		<a href="https://www.opencart.com/index.php?route=marketplace/extension&filter_member=AlexDW" target="_blank" data-toggle="tooltip" title="Get more extensions" class="btn btn-info"><i class="fa fa-download"></i> More extensions</a></span>
        <button type="submit" form="form-latest" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>ExtendedSearch 1.03en</h1>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-latest" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-4">
              <select name="extendedsearch_status" id="input-status" class="form-control">
                <?php if ($extendedsearch_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
			</div>
			</div>

          <fieldset>
          <legend><?php echo $text_extsearch; ?></legend>
          <div class="form-group">
			<label class="col-sm-2 control-label" for="input-model"><?php echo $entry_model; ?></label>
			<div class="col-sm-4">
              <select name="extendedsearch_model" id="input-model" class="form-control">
                <?php if ($extendedsearch_model) { ?>
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
			<label class="col-sm-2 control-label" for="input-sku"><?php echo $entry_sku; ?></label>
			<div class="col-sm-4">
              <select name="extendedsearch_sku" id="input-sku" class="form-control">
                <?php if ($extendedsearch_sku) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
			<label class="col-sm-2 control-label" for="input-upc"><?php echo $entry_upc; ?></label>
			<div class="col-sm-4">
              <select name="extendedsearch_upc" id="input-upc" class="form-control">
                <?php if ($extendedsearch_upc) { ?>
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
			<label class="col-sm-2 control-label" for="input-ean"><?php echo $entry_ean; ?></label>
			<div class="col-sm-4">
              <select name="extendedsearch_ean" id="input-ean" class="form-control">
                <?php if ($extendedsearch_ean) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
			<label class="col-sm-2 control-label" for="input-jan"><?php echo $entry_jan; ?></label>
			<div class="col-sm-4">
              <select name="extendedsearch_jan" id="input-jan" class="form-control">
                <?php if ($extendedsearch_jan) { ?>
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
			<label class="col-sm-2 control-label" for="input-isbn"><?php echo $entry_isbn; ?></label>
			<div class="col-sm-4">
              <select name="extendedsearch_isbn" id="input-isbn" class="form-control">
                <?php if ($extendedsearch_isbn) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
			<label class="col-sm-2 control-label" for="input-mpn"><?php echo $entry_mpn; ?></label>
			<div class="col-sm-4">
              <select name="extendedsearch_mpn" id="input-mpn" class="form-control">
                <?php if ($extendedsearch_mpn) { ?>
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
			<label class="col-sm-2 control-label" for="input-location"><?php echo $entry_location; ?></label>
			<div class="col-sm-4">
              <select name="extendedsearch_location" id="input-location" class="form-control">
                <?php if ($extendedsearch_location) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
			<label class="col-sm-2 control-label" for="input-attr"><?php echo $entry_attr; ?></label>
			<div class="col-sm-4">
              <select name="extendedsearch_attr" id="input-attr" class="form-control">
                <?php if ($extendedsearch_attr) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          </fieldset>

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>