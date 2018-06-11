<div class="simplecheckout-block" id="simplecheckout_login" <?php echo $has_error ? 'data-error="true"' : '' ?>>
    <div class="simplecheckout-block-content">
        <div id="simple_login_header"><img style="cursor:pointer;" data-onclick="close" src="<?php echo $additional_path ?>catalog/view/image/close.png"></div>
        <?php if ($error_login) { ?>
        <div class="simplecheckout-warning-block"><?php echo $error_login ?></div>
        <?php } ?>
        <table class="simplecheckout-login">
            <tr>
                <td class="simplecheckout-login-left"><?php echo $entry_email; ?></td>
                <td class="simplecheckout-login-right"><input type="text" name="email" value="<?php echo $email; ?>" /></td>
            </tr>
            <tr>
                <td class="simplecheckout-login-left"><?php echo $entry_password; ?></td>
                <td class="simplecheckout-login-right"><input type="password" name="password" value="" /></td>
            </tr>
            <tr>
                <td></td>
                <td class="simplecheckout-login-right"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></td>
            </tr>
            <tr>
                <td></td>
                <td class="simplecheckout-login-right buttons"><a id="simplecheckout_button_login" data-onclick="login" class="button btn-primary button_oc btn"><span><?php echo $button_login; ?></span></a></td>
            </tr>
        </table>
    </div>
</div>