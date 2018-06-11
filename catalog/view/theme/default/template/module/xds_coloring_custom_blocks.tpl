<div class="row custom-blocks">
	<?php foreach ($blocks as $block) { ?>
	<div class="col-sm-4">
		<div class="custom-block">
			<?php if ($block['link'][$language_id]) {?>
			<a href="<?php echo $block['link'][$language_id]; ?>">
				<div class="image">
					<img src="<?php echo $block['image']; ?>" alt="<?php echo $block['title'][$language_id]; ?>" />
				</div>
				<div class="text">
					<h4><?php echo $block['title'][$language_id]; ?></h4>
					<p><?php echo $block['description'][$language_id]; ?></p>
				</div>
			</a>
			<?php } else { ?>
			<div class="image">
					<img src="<?php echo $block['image']; ?>" alt="<?php echo $block['title'][$language_id]; ?>" />
				</div>
				<div class="text">
					<h4><?php echo $block['title'][$language_id]; ?></h4>
					<p><?php echo $block['description'][$language_id]; ?></p>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
</div>
<script>
	function adddotdotdot($element) {
		$(".custom-block h4,.custom-block p").dotdotdot();
	}
	$(document).ready(adddotdotdot);
	$(window).resize(adddotdotdot);
</script>