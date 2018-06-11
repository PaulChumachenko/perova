<?if(!defined("TDM_PROLOG_INCLUDED") || TDM_PROLOG_INCLUDED!==true) die(); ?>
<table class="tdlist">

	<?php foreach($arResult['PARTS'] as $NumKey => $arPart) : ?>
		<?php if($arPart['PKEY']=='') continue;
			$Cnt++; $PCnt=0; $OpCnt=0; $cm=''; $AddF=0;
			//Criteria display method
			if($arPart['CRITERIAS_COUNT']>0){
				foreach($arPart['CRITERIAS'] as $Criteria=>$Value){
					if($Criteria!=''){$arPart['CRITERIA'].=$cm.$Criteria.' - '.$Value;}else{$arPart['CRITERIA'].=$cm.UWord($Value);} $cm='; ';
				}
			}
			//Pictures display method
			if($arPart['IMG_ZOOM']=='Y'){
				$Zoom = $arPart['IMG_SRC'];
				$ZClass = 'cbx_imgs';
				$PicText = '';
				$Target = '';
			} else {
				$Zoom = 'https://www.google.com/search?q='.$arPart['BRAND'].'+'.$arPart['ARTICLE'].'&tbm=isch';
				$ZClass = '';
				$PicText = Lng('Search_photo_in_google',1,0);
				$Target='target="_blank"';
			}

			if(TDM_ISADMIN AND $arPart['LINK_CODE'] != ''){
				$BrandClass = 'linked';
				$BrLink = '<a href="/'.TDM_ROOT_DIR.'/admin/dbedit.php?selecttable=Y&table=TDM_LINKS&LINK='.$arPart['LINK_LEFT_AKEY'].'" target="_blank" class="ttip link" title="'.$arPart['LINK_INFO'].' '.$arPart['LINK_CODE'].'"></a>';
			} else {
				$BrandClass='';
				$BrLink='';
			}
		?>
		<tr style="border-bottom: 1px solid #11215f;">


					<td class="rigbord" width="25%">
					<?php if(is_array($arPart["IMG_ADDITIONAL"])) : ?>
					<?php foreach($arPart["IMG_ADDITIONAL"] as $AddImgSrc): ?>
						<?php $AddF++; ?>
						<a href="<?= $AddImgSrc ?>" class="cbx_imgs" rel="img<?= $arPart['PKEY'] ?>" title="<?= $arPart['BRAND'] ?> <?= $arPart['ARTICLE'] ?>"></a>
					<?php endforeach; ?>
				<?php endif; ?>
				<a href="<?= $Zoom ?>" class="image <?= $ZClass ?>" rel="img<?= $arPart['PKEY'] ?>" <?= $Target ?> title="<?= $arPart['BRAND'] ?> <?= $arPart['ARTICLE'] ?>">
					<?php if($PicText!=''): ?>
						<div class="gosrch ttip" title="<?= $PicText ?>"><?= Lng('Search_photo',1,0) ?></div>
					<?php else: ?>
						<div class="prevphoto" style="background-image:url('<?= $arPart['IMG_SRC'] ?>');">
							<?php if($AddF > 0) : ?>
								<div class="addphoto" title="<?= Lng('Photo_count',1,0); ?>">x<?= ($AddF+1) ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</a>




				<br>

			</td>
			<td valign="top" align="center" class="rigbord" width="25%">

				<br><p class="name" title="Название: <?= $arPart['TD_NAME'] ?>"><?= $arPart['NAME'] ?></p>


				<div class="criteria">
<b style=font-size:22px;><?= !empty($arPart['PC_MANUFACTURER']) ? $arPart['PC_MANUFACTURER'] : $arPart['BRAND'] ?></b>

					<p style="font-size:18px; display: inline; margin-left: 15px; color: #827d7d;"><?= $arPart['PC_SKU'] ?></p>


				</div>

				</td>






			<td valign="top" width="25%" class="rigbord">

				<div style="font-weight:bold; font-size:16px;" align="center"><?= Lng('Информация',1,0); ?></div>
				<br>
				<?php if($arPart["AID"] > 0) : ?>
					<table class="propstb">


				<div class="itemprops" id="props<?= $arPart['PKEY'] ?>">



					<?php if($arPart["PROPS_COUNT"]>0) : ?>
						<?php foreach($arPart['PROPS'] as $PName=>$PValue) : ?>
							<span class="criteria">
								<?= $PName ?><?= !empty($PValue) ? $PValue : '.' ?>
							</span><br>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php if($arPart["PROPS_COUNT"] > 3) : ?>

				<?php endif; ?>
				<?php if((!isset($_GET['brand'])) OR (TDMSingleKey($_GET['article'])!=TDMSingleKey($arPart['AKEY']) AND TDMSingleKey($_GET['brand'],true)!=TDMSingleKey($arPart['BRAND'],true)) ) : ?>

				<?php endif; ?>


				<?= $BrLink ?><br>
				<?php if($arPart['KIND'] > 0) : ?>
					<span style="font-size:11px;"><?= TDMPrintArtKinde($arPart['KIND']) ?></span>
				<?php endif; ?>

<table cellspacing="0" cellpadding="0">
 <tr>
  <td style="width: 50%">
							<a href="/<?=TDM_ROOT_DIR?>/props.php?of=<?=$arPart["AID"]?>" class="popup">
								<button type="button" class="btn btn-primary buy" class="tdcartadd">ОЕ Номера</button>	</a>
	</td>  <td style="width: 50%;">

							<a href="javascript:void(0)" OnClick="AppWin('<?=TDM_ROOT_DIR?>',<?=$arPart["AID"]?>,980)"  target="_blank">
						<button type="button" class="btn btn-primary buy" class="tdcartadd">Применяемость</button></a>
</td></tr>
					</table>
				<?php endif; ?>










			<td valign="top" align="center" width="100%" class="rigbord tdlist" <?= $HClass ?> <?= $TopBord ?>" <?= $HStyle ?>>
				<?php if($arPart["PRICES_COUNT"] > 0) : ?>

						<?php foreach($arResult['PRICES'][$arPart['PKEY']] as $arPrice) : ?>
							<?php
								$PCnt++;
								$TopBord = $PCnt > 1 ? 'topbord' : '';
								$HClass = $PCnt > $arResult['LIST_PRICES_LIMIT'] ? 'pr' . $arPart['PKEY'] : '';
								$HStyle = $PCnt > $arResult['LIST_PRICES_LIMIT'] ? 'style="display:none;"' : '';?>


								<br>
									<b style="font-size:15px; color: #000;"><?= Lng('Наличие: ',1,1) ?><?= $arPrice['AVAILABLE'] >= 4 ? ">4" : $arPrice['AVAILABLE'] ?></b>
								<br>
	<b style="font-size:12px; color: #000;"  valign="top" align="center" class="day ttip" <?= Lng('Dtime_delivery',1,0)?>"><?=Lng('В пути:',1,1) ?><?= TDM_ISADMIN ? 'title="' . $arPrice['INFO'] . '"' : '' ?>
									<?= $arPrice['DAY'] ?></b>
								<br><br><br><br>
				<p style="font-size:24px;" valign="top" align="center" class="cost ttip">
									<?php if(!empty($arPrice['EDIT_LINK'])) : ?>
										<a href="<?= $arPrice['EDIT_LINK'] ?>" class="popup editprice" title="<?= Lng('Price',1,0) ?>: <?= Lng('Edit',2,0) ?>"></a>
									<?php endif; ?>
	                                <?= $arPrice['PRICE'] ?>
	                                <?= $_SESSION['TDM_CMS_USER_GROUP'] === 7 ? $arPrice['OPTIONS']['VIEW_INTAB'] : '' ?></p>
								<br><br><br>
									<b valign="top" align="center" class="tocart">
									<button type="button" class="btn btn-primary buy" class="tdcartadd" OnClick="TDMAddToCart('<?= $arPrice['PHID'] ?>')"><img src="https://autopartix.com/autoparts/media/cart/But.png" width="35" height="25" style="vertical-align: middle">В корзину</button>
		                            <input type="number" style="width:35px;border: 1px solid #337ab7;" value="1" id="Qt_465e8b499389d5f8809393c6505cfa4d" min="1" max="2"></b>

						<?php endforeach; ?>

					<?php if($arPart["PRICES_COUNT"]>$arResult['LIST_PRICES_LIMIT']) : ?>
						<a href="javascript:void(0)" OnClick="ShowMoreListPrices('<?=$arPart['PKEY']?>')" class="sbut sb<?=$arPart['PKEY']?>">&#9660; <?=Lng('Show_more_prices',1,0)?> (<?=($arPart["PRICES_COUNT"]-$arResult['LIST_PRICES_LIMIT'])?>)</a>
					<?php endif; ?>
				<?php elseif($arResult['ALLOW_ORDER']==1) : ?>
					<a href="javascript:void(0)" class="tdorder" OnClick="TDMOrder('<?= $arPart['PKEY'] ?>')"><?= Lng('Order',1,0) ?></a>
				<?php endif; ?>
				<?php if(TDM_ISADMIN) : ?>
					<?= $arPart["PRICES_COUNT"] <= 0 ? "<br/>" : "" ?>
					<a href="/<?=TDM_ROOT_DIR?>/admin/dbedit_price.php?ID=NEW&ARTICLE=<?=urlencode($arPart['ARTICLE'])?>&BRAND=<?=urlencode($arPart['BRAND'])?>" class="popup addprice" title="Add price record">+$</a>
					<a href="/<?=TDM_ROOT_DIR?>/admin/dbedit_link.php?ID=NEW&BKEY=<?=$arPart['BKEY']?>&AKEY=<?=$arPart['AKEY']?>" class="popup addprice" title="Add cross record">+X</a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>