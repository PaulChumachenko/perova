<?php if(!defined("TDM_PROLOG_INCLUDED") || TDM_PROLOG_INCLUDED!==true) die(); ?>

<table class="tdlist">
	<tr class="head">
		<td>&nbsp;<?=Lng('Number',1,0);?></td>
		<td>&nbsp;<?=Lng('Brand1',1,0);?></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=Lng('Article',1,0);?></td>
		<td><?=Lng('Name',1,0);?></td>
		<td></td><td></td><td></td>
		<?php if($_SESSION['TDM_CMS_USER_GROUP']===7): ?>
			<?= $arPrice['OPTIONS']['VIEW_INTAB'] ?>
		<?php endif; ?>
		<td style="padding:0px; text-align:right;">
			<table class="listprice"><tr class="thead"></table>
		</td>
	</tr>

	<?php foreach($arResult['PARTS'] as $NumKey => $arPart) : ?>
		<?php if($arPart['PKEY']=='') continue;
		$Cnt++; $PCnt=0; $OpCnt=0; $cm=''; $AddF=0;
		//Criteria display method
		if($arPart['CRITERIAS_COUNT']>0){
			foreach($arPart['CRITERIAS'] as $Criteria=>$Value){
				if($Criteria!=''){$arPart['CRITERIA'].=$cm.$Criteria.' - '.$Value;}else{$arPart['CRITERIA'].=$cm.UWord($Value);} $cm='; ';
			}
		}

		if(TDM_ISADMIN AND $arPart['LINK_CODE']!=''){
			$BrandClass='linked';
			$BrLink = '<a href="/'.TDM_ROOT_DIR.'/admin/dbedit.php?selecttable=Y&table=TDM_LINKS&LINK='.$arPart['LINK_LEFT_AKEY'].'" target="_blank" class="ttip link" title="'.$arPart['LINK_INFO'].'<br>'.$arPart['LINK_CODE'].'"></a>';
		} else {
			$BrandClass='';
			$BrLink='';
		} ?>
		<tr class="cols">
			<td><a href="/<?=TDM_ROOT_DIR?>/search/<?= $arPart['AKEY'] ?>/<?= BrandNameEncode($arPart['BRAND']) ?>"><?= $arPart['PC_SKU'] ?></a></td>
			<td class="tdbrand">
				<a href="/<?=TDM_ROOT_DIR?>/search/<?= $arPart['AKEY'] ?>/<?= BrandNameEncode($arPart['BRAND']) ?>" class="<?=$BrandClass?>" title="<?= Lng('Information_about_brand',0,0);?>"><?= !empty($arPart['PC_MANUFACTURER']) ? $arPart['PC_MANUFACTURER'] : $arPart['BRAND'] ?></a>
				<?= $BrLink ?><br/>
				<?php if($arPart['KIND']>0) : ?>
					<span style="font-size:11px;"><?= TDMPrintArtKinde($arPart['KIND']) ?></span>
				<?php endif; ?>
			</td>
			<td><a href="/<?=TDM_ROOT_DIR?>/search/<?= $arPart['AKEY'] ?>/<?= BrandNameEncode($arPart['BRAND']) ?>"><?= $arPart['ARTICLE'] ?></a></td>
			<td>
				
					<p class="name" title="<?=$arPart['TD_NAME']?>"><?= $arPart['NAME'] ?></p>

			</td>

			<td style="width:17px; white-space:nowrap;" class="rigbord">
				<?php if($arPart["AID"]>0) : ?>
					<table class="propstb">
						<tr>
							<td><a href="/<?=TDM_ROOT_DIR?>/props.php?of=<?= $arPart["AID"] ?>" class="dopinfo popup" title="<?= Lng('Additional_Information',1,0) ?>"></a></td>
							<td><a href="javascript:void(0)" onclick="AppWin('<?=TDM_ROOT_DIR?>',<?=$arPart["AID"]?>,980)" class="carsapp" target="_blank" title="<?=Lng('Applicability_to_model_cars',1,0)?>"></a></td>
						</tr>
					</table>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<?php if($arPart["PRICES_COUNT"] > 0) : ?>
					<table class="listprice">
						<?php foreach($arResult['PRICES'][$arPart['PKEY']] as $arPrice) : ?>
							<?php
							$PCnt++;
							$TopBord = $PCnt > 1 ? 'topbord' : '';
							$HClass = $PCnt > $arResult['LIST_PRICES_LIMIT'] ? 'pr'.$arPart['PKEY'] : '';
							$HStyle = $PCnt > $arResult['LIST_PRICES_LIMIT'] ? 'style="display:none;"' : '';
							?>

						<?php endforeach; ?>
						<?php if($arPart["PRICES_COUNT"]>$arResult['LIST_PRICES_LIMIT']) : ?>
							<a href="javascript:void(0)" onclick="ShowMoreListPrices('<?=$arPart['PKEY']?>')" class="sbut sb<?=$arPart['PKEY']?>">&#9660; <?=Lng('Show_more_prices',1,0)?> (<?=($arPart["PRICES_COUNT"]-$arResult['LIST_PRICES_LIMIT'])?>)</a>
						<?php endif; ?>
					</table>
				<?php elseif($arResult['ALLOW_ORDER']==1) : ?>
					<a href="javascript:void(0)" class="tdorder" onclick="TDMOrder('<?=$arPart['PKEY']?>')"><?=Lng('Order',1,0)?></a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>