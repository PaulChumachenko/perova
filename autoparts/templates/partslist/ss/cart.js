$(document).ready(function () {
   $('.partItem .dropdown-menu').bind('click', function (event) {
       var next = $($(event.target).parents('li'));
       var nextRepl = $($(event.target).parents('ul.dropdown-menu'));
       var current = $($($($($(event.target).parents('.btn-group')).children('.dropdown-toggle')).children('ul')).children('li'));
       var currentBlock = $($($($(event.target).parents('.btn-group')).children('.dropdown-toggle')).children('ul'));
       var buttonBuy = $($($($(event.target).parents('.itemPrices')).children('p')).children('.buy'));
       var qty = $($($($(event.target).parents('.itemPrices')).children('p')).children('input[type="number"]'));
       var qtyNext = $($($($($(event.target).parents('table')).children('tbody')).children('tr:eq(1)')).children('td')).text().replace(/[<>]+/, '');
       var nextData = next.children('table').data('phid');

       console.log(nextData);

       if (typeof nextData != 'undefined') {
           buttonBuy.data('phid', nextData);
           qty.attr('id', 'Qt_' + nextData);
           qty.attr('name', 'Qt_' + nextData);
           qty.attr('max', qtyNext);
           qty.val(1);
           $(this).prepend(current.clone());
           currentBlock.empty();
           currentBlock.append(next);
       }

   });

    $('.partItem button.buy').bind('click', function (event) {
        var phid = $(this).data('phid');
        TDMAddToCart(phid);
    });
});