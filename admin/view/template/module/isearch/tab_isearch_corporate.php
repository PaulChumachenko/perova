<h3>iSearchCorporate</h3>
<table class="form">
    <tr>
        <td>Use Caching<span class="help">This will enable iSearchCorporate cached searching. Note that if you change your search fields, you will have to return here and refresh the cache.</span></td>
        <td>
            <div class="col-xs-3">
            <select name="iSearch[EnableCaching]" class="enableCaching form-control">
                <option value="No" <?php echo (empty($data['iSearch']['EnableCaching']) || $data['iSearch']['EnableCaching'] == 'No') ? 'selected=selected' : ''?>>No</option>
                <option value="Yes" <?php echo (!empty($data['iSearch']['EnableCaching']) && $data['iSearch']['EnableCaching'] == 'Yes') ? 'selected=selected' : ''?>>Yes</option>
            </select>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            Pre-resize images<span class="help">If this is checked, your images will be automatically resized during the caching. This will increase the performance of the instant results.</span>
        </td>
        <td>
            <div class="col-xs-3">
            <input type="checkbox" name="iSearch[CorporatePreresize]" value="1" <?php echo !empty($data['iSearch']['CorporatePreresize']) ? 'checked="checked"' : ''; ?> />
            </div>
        </td>
    </tr>
    <tr>
        <td>Refresh Cache</td>
        <td>
            <div style="padding-left: 15px;">
            <?php if (!empty($data['iSearch']['EnableCaching']) && $data['iSearch']['EnableCaching'] == 'Yes') : ?>
                <a href="index.php?route=module/isearch/refreshcache&token=<?php echo $token; ?>" class="addWordButton btn btn-primary" id="refreshCacheButton">Refresh Cache</a>
            <?php endif; ?>
            </div>
            <script type="text/javascript">
                var warned = false;
                $("input[type='checkbox'][id^='searchIn']").each(function(index, value) {
                    $(this).change(function() {
                        if (!warned) {
                            warned = true;
                            alert("NOTE: Keep in mind that you will have to recache your iSearchCorporate cache if you change the search fields.");
                        }
                    });
                });
            </script>
            
            <?php if ($refreshInit) { ?>
            <div class="col-xs-3">
            <span id="refreshLoadingImage"><img src="view/image/isearch/loading.gif" alt="Loading" /> Caching... Please wait...</span>
            <div id="refreshProgress" style="margin-top: 10px;">

                <div id="refreshProgressbar" style="margin-bottom: 10px; width: 300px;">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div>Percent complete: <span id="refreshPercent">0</span>%</div>
                <div>Products cached: <span id="refreshProducts">0</span></div>
                <div>Products per second: <span id="refreshPPS">0</span></div>
                <div>Time left: <span id="refreshTimeLeft">0:00:00</span></div>
                <div id="refreshMessage"></div>
            </div>
            </div>
            <script type="text/javascript">
                var resend = true;
                var xhr = null;
                var seconds;
                var timer = null;
                var oldAttr = $('.submitButton').attr('onclick');
                
                var restoreButtons = function() {
                    $('#refreshCacheButton').text("Refresh Cache").unbind("click");
                    $('.submitButton').attr('onclick', oldAttr);
                    $('#refreshLoadingImage').hide();
                }
                
                var countSeconds = function() {
                    seconds++;
                }
                
                var zeroPad = function (num, places) {
                    var zero = places - num.toString().length + 1;
                    return Array(+(zero > 0 && zero)).join("0") + num;
                }
                
                var initProgressbar = function() {
                    $('#refreshProgressbar .progress-bar').attr('aria-valuenow', '0');
                    $('#refreshProgressbar .progress-bar').css('width', '0%');                         
                }
                
                var sendCacheRefresh = function() {
                    xhr = jQuery.ajax({
                        url: 'index.php?route=module/isearch/refreshprogress&token=<?php echo $token; ?>',
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function(jqXHR, settings) {
                            // Set the submit buttons to inactive
                            $('#refreshCacheButton').text("Abort").unbind("click").bind("click", function(event) {
                                event.preventDefault();
                                $(this).text("Aborting...");
                                resend = false;
                            });
                            $('.submitButton').attr('onclick', '');
                            if (timer == null) {
                                seconds = 1;
                                timer = setInterval(countSeconds, 1000);
                            }
                            initProgressbar();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            resend = false;
                            $('#refreshMessage').text("Error: " + errorThrown);
                            clearInterval(timer);
                            restoreButtons();
                        },
                        success: function(data, textStatus, jqXHR) {
                            $('#refreshPercent').text(data.percent);
                            $('#refreshProducts').text(data.current);
                            var percent = data.percent;

                            $('#refreshProgressbar .progress-bar').attr('aria-valuenow', percent);
                            $('#refreshProgressbar .progress-bar').css('width', percent + '%');  

                            var pps = data.current/seconds;
                            var allSecondsRemaining = Math.round((data.all - data.current)/pps);
                            var hoursRemaining =  zeroPad(Math.floor(allSecondsRemaining/3600), 2);
                            var minutesRemaining = zeroPad(Math.floor((allSecondsRemaining%3600)/60), 2);
                            var secondsRemaining = zeroPad(Math.floor((allSecondsRemaining%60)), 2);
                            
                            $('#refreshPPS').text(Math.round(pps));
                            $('#refreshTimeLeft').text(hoursRemaining + ':' + minutesRemaining + ':' + secondsRemaining);
                            
                            if (resend) {
                                if (data.complete == 'false') {
                                    sendCacheRefresh();
                                } else {
                                    clearInterval(timer);
                                    restoreButtons();
                                    
                                    if (data.error == false) {
                                        $('#refreshMessage').text('Cache refresh completed successfully!');
                                    } else {
                                        $('#refreshMessage').text('Error: ' + data.error);
                                    }
                                }
                            } else {
                                $('#refreshMessage').text("Cache refresh aborted.");
                                clearInterval(timer);
                                restoreButtons();
                            }
                        }
                    });
                }
                
                sendCacheRefresh();
            </script>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td>
            Search method<span class="help">&quot;FULLTEXT&quot; is the fastest method, however, <a href="http://dev.mysql.com/doc/refman/5.5/en/fulltext-stopwords.html" target="_blank">the following words</a> are excluded as search terms. &quot;FULLTEXT and LIKE&quot; will use FULLTEXT for the standard words and LIKE matching for words with special characters (e.g. &quot;v2.0-a&quot;). It will yield best results in non-strict search.</span>
        </td>
        <td>
            <div class="col-xs-3">
            <select class="form-control" name="iSearch[CorporateSearchMethod]">
                <option value="fulltext"<?php echo !empty($data['iSearch']['CorporateSearchMethod']) && $data['iSearch']['CorporateSearchMethod'] == 'fulltext' ? ' selected="selected"' : ''; ?>>FULLTEXT</option>
                <option value="fulltext_like_"<?php echo !empty($data['iSearch']['CorporateSearchMethod']) && $data['iSearch']['CorporateSearchMethod'] == 'fulltext_like_' ? ' selected="selected"' : ''; ?>>FULLTEXT and LIKE &quot;%word%&quot;</option>
                <option value="like_"<?php echo !empty($data['iSearch']['CorporateSearchMethod']) && $data['iSearch']['CorporateSearchMethod'] == 'like_' ? ' selected="selected"' : ''; ?>>LIKE &quot;word%&quot;</option>
                <option value="_like_"<?php echo !empty($data['iSearch']['CorporateSearchMethod']) && $data['iSearch']['CorporateSearchMethod'] == '_like_' ? ' selected="selected"' : ''; ?>>LIKE &quot;%word%&quot;</option>
            </select>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            Search languages<span class="help">Set whether the search results will match your customer's current language, or all available languages.</span>
        </td>
        <td>
            <div class="col-xs-3">
            <select class="form-control" name="iSearch[CorporateSearchLanguages]">
                <option value="single"<?php echo !empty($data['iSearch']['CorporateSearchLanguages']) && $data['iSearch']['CorporateSearchLanguages'] == 'single' ? ' selected="selected"' : ''; ?>>Single language</option>
                <option value="all"<?php echo !empty($data['iSearch']['CorporateSearchLanguages']) && $data['iSearch']['CorporateSearchLanguages'] == 'all' ? ' selected="selected"' : ''; ?>>All languages</option>
            </select>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            Custom weights<span class="help">Select which matched fields will have the highest weight. The products will be first sorted according to the weight of the field where the search term has a match, and secondly they will be sorted according to the custom sort rules you set in the next option.</span>
        </td>
        <td>
            <div class="col-xs-3">
            <table>
                <tbody id="corporateCustomWeight">
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="right">
                            <a class="btn btn-primary" id="corporateCustomWeightAdd">+ Add weight</a>
                        </td>
                    </tr>
                </tfoot>
            </table>
            </div>
            <script type="text/javascript">
                var custom_weight_entries = <?php echo !empty($data['iSearch']['CorporateCustomWeight']) ? json_encode($data['iSearch']['CorporateCustomWeight']) : '[]' ?>;
                var custom_weight_entries_index = 0;
                
                var addCustomWeightEntry = function(entry) {
                    var html = '<tr>';
                    html += '<td>';
                    html += '<select class="form-control" name="iSearch[CorporateCustomWeight][' + custom_weight_entries_index + '][field]">';
                    
                    <?php if (!empty($data['iSearch']['SearchIn']['ProductName'])) : ?> html += '<option value="name"' + (typeof entry.field != 'undefined' && entry.field == 'name' ? ' selected="selected"' : '') + '>Name</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['ProductModel'])) : ?> html += '<option value="model"' + (typeof entry.field != 'undefined' && entry.field == 'model' ? ' selected="selected"' : '') + '>Model</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['UPC'])) : ?> html += '<option value="upc"' + (typeof entry.field != 'undefined' && entry.field == 'upc' ? ' selected="selected"' : '') + '>UPC</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['SKU'])) : ?> html += '<option value="sku"' + (typeof entry.field != 'undefined' && entry.field == 'sku' ? ' selected="selected"' : '') + '>SKU</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['EAN'])) : ?> html += '<option value="ean"' + (typeof entry.field != 'undefined' && entry.field == 'ean' ? ' selected="selected"' : '') + '>EAN</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['JAN'])) : ?> html += '<option value="jan"' + (typeof entry.field != 'undefined' && entry.field == 'jan' ? ' selected="selected"' : '') + '>JAN</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['ISBN'])) : ?> html += '<option value="isbn"' + (typeof entry.field != 'undefined' && entry.field == 'isbn' ? ' selected="selected"' : '') + '>ISBN</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['MPN'])) : ?> html += '<option value="mpn"' + (typeof entry.field != 'undefined' && entry.field == 'mpn' ? ' selected="selected"' : '') + '>MPN</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['Manufacturer'])) : ?> html += '<option value="manufacturer"' + (typeof entry.field != 'undefined' && entry.field == 'manufacturer' ? ' selected="selected"' : '') + '>Manufacturer</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['AttributeNames'])) : ?> html += '<option value="attributes"' + (typeof entry.field != 'undefined' && entry.field == 'attributes' ? ' selected="selected"' : '') + '>Attribute Name</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['AttributeValues'])) : ?> html += '<option value="attributes_values"' + (typeof entry.field != 'undefined' && entry.field == 'attributes_values' ? ' selected="selected"' : '') + '>Attribute Value</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['Categories'])) : ?> html += '<option value="categories"' + (typeof entry.field != 'undefined' && entry.field == 'categories' ? ' selected="selected"' : '') + '>Category</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['Filters'])) : ?> html += '<option value="filters"' + (typeof entry.field != 'undefined' && entry.field == 'filters' ? ' selected="selected"' : '') + '>Filter</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['Description'])) : ?> html += '<option value="description"' + (typeof entry.field != 'undefined' && entry.field == 'description' ? ' selected="selected"' : '') + '>Description</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['Tags'])) : ?> html += '<option value="tags"' + (typeof entry.field != 'undefined' && entry.field == 'tags' ? ' selected="selected"' : '') + '>Tags</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['Location'])) : ?> html += '<option value="location"' + (typeof entry.field != 'undefined' && entry.field == 'location' ? ' selected="selected"' : '') + '>Location</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['OptionName'])) : ?> html += '<option value="optionname"' + (typeof entry.field != 'undefined' && entry.field == 'optionname' ? ' selected="selected"' : '') + '>Option Name</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['OptionValue'])) : ?> html += '<option value="optionvalue"' + (typeof entry.field != 'undefined' && entry.field == 'optionvalue' ? ' selected="selected"' : '') + '>Option Value</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['MetaDescription'])) : ?> html += '<option value="metadescription"' + (typeof entry.field != 'undefined' && entry.field == 'metadescription' ? ' selected="selected"' : '') + '>Meta Description</option>';<?php endif; ?>
                    <?php if (!empty($data['iSearch']['SearchIn']['MetaKeyword'])) : ?> html += '<option value="metakeyword"' + (typeof entry.field != 'undefined' && entry.field == 'metakeyword' ? ' selected="selected"' : '') + '>Meta Keyword</option>';<?php endif; ?>
                    
                    html += '</select>';
                    html += '</td>';
                    html += '<td>';
                    html += '<input class="form-control" type="number" min="-100" max="100" value="' + (typeof entry.weight != 'undefined' ? entry.weight : '0') + '" name="iSearch[CorporateCustomWeight][' + custom_weight_entries_index + '][weight]" />';
                    html += '</td>';
                    html += '<td>';
                    html += '<a class="btn btn-danger corporateCustomWeightRemove">- Remove</a>';
                    html += '</td>';
                    html += '</tr>';
                    $('#corporateCustomWeight').append(html);
                    
                    $('.corporateCustomWeightRemove').unbind().click(function() {
                        $(this).closest('tr').remove();
                    });
                    
                    custom_weight_entries_index++;
                }
                
                for (var i in custom_weight_entries) {
                    var custom_entry = custom_weight_entries[i];
                    addCustomWeightEntry(custom_entry);
                }
                
                $('#corporateCustomWeightAdd').click(function() {
                    addCustomWeightEntry({});
                });
            </script>
            <style type="text/css">
            #corporateCustomWeight td {
                padding: 5px 5px 5px 0;
            }
            </style>
        </td>
    </tr>
    <tr>
        <td>
            Custom ordering<span class="help">How the instant results will be ordered and what the default ordering will be for the standard results.</span>
        </td>
        <td>
            <div class="col-xs-3">
            <table>
                <tbody id="corporateCustomOrder">
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="right">
                            <a class="btn btn-primary" id="corporateCustomOrderAdd">+ Add ordering</a>
                        </td>
                    </tr>
                </tfoot>
            </table>
            </div>
            <script type="text/javascript">
                var custom_order_entries = <?php echo !empty($data['iSearch']['CorporateCustomOrder']) ? json_encode($data['iSearch']['CorporateCustomOrder']) : '[]' ?>;
                var custom_order_entries_index = 0;
                
                var addCustomEntry = function(entry) {
                    var html = '<tr>';
                    html += '<td>';
                    html += '<select class="form-control" name="iSearch[CorporateCustomOrder][' + custom_order_entries_index + '][order]">';
                    
                    html += '<option value="in_stock"' + (typeof entry.order != 'undefined' && entry.order == 'in_stock' ? ' selected="selected"' : '') + '>In Stock</option>';
                    html += '<option value="price"' + (typeof entry.order != 'undefined' && entry.order == 'price' ? ' selected="selected"' : '') + '>Price</option>';
                    html += '<option value="quantity"' + (typeof entry.order != 'undefined' && entry.order == 'quantity' ? ' selected="selected"' : '') + '>Quantity</option>';
                    html += '<option value="sales_amount"' + (typeof entry.order != 'undefined' && entry.order == 'sales_amount' ? ' selected="selected"' : '') + '>Sales Amount</option>';
                    html += '<option value="orders_amount"' + (typeof entry.order != 'undefined' && entry.order == 'orders_amount' ? ' selected="selected"' : '') + '>Order Amount</option>';
                    html += '<option value="viewed"' + (typeof entry.order != 'undefined' && entry.order == 'viewed' ? ' selected="selected"' : '') + '>Viewed</option>';
                    html += '<option value="name_length"' + (typeof entry.order != 'undefined' && entry.order == 'name_length' ? ' selected="selected"' : '') + '>Name Length</option>';
                    html += '<option value="name"' + (typeof entry.order != 'undefined' && entry.order == 'name' ? ' selected="selected"' : '') + '>Name</option>';
                    
                    html += '</select>';
                    html += '</td>';
                    html += '<td>';
                    html += '<select class="form-control" name="iSearch[CorporateCustomOrder][' + custom_order_entries_index + '][order_direction]">';
                    
                    html += '<option value="desc"' + (typeof entry.order_direction != 'undefined' && entry.order_direction == 'desc' ? ' selected="selected"' : '') + '>DESC</option>';
                    html += '<option value="asc"' + (typeof entry.order_direction != 'undefined' && entry.order_direction == 'asc' ? ' selected="selected"' : '') + '>ASC</option>';
                    
                    html += '</select>';
                    html += '</td>';
                    html += '<td>';
                    html += '<a class="btn btn-danger corporateCustomOrderRemove">- Remove</a>';
                    html += '</td>';
                    html += '</tr>';
                    $('#corporateCustomOrder').append(html);
                    
                    $('.corporateCustomOrderRemove').unbind().click(function() {
                        $(this).closest('tr').remove();
                    });
                    
                    custom_order_entries_index++;
                }
                
                for (var i in custom_order_entries) {
                    var custom_entry = custom_order_entries[i];
                    addCustomEntry(custom_order_entries[i]);
                }
                
                $('#corporateCustomOrderAdd').click(function() {
                    addCustomEntry({});
                });
            </script>
            <style type="text/css">
            #corporateCustomOrder td {
                padding: 5px 5px 5px 0;
            }
            </style>
        </td>
    </tr>
    <tr>
        <td>
            LIKE index length<span class="help">How big should be the LIKE index. Note that the higher the number, the heavier the index.</span>
        </td>
        <td>
            <div class="col-xs-3">
            <input class="form-control" type="number" min="1" max="100" name="iSearch[CorporateLikeIndexLength]" value="<?php echo !empty($data['iSearch']['CorporateLikeIndexLength']) ? $data['iSearch']['CorporateLikeIndexLength'] : '10'; ?>" />
            </div>
        </td>
    </tr>
    <tr>
        <td>
            CRON Job Command<span class="help">Paste this command into your CRON job panel to automatically refresh the cache. The progress is saved in /system/logs/isearch_corporate.txt</span>
        </td>
        <td>
            <div class="col-xs-3">
            <pre><?php echo PHP_BINDIR.'/php -q ' . dirname(getcwd()) . '/system/library/isearch.php' ?></pre>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            Useful information
        </td>
        <td>
            <div class="col-xs-6">
            <ul>
                <li><strong>ft_min_word_len: </strong><?php echo $ft_min_word_len; ?><br />This value determines the minimum word length which will be accepted to the FULLTEXT index. This means that words below this length will not be searchable and will return zero results. If you wish to change this value, you should contact your MySQL administrator. Do not forget to refresh your iSearch Cache afterwards.<br /><br /></li>
                <li><strong>ft_max_word_len: </strong><?php echo $ft_max_word_len; ?><br />This value determines the maximum word length which will be accepted to the FULLTEXT index. This means that words above this length will not be searchable and will return zero results. If you wish to change this value, you should contact your MySQL administrator. Do not forget to refresh your iSearch Cache afterwards.<br /><br /></li>
                <li>Note that FULLTEXT has <a href="http://dev.mysql.com/doc/refman/5.5/en/fulltext-stopwords.html" target="_blank">a few stop words</a>, which are by default not included in the search index. If you wish to change this list, you should contact your MySQL administrator. Do not forget to refresh your iSearch Cache afterwards.<br /><br /></li>
                <li>If you use FULLTEXT search, the hyphen '-' is not regarded as a valid character and it does not return any results. You can use the &quot;FULLTEXT and LIKE&quot; search method from above in order to return valid results.</li>
            </ul>
            </div>
        </td>
    </tr>
</table>

<h3>Category Results</h3>
<table class="form">
    <tr>
        <td>Show Categories in Instant Results<span class="help">This will display resulting categories in the instant results (while typing)</span></td>
        <td>
            <div class="col-xs-3">
                <select name="iSearch[EnableCategoriesInstant]" class="form-control enableCaching">
                    <option value="No" <?php echo (empty($data['iSearch']['EnableCategoriesInstant']) || $data['iSearch']['EnableCategoriesInstant'] == 'No') ? 'selected=selected' : ''?>>Do not show</option>
                    <option value="AboveProducts" <?php echo (!empty($data['iSearch']['EnableCategoriesInstant']) && $data['iSearch']['EnableCategoriesInstant'] == 'AboveProducts') ? 'selected=selected' : ''?>>Show above products results</option>
                    <option value="LeftOfProducts" <?php echo (!empty($data['iSearch']['EnableCategoriesInstant']) && $data['iSearch']['EnableCategoriesInstant'] == 'LeftOfProducts') ? 'selected=selected' : ''?>>Show left of products results</option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td>Show Product Count in Instant Results<span class="help">This will display the product count in parentheses after the category names</span></td>
        <td>
            <div class="col-xs-3">
                <select name="iSearch[ShowProductCountInstant]" class="form-control enableCaching">
                    <option value="No" <?php echo (empty($data['iSearch']['ShowProductCountInstant']) || $data['iSearch']['ShowProductCountInstant'] == 'No') ? 'selected=selected' : ''?>>No</option>
                    <option value="Yes" <?php echo (!empty($data['iSearch']['ShowProductCountInstant']) && $data['iSearch']['ShowProductCountInstant'] == 'Yes') ? 'selected=selected' : ''?>>Yes</option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            Number of categories in Instant Results
        </td>
        <td>
            <div class="col-xs-3">
                <input class="form-control" type="number" min="1" max="100" name="iSearch[CategoryCountInstant]" value="<?php echo !empty($data['iSearch']['CategoryCountInstant']) ? $data['iSearch']['CategoryCountInstant'] : '5'; ?>" />
          </div>
        </td>
    </tr>
    <tr>
        <td>
            Instant Results categories heading
        </td>
        <td>
            <div class="col-xs-3">
            <?php foreach ($languages as $language) : ?>
                <div class="form-group">
                <div class="input-group">
            <div class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></div>
            <input class="form-control" type="text" name="iSearch[<?php echo $language['language_id']; ?>][CategoryHeadingInstant]" value="<?php echo (!isset($data['iSearch'][$language['language_id']]['CategoryHeadingInstant'])) ? 'Top Category Results' : $data['iSearch'][$language['language_id']]['CategoryHeadingInstant']; ?>" />
          </div>
          </div>
  <?php endforeach; ?>
  </div>
        </td>
    </tr>
    <tr>
        <td>
            Instant Results &quot;Matches&quot; text<span class="help">Use <strong>(N)</strong> to designate the number of results</span>
        </td>
        <td>
            <div class="col-xs-3">
            <?php foreach ($languages as $language) : ?>
    <div class="form-group">
                <div class="input-group">
        <div class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></div>
        <input class="form-control" type="text" name="iSearch[<?php echo $language['language_id']; ?>][MatchesTextInstant]" value="<?php echo (!isset($data['iSearch'][$language['language_id']]['MatchesTextInstant'])) ? '(N) Matches' : $data['iSearch'][$language['language_id']]['MatchesTextInstant']; ?>" />
      </div>
  </div>
  <?php endforeach; ?>
  </div>
        </td>
    </tr>
    <tr>
        <td>Show Categories in Standard Results<span class="help">This will display resulting categories in the standard results (after hitting &quot;Enter&quot;)</span></td>
        <td>
            <div class="col-xs-3">
                <select name="iSearch[EnableCategoriesStandard]" class="form-control enableCaching">
                    <option value="No" <?php echo (empty($data['iSearch']['EnableCategoriesStandard']) || $data['iSearch']['EnableCategoriesStandard'] == 'No') ? 'selected=selected' : ''?>>No</option>
                    <option value="Yes" <?php echo (!empty($data['iSearch']['EnableCategoriesStandard']) && $data['iSearch']['EnableCategoriesStandard'] == 'Yes') ? 'selected=selected' : ''?>>Yes</option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td>Show Product Count in Standard Results<span class="help">This will display the product count in parentheses after the category names</span></td>
        <td>
            <div class="col-xs-3">
                <select name="iSearch[ShowProductCountStandard]" class="form-control enableCaching">
                    <option value="No" <?php echo (empty($data['iSearch']['ShowProductCountStandard']) || $data['iSearch']['ShowProductCountStandard'] == 'No') ? 'selected=selected' : ''?>>No</option>
                    <option value="Yes" <?php echo (!empty($data['iSearch']['ShowProductCountStandard']) && $data['iSearch']['ShowProductCountStandard'] == 'Yes') ? 'selected=selected' : ''?>>Yes</option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            Number of categories in Standard Results
        </td>
        <td>
            <div class="col-xs-3">
                <input class="form-control" type="number" min="1" max="100" name="iSearch[CategoryCountStandard]" value="<?php echo !empty($data['iSearch']['CategoryCountStandard']) ? $data['iSearch']['CategoryCountStandard'] : '5'; ?>" />
            </div>
        </td>
    </tr>
    <tr>
        <td>
            Standard Results categories heading
        </td>
        <td>
            <div class="col-xs-3">
            <?php foreach ($languages as $language) : ?>
    <div class="form-group">
                <div class="input-group">
            <div class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></div>
        <input class="form-control" type="text" name="iSearch[<?php echo $language['language_id']; ?>][CategoryHeadingStandard]" value="<?php echo (!isset($data['iSearch'][$language['language_id']]['CategoryHeadingStandard'])) ? 'Categories meeting the search criteria' : $data['iSearch'][$language['language_id']]['CategoryHeadingStandard']; ?>" />
      </div>
    </div>
  <?php endforeach; ?>
  </div>
        </td>
    </tr>
    <tr>
        <td>
            Standard Results &quot;Show More&quot; text
        </td>
        <td>
            <div class="col-xs-3">
            <?php foreach ($languages as $language) : ?>
    <div class="form-group">
                <div class="input-group">
            <div class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></div>
        <input class="form-control" type="text" name="iSearch[<?php echo $language['language_id']; ?>][ShowMoreTextStandard]" value="<?php echo (!isset($data['iSearch'][$language['language_id']]['ShowMoreTextStandard'])) ? 'Show More' : $data['iSearch'][$language['language_id']]['ShowMoreTextStandard']; ?>" />
        </div>
    </div>
  <?php endforeach; ?>
  </div>
        </td>
    </tr>
    <tr>
        <td>
            Standard Results &quot;Show Less&quot; text
        </td>
        <td>
            <div class="col-xs-3">
            <?php foreach ($languages as $language) : ?>
    <div class="form-group">
                <div class="input-group">
            <div class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></div>
        <input class="form-control" type="text" name="iSearch[<?php echo $language['language_id']; ?>][ShowLessTextStandard]" value="<?php echo (!isset($data['iSearch'][$language['language_id']]['ShowLessTextStandard'])) ? 'Show Less' : $data['iSearch'][$language['language_id']]['ShowLessTextStandard']; ?>" />
      </div>
    </div>
  <?php endforeach; ?>
            </div>
        </td>
    </tr>
</table>