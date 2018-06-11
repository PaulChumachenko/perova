<style type="text/css">
.iSearchBox li .iMarq {
	background-color:<?php echo (empty($data['iSearch']['HighlightColor'])) ? '#F7FF8C' : $data['iSearch']['HighlightColor'] ?>;
}
.iSearchBoxWrapper .iSearchBox {
	width: <?php echo (empty($data['iSearch']['ResultsBoxWidth'])) ? '370px' : $data['iSearch']['ResultsBoxWidth'] . (is_numeric($data['iSearch']['ResultsBoxWidth']) ? 'px' : ''); ?> !important;
}

<?php if (!empty($data['iSearch']['ResultsShowImages'])): ?>
	<?php if ($data['iSearch']['ResultsShowImages'] == 'no'): ?>
		.iSearchBox li img {
			display:none;
		}
		.iSearchBox li h3 {
			width: 72%;
		}
	<?php endif; ?>
	<?php if ($data['iSearch']['ResultsShowModels'] == 'no'): ?>
		.iSearchBox li .iSearchModel {
			display:none;
		}
	<?php endif; ?>
	<?php if ($data['iSearch']['ResultsShowPrices'] == 'no'): ?>
		.iSearchBox li .iSearchPrice {
			display:none;
		}
	<?php endif; ?>
	<?php if ($data['iSearch']['ResultsTitleFontSize'] != ''): ?>
		.iSearchBox li h3 {
			font-size:<?php echo $data['iSearch']['ResultsTitleFontSize'] . (is_numeric($data['iSearch']['ResultsTitleFontSize']) ? 'px' : '')?>;
		}
	<?php endif; ?>
	<?php if ($data['iSearch']['ResultsBoxTitleWidth'] != ''): ?>
		.iSearchBox li h3 {
			width:<?php echo $data['iSearch']['ResultsBoxTitleWidth'] . (is_numeric($data['iSearch']['ResultsBoxTitleWidth']) ? 'px' : '')?>;
		}
	<?php endif; ?>
	.iSearchBox li h3 {
		font-weight:<?php echo $data['iSearch']['ResultsTitleFontWeight']?>;
	}
	<?php if ($data['iSearch']['ResultsBoxHeight'] != ''): ?>
	.iSearchBox li .iSearchItem {
		height: <?php echo $data['iSearch']['ResultsBoxHeight'] . (is_numeric($data['iSearch']['ResultsBoxHeight']) ? 'px' : '')?>;
	}
	<?php endif; ?>
<?php endif; ?>
</style>

<style type="text/css">
<?php echo $data['iSearch']['CustomCSS']; ?>
</style>

<script type="text/javascript">
	var ocVersion = "<?php echo (defined('VERSION')) ? VERSION : '1.5.5.1'; ?>";
	var moreResultsText = '<?php echo !empty($data['iSearch'][$language_id]['ResultsMoreResultsLabel']) ? $data['iSearch'][$language_id]['ResultsMoreResultsLabel'] : 'View All Results';?>';
	var noResultsText = '<?php echo !empty($data['iSearch'][$language_id]['ResultsNoResultsLabel']) ? $data['iSearch'][$language_id]['ResultsNoResultsLabel'] : 'No results found';?>';
	//var SCWords = $.parseJSON('<?php echo json_encode($data['iSearch']['SCWords'])?>');
	//var spellCheckSystem = '<?php echo $data['iSearch']['ResultsSpellCheckSystem']?>';
	var useAJAX = '<?php echo $data['iSearch']['UseAJAX']?>';
	var loadImagesOnInstantSearch = '<?php echo $data['iSearch']['ResultsShowImages']?>';
	var useStrictSearch = '<?php echo $data['iSearch']['UseStrictSearch']?>';
	var responsiveDesign = '<?php echo $data['iSearch']['ResponsiveDesign']?>';
    var afterHittingEnter = '<?php echo $data['iSearch']['AfterHittingEnter']?>';
    var productHeadingInstant = '<?php echo !empty($data['iSearch'][$language_id]['ProductHeadingInstant']) ? $data['iSearch'][$language_id]['ProductHeadingInstant'] : ""; ?>';
	var suggestionHeadingInstant = '<?php echo !empty($data['iSearch'][$language_id]['SuggestionHeadingInstant']) ? $data['iSearch'][$language_id]['SuggestionHeadingInstant'] : ""; ?>';
	var searchInModel = '<?php echo (!empty($data['iSearch']['SearchIn']['ProductModel'])) ? 'yes' : 'no'?>';
	var searchInDescription = <?php echo (!empty($data['iSearch']['SearchIn']['Description'])) ? 'true' : 'false'?>;
	var productsData = [];
	var iSearchResultsLimit = '<?php echo $data['iSearch']['ResultsLimit']?>';
</script>