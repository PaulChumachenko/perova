<div class="list-group">
  <?php if (!$logged) { ?>
  <a href="<?php echo $login; ?>" class="list-group-item"><i class="fa fa-sign-in list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_login; ?></a>
	<a href="<?php echo $register; ?>" class="list-group-item"><i class="fa fa-pencil list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_register; ?></a>
	<a href="<?php echo $forgotten; ?>" class="list-group-item"><i class="fa fa-question list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_forgotten; ?></a>
  <?php } ?>
  <a href="<?php echo $account; ?>" class="list-group-item"><i class="glyphicon glyphicon-user list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_account; ?></a>
  <?php if ($logged) { ?>
  <a href="<?php echo $edit; ?>" class="list-group-item"><i class="fa fa-edit list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_edit; ?></a>
	<a href="<?php echo $password; ?>" class="list-group-item"><i class="fa fa-unlock list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_password; ?></a>
  <?php } ?>
  <a href="<?php echo $address; ?>" class="list-group-item"><i class="fa fa-building-o list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_address; ?></a>
	<a href="<?php echo $order; ?>" class="list-group-item"><i class="fa fa-history list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_order; ?></a>
		<a href="<?php echo $return; ?>" class="list-group-item"><i class="fa fa-exchange list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_return; ?></a>

	<a href="<?php echo $newsletter; ?>" class="list-group-item"><i class="fa fa-envelope-o list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_newsletter; ?></a>
	
  <?php if ($logged) { ?>
  <a href="<?php echo $logout; ?>" class="list-group-item"><i class="fa fa-sign-out list-group-icon"></i>&nbsp;&nbsp;<?php echo $text_logout; ?></a>
  <?php } ?>
</div>