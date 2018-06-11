<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div class="col-12"><?php echo $message; ?></div>
  </div>
</div>
<script>
	$(function(){
		$('header').css({'display':'none'});
		$('footer').css({'display':'none'});
	});
</script>
<?php echo $footer; ?>