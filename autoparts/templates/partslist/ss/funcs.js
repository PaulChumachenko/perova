function ShowMPrices(pkey){$('.ip'+pkey).show(); $('.sb'+pkey).hide(); $('.hb'+pkey).show();}
function HideMPrices(pkey){$('.ip'+pkey).hide(); $('.hb'+pkey).hide(); $('.sb'+pkey).show();}

function ShowMoreListPrices(pkey){
	$('.pr'+pkey).show('fast'); $('.sb'+pkey).hide(); $('.op'+pkey).show('fast');
}

function AddTips(track){
	$(function() {  $( document ).tooltip({track:true, content:function(){return $(this).prop('title');}});   });
}

function AddFSlyler(options){
	(function($) {  $(function(){ $(options).styler(); }) })(jQuery)
}

function TDMAddToCart(PHID){
	var QTY = $("#Qt_"+PHID).val();
	$("<form action='' id='addcartform' method='post'><input type='hidden' name='PHID' value='"+PHID+"'/><input type='hidden' name='QTY' value='"+QTY+"'/></form>").appendTo('body');
	$("#addcartform").submit();
}

function ResetWSCache(){
	$("<form action='' id='resetwscform' method='post'><input type='hidden' name='wsc' value='reset'/>").appendTo('body');
	$("#resetwscform").submit();
}

function TDMOrder(PKEY){
	$("<form action='' id='addcartform' method='post'><input type='hidden' name='TDORDER' value='"+PKEY+"'/></form>").appendTo('body');
	$("#addcartform").submit();
}

function AppWin(TDM_ROOT_DIR,ID,Width){
	var Left = (screen.width/2)-(Width/2);
	var Height = (screen.height-200);
	var newWin = window.open("/"+TDM_ROOT_DIR+"/apps.php?of="+ID, "JSSite",
	   "width="+Width+",height="+Height+",left="+Left+",top=40,resizable=yes,scrollbars=yes,status=no,menubar=no,toolbar=no,location=no,directories=no"
	);
	newWin.focus();
	$(newWin).blur(function() {
		newWin.close();
	});
}

var ShowLettersFilter=0;
$(document).ready(function () {
    if(ShowLettersFilter==1){
		var ABrandsDiv=$('.bfname');
		ABrandsDiv.hide();
		var LetsDiv = $('.letfilter > a');
		LetsDiv.click(
			function (){
				FstLet=$(this).text();
				LetsDiv.removeClass("active");
				$(this).addClass("active");
				if(FstLet=='ВСЕ') ABrandsDiv.show();
				else{
					ABrandsDiv.hide();
					ABrandsDiv.each(function(i){
						var AText = $(this).eq(0).text().toUpperCase();
						if(RegExp('^' + FstLet).test(AText)) {
							$(this).fadeIn(400);
						}
					});
				}
		});
	}
});

var FIRST_PAGE_LINK='';
function AddBrandFilter(BKEY){
	$("<form action='"+FIRST_PAGE_LINK+"' id='bfilterform' method='post'><input type='hidden' name='BRAND_FILTER' value='"+BKEY+"'/></form>").appendTo('body');
	$("#bfilterform").submit();
}

function RemoveBrandFilter(BKEY){
	$("<form action='"+FIRST_PAGE_LINK+"' id='bfilterform' method='post'><input type='hidden' name='BRAND_REMOVE' value='"+BKEY+"'/></form>").appendTo('body');
	$("#bfilterform").submit();
}

function ShowMoreProps(But,TDItem){
	var curHeight = $('#'+TDItem).height();
	autoHeight = $('#'+TDItem).css('height','auto').height();
	$('#'+TDItem).height(curHeight);
	$('#'+TDItem).stop().animate({'height':autoHeight}, 500);
	$(But).hide('normal');
}