<table class="form">
  <tr>
    <td><span class="required">*</span> <?php echo $entry_code; ?></td>
    <td valign="top">
        <div class="">
            <select name="iSearch[Enabled]" class="iSearchEnabled form-control">
                <option value="yes" <?php echo ($data['iSearch']['Enabled'] == 'yes') ? 'selected=selected' : ''?>>Enabled</option>
                <option value="no" <?php echo ($data['iSearch']['Enabled'] == 'no') ? 'selected=selected' : ''?>>Disabled</option>
            </select>
        </div>    
   </td>
  </tr>
  <tr>
    <td valign="top"><span class="required">*</span> Search in: <span class="help">Choose only the parameters you need.</span></td>
    <td>
    <div>    
        <div class="searchInSpan">
            <input type="checkbox" id="searchIn1" name="iSearch[SearchIn][ProductName]" <?php if(!empty($data['iSearch']['SearchIn']['ProductName'])) { echo ($data['iSearch']['SearchIn']['ProductName'] == 'on') ? 'checked=checked' : ''; } else { echo ''; } ?> /><label class="checkbox-inline lbl" for="searchIn1">Product Name</label>
        </div>
        <div class="searchInSpan">
            <input type="checkbox" id="searchIn2" name="iSearch[SearchIn][ProductModel]"  <?php if(!empty($data['iSearch']['SearchIn']['ProductModel'])) echo ($data['iSearch']['SearchIn']['ProductModel'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn2">Product Model</label>
        </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn3" name="iSearch[SearchIn][UPC]" <?php if(!empty($data['iSearch']['SearchIn']['UPC'])) echo ($data['iSearch']['SearchIn']['UPC'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn3">UPC</label>        </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn10" name="iSearch[SearchIn][SKU]" <?php if(!empty($data['iSearch']['SearchIn']['SKU'])) echo ($data['iSearch']['SearchIn']['SKU'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn10">SKU</label>      </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn15" name="iSearch[SearchIn][EAN]" <?php if(!empty($data['iSearch']['SearchIn']['EAN'])) echo ($data['iSearch']['SearchIn']['EAN'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn15">EAN</label>      </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn16" name="iSearch[SearchIn][JAN]" <?php if(!empty($data['iSearch']['SearchIn']['JAN'])) echo ($data['iSearch']['SearchIn']['JAN'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn16">JAN</label>      </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn17" name="iSearch[SearchIn][ISBN]" <?php if(!empty($data['iSearch']['SearchIn']['ISBN'])) echo ($data['iSearch']['SearchIn']['ISBN'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn17">ISBN</label>      </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn18" name="iSearch[SearchIn][MPN]" <?php if(!empty($data['iSearch']['SearchIn']['MPN'])) echo ($data['iSearch']['SearchIn']['MPN'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn18">MPN</label>      </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn4" name="iSearch[SearchIn][Manufacturer]" <?php if(!empty($data['iSearch']['SearchIn']['Manufacturer'])) echo ($data['iSearch']['SearchIn']['Manufacturer'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn4">Manufacturer</label>        
        </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn7" name="iSearch[SearchIn][AttributeNames]" <?php if(!empty($data['iSearch']['SearchIn']['AttributeNames'])) echo ($data['iSearch']['SearchIn']['AttributeNames'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn7">Attribute Names</label>       </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn7_1" name="iSearch[SearchIn][AttributeValues]" <?php if(!empty($data['iSearch']['SearchIn']['AttributeValues'])) echo ($data['iSearch']['SearchIn']['AttributeValues'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn7_1">Attribute Values</label>       </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn8" name="iSearch[SearchIn][Categories]" <?php if(!empty($data['iSearch']['SearchIn']['Categories'])) echo ($data['iSearch']['SearchIn']['Categories'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn8">Categories</label>        </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn19" name="iSearch[SearchIn][Filters]" <?php if(!empty($data['iSearch']['SearchIn']['Filters'])) echo ($data['iSearch']['SearchIn']['Filters'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn19">Filters</label>      </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn9" name="iSearch[SearchIn][Description]" <?php if(!empty($data['iSearch']['SearchIn']['Description'])) echo ($data['iSearch']['SearchIn']['Description'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn9">Description</label>        </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn5" name="iSearch[SearchIn][Tags]" <?php if(!empty($data['iSearch']['SearchIn']['Tags'])) echo ($data['iSearch']['SearchIn']['Tags'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn5">Tags</label>        </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn6" name="iSearch[SearchIn][Location]" <?php if(!empty($data['iSearch']['SearchIn']['Location'])) echo ($data['iSearch']['SearchIn']['Location'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn6">Location</label>        
        </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn11" name="iSearch[SearchIn][OptionName]" <?php if(!empty($data['iSearch']['SearchIn']['OptionName'])) echo ($data['iSearch']['SearchIn']['OptionName'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn11">Option Name</label>     
        </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn12" name="iSearch[SearchIn][OptionValue]" <?php if(!empty($data['iSearch']['SearchIn']['OptionValue'])) echo ($data['iSearch']['SearchIn']['OptionValue'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn12">Option Value</label>     
        </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn13" name="iSearch[SearchIn][MetaDescription]" <?php if(!empty($data['iSearch']['SearchIn']['MetaDescription'])) echo ($data['iSearch']['SearchIn']['MetaDescription'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn13">Meta Description</label>     
        </div>
        <div class="searchInSpan onlyUseAJAX">
            <input type="checkbox" id="searchIn14" name="iSearch[SearchIn][MetaKeyword]" <?php if(!empty($data['iSearch']['SearchIn']['MetaKeyword'])) echo ($data['iSearch']['SearchIn']['MetaKeyword'] == 'on') ? 'checked=checked' : ''?> /><label class="checkbox-inline lbl" for="searchIn14">Meta Keyword</label>     
        </div>
    </div>   
    </td>
  </tr>
  <tr>
    <td><span class="required">*</span> Responsive Design <span class="help">Select &quot;Yes&quot; if you want to make iSearch Results width fit your responsive design theme Search Field</span></td>
    <td valign="top">
        <div class="">
            <select name="iSearch[ResponsiveDesign]" class="ResponsiveDesign form-control">
                <option value="no" <?php echo ($data['iSearch']['ResponsiveDesign'] == 'no') ? 'selected=selected' : ''?>>No</option>
                <option value="yes" <?php echo ($data['iSearch']['ResponsiveDesign'] == 'yes') ? 'selected=selected' : ''?>>Yes</option>
            </select>
        </div>    
   </td>
  </tr>
  <tr>
    <td><span class="required">*</span> Use AJAX <span class="help">Select &quot;Yes&quot; if you want to load the search results asynchronously from the server on typing, or &quot;No&quot; to cache them on page load first (some features are limited in non-AJAX mode due to performance considerations)</span></td>
    <td valign="top">
        <div class="">
            <select name="iSearch[UseAJAX]" class="UseAJAX form-control">
                <option value="yes" <?php echo ($data['iSearch']['UseAJAX'] == 'yes') ? 'selected=selected' : ''?>>Yes</option>
                <option value="no" <?php echo ($data['iSearch']['UseAJAX'] == 'no') ? 'selected=selected' : ''?>>No</option>
            </select>
        </div>    
        
        <script>
        $('select.UseAJAX').change(function() {
            if($(this).val() == 'no') {
                $('.onlyUseAJAX').slideUp();
            } else { 
                $('.onlyUseAJAX').slideDown();
            }
        });
        
        var useAJAX = '<?php echo $data['iSearch']['UseAJAX']; ?>';
        if (useAJAX == 'no') {
            $('.onlyUseAJAX').hide();
        }
        
        </script>
   </td>
  </tr>
  <tr>
    <td><span class="required">*</span> Use Strict Search<span class="help">Strict Search searches for your query strictly the whole phrase as-it-is (example: &quot;blue jeans&quot; will match all products that have the full phrase &quot;blue jeans&quot;). If set to &quot;No&quot;, it will search the whole phrase as well as the separate words (example: &quot;blue jeans&quot; will match all products that have &quot;blue&quot; and/or &quot;jeans&quot;).</span></td>
    <td valign="top">
        <div class="">
            <select name="iSearch[UseStrictSearch]" class="UseStrictSearch form-control">
                <option value="yes" <?php echo ($data['iSearch']['UseStrictSearch'] == 'yes') ? 'selected=selected' : ''?>>Yes</option>
                <option value="no" <?php echo ($data['iSearch']['UseStrictSearch'] == 'no') ? 'selected=selected' : ''?>>No</option>
            </select>
        </div>
   </td>
  </tr>
  <tr>
    <td><span class="required">*</span> Search Engine on hitting 'Enter' <span class="help">Choose which search engine you prefer to be used. If you choose the default OpenCart search engine, the module will do the instant search, and on submit will produce your original OpenCart search results.</span></td>
    <td valign="top">
       <div class="row" style="margin-left:0;">
        <div class="">
            <select name="iSearch[AfterHittingEnter]" class="AfterHittingEnter form-control">
                <option value="isearchengine2000" <?php echo ($data['iSearch']['AfterHittingEnter'] == 'isearchengine1551') ? 'selected=selected' : ''?>>iSearch engine for OpenCart 2</option>
                <option value="default" <?php echo ($data['iSearch']['AfterHittingEnter'] == 'default') ? 'selected=selected' : ''?>>Default OpenCart engine</option>
            </select>
            <p class="help"><strong>Disclaimer:</strong> In case your theme is heavily modified and you have chosen iSearch engine for OpenCart, there may be conflicts between the files of iSearch and the theme files.</p>
        </div>
      </div>
        
   </td>
  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo $entry_highlightcolor; ?></td>
    <td>
        <div class="">
            <input class="form-control" type="text" name="iSearch[HighlightColor]" value="<?php echo (empty($data['iSearch']['HighlightColor'])) ? '#F7FF8C' : $data['iSearch']['HighlightColor']?>" />
        </div>
    </td>
  </tr>
  <tr>
    <td><span class="required">*</span> Limit Results to <span class="help">Denotes on which result to cut off the instant box when iSearch-ing</span></td>
    <td>
        <div class="">
            <input class="form-control" type="text" name="iSearch[ResultsLimit]" value="<?php echo (empty($data['iSearch']['ResultsLimit'])) ? '5' : $data['iSearch']['ResultsLimit']?>" /></td>
        </div>
  </tr>
  <tr>
    <td>Results Box Width (px)<span class="help">Width of the box in pixels. Default is &quot;370px&quot;.</span></td>
    <td>
        <div class="">
            <input class="form-control" type="text" name="iSearch[ResultsBoxWidth]" value="<?php echo (empty($data['iSearch']['ResultsBoxWidth'])) ? '370px' : $data['iSearch']['ResultsBoxWidth']?>" /></td>
        </div>
  </tr>
  <tr>
    <td>Results Box Height (px)<span class="help">Height of the box in pixels. Leave empty for &quot;auto&quot;.</span></td>
    <td>
    <div class="">
        <input class="form-control" type="text" name="iSearch[ResultsBoxHeight]" value="<?php echo (empty($data['iSearch']['ResultsBoxHeight'])) ? '' : $data['iSearch']['ResultsBoxHeight']?>" /></td>
  </tr>
  <tr>
    <td>Instant Results Image Width (px)<span class="help">Width of the instant result images in pixels. Default is 80.</span></td>
    <td>
        <div class="">
            <input class="form-control" type="number" name="iSearch[InstantResultsImageWidth]" value="<?php echo (empty($data['iSearch']['InstantResultsImageWidth'])) ? '80' : $data['iSearch']['InstantResultsImageWidth']?>" /></td>
        </div>
  </tr>
  <tr>
    <td>Instant Results Image Height (px)<span class="help">Height of the instant result images in pixels. Default is 80.</span></td>
    <td>
        <div class="">
            <input class="form-control" type="number" name="iSearch[InstantResultsImageHeight]" value="<?php echo (empty($data['iSearch']['InstantResultsImageHeight'])) ? '80' : $data['iSearch']['InstantResultsImageHeight']?>" /></td>
        </div>
  </tr>
  <tr>
    <td>Results Title Width<span class="help">Width of the title, typically in %.</span></td>
    <td>
        <div class="">
            <input class="form-control" type="text" name="iSearch[ResultsBoxTitleWidth]" value="<?php echo (empty($data['iSearch']['ResultsBoxTitleWidth'])) ? '42%' : $data['iSearch']['ResultsBoxTitleWidth']?>" /></td>
        </div>
  </tr>
  <tr>
    <td>Result Title Font Size (px)<span class="help">Leave empty for your site default font size.</span></td>
    <td>
        <div class="">
            <input class="form-control" type="text" name="iSearch[ResultsTitleFontSize]" value="<?php echo (empty($data['iSearch']['ResultsTitleFontSize'])) ? '' : $data['iSearch']['ResultsTitleFontSize']?>" /></td>
        </div>
  </tr>
  <tr>
    <td>Result Title Font Weight<span class="help">Choose one</span></td>
    <td>
        <div class="">
            <select class="form-control" name="iSearch[ResultsTitleFontWeight]" class="ResultsTitleFontWeight">
                <option value="bold" <?php echo ($data['iSearch']['ResultsTitleFontWeight'] == 'bold') ? 'selected=selected' : ''?>>Bold</option>
                <option value="normal" <?php echo ($data['iSearch']['ResultsTitleFontWeight'] == 'normal') ? 'selected=selected' : ''?>>Normal</option>
            </select>
        </div>
    </td>
  </tr>
  <tr>
    <td>Show Images<span class="help">Show product images in the results box</span></td>
    <td>
        <div class="">
            <select class="form-control" name="iSearch[ResultsShowImages]" class="ResultsShowImages">
                <option value="yes" <?php echo ($data['iSearch']['ResultsShowImages'] == 'yes') ? 'selected=selected' : ''?>>Yes</option>
                <option value="no" <?php echo ($data['iSearch']['ResultsShowImages'] == 'no') ? 'selected=selected' : ''?>>No</option>
            </select>
        </div>
    </td>
  </tr>
  <tr>
    <td>Show Models<span class="help">Show product models in the results box</span></td>
    <td>
        <div class="">
            <select class="form-control" name="iSearch[ResultsShowModels]" class="ResultsShowModels">
                <option value="no" <?php echo ($data['iSearch']['ResultsShowModels'] == 'no') ? 'selected=selected' : ''?>>No</option>
                <option value="yes" <?php echo ($data['iSearch']['ResultsShowModels'] == 'yes') ? 'selected=selected' : ''?>>Yes</option>
            </select>
        </div>
    </td>
  </tr>
  <tr>
    <td>Show Prices<span class="help">Show product prices in the results box</span></td>
    <td>
        <div class="">
            <select class="form-control" name="iSearch[ResultsShowPrices]" class="ResultsShowPrices">
                <option value="no" <?php echo ($data['iSearch']['ResultsShowPrices'] == 'no') ? 'selected=selected' : ''?>>No</option>
                <option value="yes" <?php echo ($data['iSearch']['ResultsShowPrices'] == 'yes') ? 'selected=selected' : ''?>>Yes</option>
            </select>
        </div>
    </td>
  </tr>
  <tr>
    <td>
      Search terms suggestions heading
    </td>
    <td>
      <div>
        <?php foreach ($languages as $language) : ?>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></div>
            <input class="form-control" type="text" name="iSearch[<?php echo $language['language_id']; ?>][SuggestionHeadingInstant]" value="<?php echo (!isset($data['iSearch'][$language['language_id']]['SuggestionHeadingInstant'])) ? 'Search Term Suggestions' : $data['iSearch'][$language['language_id']]['SuggestionHeadingInstant']; ?>" />
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </td>
  </tr>
  <tr>
    <td>Number of search term suggestions<span class="help">Set to 0 to disable search term suggestions.</span></td>
    <td>
        <div class="">
            <input class="form-control" type="number" name="iSearch[SuggestionCount]" value="<?php echo (empty($data['iSearch']['SuggestionCount'])) ? '5' : $data['iSearch']['SuggestionCount']?>" />
        </div>
    </td>
  </tr>
  <tr>
    <td>Clear search term suggestions<span class="help">Use this to delete the search term suggestions from your database.</span></td>
    <td>
        <a href="<?php echo $href_clear_suggestions; ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Clear suggestions</a>
    </td>
  </tr>
  <tr>
    <td>
      Instant Results products heading
    </td>
    <td>
      <div>
        <?php foreach ($languages as $language) : ?>
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></div>
            <input class="form-control" type="text" name="iSearch[<?php echo $language['language_id']; ?>][ProductHeadingInstant]" value="<?php echo (!isset($data['iSearch'][$language['language_id']]['ProductHeadingInstant'])) ? 'Top Product Results' : $data['iSearch'][$language['language_id']]['ProductHeadingInstant']; ?>" />
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </td>
  </tr>
  <tr>
    <td>More Results Title<span class="help">This is the label that shows up if more results than the results limit are found</span></td>
    <td>
        <div class="">
            <?php foreach ($languages as $language) : ?>
            <div class="form-group">
                <div class="input-group">
                   <div class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></div>
                   <input class="form-control" type="text" name="iSearch[<?php echo $language['language_id']; ?>][ResultsMoreResultsLabel]" value="<?php echo (empty($data['iSearch'][$language['language_id']]['ResultsMoreResultsLabel'])) ? 'View All Results' : $data['iSearch'][$language['language_id']]['ResultsMoreResultsLabel']; ?>" /><br />
                </div>
            </div>   
            <?php endforeach; ?>
        </div>
    </td>
  </tr>
  <tr>
    <td>Not Found Text<span class="help">The text that appears when there are no search results.</span></td>
    <td>
        <div class="">
            <?php foreach ($languages as $language) : ?>
            <div class="form-group">
                <div class="input-group">
                   <div class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></div>
                   <input class="form-control" type="text" name="iSearch[<?php echo $language['language_id']; ?>][ResultsNoResultsLabel]" value="<?php echo (empty($data['iSearch'][$language['language_id']]['ResultsNoResultsLabel'])) ? 'No Results Found' : $data['iSearch'][$language['language_id']]['ResultsNoResultsLabel']; ?>" /><br />
                </div>
            </div>   
            <?php endforeach; ?>
        </div>
    </td>
  </tr>
  <tr>
    <td valign="top">Custom CSS<span class="help">Put your custom CSS here</span></td>
    <td>
        <div class="">
            <textarea class="form-control" name="iSearch[CustomCSS]" style="width:320px; height:200px;"><?php echo (empty($data['iSearch']['CustomCSS'])) ? '' : $data['iSearch']['CustomCSS']?></textarea>
        </div>                    
    </td>
  </tr>
</table>
<script>
$('.iSearchLayout input[type=checkbox]').change(function() {
    if ($(this).is(':checked')) { 
        $('.iSearchItemStatusField', $(this).parent()).val(1);
    } else {
        $('.iSearchItemStatusField', $(this).parent()).val(0);
    }
});

$('.iSearchEnabled').change(function() {
    toggleiSearchActive(true);
});

var toggleiSearchActive = function(animated) {
    if ($('.iSearchEnabled').val() == 'yes') {
        if (animated) 
            $('.iSearchActiveTR').fadeIn();
        else 
            $('.iSearchActiveTR').show();
    } else {
        if (animated) 
            $('.iSearchActiveTR').fadeOut();
        else 
            $('.iSearchActiveTR').hide();
    }
}

toggleiSearchActive(false);
</script>
