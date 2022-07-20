(() => {
    var __webpack_modules__ = [ , () => {
        jQuery.extend(jQuery.expr[":"], {
            focusable: function focusable(el) {
                return jQuery(el).is("a, button, :input, [tabindex]");
            }
        });
    }, , , (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        __webpack_require__.d(__webpack_exports__, {
            default: () => __WEBPACK_DEFAULT_EXPORT__
        });
        var js_cookie__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(5);
        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }
        function _defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }
        function _createClass(Constructor, protoProps, staticProps) {
            if (protoProps) _defineProperties(Constructor.prototype, protoProps);
            if (staticProps) _defineProperties(Constructor, staticProps);
            Object.defineProperty(Constructor, "prototype", {
                writable: false
            });
            return Constructor;
        }
        var ThemeSwitch = function() {
            function ThemeSwitch($) {
                _classCallCheck(this, ThemeSwitch);
                var $switch = this;
                $(".yuki-theme-switch").each((function() {
                    var $this = $(this);
                    if ($this.hasClass("yuki-theme-switch-initialized")) {
                        return;
                    }
                    $this.addClass("yuki-theme-switch-initialized");
                    $this.on("click", $switch.toggle.bind($switch));
                }));
            }
            _createClass(ThemeSwitch, [ {
                key: "toggle",
                value: function toggle() {
                    var current = jQuery(document.documentElement).attr("data-yuki-theme");
                    if (current === "dark") {
                        this.setLightMode();
                    } else {
                        this.setDarkMode();
                    }
                }
            }, {
                key: "setLightMode",
                value: function setLightMode() {
                    this.setGlobalTransition();
                    jQuery(document.documentElement).attr("data-yuki-theme", "light");
                    js_cookie__WEBPACK_IMPORTED_MODULE_0__["default"].set("yuki-color-mode", "light", {
                        expires: 365
                    });
                }
            }, {
                key: "setDarkMode",
                value: function setDarkMode() {
                    this.setGlobalTransition();
                    jQuery(document.documentElement).attr("data-yuki-theme", "dark");
                    js_cookie__WEBPACK_IMPORTED_MODULE_0__["default"].set("yuki-color-mode", "dark", {
                        expires: 365
                    });
                }
            }, {
                key: "setGlobalTransition",
                value: function setGlobalTransition() {
                    document.body.classList.add("transition-force-none");
                    setTimeout((function() {
                        document.body.classList.remove("transition-force-none");
                    }), 50);
                }
            } ]);
            return ThemeSwitch;
        }();
        const __WEBPACK_DEFAULT_EXPORT__ = ThemeSwitch;
    }, (__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        __webpack_require__.d(__webpack_exports__, {
            default: () => __WEBPACK_DEFAULT_EXPORT__
        });
        function assign(target) {
            for (var i = 1; i < arguments.length; i++) {
                var source = arguments[i];
                for (var key in source) {
                    target[key] = source[key];
                }
            }
            return target;
        }
        var defaultConverter = {
            read: function(value) {
                if (value[0] === '"') {
                    value = value.slice(1, -1);
                }
                return value.replace(/(%[\dA-F]{2})+/gi, decodeURIComponent);
            },
            write: function(value) {
                return encodeURIComponent(value).replace(/%(2[346BF]|3[AC-F]|40|5[BDE]|60|7[BCD])/g, decodeURIComponent);
            }
        };
        function init(converter, defaultAttributes) {
            function set(key, value, attributes) {
                if (typeof document === "undefined") {
                    return;
                }
                attributes = assign({}, defaultAttributes, attributes);
                if (typeof attributes.expires === "number") {
                    attributes.expires = new Date(Date.now() + attributes.expires * 864e5);
                }
                if (attributes.expires) {
                    attributes.expires = attributes.expires.toUTCString();
                }
                key = encodeURIComponent(key).replace(/%(2[346B]|5E|60|7C)/g, decodeURIComponent).replace(/[()]/g, escape);
                var stringifiedAttributes = "";
                for (var attributeName in attributes) {
                    if (!attributes[attributeName]) {
                        continue;
                    }
                    stringifiedAttributes += "; " + attributeName;
                    if (attributes[attributeName] === true) {
                        continue;
                    }
                    stringifiedAttributes += "=" + attributes[attributeName].split(";")[0];
                }
                return document.cookie = key + "=" + converter.write(value, key) + stringifiedAttributes;
            }
            function get(key) {
                if (typeof document === "undefined" || arguments.length && !key) {
                    return;
                }
                var cookies = document.cookie ? document.cookie.split("; ") : [];
                var jar = {};
                for (var i = 0; i < cookies.length; i++) {
                    var parts = cookies[i].split("=");
                    var value = parts.slice(1).join("=");
                    try {
                        var foundKey = decodeURIComponent(parts[0]);
                        jar[foundKey] = converter.read(value, foundKey);
                        if (key === foundKey) {
                            break;
                        }
                    } catch (e) {}
                }
                return key ? jar[key] : jar;
            }
            return Object.create({
                set,
                get,
                remove: function(key, attributes) {
                    set(key, "", assign({}, attributes, {
                        expires: -1
                    }));
                },
                withAttributes: function(attributes) {
                    return init(this.converter, assign({}, this.attributes, attributes));
                },
                withConverter: function(converter) {
                    return init(assign({}, this.converter, converter), this.attributes);
                }
            }, {
                attributes: {
                    value: Object.freeze(defaultAttributes)
                },
                converter: {
                    value: Object.freeze(converter)
                }
            });
        }
        var api = init(defaultConverter, {
            path: "/"
        });
        const __WEBPACK_DEFAULT_EXPORT__ = api;
    }, (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        __webpack_require__.d(__webpack_exports__, {
            default: () => __WEBPACK_DEFAULT_EXPORT__
        });
        function _defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }
        function _createClass(Constructor, protoProps, staticProps) {
            if (protoProps) _defineProperties(Constructor.prototype, protoProps);
            if (staticProps) _defineProperties(Constructor, staticProps);
            Object.defineProperty(Constructor, "prototype", {
                writable: false
            });
            return Constructor;
        }
        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }
        var Navigation = _createClass((function Navigation($) {
            _classCallCheck(this, Navigation);
            $("ul.sf-menu").superfish({
                animation: {
                    opacity: "show",
                    marginTop: "0"
                },
                animationOut: {
                    opacity: "hide",
                    marginTop: "10"
                },
                speed: 300,
                speedOut: 150
            });
        }));
        const __WEBPACK_DEFAULT_EXPORT__ = Navigation;
    }, (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        __webpack_require__.d(__webpack_exports__, {
            default: () => __WEBPACK_DEFAULT_EXPORT__
        });
        function _defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }
        function _createClass(Constructor, protoProps, staticProps) {
            if (protoProps) _defineProperties(Constructor.prototype, protoProps);
            if (staticProps) _defineProperties(Constructor, staticProps);
            Object.defineProperty(Constructor, "prototype", {
                writable: false
            });
            return Constructor;
        }
        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }
        var CollapsableMenu = _createClass((function CollapsableMenu($) {
            _classCallCheck(this, CollapsableMenu);
            $(".yuki-collapsable-menu.collapsable").each((function(_, menu) {
                $(menu).find(".menu-item-has-children, .page_item_has_children").each((function(_, item) {
                    var $submenu = $(item).find("> .sub-menu, > .children");
                    var $toggle = $(item).find("> a .yuki-dropdown-toggle");
                    $submenu.hide();
                    $toggle.on("click", (function(ev) {
                        ev.stopPropagation();
                        ev.preventDefault();
                        $toggle.toggleClass("active");
                        $submenu.slideToggle(300);
                    }));
                }));
            }));
        }));
        const __WEBPACK_DEFAULT_EXPORT__ = CollapsableMenu;
    }, , (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        __webpack_require__.d(__webpack_exports__, {
            default: () => __WEBPACK_DEFAULT_EXPORT__
        });
        function _defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }
        function _createClass(Constructor, protoProps, staticProps) {
            if (protoProps) _defineProperties(Constructor.prototype, protoProps);
            if (staticProps) _defineProperties(Constructor, staticProps);
            Object.defineProperty(Constructor, "prototype", {
                writable: false
            });
            return Constructor;
        }
        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }
        var Toggle = _createClass((function Toggle($) {
            _classCallCheck(this, Toggle);
            var _this = this;
            var scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
            if (scrollBarWidth > 0) {
                document.body.style.setProperty("--scrollbar-width", "".concat(scrollBarWidth, "px"));
            }
            $("[data-toggle-target]").each((function() {
                var $this = $(this);
                if ($this.hasClass("yuki-toggleable")) {
                    return;
                }
                $this.addClass("yuki-toggleable");
                $(this).on("click", (function() {
                    var el = $(this);
                    var $target = $(el.data("toggle-target"));
                    var $showFocus = $(el.data("toggle-show-focus"));
                    var $hiddenFocus = $(el.data("toggle-hidden-focus"));
                    console.log($target.hasClass("active"));
                    $target.toggleClass("active");
                    if ($target.hasClass("active")) {
                        $(document.body).addClass("yuki-modal-visible");
                        if ($showFocus && $showFocus.first()) {
                            setTimeout((function() {
                                return $showFocus.first().focus();
                            }), 100);
                        }
                    } else {
                        setTimeout((function() {
                            $(document.body).removeClass("yuki-modal-visible");
                        }), 300);
                        if ($hiddenFocus && $hiddenFocus.first()) {
                            setTimeout((function() {
                                return $hiddenFocus.first().focus();
                            }), 100);
                        }
                    }
                }));
            }));
        }));
        const __WEBPACK_DEFAULT_EXPORT__ = Toggle;
    }, (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        __webpack_require__.d(__webpack_exports__, {
            default: () => __WEBPACK_DEFAULT_EXPORT__
        });
        function _defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }
        function _createClass(Constructor, protoProps, staticProps) {
            if (protoProps) _defineProperties(Constructor.prototype, protoProps);
            if (staticProps) _defineProperties(Constructor, staticProps);
            Object.defineProperty(Constructor, "prototype", {
                writable: false
            });
            return Constructor;
        }
        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }
        var FocusRedirect = _createClass((function FocusRedirect($) {
            _classCallCheck(this, FocusRedirect);
            $("[data-redirect-focus]").each((function() {
                var $this = $(this);
                var $target = $($this.data("redirect-focus"));
                $this.on("keydown", (function(ev) {
                    var tabKey = ev.keyCode === 9;
                    var shiftKey = ev.shiftKey;
                    var $focusable = $this.find(":focusable:visible");
                    var $last = $focusable.last();
                    var $first = $focusable.first();
                    var active = document.activeElement;
                    if (tabKey && !shiftKey && $last.is(active)) {
                        ev.preventDefault();
                        $target.focus();
                    }
                    if (tabKey && shiftKey && $first.is(active)) {
                        ev.preventDefault();
                        $target.focus();
                    }
                }));
                $target.on("keydown", (function(ev) {
                    if (!$this.is(":visible")) {
                        return;
                    }
                    var tabKey = ev.keyCode === 9;
                    var shiftKey = ev.shiftKey;
                    var $focusable = $this.find(":focusable:visible");
                    var $last = $focusable.last();
                    var $first = $focusable.first();
                    if (tabKey && !shiftKey) {
                        ev.preventDefault();
                        $first.focus();
                    }
                    if (tabKey && shiftKey) {
                        ev.preventDefault();
                        $last.focus();
                    }
                }));
            }));
        }));
        const __WEBPACK_DEFAULT_EXPORT__ = FocusRedirect;
    }, (__unused_webpack_module, __webpack_exports__, __webpack_require__) => {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        __webpack_require__.d(__webpack_exports__, {
            default: () => __WEBPACK_DEFAULT_EXPORT__
        });
        function _defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || false;
                descriptor.configurable = true;
                if ("value" in descriptor) descriptor.writable = true;
                Object.defineProperty(target, descriptor.key, descriptor);
            }
        }
        function _createClass(Constructor, protoProps, staticProps) {
            if (protoProps) _defineProperties(Constructor.prototype, protoProps);
            if (staticProps) _defineProperties(Constructor, staticProps);
            Object.defineProperty(Constructor, "prototype", {
                writable: false
            });
            return Constructor;
        }
        function _classCallCheck(instance, Constructor) {
            if (!(instance instanceof Constructor)) {
                throw new TypeError("Cannot call a class as a function");
            }
        }
        var ToTop = _createClass((function ToTop($) {
            _classCallCheck(this, ToTop);
            var $scrollTop = $("#scroll-top");
            $(window).on("scroll", (function() {
                if ($(this).scrollTop() > 200) {
                    $scrollTop.addClass("active");
                } else {
                    $scrollTop.removeClass("active");
                }
            }));
            $scrollTop.on("click", (function() {
                $("html, body").scrollTop(0);
                return false;
            }));
        }));
        const __WEBPACK_DEFAULT_EXPORT__ = ToTop;
    } ];
    var __webpack_module_cache__ = {};
    function __webpack_require__(moduleId) {
        var cachedModule = __webpack_module_cache__[moduleId];
        if (cachedModule !== undefined) {
            return cachedModule.exports;
        }
        var module = __webpack_module_cache__[moduleId] = {
            exports: {}
        };
        __webpack_modules__[moduleId](module, module.exports, __webpack_require__);
        return module.exports;
    }
    (() => {
        __webpack_require__.n = module => {
            var getter = module && module.__esModule ? () => module["default"] : () => module;
            __webpack_require__.d(getter, {
                a: getter
            });
            return getter;
        };
    })();
    (() => {
        __webpack_require__.d = (exports, definition) => {
            for (var key in definition) {
                if (__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
                    Object.defineProperty(exports, key, {
                        enumerable: true,
                        get: definition[key]
                    });
                }
            }
        };
    })();
    (() => {
        __webpack_require__.o = (obj, prop) => Object.prototype.hasOwnProperty.call(obj, prop);
    })();
    (() => {
        __webpack_require__.r = exports => {
            if (typeof Symbol !== "undefined" && Symbol.toStringTag) {
                Object.defineProperty(exports, Symbol.toStringTag, {
                    value: "Module"
                });
            }
            Object.defineProperty(exports, "__esModule", {
                value: true
            });
        };
    })();
    var __webpack_exports__ = {};
    (() => {
        "use strict";
        __webpack_require__.r(__webpack_exports__);
        var _modules_focusable__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(1);
        var _modules_focusable__WEBPACK_IMPORTED_MODULE_0___default = __webpack_require__.n(_modules_focusable__WEBPACK_IMPORTED_MODULE_0__);
        var _modules_theme_switch__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(4);
        var _modules_navigation__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(6);
        var _modules_collapsable_menu__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(7);
        var _modules_focus_redirect__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(10);
        var _modules_toggle__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(9);
        var _modules_to_top__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(11);
        if (wp.customize && wp.customize.selectiveRefresh) {
            wp.customize.selectiveRefresh.bind("partial-content-rendered", (function() {
                if (window.ScrollReveal) ScrollReveal().sync();
                new _modules_theme_switch__WEBPACK_IMPORTED_MODULE_1__["default"](jQuery);
                new _modules_navigation__WEBPACK_IMPORTED_MODULE_2__["default"](jQuery);
                new _modules_collapsable_menu__WEBPACK_IMPORTED_MODULE_3__["default"](jQuery);
                new _modules_focus_redirect__WEBPACK_IMPORTED_MODULE_4__["default"](jQuery);
                new _modules_toggle__WEBPACK_IMPORTED_MODULE_5__["default"](jQuery);
                new _modules_to_top__WEBPACK_IMPORTED_MODULE_6__["default"](jQuery);
            }));
        }
    })();
})();