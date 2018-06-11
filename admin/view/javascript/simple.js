/*
@author Dmitriy Kubarev
@link   http://www.simpleopencart.com
@link   http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(searchElement, fromIndex) {
        if (this === undefined || this === null) {
            throw new TypeError('"this" is null or not defined');
        }

        var length = this.length >>> 0; // Hack to convert object.length to a UInt32

        fromIndex = +fromIndex || 0;

        if (Math.abs(fromIndex) === Infinity) {
            fromIndex = 0;
        }

        if (fromIndex < 0) {
            fromIndex += length;
            if (fromIndex < 0) {
                fromIndex = 0;
            }
        }

        for (; fromIndex < length; fromIndex++) {
            if (this[fromIndex] === searchElement) {
                return fromIndex;
            }
        }

        return -1;
    };
}

if (!Array.isArray) {
    Array.isArray = function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
    };
}

(function($) {
    $(function() {


        // stupid hack for jquery 1.7.1
        $("option[selected]").attr("selected", "selected");
        $("input[checked]").attr("checked", "checked");
//      $('input[type=date]').datepicker();
//      $('input[type=time]').timepicker();

        $("[data-tooltip]").easyTooltip();
    });

    var simpleModule = angular.module("Simple", []);

    simpleModule.directive("htabs", function() {
        return {
            restrict: "E",
            transclude: true,
            scope: {},
            controller: function($scope, $element) {
                var tabs = $scope.tabs = [];

                $scope.select = function(tab) {
                    angular.forEach(tabs, function(tab) {
                        tab.selected = false;
                    });
                    tab.selected = true;
                };

                $scope.showTitle = function(tab) {
                    if (typeof tab.titleShow === "undefined") {
                        return true;
                    }
                    return tab.titleShow;
                };

                this.addTab = function(tab) {
                    if (tabs.length === 0) {
                        $scope.select(tab);
                    }
                    tabs.push(tab);
                };
            },
            templateUrl: "htabs",
            replace: true
        };
    });

    simpleModule.directive("htab", function() {
        return {
            require: "^htabs",
            restrict: "E",
            transclude: true,
            scope: {
                title: "@",
                titleLangId: "@",
                titleShow: "@"
            },
            link: function(scope, element, attrs, tabsCtrl) {
                tabsCtrl.addTab(scope);
            },
            template: "<div ng-show='selected' ng-transclude></div>",
            replace: true
        };
    });

    simpleModule.directive("vtabs", function() {
        return {
            restrict: "E",
            transclude: true,
            scope: {
                extendable: "@",
                extendablePlaceholder: "@",
                extendableMethod: "&",
                removableMethod: "&"
            },
            controller: function($rootScope, $scope, $element, $sce) {
                var tabs = $scope.tabs = [];

                $scope.extendableId = "";

                function indexOfTab(id) {
                    for (var index in tabs) {
                        if (!tabs.hasOwnProperty(index)) continue;

                        if (tabs[index].removableId == id) {
                            return index;
                        }
                    }
                    return -1;
                }

                $scope.select = function(tab) {
                    angular.forEach(tabs, function(tab) {
                        tab.selected = false;
                    });
                    tab.selected = true;
                };

                $scope.selectFirst = function() {
                    var first;

                    angular.forEach(tabs, function(tab) {
                        if (typeof first === "undefined") {
                            first = tab;
                        }
                        tab.selected = false;
                    });

                    if (typeof first !== "undefined") {
                        first.selected = true;
                    }
                };

                $scope.remove = function(tab) {
                    $scope.removableMethod({
                        id: tab.removableId
                    });
                    tabs.splice(indexOfTab(tab.removableId), 1);
                    $scope.selectFirst();
                };

                this.addTab = function(tab) {
                    if (tabs.length === 0) {
                        $scope.select(tab);
                    }
                    tabs.push(tab);
                };
            },
            templateUrl: "vtabs",
            replace: true
        };
    });

    simpleModule.directive("vtab", function() {
        return {
            require: "^vtabs",
            restrict: "E",
            transclude: true,
            scope: {
                title: "@",
                titleLangId: "@",
                removable: "@",
                removableId: "@",
                tooltip: "@"
            },
            link: function(scope, element, attrs, tabsCtrl) {
                tabsCtrl.addTab(scope);
            },
            template: "<div class='vtabs-content' ng-show='selected' ng-transclude></div>",
            replace: true
        };
    });

    simpleModule.directive("apivalue", function() {
        return {
            restrict: "E",
            compile: function compile(element, attrs, transclude) {
                return function($scope, element, attrs) {
                    $scope.forProperty = attrs.forProperty;
                };
            },
            templateUrl: "apivalue",
            replace: true
        };
    });

    simpleModule.directive("visualtemplate", function($compile, $rootScope) {
        return function(scope, element, attrs) {
            scope.$watch(
                function(scope) {
                    return scope.$eval(attrs.visualtemplate);
                },
                function(value) {
                    var tmp = value,
                        blocks = [],
                        result = "",
                        label = "",
                        useHideOptions = false,
                        hasOwnHeader = true;

                    function camelize(str) {
                        if (str.indexOf("_") > 0) {
                            var tmp = str.split("_");

                            for (var i in tmp) {
                                if (!tmp.hasOwnProperty(i)) continue;

                                tmp[i] = tmp[i].toLowerCase();
                                if (i > 0) {
                                    tmp[i] = tmp[i].charAt(0).toUpperCase() + tmp[i].substr(1);
                                }
                            }

                            return tmp.join("");
                        }

                        return str;
                    }

                    tmp = tmp.replace(/\s+/g, "");
                    tmp = tmp.replace(/^\{|\}$/g, "");

                    blocks = tmp ? tmp.split("}{") : [];

                    var position = 0;
                    result = '<div step="{{step.id}}" class="simple-dummy" position="0">&nbsp;</div>';
                    position++;
                    for (var i in blocks) {
                        if (!blocks.hasOwnProperty(i)) continue;

                        if (!blocks[i]) {
                            continue;
                        }

                        useHideOptions = false;
                        hasOwnHeader = false;
                        label = blocks[i];

                        if (typeof $rootScope.blocks[blocks[i]] !== "undefined") {
                            label = $rootScope.blocks[blocks[i]].label;
                            useHideOptions = $rootScope.blocks[blocks[i]].useHideOptions;
                            hasOwnHeader = $rootScope.blocks[blocks[i]].hasOwnHeader;
                        }

                        var pathToAdminImages = typeof $rootScope.settings.additionalPath !== "undefined" ? $rootScope.settings.additionalPath + "admin/" : "";

                        if (blocks[i] === 'left_column') {
                            result += '<div class="left-column" step="{{step.id}}" position="' + position + '"><img src="' + pathToAdminImages + 'view/image/delete.png" ng-click="removeBlock(step.id, \'' + position + '\')" alt="remove" style="float:right;">';
                        } else if (blocks[i] === 'right_column') {
                            result += '<div class="right-column" step="{{step.id}}" position="' + position + '"><img src="' + pathToAdminImages + 'view/image/delete.png" ng-click="removeBlock(step.id, \'' + position + '\')" alt="remove" style="float:right;">';
                        } else if (blocks[i] === 'three_column') {
                            result += '<div class="three-column" step="{{step.id}}" position="' + position + '"><img src="' + pathToAdminImages + 'view/image/delete.png" ng-click="removeBlock(step.id, \'' + position + '\')" alt="remove" style="float:right;">';
                        } else if (blocks[i] === '/left_column' || blocks[i] === '/right_column' || blocks[i] === '/three_column') {
                            result += '</div>';
                        } else if (blocks[i]) {
                            result += '<div class="simple-block" block="' + blocks[i] + '" step="{{step.id}}" position="' + position + '" ui-draggable>';
                            result += '<div class="simple-block-header"><strong>' + label + '</strong><img src="' + pathToAdminImages + 'view/image/delete.png" ng-click="removeBlock(step.id, ' + position + ')" style="float:right;"></div>';
                            result += '<div>';
                            var camelBlockName = camelize(blocks[i]);
                            if (hasOwnHeader) {
                                result += '<div><label><input type="checkbox" ng-init="checkout.' + camelBlockName + '.displayHeader = isset(checkout.' + camelBlockName + '.displayHeader) ? checkout.' + camelBlockName + '.displayHeader : true" ng-model="checkout.' + camelBlockName + '.displayHeader">' + $rootScope.texts.displayHeader + '</label></div>';
                            }
                            if (useHideOptions) {
                                result += '<div><label><input type="checkbox" ng-model="checkout.' + camelBlockName + '.hideForGuest">' + $rootScope.texts.hideForGuest + '</label></div>';
                                result += '<div><label><input type="checkbox" ng-model="checkout.' + camelBlockName + '.hideForLogged">' + $rootScope.texts.hideForLogged + '</label></div>';
                            }
                            if (blocks[i] == "payment_address" || blocks[i] == "shipping_address") {
                                result += '<div><label><input type="checkbox" ng-model="checkout.' + camelBlockName + '.combined">' + $rootScope.texts.combineWithCustomer + '</label></div>';
                            }
                            result += '</div>';
                            result += '</div>';
                        }
                        position++;
                    }
                    result += '<div step="{{step.id}}" class="simple-dummy" position="' + position + '">&nbsp;</div>';

                    element.html(result);

                    scope.parseAvaliableBlocks();

                    $compile(element.contents())(scope);
                }
            );
        };
    });

    simpleModule.directive("uiDraggable", function($rootScope, $document) {
        return function(scope, element, attr) {
            var startX = 0,
                startY = 0,
                x = 0,
                y = 0,
                zIndex = 1,
                moved = false;

            element.on("mousedown", function(event) {
                $document.unbind("mousemove", mousemove);
                $document.unbind("mouseup", mouseup);

                event.preventDefault();

                startX = event.pageX - x;
                startY = event.pageY - y;
                zIndex = element.css("z-index");

                element.css({
                    "position": "relative"
                });

                moved = false;

                $document.on("mousemove", mousemove);
                $document.on("mouseup", mouseup);
            });

            function mousemove(event) {
                y = event.pageY - startY;
                x = event.pageX - startX;

                moved = true;

                element.css({
                    "top": y + "px",
                    "left": x + "px",
                    "z-index": 1000
                });

            }

            function mouseup(event) {
                $document.unbind("mousemove", mousemove);
                $document.unbind("mouseup", mouseup);

                startX = 0;
                startY = 0;
                x = 0;
                y = 0;

                element.css({
                    "top": startY + "px",
                    "left": startX + "px",
                    "z-index": 1,
                    "position": "static"
                });

                if (moved) {
                    checkAndMoveElement(event);
                }
                moved = false;
            }

            function checkAndMoveElement(event) {
                var destination = $(window.document.elementFromPoint(event.clientX, event.clientY));
                if (destination.hasClass("simple-dummy") || destination.hasClass("simple-step") || destination.parents(".simple-step").length) {
                    if (destination.hasClass("simple-dummy")) {
                        $rootScope.$broadcast("drop", {
                            step: destination.attr("step"),
                            position: ~~destination.attr("position"),
                            current: ~~element.attr("position") || -1,
                            block: element.attr("block")
                        });
                    } else if ((destination.hasClass("left-column") || destination.hasClass("right-column") || destination.hasClass("three-column")) && element.hasClass("simple-block")) {
                        $rootScope.$broadcast("drop", {
                            step: destination.attr("step"),
                            position: ~~destination.attr("position") + 1,
                            current: ~~element.attr("position") || -1,
                            block: element.attr("block")
                        });
                    } else if (destination.parents(".simple-block").length && element.hasClass("simple-block")) {
                        $rootScope.$broadcast("drop", {
                            step: destination.parents(".simple-block").attr("step"),
                            position: ~~destination.parents(".simple-block").attr("position"),
                            current: ~~element.attr("position") || -1,
                            block: element.attr("block")
                        });
                    }
                }
            }
        };
    });

    simpleModule.directive("scenario", function() {
        return {
            require: "^simpleSetController",
            restrict: "E",
            scope: true,
            templateUrl: "scenario",
            replace: true
        };
    });

    simpleModule.directive("rows", function() {
        return {
            require: "^simpleSetController",
            restrict: "E",
            transclude: true,
            templateUrl: "rows",
            replace: true
        };
    });

    // controller

    simpleModule.controller("simpleMainController", ["$rootScope", "$scope",
        function($rootScope, $scope) {
            $scope.save = function() {
                for (var id in $rootScope.blocks) {
                    if (!$rootScope.blocks.hasOwnProperty(id)) continue;

                    if ($rootScope.blocks[id].required) {
                        for (var j in $rootScope.blocks[id].used) {
                            if (!$rootScope.blocks[id].used.hasOwnProperty(j)) continue;

                            if (!$rootScope.blocks[id].used[j]) {
                                alert($rootScope.errors["blocksRequired"]);
                                return;
                            }
                        }
                    }
                }

                for (var i in $rootScope.settings.checkout) {
                    if (!$rootScope.settings.checkout.hasOwnProperty(i)) continue;

                    for (var index in $rootScope.settings.checkout[i].steps) {
                        if (!$rootScope.settings.checkout[i].steps.hasOwnProperty(index)) continue;

                        if ($rootScope.settings.checkout[i].steps[index].template === "") {
                            $rootScope.settings.checkout[i].steps.splice(index, 1);
                        }
                    }
                }

                $.ajax({
                    url: $rootScope.actionMain + (typeof $rootScope.settings.additionalParams !== "undefined" ? "&" + $rootScope.settings.additionalParams : ""),
                    type: "POST",
                    dataType: "json",
                    data: {
                        "simple_settings": JSON.stringify($rootScope.settings),
                        "simple_address_format": $rootScope.settings.addressFormat,
                        "simple_replace_cart": $rootScope.settings.replaceCart ? 1 : 0,
                        "simple_replace_checkout": $rootScope.settings.replaceCheckout ? 1 : 0,
                        "simple_replace_register": $rootScope.settings.replaceRegister ? 1 : 0,
                        "simple_replace_edit": $rootScope.settings.replaceEdit ? 1 : 0,
                        "simple_replace_address": $rootScope.settings.replaceAddress ? 1 : 0
                    },
                    beforeSend: function() {
                        $("#saving").show();
                    },
                    success: function(data) {
                        if (data["success"]) {
                            $(".success").show();
                            setTimeout(function() {
                                $(".success").hide();
                            }, 1500);
                            $("#saving").hide();
                        }
                    }
                });
            };

            $scope.empty = function(value) {
                var count = 0;
                if (Array.isArray(value)) {
                    for (var i in value) {
                        if (!value.hasOwnProperty(i)) continue;
                        count++;
                    }
                    return count ? false : true;
                }

                return angular.isObject(value) ? $.isEmptyObject(value) : !value;
            };

            $scope.isArray = function(value) {
                return Array.isArray(value);
            };

            $scope.arrayToObject = function(arr) {
                var rv = {};

                for (var i = 0; i < arr.length; ++i)
                    if (arr[i] !== undefined) rv[i] = arr[i];

                return rv;
            };

            $scope.isset = function(value) {
                return typeof value !== "undefined" ? true : false;
            };

            $scope.addSettingsGroup = function() {
                var copy = angular.copy($rootScope.settings.checkout[0]);
                copy.settingsId = $rootScope.settings.checkout.length;
                $rootScope.settings.checkout.push(copy);
                $rootScope.settingsId = copy.settingsId;
            };

            $scope.removeSettingsGroup = function(id) {
                if (!id) {
                    return;
                }
                var removeId = -1;
                for (var i in $rootScope.settings.checkout) {
                    if (!$rootScope.settings.checkout.hasOwnProperty(i)) continue;

                    if ($rootScope.settings.checkout[i].settingsId == id) {
                        removeId = i;
                    }
                }
                if (removeId > -1) {
                    $rootScope.settings.checkout.splice(removeId, 1);
                    $rootScope.settingsId = 0;
                }
            };

            $scope.log = function(data) {
                console.log(data);
            };
        }
    ]);

    simpleModule.controller("simpleRowController", ["$rootScope", "$scope",
        function($rootScope, $scope) {
            $scope.rows = [];

            function expandValues(text) {
                var values = text.split(";"),
                    result = [],
                    id = "",
                    txt = "";

                for (var i in values) {
                    if (!values.hasOwnProperty(i)) continue;

                    var pair = values[i].split("=");
                    if (pair[0] && pair[1]) {
                        id = pair[0].trim();
                        txt = pair[1].trim();
                        result.push({
                            id: id,
                            text: txt
                        });
                    }
                }

                return result;
            }

            function indexOfRow(id) {
                for (var index in $scope.rows) {
                    if (!$scope.rows.hasOwnProperty(index)) continue;

                    if ($scope.rows[index].id == id) {
                        return index;
                    }
                }

                return -1;
            }

            function joinValues(object) {
                var tmp = [];

                for (var i in object) {
                    if (!object.hasOwnProperty(i)) continue;

                    tmp.push(i + "=" + object[i]);
                }

                return tmp.join(",");
            }

            $scope.createRow = function(id) {
                id = id.toLowerCase();

                var check = new RegExp("^[a-zA-Z][a-zA-Z0-9_]*$");

                if (!id || !check.test(id) || id === "metadata" || id === "custom_field") {
                    alert($rootScope.errors.incorrectId);
                    return;
                }

                if (indexOfRow(id) > -1) {
                    alert($rootScope.errors.usedId);
                    return;
                }

                $scope.rows.push({
                    "id": id,
                    "custom": true,
                    "object": "customer"
                });
            };

            $scope.deleteRow = function(id) {
                var index = indexOfRow(id);
                if (index > -1) {
                    $scope.rows.splice(index, 1);
                }
            };

            $scope.parseValues = function(id, langCode, text) {
                var index = indexOfRow(id);
                if (index > -1) {
                    $scope.rows[index].valuesList[langCode] = expandValues(text);
                }
            };

            $scope.changeDefault = function(id) {
                var index = indexOfRow(id);

                if (index > -1) {
                    if ($scope.rows[index].type == 'checkbox' && !angular.isObject($scope.rows[index]['default'].saved)) {
                        $scope.rows[index]['default'].saved = {};
                    } else if ($scope.rows[index].type != 'checkbox' && angular.isObject($scope.rows[index]['default'].saved)) {
                        $scope.rows[index]['default'].saved = '';
                    }
                }
            };

            $scope.loadValues = function(id) {
                var index = indexOfRow(id);

                if (index > -1) {
                    var filter = "";
                    var filterIndex = indexOfRow($scope.rows[index].values.filter);

                    if (filterIndex > -1) {
                        filter = !angular.isObject($scope.rows[filterIndex]['default'].saved) ? $scope.rows[filterIndex]['default'].saved : joinValues($scope.rows[filterIndex]['default'].saved);
                    }

                    $.getJSON($rootScope.storeUrl + "index.php?route=common/simple_connector&method=" + $scope.rows[index].values.method + "&filter=" + filter + ($scope.rows[index].custom ? "&custom=1 " : "") + (typeof $rootScope.settings.additionalParams !== "undefined" ? "&" + $rootScope.settings.additionalParams : ""), function(json) {
                        $scope.rows[index].valuesList[$rootScope.currentLanguage] = json;
                        $scope.$apply();
                    });
                }
            };

            $scope.reloadDependedValues = function(id) {
                for (var index in $scope.rows) {
                    if (!$scope.rows.hasOwnProperty(index)) continue;

                    if (typeof $scope.rows[index].values !== "undefined" && $scope.rows[index].values.source == "model" && $scope.rows[index].values.method !== "" && $scope.rows[index].values.filter == id) {
                        $scope.loadValues($scope.rows[index].id);
                    }
                }
            };

            $scope.loadAllValues = function() {
                var index = -1,
                    field;

                for (index in $rootScope.settings.fields) {
                    field = $rootScope.settings.fields[index];
                    if ($scope.inArray(field.type, $rootScope.typesWithValues) && field.values.source == "model" && !field.values.filter) {
                        $scope.loadValues(field.id);
                    }
                }

                for (index in $rootScope.settings.fields) {
                    field = $rootScope.settings.fields[index];
                    if ($scope.inArray(field.type, $rootScope.typesWithValues) && field.values.source == "model" && field.values.filter) {
                        $scope.loadValues(field.id);
                    }
                }
            };

            $rootScope.inArray = function(type, arr) {
                return $.inArray(type, arr) >= 0 ? true : false;
            };
        }
    ]);

    simpleModule.controller("simpleStepsController", ["$rootScope", "$scope", "$sce", "$compile",
        function($rootScope, $scope, $sce, $compile) {
            function getCurrentSettingsGroup() {
                for (var i in $rootScope.settings.checkout) {
                    if (!$rootScope.settings.checkout.hasOwnProperty(i)) continue;

                    if ($rootScope.settings.checkout[i].settingsId == $rootScope.settingsId) {
                        return $rootScope.settings.checkout[i];
                    }
                }

                return $rootScope.settings.checkout[0];
            }

            function indexOfStep(id) {
                var currentSettingsGroup = getCurrentSettingsGroup();

                for (var index in currentSettingsGroup.steps) {
                    if (!currentSettingsGroup.steps.hasOwnProperty(index)) continue;

                    if (currentSettingsGroup.steps[index].id == id) {
                        return index;
                    }
                }

                return -1;
            }

            $scope.addStep = function() {
                var currentSettingsGroup = getCurrentSettingsGroup();

                var id = "step_" + currentSettingsGroup.steps.length;

                $scope.removePaymentForm();

                currentSettingsGroup.steps.push({
                    id: id
                });
            };

            $scope.removeStep = function(id) {
                var currentSettingsGroup = getCurrentSettingsGroup();
                currentSettingsGroup.steps.splice(indexOfStep(id));
                $scope.parseAvaliableBlocks();
            };

            $scope.getLastStepId = function() {
                var currentSettingsGroup = getCurrentSettingsGroup();
                return "step_" + (currentSettingsGroup.steps.length - 1);
            };

            $scope.parseAvaliableBlocks = function() {
                var tmp,
                    blocks;

                var currentSettingsGroup = getCurrentSettingsGroup();

                for (var id in $rootScope.blocks) {
                    if (!$rootScope.blocks.hasOwnProperty(id)) continue;

                    $rootScope.blocks[id].used[$rootScope.settingsId] = false;
                }

                for (var index in currentSettingsGroup.steps) {
                    if (!currentSettingsGroup.steps.hasOwnProperty(index)) continue;

                    tmp = currentSettingsGroup.steps[index].template;
                    tmp = tmp.replace(/\s+/g, "");
                    tmp = tmp.replace(/\{left_column\}(.*?)\{\/left_column\}/g, "$1");
                    tmp = tmp.replace(/\{right_column\}(.*?)\{\/right_column\}/g, "$1");
                    tmp = tmp.replace(/\{three_column\}(.*?)\{\/three_column\}/g, "$1");
                    tmp = tmp.replace(/^\{|\}$/g, "");

                    blocks = tmp ? tmp.split("}{") : [];

                    for (var j in blocks) {
                        if (!blocks.hasOwnProperty(j)) continue;

                        if (blocks[j] == "comment") {
                            continue;
                        }
                        if (typeof $rootScope.blocks[blocks[j]] !== "undefined") {
                            $rootScope.blocks[blocks[j]].used[$rootScope.settingsId] = true;
                        }
                    }
                }
            };

            $scope.removePaymentForm = function() {
                var tmp = '',
                    position = -1,
                    blocks = [],
                    founded = false;

                var currentSettingsGroup = getCurrentSettingsGroup();

                for (var index in currentSettingsGroup.steps) {
                    if (!currentSettingsGroup.steps.hasOwnProperty(index)) continue;

                    tmp = currentSettingsGroup.steps[index].template;
                    tmp = tmp.replace(/\s+/g, "");
                    tmp = tmp.replace(/^\{|\}$/g, "");

                    blocks = tmp ? tmp.split("}{") : [];

                    position = blocks.indexOf("payment_form");

                    if (position > -1) {
                        blocks.splice(position, 1);
                        founded = true;
                    }

                    currentSettingsGroup.steps[index].template = blocks.length ? ("{" + blocks.join("}{") + "}") : '';

                    if (founded) {
                        break;
                    }
                }

                $scope.parseAvaliableBlocks();
            };

            $scope.removeBlock = function(stepId, position) {
                var currentSettingsGroup = getCurrentSettingsGroup();

                var blocks = [],
                    tmp = currentSettingsGroup.steps[indexOfStep(stepId)].template,
                    additionalPosition = -1;

                tmp = tmp.replace(/\s+/g, "");
                tmp = tmp.replace(/^\{|\}$/g, "");

                blocks = tmp ? tmp.split("}{") : [];

                if (blocks[position - 1] == "left_column") {
                    additionalPosition = blocks.indexOf("/left_column", position);
                } else if (blocks[position - 1] == "right_column") {
                    additionalPosition = blocks.indexOf("/right_column", position);
                } else if (blocks[position - 1] == "three_column") {
                    additionalPosition = blocks.indexOf("/three_column", position);
                }

                blocks.splice(position - 1, 1);
                if (additionalPosition > -1) {
                    blocks.splice(additionalPosition - 1, 1);
                }

                currentSettingsGroup.steps[indexOfStep(stepId)].template = blocks.length ? ("{" + blocks.join("}{") + "}") : "";
            };

            $scope.changeTemplate = function(what) {
                var currentSettingsGroup = getCurrentSettingsGroup();

                var blocks = [],
                    tmp = currentSettingsGroup.steps[indexOfStep(what.step)].template;

                tmp = tmp.replace(/\s+/g, "");
                tmp = tmp.replace(/^\{|\}$/g, "");

                blocks = tmp ? tmp.split("}{") : [];

                if (what.current == -1) {
                    if (what.block == "two") {
                        blocks.splice(what.position, 0, "left_column", "/left_column", "right_column", "/right_column");
                    } else if (what.block == "three") {
                        blocks.splice(what.position, 0, "three_column", "/three_column", "three_column", "/three_column", "three_column", "/three_column");
                    } else {
                        blocks.splice(what.position > 0 ? what.position - 1 : what.position, 0, what.block);
                    }
                } else {
                    blocks.splice(what.current - 1, 1);
                    blocks.splice(what.position > 0 ? what.position - 1 : what.position, 0, what.block);
                }

                currentSettingsGroup.steps[indexOfStep(what.step)].template = blocks.length ? ("{" + blocks.join("}{") + "}") : "";
                $scope.$apply();
            };

            $scope.$on("drop", function(event, what) {
                $scope.changeTemplate(what);
            });
        }
    ]);

    simpleModule.controller("simpleAddressFormatsController", function($rootScope, $scope) {});

    simpleModule.controller("simpleShippingController", function($rootScope, $scope) {
        $scope.setData = {
            code: "",
            title: ""
        };

        function getCurrentSettingsGroup() {
            for (var i in $rootScope.settings.checkout) {
                if (!$rootScope.settings.checkout.hasOwnProperty(i)) continue;

                if ($rootScope.settings.checkout[i].settingsId == $rootScope.settingsId) {
                    return $rootScope.settings.checkout[i];
                }
            }

            return $rootScope.settings.checkout[0];
        }

        $scope.removeShippingSubmethod = function(methodCode, submethodCode) {
            var currentSettingsGroup = getCurrentSettingsGroup();
            delete currentSettingsGroup.shipping.methods[methodCode].methods[submethodCode];
            delete $rootScope.shippingMethods[submethodCode];

            $.get("index.php?route=module/simple/remove_method&code=" + methodCode + "&subcode=" + submethodCode + "&token=" + $rootScope.token + (typeof $rootScope.settings.additionalParams !== "undefined" ? "&" + $rootScope.settings.additionalParams : ""));
        };

        $scope.addShippingSubmethod = function(methodCode) {
            var check = new RegExp("^[a-zA-Z0-9_*]*$");

            if (!$scope.setData.code || !check.test($scope.setData.code)) {
                alert($rootScope.errors.incorrectId);
                return;
            }

            var currentSettingsGroup = getCurrentSettingsGroup();

            var fullCode = methodCode + "." + $scope.setData.code;

            if (!$scope.setData.title) {
                $scope.setData.title = fullCode;
            }

            currentSettingsGroup.shipping.methods[methodCode].methods[fullCode] = {
                code: fullCode,
                title: {
                    ru: $scope.setData.title
                }
            };

            $rootScope.shippingMethods[fullCode] = {
                code: fullCode,
                title: {
                    ru: $scope.setData.title
                }
            };
        };
    });

    simpleModule.controller("simpleSetController", function($rootScope, $scope) {
        $scope.setData = {
            byDefault: 1,
            shippingMethod: "",
            paymentMethod: "",
            scenario: "default",
            rows: {},
            row: "",
            filterForObjects: [],
            selectedType: "",
            selectedId: "",
            both: false,
            onlyCustom: false
        };

        $scope.findRow = function(type, id) {
            for (var i in $scope.setData.rows[$scope.setData.scenario]) {
                if (!$scope.setData.rows[$scope.setData.scenario].hasOwnProperty(i)) continue;

                if ($scope.setData.rows[$scope.setData.scenario][i].type == type && $scope.setData.rows[$scope.setData.scenario][i].id == id) {
                    return i;
                }
            }
            return -1;
        };

        $scope.getScenarios = function() {
            var result = [];

            for (var scenarioId in $scope.setData.rows) {
                if (!$scope.setData.rows.hasOwnProperty(scenarioId)) continue;

                if (scenarioId == "default") {
                    continue;
                }

                var tmp = scenarioId.split("|");
                var name = "";

                if (typeof tmp[0] !== "undefined" && typeof $rootScope.shippingMethods[tmp[0]] !== "undefined" && typeof $rootScope.shippingMethods[tmp[0]]["title"][$rootScope.currentLanguage] !== "undefined") {
                    name = $rootScope.shippingMethods[tmp[0]]["title"][$rootScope.currentLanguage];
                }

                if (typeof tmp[1] !== "undefined" && typeof $rootScope.paymentMethods[tmp[1]] !== "undefined" && typeof $rootScope.paymentMethods[tmp[1]]["title"][$rootScope.currentLanguage] !== "undefined") {
                    if (name !== "") {
                        name = name + " + ";
                    }

                    name = name + $rootScope.paymentMethods[tmp[1]]["title"][$rootScope.currentLanguage];
                }

                result.push(name);
            }

            return result;
        };

        $scope.getScenariosCount = function() {
            var count = -1;

            for (var scenarioId in $scope.setData.rows) {
                if (!$scope.setData.rows.hasOwnProperty(scenarioId)) continue;

                count++;
            }

            return count;
        };

        function getScenarioName() {
            if ($scope.setData.shippingMethod || $scope.setData.paymentMethod) {
                return $scope.setData.shippingMethod + "|" + $scope.setData.paymentMethod;
            }

            return "default";
        }

        $scope.setScenarioName = function(shippingMethod, paymentMethod) {
            $scope.setData.shippingMethod = shippingMethod;
            $scope.setData.paymentMethod = paymentMethod;
            $scope.setData.scenario = getScenarioName();
            $scope.setData.byDefault = 0;
        };

        $scope.selectAll = function(type, id, what, status) {
            var index = $scope.findRow(type, id);

            var row = {};

            if (index >= 0) {
                row = $scope.setData.rows[$scope.setData.scenario][index];
            }

            var values = $scope.getFieldValues(row.masterField);

            for (var i in values) {
                if (!values.hasOwnProperty(i)) continue;

                if (what == "display") {
                    row.displayWhen[values[i].id] = status;
                }

                if (what == "require") {
                    row.requireWhen[values[i].id] = status;
                }
            }

            $scope.createScenario();
        };

        $scope.createScenario = function() {
            var scenario = getScenarioName();

            if (typeof $scope.setData.rows[scenario] === "undefined") {
                $scope.setData.rows[scenario] = [];
                for (var index in $scope.setData.rows["default"]) {
                    if (!$scope.setData.rows["default"].hasOwnProperty(index)) continue;

                    var copy = angular.copy($scope.setData.rows["default"][index]);
                    $scope.setData.rows[scenario].push(copy);
                }
                $scope.setData.scenario = scenario;
            }
        };

        $scope.resetScenarioToDefault = function() {
            for (var i in $scope.setData.rows) {
                if (!$scope.setData.rows.hasOwnProperty(i)) continue;

                if (i !== "default") {
                    delete $scope.setData.rows[i];
                }
            }

            $scope.setData.byDefault = 1;
            $scope.setData.scenario = "default";
            $scope.setData.shippingMethod = "";
            $scope.setData.paymentMethod = "";
        };

        $scope.setScenario = function() {
            var scenario = getScenarioName();

            if (typeof $scope.setData.rows[scenario] !== "undefined") {
                $scope.setData.scenario = scenario;
            } else {
                $scope.setData.scenario = "default";
            }
        };

        function getFieldsInRows() {
            var fields = [];
            for (var index in $scope.setData.rows[$scope.setData.scenario]) {
                if (!$scope.setData.rows[$scope.setData.scenario].hasOwnProperty(index)) continue;

                if ($scope.setData.rows[$scope.setData.scenario][index].type == "field") {
                    fields.push($scope.setData.rows[$scope.setData.scenario][index].id);
                }
            }
            return fields;
        }

        $scope.getAvailableFields = function() {
            var fields = [],
                usedFields = getFieldsInRows(),
                field,
                skipByObjects;

            for (var index in $rootScope.settings.fields) {
                if (!$rootScope.settings.fields.hasOwnProperty(index)) continue;

                field = $rootScope.settings.fields[index];

                if ($scope.setData.onlyCustom && !field.custom) {
                    continue;
                }

                skipByObjects = true;
                if (!field.custom) {
                    for (var o in field.objects) {
                        if (!field.objects.hasOwnProperty(o)) continue;

                        if (typeof field.objects[o] !== "undefined" && field.objects[o] && $scope.setData.filterForObjects.indexOf(o) >= 0) {
                            skipByObjects = false;
                            break;
                        }
                    }
                } else {
                    if ($scope.setData.filterForObjects.indexOf(field.object) >= 0) {
                        skipByObjects = false;
                    }
                }

                if (!skipByObjects && usedFields.indexOf(field.id) < 0) {
                    fields.push(field);
                }
            }

            return fields;
        };

        $scope.getAvailableHeaders = function() {
            var headers = [];

            for (var index in $rootScope.settings.headers) {
                if (!$rootScope.settings.headers.hasOwnProperty(index)) continue;

                var header = $rootScope.settings.headers[index];

                headers.push(header);
            }

            return headers;
        };

        $scope.addRow = function() {
            function compareSort(objA, objB) {
                return objA.sortOrder - objB.sortOrder;
            }

            if ($scope.setData.row) {
                var info = $scope.setData.row.split(":");
                var type = info[0];
                var id = info[1];

                if ($scope.findRow(type, id) == -1) {
                    $scope.createScenario();

                    var sort = $scope.setData.rows[$scope.setData.scenario].length + 1;

                    $scope.setData.rows[$scope.setData.scenario].sort(compareSort);

                    $scope.setData.rows[$scope.setData.scenario].push({
                        type: type,
                        id: id,
                        sortOrder: sort
                    });

                    var k = 1;
                    for (var i in $scope.setData.rows[$scope.setData.scenario]) {
                        if (!$scope.setData.rows[$scope.setData.scenario].hasOwnProperty(i)) continue;

                        $scope.setData.rows[$scope.setData.scenario][i].sortOrder = k++;
                    }
                }

                $scope.setData.row = "";
            }
        };

        $scope.removeRow = function(type, id) {
            var index = $scope.findRow(type, id);
            if (index > -1) {
                $scope.createScenario();
                $scope.setData.rows[$scope.setData.scenario].splice(index, 1);
            }
        };

        function indexOfField(id) {
            for (var index in $rootScope.settings.fields) {
                if (!$rootScope.settings.fields.hasOwnProperty(index)) continue;

                if ($rootScope.settings.fields[index].id == id) {
                    return index;
                }
            }

            return -1;
        }

        function indexOfHeader(id) {
            for (var index in $rootScope.settings.headers) {
                if (!$rootScope.settings.headers.hasOwnProperty(index)) continue;

                if ($rootScope.settings.headers[index].id == id) {
                    return index;
                }
            }

            return -1;
        }

        $scope.existRow = function(type, id) {
            if (type === "header") {
                if (indexOfHeader(id) >= 0) {
                    return true;
                }
            } else if (type === "field") {
                var index = indexOfField(id);
                if (index >= 0) {
                    var field = $rootScope.settings.fields[index];
                    var skipByObjects = true;
                    if (!field.custom) {
                        for (var o in field.objects) {
                            if (!field.objects.hasOwnProperty(o)) continue;

                            if (typeof field.objects[o] !== "undefined" && field.objects[o] && $scope.setData.filterForObjects.indexOf(o) >= 0) {
                                skipByObjects = false;
                                break;
                            }
                        }
                    } else {
                        if ($scope.setData.filterForObjects.indexOf(field.object) >= 0) {
                            skipByObjects = false;
                        }
                    }
                    return !skipByObjects;
                }
            } else if (type === "splitter") {
                return true;
            }

            return false;
        };

        $scope.getFieldValues = function(id) {
            var index = indexOfField(id);
            if (index > -1) {
                var field = $rootScope.settings.fields[index];
                if (typeof field.valuesList !== "undefined" && field.valuesList[$rootScope.currentLanguage] !== "undefined") {
                    return $rootScope.settings.fields[index].valuesList[$rootScope.currentLanguage];
                }
            }
            return [];
        };

        $scope.deleteUnused = function(initial, current) {
            var remove = false;

            if (current) {
                for (var i in initial) {
                    if (!initial.hasOwnProperty(i)) continue;

                    remove = true;

                    for (var j in current) {
                        if (!current.hasOwnProperty(j)) continue;

                        if (typeof current[j]["id"] !== "undefined" && current[j]["id"] == i) {
                            remove = false;
                        }
                    }

                    if (remove) {
                        delete initial[i];
                    }
                }
            }

            return initial;
        }

        $scope.isFieldAutoreload = function(id) {
            var index = indexOfField(id);
            if (index > -1) {
                var field = $rootScope.settings.fields[index];
                if (typeof field.autoreload !== "undefined" && field.autoreload) {
                    return true;
                }
            }
            return false;
        };

        $scope.getFieldName = function(id) {
            var index = indexOfField(id);
            if (index > -1) {
                var field = $rootScope.settings.fields[index];
                if (typeof field.label !== "undefined" && field.label[$rootScope.currentLanguage] !== "undefined" && field.label[$rootScope.currentLanguage]) {
                    return field.label[$rootScope.currentLanguage];
                } else {
                    return field.id;
                }
            }
            return "";
        };

        $scope.getHeaderName = function(id) {
            var index = indexOfHeader(id);
            if (index > -1) {
                var header = $rootScope.settings.headers[index];
                if (typeof header.label !== "undefined" && header.label[$rootScope.currentLanguage] !== "undefined" && header.label[$rootScope.currentLanguage]) {
                    return header.label[$rootScope.currentLanguage];
                } else {
                    return header.id;
                }
            }
            return "";
        };

        $scope.select = function(type, id) {
            $scope.setData.selectedType = type;
            $scope.setData.selectedId = id;
        };

        $scope.selected = function(type, id) {
            return type != 'splitter' && $scope.setData.selectedType == type && $scope.setData.selectedId == id ? true : false;
        };

        $scope.sortAllRows = function() {
            function compareSort(objA, objB) {
                return objA.sortOrder - objB.sortOrder;
            }

            for (var scenario in $scope.setData.rows) {
                if (!$scope.setData.rows.hasOwnProperty(scenario)) continue;

                for (var i in $scope.setData.rows[scenario]) {
                    if (!$scope.setData.rows[scenario].hasOwnProperty(i)) continue;

                    if (!$scope.existRow($scope.setData.rows[scenario][i].type, $scope.setData.rows[scenario][i].id)) {
                        $scope.setData.rows[scenario].splice(i, 1);
                    }
                }

                $scope.setData.rows[scenario].sort(compareSort);
            }
        };
    });

    simpleModule.directive('uiSortable', function($templateCache) {
        return {
            link: function($scope, element, attrs) {
                element.sortable({
                    revert: true,
                    stop: function(event, ui) {
                        var i = 1,
                            type,
                            id;

                        $scope.createScenario();

                        var rows = JSON.parse(JSON.stringify($scope.setData.rows[$scope.setData.scenario]));

                        ui.item.parent().find('div.set-row').each(function() {
                            type = $(this).attr("row-type");
                            id = $(this).attr("row-id");

                            var index = $scope.findRow(type, id);

                            if (index >= 0) {
                                rows[index].sortOrder = i++;
                            }
                        });


                        $scope.setData.rows[$scope.setData.scenario] = null;

                        $scope.$apply();

                        $scope.setData.rows[$scope.setData.scenario] = rows;

                        $scope.$apply();
                    }
                });
            }
        };
    });

    window.simpleModule = simpleModule;

})(jQuery);