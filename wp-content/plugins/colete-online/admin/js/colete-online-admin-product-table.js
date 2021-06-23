"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

(function ($) {
  'use strict';

  $.fn.ColeteOnlineProductTable = function () {
    var objects = [];
    this.each(function () {
      objects.push(new $.ColeteOnlineProductTable($(this)));
    });
    return objects;
  };

  $.ColeteOnlineProductTable = function (element) {
    var _this = this;

    this.element = element;
    this.subscribers = {
      'validityChanges': []
    };
    var productPackages = [];
    this.unique = 0;
    $(element).find("[data-package]").each(function (key, el) {
      var e = $(el);
      var pack = +e[0].dataset["package"];
      var weight = +e.find("[data-package-weight]")[0].dataset.packageWeight;
      var width = +e.find("[data-package-width]")[0].dataset.packageWidth;
      var length = +e.find("[data-package-length]")[0].dataset.packageLength;
      var height = +e.find("[data-package-height]")[0].dataset.packageHeight;
      var name = e.find(".coleteonline-product-name").text();
      var thumbnail = e.find(".coleteonline-thumb").html();

      if (productPackages[pack] === undefined) {
        productPackages[pack] = [];
      }

      productPackages[pack].push({
        el: e,
        unique: ++_this.unique,
        name: name,
        thumbnail: thumbnail,
        weight: weight,
        width: width,
        length: length,
        height: height,
        manual: false,
        editable: false
      });
    });
    this.productPackages = productPackages.filter(function (p) {
      return p;
    });
    this.initialPackages = JSON.parse(JSON.stringify(this.productPackages));
    this.totals = [];
    this.render();
    $(".coleteonline-add-package").click(this.addPackage.bind(this));
    $(".coleteonline-reset-packages").click(this.resetAndRender.bind(this));
  };

  $.ColeteOnlineProductTable.prototype.render = function () {
    var packages = this.productPackages;
    var tbody = $(this.element).find("tbody");
    tbody.empty();
    var packagesCount = 0;

    for (var i = 0; i < packages.length; ++i) {
      if (packages[i] !== undefined && packages[i].length) {
        ++packagesCount;
      }
    }

    for (var _i = 0; _i < packages.length; ++_i) {
      var list = packages[_i];

      var _iterator = _createForOfIteratorHelper(list),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var p = _step.value;

          if (p.manual && list.length > 1) {
            p.editable = true;
          }

          if (p.manual && !p.editable) {
            continue;
          }

          var selectText = "<select\n          class=\"coleteonline-package-select-input\"\n          name=\"coleteonline-package-select-input\">";

          for (var k = 0; k <= packagesCount; ++k) {
            selectText += "<option value=\"".concat(k + 1, "\"\n            ").concat(k + 1 === _i + 1 ? "selected" : "", "\n          >").concat(k + 1, "</option>");
          }

          ;
          selectText += "</select>";
          tbody.append($("\n          <tr data-package=\"".concat(_i + 1, "\"\n            data-unique=\"").concat(p.unique, "\"\n            class=\"product-row\">\n            <td class=\"coleteonline-thumb\">\n              ").concat(p.thumbnail, "\n            </td>\n            <td class=\"coleteonline-product-name\">\n              ").concat(p.name, "\n            </td>\n            <td class=\"coleteonline-package-select\">\n              ").concat(selectText, "\n            </td>\n            <td class=\"coleteonline-package-weight\"\n              data-package-weight=\"").concat(p.weight, "\"\n              data-type=\"weight\"\n            >\n              ").concat(p.editable ? '<input type="text" ' + 'class="coleteonline-package-input ' + 'coleteonline-package-weight-input" ' + 'name="coleteonline-package-weight-input" ' + 'value="' + p.weight + '"/>' : p.weight, " kg\n            </td>\n            <td class=\"coleteonline-package-dimensions width\"\n              data-package-width=\"").concat(p.width, "\"\n              data-type=\"width\"\n            >\n              ").concat(p.editable ? '<input type="text" ' + 'class="coleteonline-package-input ' + 'coleteonline-package-width-input" ' + 'name="coleteonline-package-width-input" ' + 'value="' + p.width + '"/>' : p.width, " cm\n            </td>\n            <td class=\"coleteonline-package-dimensions length\"\n              data-package-length=\"").concat(p.length, "\"\n              data-type=\"length\"\n            >\n              ").concat(p.editable ? '<input type="text" ' + 'class="coleteonline-package-input ' + 'coleteonline-package-length-input" ' + 'name="coleteonline-package-length-input" ' + 'value="' + p.length + '"/>' : p.length, " cm\n            </td>\n            <td class=\"coleteonline-package-dimensions height\"\n              data-package-height=\"").concat(p.height, "\"\n              data-type=\"height\"\n            >\n              ").concat(p.editable ? '<input type="text" ' + 'class="coleteonline-package-input ' + 'coleteonline-package-height-input" ' + 'name="coleteonline-package-height-input" ' + 'value="' + p.height + '"/>' : p.height, " cm\n            </td>\n            <td>\n              <button type=\"button\" class=\"button coleteonline-remove-item\">\n                Del\n              </button>\n            </td>\n          </tr>")));
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      if (list.length) {
        this.renderTotalRow(list, _i);
      }
    }

    $("#coleteonline-order-content-input").val(this.getContent.bind(this));
    $(".coleteonline-package-select-input").unbind("change");
    $(".coleteonline-package-select-input").change(this.packageChanged.bind(this));
    $(".coleteonline-package-input").unbind("change");
    $(".coleteonline-package-input").change(this.packageCharacteristicsChanged.bind(this));
    $(".coleteonline-add-item").unbind("click");
    $(".coleteonline-add-item").click(this.addItem.bind(this));
    $(".coleteonline-remove-item").unbind("click");
    $(".coleteonline-remove-item").click(this.removeItem.bind(this));
    $(".coleteonline-remove-package").unbind("click");
    $(".coleteonline-remove-package").click(this.removePackage.bind(this));
    $(".total-row").find("input").unbind("change");
    $(".total-row").find("input").change(this.totalRowChange.bind(this));
    this.validateAllFields();
  };

  $.ColeteOnlineProductTable.prototype.renderTotalRow = function (list, idx) {
    var calculate = false;

    if (this.totals[idx] === undefined) {
      this.totals[idx] = {
        weight: 0,
        width: 0,
        length: 0,
        height: 0
      };
      calculate = true;
    }

    var totals = this.totals[idx];

    if (calculate) {
      var _iterator2 = _createForOfIteratorHelper(list),
          _step2;

      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var p = _step2.value;
          totals.weight += p.weight;
          totals.width = Math.max(p.width, totals.width);
          totals.length = Math.max(p.length, totals.length);
          totals.height = Math.max(p.height, totals.height);
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
    }

    var tbody = $(this.element).find("tbody");
    tbody.append("\n      <tr class=\"total-row\" data-total-package=\"".concat(idx, "\">\n        <td colspan=\"3\">\n          Total ").concat(idx + 1, "\n          <button type=\"button\" class=\"button coleteonline-add-item\">\n            +\n          </button>\n        </td>\n        <td>\n          <input type=\"text\"\n            class=\"coleteonline-package-weight-total-input\"\n            name=\"coleteonline-package-weight-total-input\"\n            data-type=\"weight\"\n            value=\"").concat(totals.weight, "\"/> kg\n        </td>\n        <td>\n          <input type=\"text\"\n            class=\"coleteonline-package-width-total-input\"\n            name=\"coleteonline-package-width-total-input\"\n            data-type=\"width\"\n            value=\"").concat(totals.width, "\"/> cm\n        </td>\n        <td>\n          <input type=\"text\"\n            class=\"coleteonline-package-length-total-input\"\n            name=\"coleteonline-package-length-total-input\"\n            data-type=\"length\"\n            value=\"").concat(totals.length, "\"/> cm\n        </td>\n        <td>\n          <input type=\"text\"\n            class=\"coleteonline-package-height-total-input\"\n            name=\"coleteonline-package-height-total-input\"\n            data-type=\"height\"\n            value=\"").concat(totals.height, "\"/> cm\n        </td>\n        <td>\n          <button type=\"button\" class=\"button coleteonline-remove-package\">\n            Del\n          </button>\n        </td>\n      </tr>\n    "));
  };

  $.ColeteOnlineProductTable.prototype.packageChanged = function (el) {
    var tar = $(el.target);
    var newValue = $(el.target).val();
    var row = tar.closest("tr");
    var uniqueId = +row[0].dataset.unique;

    var _this$findItem = this.findItem(uniqueId),
        i = _this$findItem.i,
        j = _this$findItem.j,
        found = _this$findItem.found;

    this.productPackages[i].splice(j, 1);

    if (this.productPackages[newValue - 1] === undefined) {
      this.productPackages[newValue - 1] = [];
    }

    this.productPackages[newValue - 1].push(found);
    this.productPackages = this.productPackages.filter(function (p) {
      return p && p.length;
    });
    this.totals[i] = undefined;
    this.render();
  };

  $.ColeteOnlineProductTable.prototype.addPackage = function (el) {
    this.productPackages[this.productPackages.length] = [{
      unique: ++this.unique,
      name: "-",
      thumbnail: "",
      weight: 1,
      width: 15,
      length: 15,
      height: 15,
      manual: true,
      editable: false
    }];
    this.render();
  };

  $.ColeteOnlineProductTable.prototype.addItem = function (el) {
    var tar = $(el.target);
    var pack = +tar.parents("tr")[0].dataset.totalPackage;
    this.productPackages[pack].push({
      unique: ++this.unique,
      name: "-",
      thumbnail: "",
      weight: 1,
      width: 15,
      length: 15,
      height: 15,
      manual: true,
      editable: false
    });
    this.totals[pack] = undefined;
    this.render();
  };

  $.ColeteOnlineProductTable.prototype.removeItem = function (el) {
    var tar = $(el.target);
    var row = tar.closest("tr");
    var uniqueId = +row[0].dataset.unique;

    var _this$findItem2 = this.findItem(uniqueId),
        i = _this$findItem2.i,
        j = _this$findItem2.j;

    this.productPackages[i].splice(j, 1);
    this.totals[i] = undefined;
    this.productPackages = this.productPackages.filter(function (p) {
      return p && p.length;
    });

    if (!this.productPackages.length) {
      this.reset();
    }

    this.render();
  };

  $.ColeteOnlineProductTable.prototype.removePackage = function (el) {
    var tar = $(el.target);
    var row = tar.closest("tr");
    var pack = +row[0].dataset.totalPackage;
    this.productPackages.splice(pack, 1);
    this.totals.splice(pack, 1);
    this.productPackages = this.productPackages.filter(function (p) {
      return p && p.length;
    });

    if (!this.productPackages.length) {
      this.reset();
    }

    this.render();
  };

  $.ColeteOnlineProductTable.prototype.packageCharacteristicsChanged = function (el) {
    var tar = $(el.target);
    var newValue = +tar.val();
    var type = tar.parents("td")[0].dataset.type;
    var row = tar.closest("tr");
    var uniqueId = +row[0].dataset.unique;

    var _this$findItem3 = this.findItem(uniqueId),
        i = _this$findItem3.i,
        found = _this$findItem3.found;

    found[type] = +newValue;
    this.totals[i] = undefined;
    this.render();
  };

  $.ColeteOnlineProductTable.prototype.totalRowChange = function (el) {
    var tar = $(el.target);
    var newVal = +$(tar).val();
    var type = tar[0].dataset.type;
    var pack = +tar.parents("tr")[0].dataset.totalPackage;
    this.totals[pack][type] = newVal;
    this.validateDimension(tar, type, newVal);
    this.notifyValidity();
  };

  $.ColeteOnlineProductTable.prototype.validateAllFields = function () {
    var _this2 = this;

    $('input.coleteonline-package-input').each(function (k, el) {
      var type = $(el).closest('td')[0].dataset.type;

      _this2.validateDimension(el, type, $(el).val());
    });
    $('.total-row input').each(function (k, el) {
      var type = el.dataset.type;

      _this2.validateDimension(el, type, $(el).val());
    });
    this.notifyValidity();
  };

  $.ColeteOnlineProductTable.prototype.validateDimension = function (el, type, val) {
    if (type === "weight") {
      if (isNaN(val) || val < 0.1) {
        this.addInvalidClass(el);
      } else {
        this.removeInvalidClass(el);
      }
    }

    if (type === "length" || type === "width") {
      if (isNaN(val) || val < 3) {
        this.addInvalidClass(el);
      } else {
        this.removeInvalidClass(el);
      }
    }

    if (type === "height") {
      if (isNaN(val) || val < 1) {
        this.addInvalidClass(el);
      } else {
        this.removeInvalidClass(el);
      }
    }
  };

  $.ColeteOnlineProductTable.prototype.subscribe = function (type, fn) {
    this.subscribers[type].push(fn);
    this.notifyValidity();
  };

  $.ColeteOnlineProductTable.prototype.notifyValidity = function () {
    var len = $(".coleteonline-packages-table .coleteonline-invalid").length;

    var _iterator3 = _createForOfIteratorHelper(this.subscribers["validityChanges"]),
        _step3;

    try {
      for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
        var s = _step3.value;

        if (len === 0) {
          s("VALID");
        } else {
          s("INVALID");
        }
      }
    } catch (err) {
      _iterator3.e(err);
    } finally {
      _iterator3.f();
    }
  };

  $.ColeteOnlineProductTable.prototype.checkValidity = function () {
    var len = $(".coleteonline-packages-table .coleteonline-invalid").length;

    var _iterator4 = _createForOfIteratorHelper(this.subscribers["validityChanges"]),
        _step4;

    try {
      for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
        var s = _step4.value;

        if (len === 0) {
          return "VALID";
        } else {
          return "INVALID";
        }
      }
    } catch (err) {
      _iterator4.e(err);
    } finally {
      _iterator4.f();
    }
  };

  $.ColeteOnlineProductTable.prototype.addInvalidClass = function (el) {
    $(el).addClass('coleteonline-invalid');
  };

  $.ColeteOnlineProductTable.prototype.removeInvalidClass = function (el) {
    $(el).removeClass('coleteonline-invalid');
  };

  $.ColeteOnlineProductTable.prototype.reset = function () {
    this.productPackages = JSON.parse(JSON.stringify(this.initialPackages));
    this.totals = [];
  };

  $.ColeteOnlineProductTable.prototype.resetAndRender = function () {
    this.reset();
    this.render();
  };

  $.ColeteOnlineProductTable.prototype.findItem = function (uniqueId) {
    var i, j;

    mainfor: for (i = 0; i < this.productPackages.length; ++i) {
      for (j = 0; j < this.productPackages[i].length; ++j) {
        if (this.productPackages[i][j].unique === uniqueId) {
          break mainfor;
        }
      }
    }

    var found = this.productPackages[i][j];

    if (found === undefined) {
      throw "Package not found error";
    }

    return {
      i: i,
      j: j,
      found: found
    };
  };

  $.ColeteOnlineProductTable.prototype.getPackagesTotals = function () {
    return this.totals;
  };

  $.ColeteOnlineProductTable.prototype.getContent = function () {
    var items = {};

    var _iterator5 = _createForOfIteratorHelper(this.productPackages),
        _step5;

    try {
      for (_iterator5.s(); !(_step5 = _iterator5.n()).done;) {
        var pack = _step5.value;

        var _iterator6 = _createForOfIteratorHelper(pack),
            _step6;

        try {
          for (_iterator6.s(); !(_step6 = _iterator6.n()).done;) {
            var item = _step6.value;
            var n = item.name;

            if (items[n] === undefined) {
              items[n] = 0;
            }

            ++items[n];
          }
        } catch (err) {
          _iterator6.e(err);
        } finally {
          _iterator6.f();
        }
      }
    } catch (err) {
      _iterator5.e(err);
    } finally {
      _iterator5.f();
    }

    var content = [];

    for (var k in items) {
      if (items.hasOwnProperty(k)) {
        if (items[k] > 1) {
          content.push("".concat(items[k], " x ").concat(k));
        } else {
          content.push(k);
        }
      }
    }

    return content.join(", ").substr(0, 50);
  };
})(jQuery);