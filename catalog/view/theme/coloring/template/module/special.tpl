<div class="panel panel-default box-product">
	<div class="panel-heading"><i class="fa fa-tag spesial-icon"></i>&nbsp;&nbsp;<?php echo $heading_title; ?></div>
	<div id="spesial<?php echo $module; ?>" class="panel-body">
		<?php foreach ($products as $product) { ?>
		<div class="product-item">
			<div class="image">
				<a href="<?php echo $product['href']; ?>">
					<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" />
				</a>
				<?php if ($product['special']) { ?>
				<?php $new_price = preg_replace("/[^0-9]/", '', $product['special']); ?>
				<?php $old_price = preg_replace("/[^0-9]/", '', $product['price']); ?>
				<?php if ($old_price != 0) { ?>
					<?php $total_discount = round(100 - ($new_price / $old_price) * 100); ?>
					<span class="sticker st-sale">-<?php echo $total_discount; ?>%</span>
				<?php } ?>
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
			<div class="buttons">
				<div class="btn-group dropup">
				
					<?php if (($product['quantity'] <= 0) and $disable_cart_button){ ?>
					<button type="button" class="btn btn-addtocart" title="<?php echo $button_cart; ?>" disabled><?php echo $disable_cart_button_text; ?> </button>
					<?php } else { ?>
					<button type="button" class="btn btn-addtocart" onclick="cart.add('<?php echo $product['product_id']; ?>');" title="<?php echo $button_cart; ?>"><span class="glyphicon glyphicon-shopping-cart icon"></span> <?php echo $button_cart; ?> </button>
					<?php } ?>
					
						<button type="button" class="btn btn-addtocart dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-angle-down caretalt"></i>
						</button>
						<ul class="dropdown-menu">
							<li><a href="#" onclick="wishlist.add('<?php echo $product['product_id']; ?>');return false;" title="<?php echo $button_wishlist; ?>"><i class="fa fa-heart"></i> <?php echo $button_wishlist; ?></a></li>
							<li><a href="#" onclick="compare.add('<?php echo $product['product_id']; ?>');return false;" title="<?php echo $button_compare; ?>"><i class="fa fa-bar-chart"></i> <?php echo $button_compare; ?></a></li>
						</ul>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<script type="text/javascript">
		$('#spesial<?php echo $module; ?>').owlCarousel({
			responsiveBaseWidth: '#spesial<?php echo $module; ?>',
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
	</script>
</div>