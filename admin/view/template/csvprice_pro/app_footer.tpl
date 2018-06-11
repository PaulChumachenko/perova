<div class="f-help-block f-tooltip" id="prop_desc_win">
    <div class="c_top l"></div>
    <div class="c_top r"></div>
    <div class="close" onclick="$('#prop_desc_win').hide()"></div>
    <div class="f-help-content" id="prop_desc_win_content"></div>
    <div class="c_btm l"></div>
    <div class="c_btm r"></div>
</div>
<script type="text/javascript">
	var w = 0;
    $('a.delete').on('click', function () {
        if (!confirm('<?php echo $text_confirm_delete; ?>')) {
            return false;
        }
    });
    $(document).ready(function () {
        $('.form-group label').each(function () {
            var h, i, e = $(this);
            if (e.attr('data-prop_id')) {
                h = e.html();
                if (e.find("input").length) {
                    e.before('<span data-prop_id="' + e.attr('data-prop_id') + '" class="f-icon-helper" aria-hidden="true">');
                    e.after('</span>');

                    i = e.next();
                } else {
                    e.html('<span data-prop_id="' + e.attr('data-prop_id') + '" class="f-icon-helper" aria-hidden="true">' + h + '</span>');
                    i = e.find('.f-icon-helper');
                }
                if (i.length) {
                    i.click(function (ev) {
                        var o = $(this).offset();
						if ($('#prop_desc_win').is(":visible") && (o.top + o.left) == w) {
                            $('#prop_desc_win').hide();
                        } else {
                            $("#prop_desc_win_content").html(prop_descr[$(this).attr('data-prop_id')]);
                            $('#prop_desc_win').css({
                                top: o.top + 20,
                                left: o.left - 10
                            }).show();
                            w = o.top + o.left;
                        }
                        return false;
                    });
                }
            }
        });
    });

    $(document).keyup(function (e) {
        if (e.keyCode == 27 && !$('#prop_desc_win').is(':hidden')) {
            $('#prop_desc_win').hide()
        }
    });
	$(window).click( function(e) {
		if(e.target.className !== 'f-help-block')
			$('.f-help-block').hide();
	});
	$('#prop_desc_win').click(function(event){
		event.stopPropagation();
	});
    $(function () {
        $(".show_scroll").click(function (e) {
            e.preventDefault();
            var d = $('#oc2-dialog-scroll'), m = $('#oc2-modal-body'), id = $(this).parent().attr('id'), k = 0;
            m.html('<div class="scrollbox" style="width: 100% !important; height: 100% !important">' + $("#" + id + " div.scrollbox").html() + '</div>');
            $("#" + id + ' input[type="checkbox"]').each(function () {
                $(this).attr("id", "l-scroll_" + k);
                k++;
            });
            k = 0;
            $('#oc2-modal-body input[type="checkbox"]').each(function () {
                $(this).attr("id", "k-scroll_" + k);
                if ($("#l-scroll_" + k).prop('checked')) {
                    $(this).prop('checked', true);
                }
                k++;
            });
            d.on('show.bs.modal', function (e) {
                $('#oc2-modal-body input[type="checkbox"').change(function () {
                    $('#' + $(this).attr('id').replace('k-scroll_', 'l-scroll_')).prop('checked', $(this).prop('checked'));
                });
            })
            d.on('hidden.bs.modal', function (e) {
                $("#" + id + ' input[type="checkbox"]').each(function () {
                    $(this).attr('id', '');
                });
                m.html('');
            })
            d.modal('show');
            return false;
        });
    });
</script>
