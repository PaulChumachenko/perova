(function($) {
    window.Simplecheckout = function(params) {
        this.params = params;

        this.callback = params.javascriptCallback || function() {};

        this.selectors = {
            paymentForm: "#simplecheckout_payment_form",
            paymentButtons: "#simplecheckout_payment_form div.buttons:last",
            step: ".simplecheckout-step",
            buttons: "#buttons",
            buttonPrev: "#simplecheckout_button_prev",
            buttonNext: "#simplecheckout_button_next",
            buttonCreate: "#simplecheckout_button_confirm",
            buttonBack: "#simplecheckout_button_back",
            stepsMenu: "#simplecheckout_step_menu",
            stepsMenuItem: ".simple-step",
            stepsMenuDelimiter: ".simple-step-delimiter",
            proceedText: "#simplecheckout_proceed_payment",
            agreementCheckBox: "#agreement_checkbox",
            agreementWarning: "#agreement_warning",
            block: ".simplecheckout-block",
            overlay: ".simplecheckout_overlay"
        };

        this.classes = {
            stepsMenuCompleted: "simple-step-completed",
            stepsMenuCurrent: "simple-step-current"
        };

        this.blocks = [];
        this.$steps = [];
        this.requestTimerId = 0;
        this.backCount = 0;
        this.currentStep = 1;
        this.saveStepNumber = this.currentStep;
        this.stepReseted = false;
        this.formSubmitted = false;

        var checkIsInContainer = function($element, selector) {
            if ($element.parents(selector).length) {
                return true;
            }
            return false;
        };

        this.callFunc = function(func, $target) {
            var self = this;

            if (func && typeof self[func] === "function") {
                self[func]($target);
            } else if (func) {
                //console.log(func + " is not registered");
            }
        };

        this.registerBlock = function(object) {
            var self = this;
            object.setParent(self);
            self.blocks.push(object);
        };

        this.initBlocks = function() {
            var self = this;
            for (var i in self.blocks) {
                if (!self.blocks.hasOwnProperty(i)) continue;

                self.blocks[i].init();
            }
        };

        this.init = function() {
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
                self.setDirty();
            };

            self.requestTimerId = 0;

            if (self.params.useGoogleApi) {
                self.initGoogleApi(callbackForComplexField);
            }

            if (self.params.useAutocomplete) {
                self.initAutocomplete(callbackForComplexField);
            }

            self.checkIsHuman();
            self.addObserver();
            self.initPopups();
            self.initMasks();
            self.initTooltips();
            self.initDatepickers(callbackForComplexField);
            self.initTimepickers(callbackForComplexField);
            self.initFileUploader(function() {
                self.overlayAll();
            }, function() {
                self.removeOverlays();
                self.setDirty();
            });
            self.initHandlers();
            self.initBlocks();
            self.initSteps();
            self.scroll();
            self.initValidationRules();

            if (typeof self.callback === "function") {
                self.callback();
            }
        };

        this.initHandlers = function() {
            var self = this;
            $(self.params.mainContainer).find("*[data-onchange], *[data-onclick]").each(function() {
                var bind = true,
                    $element = $(this);

                for (var i in self.blocks) {
                    if (!self.blocks.hasOwnProperty(i)) continue;

                    if (checkIsInContainer($element, self.blocks[i].currentContainer)) {
                        bind = false;
                        break;
                    }
                }

                if (bind) {
                    var funcOnChange = $element.attr("data-onchange");
                    if (funcOnChange) {
                        $element.on("change", function() {
                            self.setDirty();
                            self.callFunc(funcOnChange, $element);
                        });
                    }
                    var funcOnClick = $element.attr("data-onclick");
                    if (funcOnClick) {
                        $element.on("click", function() {
                            if ($element.attr("data-onclick-stopped")) {
                                return;
                            }
                            self.setDirty();
                            self.callFunc(funcOnClick, $element);
                        });
                    }
                }
            });
        };

        this.addObserver = function() {
            var self = this;
            $(self.params.mainContainer).find("input[type=radio], input[type=checkbox], select").on("change", function() {
                if (!checkIsInContainer($(this), self.selectors.paymentForm)) {
                    self.setDirty();
                }
            });

            $(self.params.mainContainer).find("input, textarea").on("keydown", function() {
                if (!checkIsInContainer($(this), self.selectors.paymentForm)) {
                    self.setDirty();
                }
            });
        };

        this.setDirty = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);

            $mainContainer.find(self.selectors.paymentForm).attr("data-invalid", "true").empty();
            $mainContainer.find("*[data-payment-button=true]").remove();
            $mainContainer.find(self.selectors.proceedText).hide();
            self.formSubmitted = false;
            if (self.currentStep == self.stepsCount) {
                $mainContainer.find(self.selectors.buttons).show();
                $mainContainer.find(self.selectors.buttonCreate).show();
            }
        };

        this.preventOrderDeleting = function(callback) {
            var self = this;
            $.get("index.php?" + self.params.additionalParams + "route=" + self.params.mainRoute + "/prevent_delete", function() {
                if (typeof callback === "function") {
                    callback();
                }
            });
        };

        this.clickOnConfirmButton = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);
            var $paymentForm = $mainContainer.find(self.selectors.paymentForm);

            if (self.isPaymentFormEmpty()) {
                return;
            }

            var gatewayLink = $paymentForm.find("div.buttons a:last").attr("href");
            var $submitButton = $paymentForm.find("div.buttons input[type=button]:last,div.buttons input[type=submit]:last,div.buttons button:last,div.buttons a.button:last:not([href]),div.buttons a.btn:last:not([href])");
            var $lastButton = $paymentForm.find("input[type=button]:last,input[type=submit]:last,button:last");
            var lastLink = $paymentForm.find("a:last").attr("href");

            var overlayButton = function() {
                $mainContainer.find(self.selectors.buttonCreate).attr("disabled", "disabled");
                if (!$mainContainer.find(".wait").length) {
                    $mainContainer.find(self.selectors.buttonCreate).after("<span class='wait'>&nbsp;<img src='" + self.params.additionalPath + self.resources.loadingSmall + "' alt='' /></span>");
                }
            };

            var removeOverlay = function() {
                $mainContainer.find(self.selectors.buttonCreate).removeAttr("disabled");
                $mainContainer.find(".wait").remove();
            };

            if (typeof gatewayLink !== "undefined" && gatewayLink !== "" && gatewayLink !== "#") {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    window.location = gatewayLink;
                    self.blockFieldsDuringPayment();
                    self.proceed();
                });
            } else if ($submitButton.length) {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    if (!$submitButton.attr("disabled")) {
                        $submitButton.mousedown().click();
                        self.blockFieldsDuringPayment($submitButton);
                        self.proceed();
                    }
                });
            } else if ($lastButton.length) {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    if (!$lastButton.attr("disabled")) {
                        $lastButton.mousedown().click();
                        self.blockFieldsDuringPayment($lastButton);
                        self.proceed();
                    }
                });
            } else if (typeof lastLink !== "undefined" && lastLink !== "" && lastLink !== "#") {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    window.location = lastLink;
                    self.blockFieldsDuringPayment();
                    self.proceed();
                });
            }
        };

        this.isPaymentFormValid = function() {
            var self = this;
            return !self.isPaymentFormEmpty() && !$(self.params.mainContainer).find(self.selectors.paymentForm).attr("data-invalid") ? true : false;
        };

        this.isPaymentFormVisible = function() {
            var self = this;
            return !self.isPaymentFormEmpty() && $(self.params.mainContainer).find(self.selectors.paymentForm).find(":visible:not(form)").length > 0 ? true : false;
        };

        this.isPaymentFormEmpty = function() {
            var self = this;
            var $paymentForm = $(self.params.mainContainer).find(self.selectors.paymentForm);

            return $paymentForm.length && $paymentForm.find("*").length > 0 ? false : true;
        };

        this.replaceCreateButtonWithConfirm = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);
            var $paymentForm = $(self.params.mainContainer).find(self.selectors.paymentForm);

            if (self.isPaymentFormEmpty()) {
                return;
            }

            var $gatewayLink = $paymentForm.find("div.buttons a:last");
            var $submitButton = $paymentForm.find("div.buttons input[type=button]:last,div.buttons input[type=submit]:last,div.buttons button:last,div.buttons a.button:last:not([href]),div.buttons a.btn:last:not([href])");
            var $lastButton = $paymentForm.find("input[type=button]:last,input[type=submit]:last,button:last");
            var $lastLink = $paymentForm.find("a:last");

            var $obj = false;

            if ($gatewayLink.length) {
                $obj = $gatewayLink;
            } else if ($submitButton.length) {
                $obj = $submitButton;
            } else if ($lastButton.length) {
                $obj = $lastButton;
            } else if ($lastLink.length) {
                $obj = $lastLink;
            }

            if ($obj) {
                var $clone = $obj.clone(false).removeAttr("onclick");

                $mainContainer.find(self.selectors.buttonCreate).hide().before($clone);

                $clone.attr("data-payment-button", "true").bind("mousedown", function() {
                    if ($obj.attr("disabled")) {
                        return;
                    }

                    $obj.mousedown();
                }).bind("click", function() {
                    if ($obj.attr("disabled")) {
                        return;
                    }

                    self.preventOrderDeleting(function() {
                        self.proceed();
                        $obj.click();
                        self.blockFieldsDuringPayment($obj);
                    });
                });
            } else {
                $mainContainer.find(self.selectors.buttons).hide();
                self.preventOrderDeleting();
            }
        };

        this.blockFieldsDuringPayment = function($button) {
            var self = this;

            self.disableAllFieldsBeforePayment();

            if (typeof $button !== "undefined") {
                var timerId = setInterval(function() {
                    if (!$button.attr("disabled")) {
                        self.enableAllFieldsAfterPayment();
                        clearInterval(timerId);
                    }
                }, 250);
            }
        };

        this.disableAllFieldsBeforePayment = function() {
            var self = this;

            $(self.params.mainContainer).find(self.selectors.block).each(function() {
                if ($(this).attr("id") == "simplecheckout_payment_form") {
                    return;
                }
                $(this).find("input,select,textarea").attr("disabled", "disabled");
                $(this).find("[data-onclick]").attr("data-onclick-stopped", "true");
            });
        };

        this.enableAllFieldsAfterPayment = function() {
            var self = this;

            $(self.params.mainContainer).find(self.selectors.block).each(function() {
                if ($(this).attr("id") == "simplecheckout_payment_form") {
                    return;
                }
                $(this).find("input:not([data-dummy]),select,textarea").removeAttr("disabled");
                $(this).find("[data-onclick]").removeAttr("data-onclick-stopped");
            });
        };

        this.proceed = function() {
            var self = this;
            if (self.params.displayProceedText && !self.isPaymentFormVisible()) {
                $(self.params.mainContainer).find(self.selectors.proceedText).show();
            }
        };

        this.gotoStep = function($target) {
            var self = this;
            var step = $target.attr("data-step");
            if (step < self.currentStep) {
                self.currentStep = step;
                self.setDirty();
                self.displayCurrentStep();
            }
        };

        this.previousStep = function($target) {
            var self = this;
            if (self.currentStep > 1) {
                self.currentStep--;
                self.setDirty();
                self.displayCurrentStep();
            }
        };

        this.nextStep = function($target) {
            var self = this;
            if (!self.validate()) {
                return;
            }
            if (self.currentStep < self.$steps.length) {
                self.currentStep++;
            }
            self.submitForm();
        };

        this.saveStep = function() {
            var self = this;
            if (self.currentStep) {
                $(self.params.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "next_step").val(self.currentStep));
            }
        };

        this.ignorePost = function() {
            var self = this;
            $(self.params.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "ignore_post").val(1));
        };

        this.addSystemFieldsInForm = function() {
            var self = this;
            if (self.formSubmitted) {
                $(self.params.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "create_order").val(1));
            }
            if (self.currentStep) {
                $(self.params.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "next_step").val(self.currentStep));
            }
        };

        this.initSteps = function() {
            var self = this;
            var i = 1;
            var $mainContainer = $(self.params.mainContainer);
            var $steps = $mainContainer.find(self.selectors.step);

            self.stepReseted = false;
            self.$steps = [];
            self.stepsCount = $steps.length || 1;

            $steps.each(function() {
                var $step = $(this);
                self.$steps.push($step);
                // check steps before current for errors and set step with error as current
                var $errorBlocks = $step.find(self.selectors.block + "[data-error=true]");
                if (i < self.currentStep && $errorBlocks.length) {
                    self.currentStep = i;
                    self.stepReseted = true;
                }
                i++;
            });

            if (self.stepsCount > 1 && !self.stepReseted && self.currentStep == self.stepsCount && $mainContainer.attr("data-error") == "true") {
                self.currentStep--;
                self.stepReseted = true;
            }

            //a fix for case when some steps are suddenly hidden after ajax request
            if (self.stepsCount > 1 && !self.stepReseted && self.currentStep > self.stepsCount) {
                self.currentStep = self.stepsCount;
            }

            $mainContainer.find(self.selectors.paymentButtons).hide();

            if (!self.isPaymentFormVisible()) {
                $mainContainer.find(self.selectors.paymentForm).css("margin", "0px");
            }

            self.displayCurrentStep();
        };

        this.displayCurrentStep = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);

            var initButtons = function() {
                if (self.stepsCount > 1) {
                    if (self.currentStep == 1) {
                        $mainContainer.find(self.selectors.buttonPrev).hide();
                    } else {
                        $mainContainer.find(self.selectors.buttonBack).hide();
                    }

                    if (self.currentStep < self.stepsCount) {
                        $mainContainer.find(self.selectors.buttonNext).show();
                        $mainContainer.find(self.selectors.buttonCreate).hide();
                    }

                    $mainContainer.find(self.selectors.agreementCheckBox).hide();
                    $mainContainer.find(self.selectors.agreementWarning).hide();

                    if (self.currentStep == self.stepsCount - 1) {
                        $mainContainer.find(self.selectors.agreementCheckBox).show();
                        $mainContainer.find(self.selectors.agreementWarning).show();
                    }
                }

                if (self.currentStep == self.stepsCount) {
                    $mainContainer.find(self.selectors.buttonNext).hide();
                    self.replaceCreateButtonWithConfirm();
                }
            };

            var initStepsMenu = function() {
                $mainContainer.find(self.selectors.stepsMenu + " " + self.selectors.stepsMenuItem).removeClass(self.classes.stepsMenuCompleted).removeClass(self.classes.stepsMenuCurrent);
                $mainContainer.find(self.selectors.stepsMenu + " " + self.selectors.stepsMenuDelimiter + " img").attr("src", self.params.additionalPath + self.resources.next);

                for (var i = 1; i < self.currentStep; i++) {
                    $mainContainer.find(self.selectors.stepsMenu + " " + self.selectors.stepsMenuItem + "[data-step=" + i + "]").addClass(self.classes.stepsMenuCompleted);
                $mainContainer.find(self.selectors.stepsMenu + " " + self.selectors.stepsMenuDelimiter + "[data-step=" + (i + 1) + "] img").attr("src", self.params.additionalPath + self.resources.nextCompleted);
                }
                $mainContainer.find(self.selectors.stepsMenu + " " + self.selectors.stepsMenuItem + "[data-step=" + self.currentStep + "]").addClass(self.classes.stepsMenuCurrent);
            };

            var hideSteps = function() {
                $mainContainer.find(self.selectors.step).hide();
            };

            var isLastStepHasOnlyPaymentForm = function() {
                var $lastStep = $mainContainer.find(self.selectors.step + ":last");
                return $lastStep.find(self.selectors.block).length == 1 && $lastStep.find(self.selectors.paymentForm).length == 1 ? true : false;
            };

            if (self.currentStep == self.stepsCount && !self.isPaymentFormVisible() && self.isPaymentFormValid() && (isLastStepHasOnlyPaymentForm() || self.formSubmitted)) {
                self.clickOnConfirmButton();
                if (isLastStepHasOnlyPaymentForm()) {
                    self.currentStep--;
                }
            }

            hideSteps();

            if (typeof self.$steps[self.currentStep - 1] !== "undefined") {
                self.$steps[self.currentStep - 1].show();
            }

            initStepsMenu();
            initButtons();
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

            if (self.params.popup) {
                return;
            }

            if (self.params.scrollToError) {
                $($mainContainer.find("[data-error=true]:visible")).each(function() {
                    var offset = $(this).offset();
                    if (offset.top < top) {
                        top = offset.top;
                    }
                    if (offset.bottom > bottom) {
                        bottom = offset.bottom;
                    }
                });

                $($mainContainer.find(".simplecheckout-warning-block:visible")).each(function() {
                    var offset = $(this).offset();
                    if (offset.top < top) {
                        top = offset.top;
                    }
                    if (offset.bottom > bottom) {
                        bottom = offset.bottom;
                    }
                });

                $($mainContainer.find(".simplecheckout-rule:visible")).each(function() {
                    if ($(this).parents(".simplecheckout-block").length) {
                        var offset = $(this).parents(".simplecheckout-block").offset();
                        if (offset.top < top) {
                            top = offset.top;
                        }
                        if (offset.bottom > bottom) {
                            bottom = offset.bottom;
                        }
                    }
                });

                if (top < 10000 && isOutsideOfVisibleArea(top)) {
                    $("html, body").animate({
                        scrollTop: top
                    }, "slow");
                    error = true;
                } else if (bottom && isOutsideOfVisibleArea(bottom)) {
                    $("html, body").animate({
                        scrollTop: bottom
                    }, "slow");
                    error = true;
                }
            }

            if (self.params.scrollToPaymentForm && !error) {
                if (self.isPaymentFormVisible()) {
                    top = $mainContainer.find(self.selectors.paymentForm).offset().top;
                    if (top && isOutsideOfVisibleArea(top)) {
                        $("html, body").animate({
                            scrollTop: top
                        }, "slow");
                    }
                }
            }

            if ($mainContainer.find(self.selectors.stepsMenu).length && self.currentStep != self.saveStepNumber) {
                top = $mainContainer.find(self.selectors.stepsMenu).offset().top;
                if (top && isOutsideOfVisibleArea(top)) {
                    $("html, body").animate({
                        scrollTop: top
                    }, "slow");
                }
            }

            self.saveStepNumber = self.currentStep;
        };

        this.validate = function() {
            var self = this;
            var result = true;

            for (var i in self.blocks) {
                if (!self.blocks.hasOwnProperty(i)) continue;

                if (!self.blocks[i].validate()) {
                    result = false;
                }
            }

            if (!result) {
                self.scroll();
            }

            return result;
        };

        this.backHistory = function() {
            var self = this;
            history.go(-1);
        };

        this.createOrder = function() {
            var self = this;
            if (!self.validate()) {
                return;
            }
            self.formSubmitted = true;
            self.submitForm();
        };

        this.submitForm = function() {
            var self = this;
            self.requestReloadAll();
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

        this.overlayAll = function() {
            var self = this;

            for (var i in self.blocks) {
                if (!self.blocks.hasOwnProperty(i)) continue;

                self.blocks[i].overlay();
            }

            $(self.params.mainContainer).find(self.selectors.block).each(function() {
                if (!$(this).data("initialized")) {
                    SimplecheckoutBlock.prototype.overlay.apply(self, [$(this)]);
                }
            });
        };

        this.removeOverlays = function() {
            var self = this;

            $(self.params.mainContainer).find(self.selectors.overlay).remove();
            $(self.params.mainContainer).find("input:not([data-dummy]),select,textarea").removeAttr("disabled");
        };

        /**
         * Reload all blocks via main controller which includes all registered blocks as childs
         * @param  {Function} callback
         */
        this.reloadAll = function(callback) {
            var self = this;
            if (self.isReloading) {
                return;
            }
            self.addSystemFieldsInForm();
            self.isReloading = true;
            var postData = $(self.params.mainContainer).find("input,select,textarea").serialize();
            $.ajax({
                url: self.params.mainUrl,
                data: postData + "&ajax=1",
                type: "POST",
                dataType: "text",
                beforeSend: function() {
                    self.overlayAll();
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
                    self.removeOverlays();
                    self.isReloading = false;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    self.removeOverlays();
                    self.isReloading = false;
                }
            });
        };

        this.reloadBlock = function(container, callback) {
            var self = this;
            if (self.isReloading) {
                return;
            }
            self.isReloading = true;
            var postData = $(self.params.mainContainer).find("input,select,textarea").serialize();
            $.ajax({
                url: self.params.mainUrl,
                data: postData + "&ajax=1",
                type: "POST",
                dataType: "text",
                beforeSend: function() {},
                success: function(data) {
                    var newData = $(container, $(data)).get(0);
                    if (!newData && data) {
                        newData = data;
                    }
                    $(container).replaceWith(newData);
                    self.init();
                    if (typeof callback === "function") {
                        callback.call(self);
                    }
                    self.isReloading = false;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    self.isReloading = false;
                }
            });
        };

        this.registerBlock(new SimplecheckoutCart("#simplecheckout_cart", "checkout/simplecheckout_cart"));
        this.registerBlock(new SimplecheckoutShipping("#simplecheckout_shipping", "checkout/simplecheckout_shipping"));
        this.registerBlock(new SimplecheckoutPayment("#simplecheckout_payment", "checkout/simplecheckout_payment"));
        this.registerBlock(new SimplecheckoutForm("#simplecheckout_customer", "checkout/simplecheckout_customer"));
        this.registerBlock(new SimplecheckoutForm("#simplecheckout_payment_address", "checkout/simplecheckout_payment_address"));
        this.registerBlock(new SimplecheckoutForm("#simplecheckout_shipping_address", "checkout/simplecheckout_shipping_address"));
        this.registerBlock(new SimplecheckoutComment("#simplecheckout_comment", "checkout/simplecheckout_comment"));

        var login = new SimplecheckoutLogin("#simplecheckout_login", "checkout/simplecheckout_login");
        login.setParent(this);
        login.init();
        login.shareMethod("open", "openLoginBox");

        this.instances.push(this);
    };

    Simplecheckout.prototype = inherit(window.Simple.prototype);

    /**
     * It is parent of all blocks
     */

    function SimplecheckoutBlock(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;
    }

    SimplecheckoutBlock.prototype.setParent = function(object) {
        this.simplecheckout = object;
        this.params = object.params;
        this.resources = object.resources;
    };

    SimplecheckoutBlock.prototype.reloadAll = function(callback) {
        if (this.simplecheckout) {
            this.simplecheckout.requestReloadAll(callback);
        } else {
            this.reload();
        }
    };

    SimplecheckoutBlock.prototype.reload = function(callback) {
        var self = this;
        if (self.isReloading) {
            return;
        }
        self.isReloading = true;
        var postData = $(self.params.mainContainer).find(self.currentContainer).find("input,select,textarea").serialize();
        $.ajax({
            url: "index.php?" + self.params.additionalParams + "route=" + self.currentRoute,
            data: postData + "&ajax=1",
            type: "POST",
            dataType: "text",
            beforeSend: function() {
                self.overlay();
            },
            success: function(data) {
                var newData = $(self.currentContainer, $(data)).get(0);
                if (!newData && data) {
                    newData = data;
                }
                $(self.params.mainContainer).find(self.currentContainer).replaceWith(newData);
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

    SimplecheckoutBlock.prototype.load = function(callback, container) {
        var self = this;
        if (self.isLoading) {
            return;
        }
        if (typeof callback !== "function") {
            container = callback;
            callback = null;
        }
        self.isLoading = true;
        $.ajax({
            url: "index.php?" + self.params.additionalParams + "route=" + self.currentRoute,
            type: "GET",
            dataType: "text",
            beforeSend: function() {
                self.overlay();
            },
            success: function(data) {
                var newData = $(self.currentContainer, $(data)).get(0);
                if (!newData && data) {
                    newData = data;
                }
                if (newData) {
                    if (container) {
                        $(container).html(newData);
                    } else {
                        $(self.currentContainer).replaceWith(newData);
                    }
                    self.init();
                }
                if (typeof callback === "function") {
                    callback();
                }
                self.removeOverlay();
                self.isLoading = false;
            },
            error: function(xhr, ajaxOptions, thrownError) {
                self.removeOverlay();
                self.isLoading = false;
            }
        });
    };

    SimplecheckoutBlock.prototype.overlay = function(useBlock) {
        var self = this;
        var $block = (useBlock && $(useBlock)) || $(self.params.mainContainer).find(self.currentContainer);

        if ($block.length) {
            if (~~$block.height() < 50) {
                return;
            }
            $block.find("input,select,textarea").attr("disabled", "disabled");
            $block.append("<div class='simplecheckout_overlay' id='" + $block.attr("id") + "_overlay'></div>");
            $block.find(".simplecheckout_overlay")
                .css({
                    "background": "url(" + self.params.additionalParams + self.resources.loading + ") no-repeat center center",
                    "opacity": 0.4,
                    "position": "absolute",
                    "width": $block.width(),
                    "height": $block.height(),
                    "z-index": 5000
                })
                .offset({
                    top: $block.offset().top,
                    left: $block.offset().left
                });
        }
    };

    SimplecheckoutBlock.prototype.removeOverlay = function() {
        var self = this;
        var $mainContainer = $(self.params.mainContainer);

        if (typeof self.currentContainer !== "undefined") {
            $mainContainer.find(self.currentContainer).find("input:not([data-dummy]),select,textarea").removeAttr("disabled");
            $mainContainer.find(self.currentContainer + "_overlay").remove();
        }
    };

    SimplecheckoutBlock.prototype.hasError = function() {
        return $(this.params.mainContainer).find(this.currentContainer).attr("data-error") ? true : false;
    };

    SimplecheckoutBlock.prototype.init = function(useContainer) {
        var self = this;
        var $mainContainer = $(self.params.mainContainer);
        var $currentContainer = $mainContainer.find(self.currentContainer);

        if (!$currentContainer.length) {
            //$currentContainer = $(self.currentContainer);
            if (!$currentContainer.length) {
                return;
            }
        }

        var callFunc = function(func, $target) {
            if (func && typeof self[func] === "function") {
                self[func]($target);
            } else if (func) {
                //console.log(func + " is not registered");
            }
        };

        $currentContainer.find("*[data-onchange]").on("change", function() {
            if (typeof self.simplecheckout !== "undefined") {
                self.simplecheckout.setDirty();
            }
            callFunc($(this).attr("data-onchange"), $(this));
        });

        $currentContainer.find("*[data-onclick]").on("click", function() {
            if ($(this).attr("data-onclick-stopped")) {
                return;
            }

            if (typeof self.simplecheckout !== "undefined") {
                self.simplecheckout.setDirty();
            }

            callFunc($(this).attr("data-onclick"), $(this));
        });

        if (self.isEmpty()) {
            ////console.log(self.currentContainer + " is empty");
        }

        if (!self.hasError() && $currentContainer.attr("data-hide")) {
            $currentContainer.hide();
        }

        self.addFocusHandler();
        self.restoreFocus();

        $currentContainer.data("initialized", true);
    };

    SimplecheckoutBlock.prototype.validate = function() {
        var self = this;

        return self.simplecheckout.checkRules(self.currentContainer);
    };

    SimplecheckoutBlock.prototype.isEmpty = function() {
        if ($(this.params.mainContainer).find(this.currentContainer).find("*").length) {
            return false;
        }
        return true;
    };

    SimplecheckoutBlock.prototype.shareMethod = function(name, asName) {
        SimplecheckoutBlock.prototype[asName] = bind(this[name], this);
    };

    SimplecheckoutBlock.prototype.displayWarning = function() {
        $(this.params.mainContainer).find(this.currentContainer).find(".simplecheckout-warning-block").show();
    };

    SimplecheckoutBlock.prototype.hideWarning = function() {
        $(this.params.mainContainer).find(this.currentContainer).find(".simplecheckout-warning-block").hide();
    };

    SimplecheckoutBlock.prototype.focusedFieldId = "";

    SimplecheckoutBlock.prototype.addFocusHandler = function() {
        var self = this;
        var $currentContainer = $(self.params.mainContainer).find(self.currentContainer);

        $currentContainer.find("input,textarea").focus(function() {
            self.focusedFieldId = $(this).attr("id");
        });

        $currentContainer.find("input,textarea").blur(function() {
            self.focusedFieldId = "";
        });
    };

    SimplecheckoutBlock.prototype.restoreFocus = function() {
        var self = this;
        var $currentContainer = $(self.params.mainContainer).find(self.currentContainer);

        if (typeof self.focusedFieldId !== "undefined" && self.focusedFieldId && $currentContainer.find("#" + self.focusedFieldId).length > 0) {
            $currentContainer.find("#" + self.focusedFieldId).focus();
        }
    };

    function SimplecheckoutCart(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
            self.initMiniCart();
        };

        this.initMiniCart = function() {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer);
            var total = $currentContainer.find("#simplecheckout_cart_total").html();
            var weight = $currentContainer.find("#simplecheckout_cart_weight").text();

            if (total) {
                $("#cart_total").html(total);
                $("#cart-total").html(total);
                $("#cart_menu .s_grand_total").html(total);
                $("#cart .tb_items").html(total);
                $("#weight").text(weight);

                if (self.params.currentTheme == "shoppica2") {
                    $("#cart_menu div.s_cart_holder").html("");
                    $.getJSON("index.php?" + self.params.additionalParams + "route=tb/cartCallback", function(json) {
                        if (json["html"]) {
                            $("#cart_menu span.s_grand_total").html(json["total_sum"]);
                            $("#cart_menu div.s_cart_holder").html(json["html"]);
                        }
                    });
                }

                if (self.params.currentTheme == "shoppica") {
                    $("#cart_menu div.s_cart_holder").html("");
                    $.getJSON("index.php?" + self.params.additionalParams + "route=module/shoppica/cartCallback", function(json) {
                        if (json["output"]) {
                            $("#cart_menu span.s_grand_total").html(json["total_sum"]);
                            $("#cart_menu div.s_cart_holder").html(json["output"]);
                        }
                    });
                }
            }
        };

        this.increaseProductQuantity = function($target) {
            var self = this;

            var $quantity = $target.parent().find("input");
            var quantity = parseFloat($quantity.val());
            if (!isNaN(quantity)) {
                $quantity.val(quantity + 1);
                self.reloadAll();
            }
        };

        this.decreaseProductQuantity = function($target) {
            var self = this;

            var $quantity = $target.parent().find("input");
            var quantity = parseFloat($quantity.val());
            if (!isNaN(quantity) && quantity > 1) {
                $quantity.val(quantity - 1);
                self.reloadAll();
            }
        };

        this.changeProductQuantity = function($target) {
            var self = this;

            if (typeof $target[0] !== "undefined" && typeof $target[0].tagName !== "undefined" && $target[0].tagName !== "INPUT") {
                $target = $target.parents("td").find("input");
            }

            var quantity = parseFloat($target.val());

            if (!isNaN(quantity)) {
                self.reloadAll();
            }
        };

        this.removeProduct = function($target) {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer);

            var productKey = $target.attr("data-product-key");
            $currentContainer.find("#simplecheckout_remove").val(productKey);

            self.reloadAll();
        };

        this.removeGift = function($target) {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer);

            var giftKey = $target.attr("data-gift-key");
            $currentContainer.find("#simplecheckout_remove").val(giftKey);

            self.reloadAll();
        };

        this.removeCoupon = function($target) {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer);

            $currentContainer.find("input[name='coupon']").val("");
            self.reloadAll();
        };

        this.removeReward = function($target) {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer);

            $currentContainer.find("input[name='reward']").val("");
            self.reloadAll();
        };

        this.removeVoucher = function($target) {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer);

            $currentContainer.find("input[name='voucher']").val("");
            self.reloadAll();
        };
    }

    SimplecheckoutCart.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutLogin(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.initPopupLayer = function() {
            var self = this;
            var position = $("#simple_login_layer").parent().css("position");
            if (!$("#simple_login_layer").length || position == "fixed" || position == "relative" || position == "absolute") {
                $("#simple_login_layer").remove();
                $("#simple_login").remove();
                $(self.params.mainContainer).append("<div id='simple_login_layer'></div><div id='simple_login'><div id='temp_popup_container'></div></div>");
                $("#simple_login_layer").on("click", function() {
                    self.close();
                });
            }

            $("#simple_login_layer")
                .css("position", "fixed")
                .css("top", "0")
                .css("left", "0")
                .css("right", "0")
                .css("bottom", "0");

            $("#simple_login_layer").fadeTo(500, 0.8);
        };

        this.openPopup = function() {
            var self = this;
            self.initPopupLayer();
            if (!$(self.currentContainer).html()) {
                self.load(function() {
                    if ($(self.currentContainer).html()) {
                        self.resizePopup();
                    } else {
                        self.closePopup();
                    }
                }, "#temp_popup_container");
            } else {
                self.hideWarning();
                self.resizePopup();
            }
        };

        this.resizePopup = function() {
            $("#simple_login").show();
            $("#simple_login").css("height", $(this.currentContainer).outerHeight() + 20);
            $("#simple_login").css("top", $(window).height() / 2 - ($("#simple_login").outerHeight() ? $("#simple_login").outerHeight() : $("#simple_login").height()) / 2);
            $("#simple_login").css("left", $(window).width() / 2 - ($("#simple_login").outerWidth() ? $("#simple_login").outerWidth() : $("#simple_login").width()) / 2);
        };

        this.closePopup = function() {
            var self = this;
            $("#simple_login_layer").fadeOut(500, function() {
                $(this).hide().css("opacity", "1");
            });
            $("#simple_login").fadeOut(500, function() {
                $(this).hide();
            });
        };

        this.openFlat = function() {
            var self = this;
            if (!$(self.currentContainer).length) {
            $("<div id='temp_flat_container'><img src='" + self.params.additionalPath + self.resources.loading + "'></div>").insertBefore(self.params.loginBoxBefore);
                self.load("#temp_flat_container");
            }
            self.hideWarning();
            $(self.currentContainer).show();
        };

        this.closeFlat = function() {
            $(this.currentContainer).hide();
        };

        this.isOpened = function() {
            return $("#temp_flat_container *:visible").length ? true : false;
        };

        this.open = function() {
            var self = this;
            /*if (self.getParam("logged")) {
                return;
            }*/
            if (self.params.loginBoxBefore) {
                self.openFlat();
            } else {
                self.openPopup();
            }
        };

        this.close = function() {
            var self = this;
            if (self.params.loginBoxBefore) {
                self.closeFlat();
            } else {
                self.closePopup();
            }
        };

        this.login = function() {
            var self = this;
            this.reload(function() {
                if (!self.hasError()) {
                    self.closePopup();
                    self.closeFlat();
                    if (self.simplecheckout) {
                        self.simplecheckout.saveStep();
                        self.simplecheckout.ignorePost();
                        self.simplecheckout.reloadAll();
                    } else {
                        window.location.reload();
                    }
                } else {
                    self.resizePopup();
                }
            });
        };
    }

    SimplecheckoutLogin.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutComment(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.copyOnAllEntries = function($target) {
            var self = this;
            $(self.params.mainContainer).find("textarea[name=comment]").val($target.val());
        };
    }

    SimplecheckoutComment.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutShipping(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.validate = function() {
            var self = this;
            var result = true;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer);

            if ($currentContainer.is(":visible").length && !$currentContainer.find("input:checked").length && !$currentContainer.find("option:selected").length) {
                self.displayWarning();
                result = false;
            }

            if (!SimplecheckoutBlock.prototype.validate.apply(self, arguments)) {
                result = false;
            }

            return result;
        };
    }

    SimplecheckoutShipping.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutPayment(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.validate = function() {
            var self = this;
            var result = true;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer);

            if ($currentContainer.is(":visible") && !$currentContainer.find("input:checked").length && !$currentContainer.find("option:selected").length) {
                self.displayWarning();
                result = false;
            }

            if (!SimplecheckoutBlock.prototype.validate.apply(self, arguments)) {
                result = false;
            }

            return result;
        };
    }

    SimplecheckoutPayment.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutForm(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.validate = function() {
            var self = this;
            var result = true;

            if (!SimplecheckoutBlock.prototype.validate.apply(self, arguments)) {
                result = false;
            }

            return result;
        };

        this.reloadAll = function($element) {
            var self = this;
            setTimeout(function() {
                if (!$element.attr("data-valid") || $element.attr("data-valid") == "true") {
                    SimplecheckoutBlock.prototype.reloadAll.apply(self, arguments);
                }
            }, 0);

        };
    }

    SimplecheckoutForm.prototype = inherit(SimplecheckoutBlock.prototype);
})(jQuery || $);