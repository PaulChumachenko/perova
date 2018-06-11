<?php if (count($currencies) > 1) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="currency">
  <div class="btn-group">
    <button class="btn dropdown-toggle" data-toggle="dropdown">
    <?php foreach ($currencies as $currency) { ?>
    <?php if ($currency['symbol_left'] && $currency['code'] == $code) { ?>
    <strong><?php echo $currency['symbol_left']; ?></strong>
    <?php } elseif ($currency['symbol_right'] && $currency['code'] == $code) { ?>
    <strong><?php echo $currency['symbol_right']; ?></strong>
    <?php } ?>
    <?php } ?>
    <span class="hidden-xs hidden-sm"><?php echo $text_currency; ?>&nbsp;</span>&nbsp;<span class="fa fa fa-angle-down caretalt"></span></button>
    <ul class="dropdown-menu">
      <?php foreach ($currencies as $currency) { ?>
      <?php if ($currency['symbol_left']) { ?>
      <li><a class="currency-select" href="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_left']; ?>&nbsp;&nbsp;<?php echo $currency['title']; ?></a></li>
      <?php } else { ?>
      <li><a class="currency-select" href="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_right']; ?>&nbsp;&nbsp;<?php echo $currency['title']; ?></a></li>
      <?php } ?>
      <?php } ?>
    </ul>
  </div>
  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
<?php } ?>
