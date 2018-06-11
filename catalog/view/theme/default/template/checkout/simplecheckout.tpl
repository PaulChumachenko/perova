<?php if (!$ajax && !$popup && !$as_module) { ?>
<?php
$simple_page = 'simplecheckout';
$heading_title .= $display_weight ? '&nbsp;(<span id="weight">'. $weight . '</span>)' : '';
include $simple_header;
?>
<style>
    <?php if ($left_column_width) { ?>
        .simplecheckout-left-column {
            width: <?php echo $left_column_width ?>%;
        }
    <?php } ?>
    <?php if ($right_column_width) { ?>
        .simplecheckout-right-column {
            width: <?php echo $right_column_width ?>%;
        }
    <?php } ?>
    <?php if ($customer_with_payment_address) { ?>
        #simplecheckout_customer {
            margin-bottom: 0;
        }
        #simplecheckout_customer .simplecheckout-block-content {
            border-bottom-width: 0;
            padding-bottom: 0;
        }
        #simplecheckout_payment_address div.checkout-heading {
            display: none;
        }
        #simplecheckout_payment_address .simplecheckout-block-content {
            border-top-width: 0;
            padding-top: 0;
        }
    <?php } ?>
    <?php if ($customer_with_shipping_address) { ?>
        #simplecheckout_customer {
            margin-bottom: 0;
        }
        #simplecheckout_customer .simplecheckout-block-content {
            border-bottom-width: 0;
            padding-bottom: 0;
        }
        #simplecheckout_shipping_address div.checkout-heading {
            display: none;
        }
        #simplecheckout_shipping_address .simplecheckout-block-content {
            border-top-width: 0;
            padding-top: 0;
        }
    <?php } ?>
</style>
<div class="simple-content">
<?php } ?>
    <?php if (!$ajax || ($ajax && $popup)) { ?>
    <script type="text/javascript">
    (function($) {
    <?php if (!$popup && !$ajax) { ?>
        $(function(){
    <?php } ?>
            if (typeof Simplecheckout === "function") {
                var simplecheckout_<?php echo $group ?> = new Simplecheckout({
                    mainRoute: "checkout/simplecheckout",
                    additionalParams: "<?php echo $additional_params ?>",
                    additionalPath: "<?php echo $additional_path ?>",
                    mainUrl: "<?php echo $action; ?>",
                    mainContainer: "#simplecheckout_form_<?php echo $group ?>",
                    currentTheme: "<?php echo $current_theme ?>",
                    loginBoxBefore: "<?php echo $login_type == 'flat' ? '#simplecheckout_customer .simplecheckout-block-content:first' : '' ?>",
                    displayProceedText: <?php echo $display_proceed_text ? 1 : 0 ?>,
                    scrollToError: <?php echo $scroll_to_error ? 1 : 0 ?>,
                    scrollToPaymentForm: <?php echo $scroll_to_payment_form ? 1 : 0 ?>,
                    useAutocomplete: <?php echo $use_autocomplete ? 1 : 0 ?>,
                    useGoogleApi: <?php echo $use_google_api ? 1 : 0 ?>,
                    popup: <?php echo $popup ? 1 : 0 ?>,
                    javascriptCallback: function() {<?php echo $javascript_callback ?>}
                });

                simplecheckout_<?php echo $group ?>.init();

                $(document).ajaxComplete(function(e, xhr, settings) {
                    if (settings.url.indexOf("route=module/cart&remove") > 0 || (settings.url.indexOf("route=module/cart") > 0 && settings.type == "POST")) {
                        simplecheckout_<?php echo $group ?>.reloadAll();
                    }
                });
            }
    <?php if (!$popup && !$ajax) { ?>
        });
    <?php } ?>
    })(jQuery || $);
    </script>
    <?php } ?>
    <div id="simplecheckout_form_<?php echo $group ?>" <?php echo $has_error ? 'data-error="true"' : '' ?>>
        <div class="simplecheckout">
            <?php if ($steps_count > 1) { ?>
                <div id="simplecheckout_step_menu">
                <?php for ($i=1;$i<=$steps_count;$i++) { ?><span class="simple-step" data-onclick="gotoStep" data-step="<?php echo $i; ?>"><?php echo $step_names[$i-1] ?></span><?php if ($i < $steps_count) { ?><span class="simple-step-delimiter" data-step="<?php echo $i+1; ?>"><img src="<?php echo $additional_path ?>catalog/view/image/next_gray.png"></span><?php } ?><?php } ?>
                </div>
            <?php } ?>
            <?php
                $replace = array(
                    '{three_column}'     => '<div class="simplecheckout-three-column">',
                    '{/three_column}'    => '</div>',
                    '{left_column}'      => '<div class="simplecheckout-left-column">',
                    '{/left_column}'     => '</div>',
                    '{right_column}'     => '<div class="simplecheckout-right-column">',
                    '{/right_column}'    => '</div>',
                    '{step}'             => '<div class="simplecheckout-step">',
                    '{/step}'            => '</div>',
                    '{customer}'         => $simple_blocks['customer'],
                    '{payment_address}'  => $simple_blocks['payment_address'],
                    '{shipping_address}' => $simple_blocks['shipping_address'],
                    '{cart}'             => $simple_blocks['cart'],
                    '{shipping}'         => $simple_blocks['shipping'],
                    '{payment}'          => $simple_blocks['payment'],
                    '{agreement}'        => $simple_blocks['agreement'],
                    '{help}'             => $simple_blocks['help'],
                    '{summary}'          => $simple_blocks['summary'],
                    '{comment}'          => $simple_blocks['comment'],
                    '{payment_form}'     => !empty($simple_blocks['payment_form']) ? '<div class="simplecheckout-block" id="simplecheckout_payment_form">'.$simple_blocks['payment_form'].'</div>' : ''
                );

                $find = array(
                    '{three_column}',
                    '{/three_column}',
                    '{left_column}',
                    '{/left_column}',
                    '{right_column}',
                    '{/right_column}',
                    '{step}',
                    '{/step}',
                    '{customer}',
                    '{payment_address}',
                    '{shipping_address}',
                    '{cart}',
                    '{shipping}',
                    '{payment}',
                    '{agreement}',
                    '{help}',
                    '{summary}',
                    '{comment}',
                    '{payment_form}'
                );

                foreach ($simple_blocks as $key => $value) {
                    $key_clear = $key;
                    $key = '{'.$key.'}';
                    if (!array_key_exists($key, $replace)) {
                        $find[] = $key;
                        $replace[$key] = '<div class="simplecheckout-block" id="'.$key_clear.'">'.$value.'</div>';
                    }
                }

                echo trim(str_replace($find, $replace, $simple_template));
            ?>
            <div id="simplecheckout_bottom" style="width:100%;height:1px;clear:both;"></div>
            <div class="simplecheckout-proceed-payment" id="simplecheckout_proceed_payment" style="display:none;"><?php echo $text_proceed_payment ?></div>
            <?php if ($error_warning_agreement && $display_error) { ?>
                <div class="simplecheckout-warning-block" id="agreement_warning" data-error="true"><?php echo $error_warning_agreement ?></div>
            <?php } ?>
            <?php if (!$block_order) { ?>

                        <?php } ?>

                    </div>

                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="button btn-primary button_oc btn" data-onclick="createOrder" id="simplecheckout_button_confirm"><span><?php echo $button_order; ?></span></a>

        </div>
    </div>
<?php if (!$ajax && !$popup && !$as_module) { ?>
</div>
<?php include $simple_footer ?>
<?php } ?>