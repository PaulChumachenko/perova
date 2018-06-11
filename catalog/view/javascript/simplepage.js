(function($) {
    window.Simplepage = function(params) {
        this.params = params;

        this.callback = params.javascriptCallback || function() {};

        this.formSubmitted = false;
        this.popup = false;

        this.callFunc = function(func, $target) {
            var self = this;

            if (func && typeof self[func] === "function") {
                self[func]($target);
            } else if (func) {
                //console.log(func + " is not registered");
            }
        };

        this.init = function(popup) {
            var self = this;

            var callbackForComplexField = function($target) {
                var func = $target.attr("data-onchange");
                if (!func) {
                    func = $target.attr("data-onchange-delayed");
                }
                if (func && typeof self[func] === "function") {
                    self[func]($target);
                } else if (func) {
                    //console.log(func + " is not registered");
                }
            };

            if (popup) {
                self.popup = true;
            }

            self.requestTimerId = 0;

            if (self.params.useGoogleApi) {
                self.initGoogleApi(callbackForComplexField);
            }

            if (self.params.useAutocomplete) {
                self.initAutocomplete(callbackForComplexField);
            }

            self.checkIsHuman();
            self.initPopups();
            self.initMasks();
            self.initTooltips();
            self.initDatepickers(callbackForComplexField);
            self.initTimepickers(callbackForComplexField);
            self.initFileUploader(function() {
                self.overlay();
            }, function() {
                self.removeOverlay();
            });
            self.initHandlers();
            self.scroll();
            self.initValidationRules();

            if (typeof self.callback === "function") {
                self.callback();
            }
        };

        this.initHandlers = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);

            $mainContainer.find("*[data-onchange], *[data-onclick]").each(function() {
                var $element = $(this);

                var funcOnChange = $element.attr("data-onchange");
                if (funcOnChange) {
                    $element.on("change", function() {
                        self.callFunc(funcOnChange, $element);
                    });
                }

                var funcOnClick = $element.attr("data-onclick");
                if (funcOnClick) {
                    $element.on("click", function() {
                        self.callFunc(funcOnClick, $element);
                    });
                }
            });

            $mainContainer.submit(function(event) {
                self.requestReloadAll();
                event.preventDefault();
                return false;
            });
        };

        this.addSystemFieldsInForm = function() {
            var self = this;
            if (self.formSubmitted) {
                $(self.params.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "submitted").val(1));
            }
        };

        this.validate = function() {
            var self = this;

            if (!self.checkRules()) {
                self.scroll();
                return false;
            }

            return true;
        };

        this.submit = function() {
            var self = this;

            if (!self.validate()) {
                return;
            }

            self.formSubmitted = true;
            $(self.params.mainContainer).submit();
        };

        /**
         * Adds delay for reload execution on 150 ms, it allows to check sequence of events and to execute only the last request to handle of more events in one reloading
         * @param  {Function} callback
         */
        this.requestReloadAll = function(callback) {
            var self = this;
            if (self.requestTimerId) {
                clearTimeout(self.requestTimerId);
                self.requestTimerId = 0;
            }
            self.requestTimerId = setTimeout(function() {
                self.reloadAll(callback);
            }, 150);
        };

        this.overlay = function() {
            var self = this;
            var $block = $(self.params.mainContainer);
            if ($block.length) {
                $block.find("input,select,textarea").attr("disabled", "disabled");
                $block.append(
                    $("<div>")
                        .addClass("simplepage_overlay")
                        .attr("id", $block.attr("id") + "_overlay")
                        .css({
                            "background": "url(" + self.params.additionalPath + self.resources.loading + ") no-repeat center center",
                            "opacity": 0.4,
                            "position": "absolute",
                            "width": $block.width(),
                            "height": $block.height(),
                            "z-index": 5000
                        })
                );

                var $overlay = $("#"+$block.attr("id") + "_overlay");

                $overlay.offset({
                    top: $block.offset().top,
                    left: $block.offset().left
                });
            }
        };

        this.removeOverlay = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);

            $mainContainer.find("input,select,textarea").removeAttr("disabled");
            $mainContainer.find(".simplepage_overlay").remove();
        };

        this.scroll = function() {
            var self = this,
                error = false,
                top = 10000,
                bottom = 0;

            var $mainContainer = $(self.params.mainContainer);

            var isOutsideOfVisibleArea = function(y) {
                if (y < $(document).scrollTop() || y > ($(document).scrollTop() + $(document).height())) {
                    return true;
                }
                return false;
            };

            if (self.popup) {
                return;
            }

            if (self.params.scrollToError) {
                $($mainContainer.find(".simplecheckout-rule:visible")).each(function() {
                    if ($(this).parents(".simpleregister-block-content").length) {
                        var offset = $(this).parents(".simpleregister-block-content").offset();
                        if (offset.top < top) {
                            top = offset.top;
                        }
                        if (offset.bottom > bottom) {
                            bottom = offset.bottom;
                        }
                    }
                });

                if ($mainContainer.find(".warning").length) {
                    var offset = $mainContainer.find(".warning").offset();
                    if (offset.top < top) {
                        top = offset.top;
                    }
                    if (offset.bottom > bottom) {
                        bottom = offset.bottom;
                    }
                }

                if (top < 10000 && isOutsideOfVisibleArea(top)) {
                    jQuery("html, body").animate({
                        scrollTop: top
                    }, "slow");
                    error = true;
                } else if (bottom && isOutsideOfVisibleArea(bottom)) {
                    jQuery("html, body").animate({
                        scrollTop: bottom
                    }, "slow");
                    error = true;
                }
            }
        };

        this.reloadAll = function(callback) {
            var self = this;
            var postData;
            if (self.isReloading) {
                return;
            }
            self.addSystemFieldsInForm();
            self.isReloading = true;
            postData = $(self.params.mainContainer).find("input,select,textarea").serialize();
            $.ajax({
                url: self.params.mainUrl,
                data: postData + "&ajax=1",
                type: "POST",
                dataType: "text",
                beforeSend: function() {
                    self.overlay();
                },
                success: function(data) {
                    var newData = $(self.params.mainContainer, $(data)).get(0);
                    if (!newData && data) {
                        newData = data;
                    }
                    $(self.params.mainContainer).replaceWith(newData);
                    self.init();
                    if (typeof callback === "function") {
                        callback.call(self);
                    }
                    self.removeOverlay();
                    self.isReloading = false;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    self.removeOverlay();
                    self.isReloading = false;
                }
            });
        };

        this.instances.push(this);
    };

    Simplepage.prototype = inherit(window.Simple.prototype);
})(jQuery);