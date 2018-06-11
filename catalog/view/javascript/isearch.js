$(document).ready(function() {
	$(window).load(function() {
		String.prototype.capitalize = function() {
			return this.charAt(0).toUpperCase() + this.slice(1);
		}
		
		var searchBoxSelector = 'input[name=filter_name]';
		var searchParam = 'filter_name';
		var searchCompatibility = '/onefivefour';
		var searchButtonSelector = '.button-search';
		var descriptionVariable = 'filter_description';
		
		if (ocVersion >= '1.5.5.1') {
			searchBoxSelector = 'input[name=search]';
			searchParam = 'search';
			descriptionVariable = 'description';
			searchCompatibility = ''
		} 

		var searchBox = $(searchBoxSelector).first();
		var originalSearchBox = searchBox;
		
		var originalSearchBoxContainer = searchBox.closest('#search').parent().html();
		searchBox.closest('#search').parent().html('<div class="iSearchBoxWrapper">'+originalSearchBoxContainer+'<div class="clearfix"></div><div class="iSearchBox"></div><div id="iSearchBoxLoadingImage"></div></div>');
		
		var originalSearchBoxOffset = $(originalSearchBox).offset();
		var originalSearchBoxWidth = $(originalSearchBox).innerWidth();
		var originalSearchBoxHeight = $(originalSearchBox).innerHeight();
		
		$('#iSearchBoxLoadingImage').offset({
			left: (originalSearchBoxOffset.left + originalSearchBoxWidth - 21),
			top: (originalSearchBoxOffset.top + (originalSearchBoxHeight/2) - ($('#iSearchBoxLoadingImage').innerHeight()/2))
		});
		$('#iSearchBoxLoadingImage').hide();
		$('#iSearchBoxLoadingImage').css('visibility', 'visible');
		
		/** Responsive Design logic */
		var respondOnWidthChange = function() {
			var searchFieldWidth = searchBox.width() + 16;
			$('.iSearchBoxWrapper .iSearchBox').width(searchFieldWidth);
		}
		
		var iSearchAjax = null;
		
		if (responsiveDesign == 'yes') {
			$(window).resize(function() {
				respondOnWidthChange();
			});
			respondOnWidthChange();
		}
		/** END */
		
		/** Close iSearch Box on Body Click */
		$(document).click(function() {
			$('.iSearchBox').hide();
		});
		$('.iSearchBoxWrapper').click(function(event) {
			event.stopPropagation();
		});
		
		/** END */
		
		/** After Hitting Enter */
		var goIsearching = function() {
			url = $('base').attr('href') + 'index.php?route=product/isearch'+searchCompatibility;
			var filter_name = $(searchBoxSelector).first().val();
			if (filter_name) {
				url += '&'+searchParam+'=' + encodeURIComponent(filter_name);
			}
			if (searchInDescription) {
				url += '&'+descriptionVariable+'=true';	
			}
			location = url;
		}
		
		var useIsearchAfterHittingEnter = function() {
			$(searchButtonSelector).unbind('click');
			$(searchButtonSelector).bind('click',function() {
				goIsearching();
			});
			
			$(searchBoxSelector).first().unbind('keydown');
			$(searchBoxSelector).first().bind('keydown', function(e) {
				if (e.keyCode == 13) {
                    if ($('.iSearchBox li.activekey').length) {
                        $('.iSearchBox li.activekey').trigger('click');
                    } else {
                        goIsearching();
                    }
				}
			});
	
		}
		if (afterHittingEnter == 'isearchengine1541' || afterHittingEnter == 'isearchengine1551' || afterHittingEnter == 'isearchengine2000') {
			setTimeout(function() {
				useIsearchAfterHittingEnter();
			}, 1000);
		} else {
			$(searchButtonSelector).bind('click', function() {
				var url = $('base').attr('href') + 'index.php?route=product/search';	 
				var filter_name =  $(searchBoxSelector).first().val();
				
				if (filter_name) {
					url += '&'+searchParam+'=' + encodeURIComponent(filter_name);
				}
				location = url;
			});
		
			$(searchBoxSelector).first().bind('keydown', function(e) {
				if (e.keyCode == 13) {
					url = $('base').attr('href') + 'index.php?route=product/search';
					 
					var filter_name =  $(searchBoxSelector).first().val();
					
					if (filter_name) {
						url += '&'+searchParam+'=' + encodeURIComponent(filter_name);
					}
					
					location = url;
				}
			});	
		}
		
		/** END */
		
		/** Non-AJAX Loading */
		if (useAJAX == 'no') {
			$.ajax({ 
				type: 'get',
				url: 'index.php?route=module/isearch/ajaxget',
				contentType: "application/json; charset=utf-8",
				success: function(o) {
					productsData = generateProductsDataFromJSONResponse(o);
				}		
			});
		}
		
		var sortProductsByKeyword = function(keywords, products) {
			var words = keywords.split(' ');
			var sortedProducts = [];
			$(products).each(function(i,e) {
				productName = (e.name) ? e.name.toString().toLowerCase() : '';
				if (productName.indexOf(words[0].toLowerCase()) != -1) {
					sortedProducts.unshift(e);
				} else {
					sortedProducts.push(e);
				}
			});
			return sortedProducts;
		}
		
		var filterProductsByKeyword = function(keywords, products, strictMode) {
			var words = keywords.split(' ');
			var filteredProducts = [];
			$(products).each(function(i,e) {
				productName = (e.name) ? e.name.toString().toLowerCase() : '';
				productModel = (e.model) ? e.model.toString().toLowerCase() : '';
				
				var allWordsExist = true;
				if (strictMode == 'yes') {
					words[0] = keywords;	
				}
				$(words).each(function(j,w) {
					if (productName.indexOf(w.toLowerCase()) == -1) {
						if (searchInModel == 'no') {
							allWordsExist = false;
						} else {
							if (productModel.indexOf(w.toLowerCase()) == -1) {
								allWordsExist = false;
							}
						}
					}
				});
				
				if (allWordsExist == true) {
					filteredProducts.push(e);	
				}
				
		
			});
			return filteredProducts;
		}
		
		/** END */
		
		var runSpellCheck = function($searchVal) {
			/*if (SCWords) {
				$(SCWords).each(function(i, e) {
					if (e.incorrect == $searchVal) {
						$searchVal = e.correct;
					}
				});
			}*/
			return $searchVal;
		}
		
		var searchInProducts =function($name , $searchVal) {
			var iname = $name;
			var searchSplit = $searchVal.split(' ');
			var ind = null;
			$(searchSplit).each(function(i,searchWord) {
				ind = i;
				if (iname.toLowerCase().indexOf(searchWord.toLowerCase()) != -1) {
					var startPos = iname.toLowerCase().indexOf(searchWord.toLowerCase());
					var extractStr = iname.substr(startPos, searchWord.length);
					iname = iname.replace(extractStr,'{'+extractStr+'}');
				}
			});
			if (ind != null) {
				iname = iname.replace(/{/g,'<span class="iMarq">');	
				iname = iname.replace(/}/g,'</span>');	
			}
			return iname;

			var iname = $name;
			if ($name.toLowerCase().indexOf($searchVal.toLowerCase()) != -1) {
				var startPos = $name.toLowerCase().indexOf($searchVal.toLowerCase());
				var extractStr = $name.substr(startPos, $searchVal.length);
				iname = $name.replace(extractStr,'<span class="iMarq">'+extractStr+'</span>');	
			}
			return iname;
		}
		
        $(window).keydown(function(e) {
            var itemsSet = $('.iSearchBox li:not(.iSearchHeading)');

            if (!itemsSet.length) return;

            var index_next = 0;
            var index_prev = itemsSet.length - 1;

            $(itemsSet).each(function(index, element) {
                if ($(element).hasClass('activekey')) {
                    index_next = index + 1;
                    index_prev = index - 1;

                    $(element).removeClass('activekey');
                }
            });  

            if (index_next >= itemsSet.length) index_next = 0;
            if (index_prev < 0) index_prev = itemsSet.length - 1;

            if (e.which == 40) { // Down arrow
                $('.iSearchBox li:not(.iSearchHeading):eq(' + index_next + ')').addClass('activekey');
            } else if (e.which == 38) { // Up arrow
                $('.iSearchBox li:not(.iSearchHeading):eq(' + index_prev + ')').addClass('activekey');
            }
        });
        
		var generateLIs = function(searchVal, fromProducts) {
            var LIs = '';
			var suggestionLIs = '';
			searchVal = $.trim(searchVal);
			var f = 0;
			var maxFound = iSearchResultsLimit;
			
            var searchSuggestions = fromProducts.suggestions;
            fromProducts = (fromProducts.products) ? fromProducts.products : productsData;
			
			var sortedProducts = fromProducts;
			if (useAJAX == 'no') {
				sortedProducts = sortProductsByKeyword(searchVal,fromProducts);
				sortedProducts = filterProductsByKeyword(searchVal, sortedProducts, useStrictSearch);
			}
			$(sortedProducts).each(function(i,e) {
				e.name = (e.name) ? e.name.toString() : '';
				e.model = (e.model) ? e.model.toString() : '';
				e.special = (e.special) ? e.special : '';
				f++;
				if (f <= maxFound) {
					var iname = searchInProducts(e.name, searchVal);
					var imodel = searchInProducts(e.model, searchVal);
					var specialClass = (e.special) ? 'specialPrice' : '';
					var imageTag = (loadImagesOnInstantSearch == 'no') ? '' : '<img src="'+e.image+'" />';
					LIs += '<li onclick="document.location.href=\''+e.href.replace('\'', '\\\'').replace('%2F', '/')+'\'">'+imageTag+'<div class="iSearchItem"><h3>'+iname+'<div class="iSearchModel">'+imodel+'</div></h3><div class="iSearchPrice"><span class="'+specialClass+'">'+e.price+'</span><div class="iSearchSpecial">'+e.special+'</div></div><div class="clearfix"></div></div></li>';	
				}
		
			});

            console.log(searchSuggestions);

            $(searchSuggestions).each(function(i,e) {
                suggestionLIs += '<li onclick="document.location.href=\''+e.url.replace('\'', '\\\'').replace('%2F', '/')+'\'">' + e.term + '</li>';
            });

            var routeToController = (afterHittingEnter == 'isearchengine1541' || afterHittingEnter == 'isearchengine1551' || afterHittingEnter == 'isearchengine2000') ? 'product/isearch'+searchCompatibility : 'product/search';

            var viewAllHref = 'index.php?route='+routeToController+'&'+searchParam+'='+encodeURIComponent(searchVal);

            if (f == 0) {
                LIs += '<li class="iSearchNoResults">'+noResultsText+'</li>';
            }

			if (f > maxFound) {
				var viewAllLink = moreResultsText.replace('(N)', f);
				var routeToController = (afterHittingEnter == 'isearchengine1541' || afterHittingEnter == 'isearchengine1551' || afterHittingEnter == 'isearchengine2000') ? 'product/isearch'+searchCompatibility : 'product/search';
				
				LIs += '<li class="iSearchViewAllResults" onclick="document.location = \'' + viewAllHref + '\'">'+viewAllLink+'</li>';
			}

			if (f == 0) {
				LIs += '<li class="iSearchNoResults">'+noResultsText+'</li>';
			}
			
			if (LIs != '' || suggestionLIs != '') {
                var productHeading = productHeadingInstant;
                var suggestionHeading = suggestionHeadingInstant;

                var html = '';

                if (suggestionLIs != '') {
                    var suggestionHtml = '<ul class="isearchProducts">';
                    suggestionHtml += '<li class="iSearchHeading">' + suggestionHeadingInstant + '</li>';
                    suggestionHtml += suggestionLIs;
                    suggestionHtml += '</ul>';

                    html += suggestionHtml;
                }

                if (LIs != '') {
                    var productHtml = '<ul class="isearchProducts">';
                    productHtml += '<li class="iSearchHeading">' + productHeading + '</li>';
                    productHtml += LIs;
                    productHtml += '</ul>';

    				html += productHtml;
                }

                $('.iSearchBox').html(html);

				$('.iSearchBox').slideDown(70);
			} else {
				$('.iSearchBox').slideUp(50);
			}
			
		
		}
		
		var generateProductsDataFromJSONResponse = function(jsonObject) {
			var prodData = {
                products: [],
                suggestions: []
            };

			if (typeof jsonObject.products != 'undefined') {
                $(jsonObject.products).each(function(i,e) {
                    var productObj = {
                        'name' : e.name,
                        'image' : e.image,
                        'href' : e.href,
                        'model' : e.model,
                        'price' : e.price,
                        'special' : (e.special) ? e.special : ''
                    };
                    $.merge(prodData.products,[productObj]);
                });
            }

            if (typeof jsonObject.suggestions != 'undefined') {
                $(jsonObject.suggestions).each(function(i,e) {
                    var suggestionObj = {
                        'url' : e.url,
                        'term' : e.term
                    };
                    $.merge(prodData.suggestions,[suggestionObj]);
                });
            }
            
			return prodData;
		}
		
		var showSearchResults = function (val, secondTry) { 
			
			if (val.length > 1) {
				if (useAJAX == 'no') {
					generateLIs(val);
				} else {
					iSearchAjax = $.ajax({ 
						type: 'get',
						url: 'index.php?route=module/isearch/ajaxget&k='+encodeURIComponent(val),
						contentType: "application/json; charset=utf-8",
						success: function(o) {
							var prodData = generateProductsDataFromJSONResponse(o);
							if (prodData.products == '' && secondTry != true && runSpellCheck(val) != val) {
								showSearchResults(runSpellCheck(val),true);
							}
							generateLIs(val,prodData);
							
						},
						beforeSend: function(jqXHR, settings) {
							//$('#iSearchBoxLoadingImage').show();
						},
						complete: function(jqXHR, textStatus) {
							//$('#iSearchBoxLoadingImage').hide();	
						}
						
					});
				}
				
			} else {
				$('.iSearchBox').slideUp(50);
			}
		}
		
		var typewatch = (function(){
		  var timer = 0;
		  return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		  }  
		})();
		
		$(searchBoxSelector).first().attr('autocomplete', 'off').bind('keyup', function(e) {
            if (e.which == 38 || e.which == 40) return;

			if ($(this).is(':focus')) {
				if (iSearchAjax != null) iSearchAjax.abort();
				var baseVal = $(this).val();
				if (useAJAX == 'no') {
					showSearchResults(baseVal);
				} else {
					typewatch(function () {
						showSearchResults(baseVal);
					}, 300);
				}
			}
		});
	});
});