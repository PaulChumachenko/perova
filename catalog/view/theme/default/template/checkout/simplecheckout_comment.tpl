<div class="simplecheckout-block" id="simplecheckout_comment">
    <?php if ($display_header) { ?>
      <div class="checkout-heading"><?php echo $label ?></div>
    <?php } ?>
    <div class="simplecheckout-block-content">
      <textarea name="comment" data-onchange="copyOnAllEntries" placeholder="<?php echo $placeholder ?>"><?php echo $comment ?></textarea>
    </div>
</div>