<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-seoh1" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-seoh1" class="form-horizontal">
		
		<h2><?php echo $header_1;?></h2>

		
		<div class="form-group">
            <label class="col-sm-3 control-label" for="sortslimits"><span data-toggle="tooltip"><?php echo $entry_sortslimits_default; ?></span></label>
            <div class="col-sm-9">
              <select name="sortslimits_default" id="sortslimits_default" class="form-control">
                <?php if ($sortslimits_default == 'p.sort_order') { ?>
                <option value="p.sort_order" selected="selected"><?php echo $sort_order ?></option>
                <?php } else { ?>
                <option value="p.sort_order"><?php echo $sort_order ?></option>
                <?php } ?>
                <?php if ($sortslimits_default == 'p.date_added') { ?>
                <option value="p.date_added" selected="selected"><?php echo $date_added ?></option>
                <?php } else { ?>
                <option value="p.date_added"><?php echo $date_added ?></option>
                <?php } ?>
				<?php if ($sortslimits_default == 'p.quantity') { ?>
                <option value="p.quantity" selected="selected"><?php echo $quantity; ?></option>
                <?php } else { ?>
                <option value="p.quantity"><?php echo $quantity; ?></option>
                <?php } ?>
				<?php if ($sortslimits_default == 'p.model') { ?>
                <option value="p.model" selected="selected"><?php echo $model; ?></option>
                <?php } else { ?>
                <option value="p.model"><?php echo $model; ?></option>
                <?php } ?>
				<?php if ($sortslimits_default == 'rating') { ?>
                <option value="rating" selected="selected"><?php echo $rating; ?></option>
                <?php } else { ?>
                <option value="rating"><?php echo $rating; ?></option>
                <?php } ?>
				<?php if ($sortslimits_default == 'p.price') { ?>
                <option value="p.price" selected="selected"><?php echo $price; ?></option>
                <?php } else { ?>
				<option value="p.price"><?php echo $price; ?></option>
				<?php } ?>
				<?php if ($sortslimits_default == 'pd.name') { ?>
                <option value="pd.name" selected="selected"><?php echo $name; ?></option>
                <?php } else { ?>
                <option value="pd.name"><?php echo $name; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
		<div class="form-group">
            <label class="col-sm-3 control-label" for="sortslimits_default2"><span data-toggle="tooltip"><?php echo $entry_sortslimits_default2; ?></span></label>
            <div class="col-sm-9">
              <select name="sortslimits_default2" id="sortslimits_default2" class="form-control">
                <?php if ($sortslimits_default2 == 'DESC') { ?>
                <option value="DESC" selected="selected"><?php echo $desc ?></option>
                <?php } else { ?>
                <option value="DESC"><?php echo $desc ?></option>
                <?php } ?>
				<?php if ($sortslimits_default2 == 'ASC') { ?>
                <option value="ASC" selected="selected"><?php echo $asc; ?></option>
                <?php } else { ?>
                <option value="ASC"><?php echo $asc; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
		<hr>
		
		<h2><?php echo $header_2;?></h2>		

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $sort_order; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_order_ASC) { ?>
                <input type="radio" name="sortslimits_order_ASC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_order_ASC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_order_ASC) { ?>
                <input type="radio" name="sortslimits_order_ASC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_order_ASC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>			

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $name.' ^'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_name_ASC) { ?>
                <input type="radio" name="sortslimits_name_ASC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_name_ASC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_name_ASC) { ?>
                <input type="radio" name="sortslimits_name_ASC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_name_ASC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>			

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $name.' v'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_name_DESC) { ?>
                <input type="radio" name="sortslimits_name_DESC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_name_DESC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_name_DESC) { ?>
                <input type="radio" name="sortslimits_name_DESC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_name_DESC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>			

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $price.' ^'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_price_ASC) { ?>
                <input type="radio" name="sortslimits_price_ASC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_price_ASC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_price_ASC) { ?>
                <input type="radio" name="sortslimits_price_ASC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_price_ASC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>			

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $price.' v'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_price_DESC) { ?>
                <input type="radio" name="sortslimits_price_DESC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_price_DESC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_price_DESC) { ?>
                <input type="radio" name="sortslimits_price_DESC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_price_DESC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>			

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $rating.' ^'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_rating_ASC) { ?>
                <input type="radio" name="sortslimits_rating_ASC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_rating_ASC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_rating_ASC) { ?>
                <input type="radio" name="sortslimits_rating_ASC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_rating_ASC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>		

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $rating.' v'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_rating_DESC) { ?>
                <input type="radio" name="sortslimits_rating_DESC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_rating_DESC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_rating_DESC) { ?>
                <input type="radio" name="sortslimits_rating_DESC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_rating_DESC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>	
	

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $model.' ^'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_model_ASC) { ?>
                <input type="radio" name="sortslimits_model_ASC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_model_ASC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_model_ASC) { ?>
                <input type="radio" name="sortslimits_model_ASC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_model_ASC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>	
	

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $model.' v'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_model_DESC) { ?>
                <input type="radio" name="sortslimits_model_DESC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_model_DESC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_model_DESC) { ?>
                <input type="radio" name="sortslimits_model_DESC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_model_DESC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>	


          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $quantity.' ^'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_quantity_ASC) { ?>
                <input type="radio" name="sortslimits_quantity_ASC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_quantity_ASC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_quantity_ASC) { ?>
                <input type="radio" name="sortslimits_quantity_ASC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_quantity_ASC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>	

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $quantity.' v'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_quantity_DESC) { ?>
                <input type="radio" name="sortslimits_quantity_DESC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_quantity_DESC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_quantity_DESC) { ?>
                <input type="radio" name="sortslimits_quantity_DESC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_quantity_DESC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $date_added.' ^'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_date_added_ASC) { ?>
                <input type="radio" name="sortslimits_date_added_ASC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_date_added_ASC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_date_added_ASC) { ?>
                <input type="radio" name="sortslimits_date_added_ASC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_date_added_ASC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>	

          <div class="form-group">
            <label class="col-sm-3 control-label"><span data-toggle="tooltip"><?php echo $date_added.' v'; ?></span></label>
            <div class="col-sm-9">
              <label class="radio-inline">
                <?php if ($sortslimits_date_added_DESC) { ?>
                <input type="radio" name="sortslimits_date_added_DESC" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_date_added_DESC" value="1" />
                <?php echo $text_yes; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$sortslimits_date_added_DESC) { ?>
                <input type="radio" name="sortslimits_date_added_DESC" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="sortslimits_date_added_DESC" value="0" />
                <?php echo $text_no; ?>
                <?php } ?>
              </label>
            </div>
          </div>	

        </form>
      </div>
    </div>
  </div>
</div>
<style>
label.control-label span:after {
	color: #000;
	content: none;
	margin-left: 4px;
}
.form-group {
  margin-bottom: 5px;
}
</style>
 <?php echo $footer; ?>