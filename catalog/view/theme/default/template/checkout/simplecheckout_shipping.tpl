<div class="simplecheckout-block" id="simplecheckout_shipping" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $display_error && $has_error ? 'data-error="true"' : '' ?>>
    <?php if ($display_header) { ?>
        <div class="checkout-heading"><?php echo $text_checkout_shipping_method ?></div>
    <?php } ?>
    <div class="simplecheckout-warning-block" <?php echo $display_error && $has_error_shipping ? '' : 'style="display:none"' ?>><?php echo $error_shipping ?></div> 
    <div class="simplecheckout-block-content">
        <?php if (!empty($shipping_methods)) { ?>
            <?php if ($display_type == 2 ) { ?>
                <?php $current_method = false; ?>
                <select data-onchange="reloadAll" name="shipping_method">
                    <?php foreach ($shipping_methods as $shipping_method) { ?>
                        <?php if (!empty($shipping_method['title'])) { ?>
                        <optgroup label="<?php echo $shipping_method['title']; ?>">
                        <?php } ?>
                        <?php if (empty($shipping_method['error'])) { ?>
                            <?php foreach ($shipping_method['quote'] as $quote) { ?>
                                <option value="<?php echo $quote['code']; ?>" <?php echo !empty($quote['dummy']) ? 'disabled="disabled"' : '' ?> <?php echo !empty($quote['dummy']) ? 'data-dummy="true"' : '' ?> <?php if ($quote['code'] == $code) { ?>selected="selected"<?php } ?>><?php echo $quote['title']; ?><?php echo !empty($quote['text']) ? ' - '.$quote['text'] : ''; ?></option>
                                <?php if ($quote['code'] == $code) { $current_method = $quote; } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <option value="<?php echo $shipping_method['code']; ?>" disabled="disabled"><?php echo $shipping_method['error']; ?></option>
                        <?php } ?>
                        <?php if (!empty($shipping_method['title'])) { ?>
                        </optgroup>
                        <?php } ?>
                    <?php } ?>
                </select>
                <?php if ($current_method) { ?>
                    <?php if (!empty($current_method['description'])) { ?>
                        <div class="simplecheckout-methods-description"><?php echo $current_method['description']; ?></div>
                    <?php } ?>
                    <?php if (!empty($rows)) { ?>
                    <table class="simplecheckout-methods-table">
                        <tr>
                            <td colspan="2">
                                <?php foreach ($rows as $row) { ?>
                                  <?php echo $row ?>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
            <table class="simplecheckout-methods-table">
                <?php foreach ($shipping_methods as $shipping_method) { ?>
                    <?php if (!empty($shipping_method['title'])) { ?>
                    <tr>
                        <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
                    </tr>
                    <?php } ?>
                    <?php if (!empty($shipping_method['warning'])) { ?>
                        <tr>
                            <td colspan="3"><div class="simplecheckout-error-text"><?php echo $shipping_method['warning']; ?></div></td>
                        </tr>
                    <?php } ?>
                    <?php if (empty($shipping_method['error'])) { ?>
                        <?php foreach ($shipping_method['quote'] as $quote) { ?>
                            <tr>
                                <td class="code">
                                    <input type="radio" data-onchange="reloadAll" name="shipping_method" <?php echo !empty($quote['dummy']) ? 'disabled="disabled"' : '' ?> <?php echo !empty($quote['dummy']) ? 'data-dummy="true"' : '' ?> value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" <?php if ($quote['code'] == $code) { ?>checked="checked"<?php } ?> />
                                </td>
                                <td class="title" valign="middle">
                                    <label for="<?php echo $quote['code']; ?>">
                                        <?php echo !empty($quote['title']) ? $quote['title'] : ''; ?>
                                    </label>
                                    <?php if (!empty($quote['img'])) { ?>
                                    <label for="<?php echo $quote['code']; ?>">
                                        <img src="<?php echo $quote['img']; ?>" width="60" height="32" border="0" style="display:block;margin:3px;">
                                    </label>
                                    <?php } ?>
                                </td>
                                <td class="quote">
                                    <label for="<?php echo $quote['code']; ?>"><?php echo !empty($quote['text']) ? $quote['text'] : ''; ?></label>
                                </td>
                            </tr>
                            <?php if (!empty($quote['description'])) { ?>
                                <tr>
                                    <td class="code">
                                    </td>
                                    <td class="title">
                                        <label for="<?php echo $quote['code']; ?>"><?php echo $quote['description']; ?></label>
                                    </td>
                                    <td class="quote">
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($quote['code'] == $code && !empty($rows)) { ?>
                                <tr>
                                    <td colspan="3">
                                        <?php foreach ($rows as $row) { ?>
                                          <?php echo $row ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3"><div class="simplecheckout-error-text"><?php echo $shipping_method['error']; ?></div></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </table>
            <?php } ?>
            <input type="hidden" name="shipping_method_current" value="<?php echo $code ?>" />
            <input type="hidden" name="shipping_method_checked" value="<?php echo $checked_code ?>" />
        <?php } ?>
        <?php if (empty($shipping_methods) && $address_empty && $display_address_empty) { ?>
            <div class="simplecheckout-warning-text"><?php echo $text_shipping_address; ?></div>
        <?php } ?>
        <?php if (empty($shipping_methods) && !$address_empty) { ?>
            <div class="simplecheckout-warning-text"><?php echo $error_no_shipping; ?></div>
        <?php } ?>
    </div>
</div>