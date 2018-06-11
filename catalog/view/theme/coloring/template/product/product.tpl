<?php echo $header; ?>
<div class="container" itemscope itemtype="http://schema.org/Product">
	<ul class="breadcrumb" prefix:v="http://rdf.data-vocabulary.org/#">
		<?php $breadcount = count($breadcrumbs) - 1; ?>
		<?php $i = 0; ?>
    <?php foreach ($breadcrumbs as $key => $breadcrumb) { ?>
		<?php $i++; ?>
		<?php if ($key != $breadcount) { ?>
		<li <?php if ($i > 1) { echo 'typeof="v:Breadcrumb"'; } ?>><a href="<?php echo $breadcrumb['href']; ?>" <?php if ($i > 1) { echo 'rel="v:url" property="v:title"'; } ?>><?php echo $breadcrumb['text']; ?></a></li>
		<?php } else {?>
		<li class="active"><?php echo $breadcrumb['text']; ?></li>
		<?php } ?>
    <?php } ?>
  </ul>
	<h3 itemprop="name"><?php echo $heading_title; ?></h3>
	<div class="well well-sm">
    <?php if ($manufacturer) { ?>
		<div class="inline-info">
			<b><?php echo $text_manufacturer; ?></b> <a href="<?php echo $manufacturers; ?>" class="red-link"><?php echo $manufacturer; ?></a>
		</div>
		<?php } ?>
		<div class="inline-info">
			<b><?php echo $text_sku; ?></b> <span itemprop="sku"><?php echo $sku; ?></span>
		</div>
		<div class="inline-info">
			<b><?php echo $text_model; ?></b> <span itemprop="model"><?php echo $model; ?></span>
		</div>
				<div class="inline-info">
			<b><?php echo $text_isbn; ?></b> <span itemprop="isbn"><?php echo $isbn; ?></span>
		</div>
		<div class="inline-info">
			<b><?php echo $text_jan; ?></b> <span itemprop="jan"><?php echo $jan; ?></span>
		</div>
		<div class="inline-info">
			<b>В наличии:&nbsp;<?php echo  $product_quantity?>шт.</b>
		</div>



    <?php if ($reward) { ?>
		<div class="inline-info">
			<b><?php echo $text_reward; ?></b> <?php echo $reward; ?>
		</div>
		<?php } ?>
		<?php if ($review_status) { ?>
		<div class="inline-info-right">
			<span class="stars">
				<?php if ($rating) { ?>
				<span itemprop = "aggregateRating" itemscope itemtype = "http://schema.org/AggregateRating">
					<meta itemprop='reviewCount' content='<?php echo preg_replace("/\D/","",$reviews); ?>' />
					<meta itemprop='worstRating' content='1' />
					<meta itemprop='bestRating' content='5' />
					<meta itemprop='ratingValue' content='<?php echo $rating; ?>' />
				</span>
				<?php } ?>
				<?php for ($i = 1; $i <= 5; $i++) { ?>
				<?php if ($rating < $i) { ?>
				<i class="fa fa-star"></i>
				<?php } else { ?>
				<i class="fa fa-star active"></i>
				<?php } ?>
				<?php } ?>
			</span>
			<a href="" onclick="$('a[href=\'#tab-review\']').trigger('click');  $('html, body').animate({ scrollTop: $('a[href=\'#tab-review\']').offset().top - 5}, 250); return false;"><?php echo $reviews; ?></a>
		</div>
		<?php } ?>
	</div>


  <div class="row">
	<?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>

			<div class="row">
        <div class="col-lg-9 col-md-8 col-sm-7">
					<div class="row">

						<?php if ($short_description_off & $short_attribute_off & $social_likes_off) { ?>
						<?php $sda_class = 'col-lg-12';?>
						<?php } else { ?>
						<?php $sda_class = 'col-lg-6';?>
						<?php } ?>


						<div class="<?php echo $sda_class; ?>">
						<?php if ($thumb || $images) { ?>
						<div class="thumbnails">
							<?php if ($thumb) { ?>
							<div class="main-image-wrapper">
								<a class="main-image" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" data-number="0">
									<img itemprop="image" src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" class="img-responsive center-block" />
								</a>
							</div>
							<?php } ?>

							<?php if ($images) { ?>
							<div class="images-additional">
								<?php if ($thumb_small) { ?>
									<a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>">
										<img src="<?php echo $thumb_small; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" data-number="0"/>
									</a>
								<?php } ?>
								<?php $number = 1 ;?>
								<?php foreach ($images as $image) { ?>
									<a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>">
										<img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" data-number="<?php echo $number; ?>"/>
									</a>
									<?php $number++;?>
								<?php } ?>
							</div>
							<?php } ?>
						</div>
						<?php } ?>
						</div>

<p><img src="http://autopartix.com/image/catalog/google.png" width="50" height="50" border="0" ><a href= https://www.google.com.ua/search?q=<?php echo $manufacturer; ?>+<?php echo $sku; ?>&tbm=isch&sa=X&ved=0ahUKEwiq_5qMi-XUAhVI2BoKHX7SAd0Q_AUICigB&biw=1366&bih=631>Поиск изображения товара в Google</a></p>
						<?php if (!$short_description_off or !$short_attribute_off or !$social_likes_off) { ?>

						<div class="col-lg-6 hidden-md hidden-sm hidden-xs">
							<?php if (!$short_description_off) { ?>
							<h5><strong><?php echo $product_short_description_text; ?></strong></h5>
							<p><?php echo utf8_substr(strip_tags(html_entity_decode($description, ENT_QUOTES, 'UTF-8')), 0, 330) . '... ' ?><a href="" class="red-link" onclick="$('a[href=\'#tab-description\']').trigger('click'); $('html, body').animate({ scrollTop: $('a[href=\'#tab-description\']').offset().top - 6}, 250); return false;"><?php echo $product_read_more_text; ?> &#8594;</a></p>
							<?php } ?>

							<?php if (!$short_attribute_off) { ?>
							<?php if ($attribute_groups) { ?>
							<?php $i = 0; ?>
							<?php foreach ($attribute_groups as $attribute_group) { ?>

							<?php if ($i < 8) { ?>
								<h5><strong><?php echo $attribute_group['name']; ?></strong></h5>
								<table class="short-attr-table">
									<tbody>
										<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
										<?php if ($i < 8) { ?>
											<tr>

												<td class="left"><span><?php echo $attribute['name']; ?></span></td>
												<td class="right"><span><?php echo $attribute['text']; ?></span></td>

											</tr>
										<?php } ?>
										<?php $i++ ?>
										<?php } ?>
									</tbody>
								</table>


							<?php } ?>

							<?php $i++ ?>
							<?php } ?>
							<p>...</p>
							<p><button class="btn btn-sm btn-default" onclick="$('a[href=\'#tab-specification\']').trigger('click'); $('html, body').animate({ scrollTop: $('a[href=\'#tab-specification\']').offset().top - 2}, 250); return false;"><?php echo $product_all_specifications_text; ?></button></p>
							<?php } ?>
							<br>
							<?php } ?>

							<?php if (!$social_likes_off) { ?>
							<div class="social-likes" style="margin-bottom:20px;">
								<div class="facebook" title="<?php echo $product_share_text; ?> на Facebook">Facebook</div>
								<div class="twitter" title="<?php echo $product_share_text; ?> в Twitter">Twitter</div>
								<div class="plusone" title="<?php echo $product_share_text; ?> в Google+">Google+</div>
							</div>
							<?php } ?>

						</div>


						<?php } ?>
					</div>


        </div>


        <div class="col-lg-3 col-md-4 col-sm-5" id="product">
          <div class="panel panel-default">
						<div class="panel-body">

						<div class="btn-group pull-right">


						</div>

						<?php if ($price) { ?>
						<div class="price" itemprop = "offers" itemscope itemtype = "http://schema.org/Offer">
							<meta itemprop="priceCurrency" content="<?php echo $currency_code; ?>" />
							<?php if (!$special) { ?>
							<meta itemprop="price" content="<?php echo preg_replace("/[^\d.]/","",rtrim($price, " \t.")); ?>" />
							<h2>
								<span ><?php echo $price; ?></span>
								<?php if ($tax) { ?>
								<span class="tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span>
								<?php } ?>
								<?php if ($points) { ?>
								<span class="points"><?php echo $text_points; ?> <strong><?php echo $points; ?></strong></span>
								<?php } ?>
							</h2>
							<?php } else { ?>
							<meta itemprop="price" content="<?php echo preg_replace("/[^\d.]/","",rtrim($special, " \t.")); ?>" />
							<h2>
								<span class="price-old">&nbsp;<?php echo $price; ?>&nbsp;</span>
								<span><?php echo $special; ?></span>
								<?php if ($tax) { ?>
								<span class="tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span>
								<?php } ?>
								<?php if ($points) { ?>
								<span class="points"><?php echo $text_points; ?> <strong><?php echo $points; ?></strong></span>
								<?php } ?>
							</h2>
							<?php } ?>

							<?php if ($discounts) { ?>
							<div class="alert-alt alert-info-alt">
								<?php foreach ($discounts as $discount) { ?>
									<div><strong><?php echo $discount['quantity']; ?></strong><?php echo $text_discount; ?><strong><?php echo $discount['price']; ?></strong></div>
								<?php } ?>
							</div>
							<?php } ?>

						</div>
						<?php } ?>




					<?php if ($product_quantity <= 0) { ?>
					<?php $al_class = 'alert-danger-alt'; ?>
					<?php } else { ?>
					<?php $al_class = 'alert-success-alt'; ?>
					<?php } ?>

          <div class="alert-alt <?php echo $al_class; ?>">
						<strong><?php echo $product_stock; ?></strong>
						<?php if ($config_stock_display & ($product_quantity > 0)) { ?>
						<br /><?php echo $product_quantity_text; ?>
						<?php } ?>
					</div>

					<?php if ($minimum > 1) { ?>
          <div class="alert-alt alert-warning-alt"><?php echo $text_minimum; ?></div>
          <?php } ?>



          <div class="options">
            <?php if ($options) { ?>
            <?php foreach ($options as $option) { ?>
            <?php if ($option['type'] == 'select') { ?>
            <div class="form-group">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>">
								<?php if ($option['required']) { ?>
									<i class="fa fa-exclamation-circle required" data-toggle="tooltip" data-placement="left" title="<?php echo $product_required_text; ?>"></i>
								<?php } ?>
								<?php echo $option['name']; ?>
							</label>
              <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                <?php if ($option_value['price']) { ?>
                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                <?php } ?>
                </option>
                <?php } ?>
              </select>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'radio') { ?>
            <div class="form-group">
              <label class="control-label">
								<?php if ($option['required']) { ?>
									<i class="fa fa-exclamation-circle required" data-toggle="tooltip" data-placement="left" title="<?php echo $product_required_text; ?>"></i>
								<?php } ?>
								<?php echo $option['name']; ?>
							</label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="radio-checbox-options">
                  <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>" />
									<label for="<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>">
                    <span class="option-name"><?php echo $option_value['name']; ?></span>
                    <?php if ($option_value['price']) { ?>
                    <span class="option-price"><?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?></span>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'checkbox') { ?>
            <div class="form-group">
              <label class="control-label">
								<?php if ($option['required']) { ?>
									<i class="fa fa-exclamation-circle required" data-toggle="tooltip" data-placement="left" title="<?php echo $product_required_text; ?>"></i>
								<?php } ?>
								<?php echo $option['name']; ?>
							</label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="radio-checbox-options">
                  <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>" />
									<label for="<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>">
                    <span class="option-name"><?php echo $option_value['name']; ?></span>
                    <?php if ($option_value['price']) { ?>
                    <span class="option-price"><?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?></span>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'image') { ?>
            <div class="form-group">
              <label class="control-label">
								<?php if ($option['required']) { ?>
									<i class="fa fa-exclamation-circle required" data-toggle="tooltip" data-placement="left" title="<?php echo $product_required_text; ?>"></i>
								<?php } ?>
								<?php echo $option['name']; ?>
							</label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="image-radio">
                  <label>
                    <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                    <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" data-toggle="tooltip" title="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" />
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'text') { ?>
            <div class="form-group">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>">
								<?php if ($option['required']) { ?>
									<i class="fa fa-exclamation-circle required" data-toggle="tooltip" data-placement="left" title="<?php echo $product_required_text; ?>"></i>
								<?php } ?>
								<?php echo $option['name']; ?>
							</label>
              <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'textarea') { ?>
            <div class="form-group">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>">
								<?php if ($option['required']) { ?>
									<i class="fa fa-exclamation-circle required" data-toggle="tooltip" data-placement="left" title="<?php echo $product_required_text; ?>"></i>
								<?php } ?>
								<?php echo $option['name']; ?>
							</label>
              <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'file') { ?>
            <div class="form-group">
              <label class="control-label">
								<?php echo $option['name']; ?>
								<?php if ($option['required']) { ?>
									<i class="fa fa-info-circle" data-toggle="tooltip" data-placement="left" title="Tooltip on left"></i>
								<?php } ?>
							</label>
              <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
              <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'date') { ?>
            <div class="form-group">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>">
								<?php if ($option['required']) { ?>
									<i class="fa fa-exclamation-circle required" data-toggle="tooltip" data-placement="left" title="<?php echo $product_required_text; ?>"></i>
								<?php } ?>
								<?php echo $option['name']; ?>
							</label>
              <div class="input-group date">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'datetime') { ?>
            <div class="form-group">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>">
								<?php if ($option['required']) { ?>
									<i class="fa fa-exclamation-circle required" data-toggle="tooltip" data-placement="left" title="<?php echo $product_required_text; ?>"></i>
								<?php } ?>
								<?php echo $option['name']; ?>
							</label>
              <div class="input-group datetime">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'time') { ?>
            <div class="form-group">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>">
								<?php if ($option['required']) { ?>
									<i class="fa fa-exclamation-circle required" data-toggle="tooltip" data-placement="left" title="<?php echo $product_required_text; ?>"></i>
								<?php } ?>
								<?php echo $option['name']; ?>
							</label>
              <div class="input-group time">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php if ($recurrings) { ?>
            <hr>
            <h3><?php echo $text_payment_recurring ?></h3>
            <div class="form-group required">
              <select name="recurring_id" class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($recurrings as $recurring) { ?>
                <option value="<?php echo $recurring['recurring_id'] ?>"><?php echo $recurring['name'] ?></option>
                <?php } ?>
              </select>
              <div class="help-block" id="recurring-description"></div>
            </div>
            <?php } ?>


          </div>
					<div class="addcart">


					<div class="row">

						<div class="col-lg-5 col-md-4 col-sm-12">
							<div class="input-group quantity" data-toggle="tooltip"  title="<?php echo $entry_qty; ?>">

								<span class="input-group-addon quantity-plus-minus">
									<button type="button" id="plus"  class="btn">+</button>
									<button type="button" id="minus"  class="btn">-</button>
								</span>

								<input type="text" name="quantity" value="<?php echo $minimum; ?>" size="2" id="input-quantity" class="form-control" />


							</div>
							<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
						</div>
						<div class="col-lg-7  col-md-8 col-sm-12">
						<?php if (($product_quantity <= 0) and $disable_cart_button){ ?>
							<button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-block btn-default " disabled><?php echo $disable_cart_button_text; ?></button>
							<?php } else {  ?>
							<button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-block btn-danger "><?php echo $button_cart; ?></button>
							<?php }  ?>
						</div>
            </div>

				 </div>

					</div>
					</div>
        </div>

				<div class="col-sm-12">
					<?php if (!$related_product_position) { ?>
					<?php if ($products) { ?>
					<div class="panel panel-default box-product related-products">
						<div class="panel-heading"><i class="glyphicon glyphicon-link"></i>&nbsp;&nbsp;<?php echo $text_related; ?></div>
						<div class="panel-body" id="related-products">
							<?php foreach ($products as $product) { ?>
							<div class="product-item">
								<div class="image">
								<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
								<?php if ($product['special']) { ?>
								<?php $new_price = preg_replace("/[^0-9]/", '', $product['special']); ?>
								<?php $old_price = preg_replace("/[^0-9]/", '', $product['price']); ?>
								<?php $total_discount = round(100 - ($new_price / $old_price) * 100); ?>
								<span class="sticker st-sale">-<?php echo $total_discount; ?>%</span>
								<?php } ?>
								</div>
								<div class="caption">
									<h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
									<?php if ($product['price']) { ?>
									<div class="price">
										<?php if (!$product['special']) { ?>
										<?php echo $product['price']; ?>
										<?php } else { ?>
										<span class="price-old">&nbsp;<?php echo $product['price']; ?>&nbsp;</span> <span class="price-new"><?php echo $product['special']; ?></span>
										<?php } ?>
										<?php if ($product['tax']) { ?>
										<br /><span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
										<?php } ?>
									</div>
									<?php } ?>
								</div>
								<!-- <div class="buttons">
									<div class="btn-group dropup">
										<button type="button" class="btn btn-addtocart" onclick="cart.add('<?php echo $product['product_id']; ?>');" title="<?php echo $button_cart; ?>"><span class="glyphicon glyphicon-shopping-cart icon"></span> <?php echo $button_cart; ?> </button>
											<button type="button" class="btn btn-addtocart dropdown-toggle" data-toggle="dropdown">
												<i class="fa fa-angle-down"></i>
											</button>
											<ul class="dropdown-menu">

												<li><a href="#" onclick="compare.add('<?php echo $product['product_id']; ?>');return false;" title="<?php echo $button_compare; ?>"><i class="fa fa-bar-chart"></i> <?php echo $button_compare; ?></a></li>
											</ul>
									</div>
								</div> -->
							</div>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
					<?php } ?>


          <ul class="nav nav-tabs product-tabs">
            <li class="active"><a href="#tab-description" data-toggle="tab"><i class="fa fa-file-text-o"></i><span class="hidden-xs">&nbsp;&nbsp;<?php echo $tab_description; ?></span></a></li>
            <?php if ($attribute_groups) { ?>
            <li><a href="#tab-specification" data-toggle="tab"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;&nbsp;<?php echo $tab_attribute; ?></span></a></li>
            <?php } ?>
            <?php if ($review_status) { ?>
            <li><a href="#tab-review" data-toggle="tab"><i class="fa fa-comment-o"></i><span class="hidden-xs">&nbsp;&nbsp;<?php echo $tab_review; ?></span></a></li>
            <?php } ?>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active red-links" id="tab-description" itemprop="description">
							<?php echo $description; ?>
						</div>
            <?php if ($attribute_groups) { ?>
            <div class="tab-pane" id="tab-specification">
              <table class="table table-bordered">
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                <thead>
                  <tr>
                    <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                  <tr>
                    <td><?php echo $attribute['name']; ?></td>
                    <td><?php echo $attribute['text']; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
                <?php } ?>
              </table>
            </div>
            <?php } ?>







            <?php if ($review_status) { ?>
            <div class="tab-pane" id="tab-review">

								<?php if ($review_guest) { ?>
								<a class="btn btn-default" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><i class="fa fa-pencil"></i>&nbsp;&nbsp;<?php echo $text_write; ?></a>
								<br><br>
								<div id="collapseOne" class="panel-collapse collapse">
									<div class="well riview-helper">
										<form class="form-horizontal">
											<div class="form-group required">
												<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
												<div class="col-sm-10">
													<input type="text" name="name" value="" id="input-name" class="form-control" />
												</div>
											</div>
											<div class="form-group required">
												<label class="col-sm-2 control-label" for="input-review"><?php echo $entry_review; ?></label>
												<div class="col-sm-10">
													<textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
												</div>
											</div>
											<!-- <div class="form-group">
												<div class="col-sm-10 col-sm-offset-2">
													<?php echo $text_note; ?>
												</div>
											</div> -->
											<div class="form-group required">
												<label class="col-sm-2 control-label"><?php echo $entry_rating; ?></label>
												<div class="col-sm-10">
													<div class="prod-rat">
														<input id="rat1" type="radio" name="rating" value="1" /><label class="rat-star" for="rat1"><i class="fa fa-star"></i></label>
														<input id="rat2" type="radio" name="rating" value="2" /><label class="rat-star" for="rat2"><i class="fa fa-star"></i></label>
														<input id="rat3" type="radio" name="rating" value="3" /><label class="rat-star" for="rat3"><i class="fa fa-star"></i></label>
														<input id="rat4" type="radio" name="rating" value="4" /><label class="rat-star" for="rat4"><i class="fa fa-star"></i></label>
														<input id="rat5" type="radio" name="rating" value="5" /><label class="rat-star" for="rat5"><i class="fa fa-star"></i></label>
													</div>
													<script>
														$('.rat-star').hover(function () {
															$(this).prevAll('.rat-star').addClass('active');
															$(this).addClass('active');
														},function () {
															$(this).prevAll('.rat-star').removeClass('active');
															$(this).removeClass('active');
														});

														$('.rat-star').click(function(){
															$('.rat-star').each(function(){
																$(this).removeClass('checked');
																$(this).prevAll('.rat-star').removeClass('checked');
															});

															$(this).addClass('checked');
															$(this).prevAll('.rat-star').addClass('checked');
														});

													</script>
												</div>
											</div>

											<?php echo $captcha; ?>


											<div class="form-group" style="margin-bottom: 0;">
												<div class="col-sm-10 col-sm-offset-2">
													<button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
												</div>
											</div>



										</form>
									</div>
								</div>
								<?php } else { ?>
									<div class="alert alert-warning"><i class="fa fa-warning"></i>&nbsp;&nbsp;<?php echo $text_login; ?></div>
								<?php } ?>
								<div id="review"></div>

						</div>
						<?php } ?>

            </div>


					<?php if ($related_product_position) { ?>
					<?php if ($products) { ?>
					<div class="panel panel-default box-product related-products">
						<div class="panel-heading"><i class="glyphicon glyphicon-link"></i>&nbsp;&nbsp;<?php echo $text_related; ?></div>
						<div class="panel-body" id="related-products">
							<?php foreach ($products as $product) { ?>
							<div class="product-item">
								<div class="image">
								<a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
								<?php if ($product['special']) { ?>
								<?php $new_price = preg_replace("/[^0-9]/", '', $product['special']); ?>
								<?php $old_price = preg_replace("/[^0-9]/", '', $product['price']); ?>
								<?php $total_discount = round(100 - ($new_price / $old_price) * 100); ?>
								<span class="sticker st-sale">-<?php echo $total_discount; ?>%</span>
								<?php } ?>
								</div>
								<div class="caption">
									<h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
									<?php if ($product['price']) { ?>
									<div class="price">
										<?php if (!$product['special']) { ?>
										<?php echo $product['price']; ?>
										<?php } else { ?>
										<span class="price-old">&nbsp;<?php echo $product['price']; ?>&nbsp;</span> <span class="price-new"><?php echo $product['special']; ?></span>
										<?php } ?>
										<?php if ($product['tax']) { ?>
										<br /><span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
										<?php } ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
					<?php } ?>

          </div>
				</div>
				<?php if ($tags) { ?>
				<p style="margin-bottom: 20px;" class="red-links"><?php echo $text_tags; ?>
					<?php for ($i = 0; $i < count($tags); $i++) { ?>
					<?php if ($i < (count($tags) - 1)) { ?>
					<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
					<?php } else { ?>
					<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
					<?php } ?>
					<?php } ?>
				</p>
				<?php } ?>
    </div>

		<div class="col-sm-12">
			<?php echo $content_bottom; ?>
		</div>
	</div>
	<?php echo $column_right; ?>
</div>


<script type="text/javascript">
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.text-danger').remove();

			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});

$('#button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}

			if (json['success']) {
				html  = '<div id="modal-cart" class="modal fade">';
					html += '  <div class="modal-dialog">';
					html += '    <div class="modal-content">';
					html += '      <div class="modal-body alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>';
					html += '    </div>';
					html += '  </div>';
					html += '</div>';

					$('body').append(html);

					$('#modal-cart').modal('show');

					setTimeout(function () {
						$('#cart-total').html(json['total']);
					}, 100);

				$('#cart > ul').load('index.php?route=common/cart/info ul li');
			}
		}, error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
	});
});

$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('#review').delegate('.pagination a', 'click', function(e) {
  e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
			$('#captcha').attr('src', 'index.php?route=tool/captcha#'+new Date().getTime());
			$('input[name=\'captcha\']').val('');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
				$('.riview-helper').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
			}

			if (json['success']) {
				$('.riview-helper').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>').remove();

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});

$(document).ready(function() {
	$('.thumbnails .images-additional').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
});
$(document).ready(function() {
	$('.thumbnails .main-image').magnificPopup({
		type:'image'
	});
});

$('.images-additional img').click(function(){
	var oldsrc = $(this).attr('src'),
			newsrc = oldsrc.replace('<?php echo $img_small;?>','<?php echo $img_big;?>'),
			newhref = $(this).parent().attr('href'),
			number = $(this).attr('data-number');

	$('.main-image img').attr('src', newsrc);
	$('.main-image').attr('href', newhref);
	$('.main-image').attr('data-number', number);
	return false;
});


$('.thumbnails .main-image img').click(function(){
	if ($('.thumbnails .images-additional').length > 0) {
		var startnumber = $(this).parent().attr('data-number');
		$('.thumbnails .images-additional').magnificPopup('open', startnumber);
		return false
	} else {
		$(this).magnificPopup('open');
		return false
	}
});



		$('#related-products').owlCarousel({
			responsiveBaseWidth: '#related-products',
			itemsCustom: [[0, 1], [448, 2], [668, 3], [848, 4], [1000, 5]],
			theme: 'product-carousel',
			navigation: true,
			slideSpeed: 200,
			paginationSpeed: 300,
			autoPlay: false,
			stopOnHover: true,
			touchDrag: false,
			mouseDrag: false,
			navigationText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
			pagination: false,
		});

    $('.quantity-plus-minus #minus').click(function () {
        var $input = $('.quantity input[type="text"]');
        var count = parseInt($input.val()) - 1;
        count = count < <?php echo $minimum; ?> ? <?php echo $minimum; ?> : count;
        $input.val(count);
        $input.change();
        return false;
    });
    $('.quantity-plus-minus #plus').click(function () {
        var $input = $('.quantity input[type="text"]');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
        });
</script>
<?php echo $footer; ?>