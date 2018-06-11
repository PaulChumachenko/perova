<?define('TDM_PROLOG_INCLUDED',true);
require_once("tdmcore/defines.php");
require_once("tdmcore/init.php");
?>
<link rel="stylesheet" href="/<?=TDM_ROOT_DIR?>/styles.css" type="text/css">
<div style="padding:30px;">
<?
$AID=intval($_GET['of']);
if($AID<=0){echo 'Error! Invalid number parameters.'; die();}

$TDMCore->DBSelect("TECDOC");
$rsProps = TDSQL::GetPropertys($AID);
$arUnqs=Array();
while($arProp = $rsProps->Fetch()){
	$Unq=$arProp['NAME'].$arProp['VALUE'];
	if(!in_array($Unq,$arUnqs)){
		$arUnqs[] = $Unq;
		$arProps[] = $arProp;
	}
}
if(count($arProps)>0){?>
	<table class="chartab"><tr class="head"><td colspan="2"><?=Lng('Characteristics',1,0)?>:</td></tr>
	<?
	foreach($arProps as $arProp){
		$arProp['NAME'] = str_replace('/мм?','/мм',$arProp['NAME']);
		$arProp['NAME'] = str_replace('? ',' ',$arProp['NAME']);
		if(strpos($arProp['NAME'],'[')>0){
			$Dim = substr($arProp['NAME'],strpos($arProp['NAME'],'['));
			$arProp['NAME'] = str_replace(' '.$Dim,'',$arProp['NAME']);
			$Dim = str_replace('[','',$Dim); $Dim = str_replace(']','',$Dim);
			$arProp['VALUE'] = $arProp["VALUE"].' '.$Dim;
		}
		?>
		<tr><td class="tarig"><?=UWord($arProp['NAME'])?>: </td><td><?=$arProp['VALUE']?></td></tr>
		<?
	}
	?>
	</table>
	<br>
	<div class="tclear"></div>
<?}?>


<?$rsNums = TDSQL::LookupAnalog($AID,Array(2,3,5)); //2-Торговый, 3-Оригинальный, 4-Неоригинальный, 5-Штрих код
while($arNum = $rsNums->Fetch()){
	$arNums[] = $arNum;
}
if(count($arNums)>0){?>

<?}?>
<?
$rsPDFs = TDSQL::GetPDFs($AID);
while($arPDF = $rsPDFs->Fetch()){
	echo '<a href="http://'.TECDOC_FILES_PREFIX.$arPDF['PATH'].'" title="Download PDF"><img src="/'.TDM_ROOT_DIR.'/media/images/pdf32.png" width="32px" height="32px" style="float:left; margin:4px 0px 0px 16px; "/></a>';
}
?>
<div class="tclear"></div>
	<br>
	<input type="button" value="<?=Lng('Close',1,0)?>" onClick="parent.$.fn.colorbox.close()" class="abutton grbut"/>
</div>