<?if(!defined("TDM_PROLOG_INCLUDED") || TDM_PROLOG_INCLUDED!==true)die();?>
<link rel="stylesheet" href="/<?=TDM_ROOT_DIR?>/media/js/colorbox/cmain.css" />
<script type="text/javascript" language="javascript" src="/<?=TDM_ROOT_DIR?>/media/js/colorbox/colorbox.js"></script>
<?jsLinkFormStyler()?>
<script>AddFSlyler('select');</script>

<script> $(function() {
	$(".popup").colorbox({rel:false, current:'', preloading:false, arrowKey:false, scrolling:false, overlayClose:false});
	$('.ttip').tooltip({ position:{my:"left+25 top+20"}, track:true, content:function(){return $(this).prop('title');}});   });
</script>

<div class="tclear"></div>
<h1><?=TDM_H1?></h1>
<?TDMShowBreadCumbs()?>
<hr style="width:86%;">
<div class="autopic" title="<?=$arResult['MFA_MFC_CODE']?>" style="background:url(<?=$arResult['BRAND_LOGO_SRC']?>)"></div>
<?=TDMShowSEOText("TOP")?>

<?if(count($arResult['PARTS'])>0){?>


<?if($arResult['SHOW_FILTER_BRANDS']==100 AND $arResult['ALL_BRANDS_COUNT']>100 AND ($arResult['PAGINATION']['TOTAL_PAGES']>1 OR $arResult['FILTERED_BRANDS_COUNT']>100) ){?>
	<script>FIRST_PAGE_LINK='<?=$arResult['FIRST_PAGE_LINK']?>';</script>
	<div class="filterdiv">
		<div class="bftitle"><?=Lng('Filter_by_manufacturer',1,0)?>: </div>
		<?if($arResult['ALL_BRANDS_COUNT']>$arResult['LETTERS_LIMIT']){?>
			<div class="letfilter"><?foreach($arResult['ALL_BRANDS_LETTERS'] as $LET){?><a href="javascript:void(0)"><?=$LET?></a><?}?></div><div class="tclear"></div>
			<script>ShowLettersFilter=1;</script>
			<div class="allbrands">
				<?foreach($arResult['ALL_BRANDS'] as $BKEY=>$BRAND){
					if($arResult['AB_MIN_PRICE_F'][$BKEY]>0){$MinPrice='<i>'.Lng('from',2,0).'</i> <span>'.$arResult['AB_MIN_PRICE_F'][$BKEY].'</span>';}else{$MinPrice='';}?>
					<a href="javascript:void(0)" class="bfname" OnClick="AddBrandFilter('<?=$BKEY?>')"><?=$BRAND?> <?=$MinPrice?></a>
				<?}?>
			</div>
			<div class="tclear"></div>
			<?if($arResult['FILTERED_BRANDS_COUNT']>0){?>
				<div class="allbrands" style="padding-top:10px;">
					<div class="filteredby"><?=Lng('Filtered_by',1,0)?>: </div>
					<?foreach($arResult['FILTERED_BRANDS'] as $BKEY=>$BRAND){
						if($arResult['AB_MIN_PRICE_F'][$BKEY]>0){$MinPrice='<i>'.Lng('from',2,0).'</i> <span>'.$arResult['AB_MIN_PRICE_F'][$BKEY].'</span>';}else{$MinPrice='';}?>
						<a href="javascript:void(0)" class="remove" OnClick="RemoveBrandFilter('<?=$BKEY?>')"><?=$BRAND?> <?=$MinPrice?> <div class="delimg"></div></a>
					<?}
					if($arResult['FILTERED_BRANDS_COUNT']>1){?>
						<a href="javascript:void(0)" class="removeall" OnClick="RemoveBrandFilter('BFRA')"><div></div></a>
					<?}?>
				</div>
			<?}?>
		<?}else{?>
			<div class="allbrands">
				<?foreach($arResult['ALL_BRANDS'] as $BKEY=>$BRAND){
					if($arResult['AB_MIN_PRICE_F'][$BKEY]>0){$MinPrice='<i>'.Lng('from',2,0).'</i> <span>'.$arResult['AB_MIN_PRICE_F'][$BKEY].'</span>';}else{$MinPrice='';}
					if(array_key_exists($BKEY,$arResult['FILTERED_BRANDS'])){?>
						<a href="javascript:void(0)" class="remove" OnClick="RemoveBrandFilter('<?=$BKEY?>')"><?=$BRAND?> <?=$MinPrice?> <div class="delimg"></div></a>
					<?}else{?>
						<a href="javascript:void(0)" class="bfname" OnClick="AddBrandFilter('<?=$BKEY?>')"><?=$BRAND?> <?=$MinPrice?></a>
					<?}?>
				<?}?>
				<?if($arResult['FILTERED_BRANDS_COUNT']>1){?>
					<a href="javascript:void(0)" class="removeall" OnClick="RemoveBrandFilter('BFRA')"> <?=$MinPrice?> <div></div></a>
				<?}?>
			</div>

		<?}?>
		<div class="tclear"></div>
		<hr>
	</div>
<?}?>



<?if($arResult['GROUP_NAME']!=''){?>
	<div class="pricetype">
		<?=Lng('Your_prices_level')?>: <b><?=$arResult['GROUP_NAME']?>
		<?if($arResult['GROUP_VIEW']==2){echo '('.$arResult['GROUP_DISCOUNT'].'%)';}?></b>
	</div>
<?}?>
<div class="tclear"></div>

<?// VIEWS
if($arResult['VIEW']=="CARD"){
	include('view_card.php');
}elseif($arResult['VIEW']=="LIST"){
	include('view_list.php');
}


}else{?>
	<br><br>
	<b><?=Lng('No_parts_for_model')?>...</b>
	<br><br><br><br>
<?}?>

<div class="tclear"></div>

<?if($arResult['PAGINATION']['TOTAL_PAGES']>1 AND $arResult['PAGINATION']['ITEMS_ON_THIS_PAGE']>6){?>
	<br>
	<?TDMShowPagination($arResult['PAGINATION'],Array(
		"PAGE_TEXT"=>"Y",
		"TOTAL_TEXT"=>Lng('Total_items',1,0),
		"PAGES_DIAPAZON"=>6,
	))?>
	<div class="tclear"></div>
	<hr>
<?}?>



<?=TDMShowSEOText("BOT")?>
<br>
<br>


<script>
	$(document).ready(function(){
		$(".cbx_imgs").colorbox({ current:'', innerWidth:900, innerHeight:600, onComplete:function(){$('.cboxPhoto').unbind().click($.colorbox.next);} });
		$(".cbx_chars").colorbox({rel:false, current:'', overlayClose:true, arrowKey:false, opacity:0.6});


	});
</script>

<?//echo '<pre>'; print_r($arResult); echo '</pre>';?>