<?php echo $header; ?>
<div class="container">

       <?php if ($products) { ?>
      <div class="well well-sm">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-5 col-xs-4">
						<div class="btn-group btn-group-justified">



						</div>


						<div class="input-group">
							<span class="input-group-addon" ><i class="fa fa-sort"></i><span class="hidden-xs hidden-sm hidden-md <?php if ($twocols) {echo 'hidden-lg';} ?>"> <?php echo $text_sort; ?></span></span>
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
					<br class="visible-xs">
					<div class="col-lg-4 col-md-3 col-sm-4 ">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-eye"></i><span class="hidden-xs hidden-sm hidden-md <?php if ($twocols) {echo 'hidden-lg';} ?>"> <?php echo $text_limit; ?></span></span>
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
             <div>
              <div class="caption">
                <h6><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>&nbsp;Модель:&nbsp;<?php echo $product['model']; ?>&nbsp;</h6><div class="btn-group">
									<?php if (($product['quantity'] <= 0) and $disable_cart_button){ ?>
									<button class="btn btn-addtocart" type="button" disabled><?php echo $disable_cart_button_text; ?></button>
									<?php } else { ?>
									<?php if ($product['price']) { ?>
<a class="btn"  data-toggle="tooltip">
                  <?php if (!$product['special']) { ?>
          Цена: <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-old">&nbsp;<?php echo $product['price']; ?>&nbsp;</span> <span class="price-new"><?php echo $product['special']; ?></span>
                  <?php } ?>
</a>

									<button class="btn btn-addtocart" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
									<?php } ?>

									<button class="btn btn-addtocart" type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
									<button class="btn btn-addtocart" type="button" ><a href="/autoparts/search/<?php echo $product['ean']; ?>/<?php echo $product['mpn']; ?>">Аналоги</i></button></a>



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

								<?php } else { ?>

								<?php } ?>

              </div>
            </div>
						<div class="clearfix"></div>
          </div>
        </div>
        <?php } ?>
      </div>

			<div class="well well-sm">
				<div class="row">
					<div class="col-md-6"><div class="pagination-wrapper"><?php echo $pagination; ?></div></div>
					<div class="col-md-6 text-right-md"><div style="padding: 6px 0;"><?php echo $results; ?></div></div>
				</div>
			</div>

      <?php } ?>
			<?php if (!$description_position) { ?>
			<?php if ($thumb || $description) { ?>

				<div class="clearfix"></div>

      </div>
      <?php } ?>
			<?php } ?>
      <?php if (!$categories && !$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
     </div>
    <?php echo $column_right; ?></div>
		<?php echo $content_bottom; ?>
</div>
<script>
	function adddotdotdot($element) {
		$(".subcategory .name-wrapper").dotdotdot();
	}
	$(document).ready(adddotdotdot);
	$(window).resize(adddotdotdot);
</script>
<?php echo $footer; ?>