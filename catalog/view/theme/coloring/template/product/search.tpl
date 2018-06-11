<?php echo $header; ?>
<div class="container">


    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>





      <?php if ($products) { ?>
			<div class="well well-sm">
				<div class="row">
					<div class="col-lg-3 col-md-4 col-sm-3 col-xs-4">
						<div class="btn-group btn-group-justified">
						<div class="btn-group">
							<button type="button" id="list-view" class="btn btn-default">
								<i class="fa fa-th-list"></i><span class="hidden-xs hidden-sm"> <?php echo $button_list; ?></span>
							</button>
						</div>
						<div class="btn-group">
							<button type="button" id="grid-view" class="btn btn-default">
								<i class="fa fa-th-large"></i><span class="hidden-xs hidden-sm"> <?php echo $button_grid; ?></span>
							</button>
						</div>
						</div>
					</div>
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
						<div class="input-group">
							<span class="input-group-addon" ><i class="fa fa-sort"></i><span class="hidden-xs hidden-sm hidden-md"> <?php echo $text_sort; ?></span></span>
							<select id="input-sort" class="form-control" onchange="location = this.value;">
								<?php foreach ($sorts as $sorts) { ?>
								<?php if ($sorts['value'] == $sort . '-' . $order) { ?>
								<option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-4 col-xs-4">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-eye"></i><span class="hidden-xs hidden-sm hidden-md"> <?php echo $text_limit; ?></span></span>
							<select id="input-limit" class="form-control" onchange="location = this.value;">
								<?php foreach ($limits as $limits) { ?>
								<?php if ($limits['value'] == $limit) { ?>
								<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>

     <div class="row">
        <?php foreach ($products as $product) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb thumbnail ">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive center-block" /></a></div>
            <div>
              <div class="caption">
                <h6><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $product['sku']; ?>&nbsp;&nbsp;&nbsp;Производитель:<?php echo $product['jan']; ?> <?php echo $product['isbn']; ?>  </h6><div class="btn-group">
									<?php if (($product['quantity'] <= 0) and $disable_cart_button){ ?>
									<button class="btn btn-addtocart" type="button" disabled><?php echo $disable_cart_button_text; ?></button>
									<?php } else { ?>
									<button class="btn btn-addtocart" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
									<?php } ?>
									<button class="btn btn-addtocart" type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
									<button class="btn btn-addtocart" type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
									<button class="btn btn-addtocart"><a href="/autoparts/search/<?php echo $product['sku'];?>/<?php echo $product['jan'];?>">Аналоги</button></a>


                <?php if ($product['price']) { ?>

                  <?php if (!$product['special']) { ?>
            &nbsp;&nbsp;&nbsp;Цена: <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-old">&nbsp;<?php echo $product['price']; ?>&nbsp;</span> <span class="price-new"><?php echo $product['special']; ?></span>
                  <?php } ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

								</div>




                </p>

                <?php } ?>
								<?php if ($product['rating']) { ?>
								        <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <i class="fa fa-star"></i>
                  <?php } else { ?>
                  <i class="fa fa-star active"></i>
                  <?php } ?>
                  <?php } ?>
										</p>
								<?php } else { ?>

								<?php } ?>

              </div>
            </div>
						<div class="clearfix"></div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
$('#button-search').bind('click', function() {
	url = 'index.php?route=product/search';

	var search = $('#content input[name=\'search\']').prop('value');

	if (search) {
		url += '&search=' + encodeURIComponent(search);
	}

	var category_id = $('#content select[name=\'category_id\']').prop('value');

	if (category_id > 0) {
		url += '&category_id=' + encodeURIComponent(category_id);
	}

	var sub_category = $('#content input[name=\'sub_category\']:checked').prop('value');

	if (sub_category) {
		url += '&sub_category=true';
	}

	var filter_description = $('#content input[name=\'description\']:checked').prop('value');

	if (filter_description) {
		url += '&description=true';
	}

	location = url;
});

$('#content input[name=\'search\']').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('select[name=\'category_id\']').on('change', function() {
	if (this.value == '0') {
		$('input[name=\'sub_category\']').prop('disabled', true);
	} else {
		$('input[name=\'sub_category\']').prop('disabled', false);
	}
});

$('select[name=\'category_id\']').trigger('change');
--></script>
<?php echo $footer; ?>