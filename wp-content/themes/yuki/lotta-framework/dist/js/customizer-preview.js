(() => {
    var __webpack_exports__ = {};
    function _toConsumableArray(arr) {
        return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread();
    }
    function _nonIterableSpread() {
        throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }
    function _unsupportedIterableToArray(o, minLen) {
        if (!o) return;
        if (typeof o === "string") return _arrayLikeToArray(o, minLen);
        var n = Object.prototype.toString.call(o).slice(8, -1);
        if (n === "Object" && o.constructor) n = o.constructor.name;
        if (n === "Map" || n === "Set") return Array.from(o);
        if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
    }
    function _iterableToArray(iter) {
        if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
    }
    function _arrayWithoutHoles(arr) {
        if (Array.isArray(arr)) return _arrayLikeToArray(arr);
    }
    function _arrayLikeToArray(arr, len) {
        if (len == null || len > arr.length) len = arr.length;
        for (var i = 0, arr2 = new Array(len); i < len; i++) {
            arr2[i] = arr[i];
        }
        return arr2;
    }
    var makeShortcutFor = function makeShortcutFor(item) {
        if (_toConsumableArray(item.children).find((function(e) {
            return e.matches(".lotta-customizer-shortcut");
        }))) {
            return;
        }
        var shortcut = document.createElement("a");
        shortcut.classList.add("lotta-customizer-shortcut");
        shortcut.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path></svg>';
        shortcut.addEventListener("click", (function(e) {
            e.preventDefault();
            e.stopPropagation();
            wp.customize.preview.send("lotta-initiate-deep-link", item.dataset.shortcutLocation);
        }));
        item.appendChild(shortcut);
    };
    var makeAllShortcuts = function makeAllShortcuts() {
        _toConsumableArray(document.querySelectorAll("[data-shortcut-location]")).map((function(el) {
            return makeShortcutFor(el);
        }));
    };
    if (wp.customize) {
        wp.customize.bind("preview-ready", (function() {
            makeAllShortcuts();
        }));
        if (wp.customize.selectiveRefresh) {
            wp.customize.selectiveRefresh.bind("partial-content-rendered", (function() {
                makeAllShortcuts();
            }));
        }
    }
})();