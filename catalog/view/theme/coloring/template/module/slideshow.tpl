<div id="slideshow<?php echo $module; ?>" class="slideshow">
	<div>
		<?php foreach ($banners as $banner) { ?>
			<?php if ($banner['link']) { ?>
			<a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
			<?php } else { ?>
			<img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
			<?php } ?>
		<?php } ?>
	</div>
</div>
<script type="text/javascript"><!--
$('#slideshow<?php echo $module; ?> > div').nivoSlider({
  effect: 'fade',               // Specify sets like: 'fold,fade,sliceDown'
  animSpeed: 400,                 // Slide transition speed
  pauseTime: 6000,                // How long each slide will show
  startSlide: 0,                  // Set starting Slide (0 index)
  directionNav: true,             // Next & Prev navigation
  controlNav: true,               // 1,2,3... navigation
  controlNavThumbs: false,        // Use thumbnails for Control Nav
  pauseOnHover: true,             // Stop animation while hovering
  manualAdvance: false,           // Force manual transitions
  prevText: ' ',               // Prev directionNav text
  nextText: ' ',               // Next directionNav text
  randomStart: false,             // Start on a random slide
  beforeChange: function(){},     // Triggers before a slide transition
  afterChange: function(){},      // Triggers after a slide transition
  slideshowEnd: function(){},     // Triggers after all slides have been shown
  lastSlide: function(){},        // Triggers when last slide is shown
  afterLoad: function(){}         // Triggers when slider has loaded
});
--></script>