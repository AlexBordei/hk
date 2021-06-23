"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

(function ($) {
  'use strict';

  var _productTables$;

  function checkFieldsValidity() {
    var len = $("#coleteonline_order_shipping_wrapper .coleteonline-invalid").length;

    if (len > 0) {
      $(".coleteonline-do-fetch-services-list").prop("disabled", true);
    } else {
      $(".coleteonline-do-fetch-services-list").prop("disabled", false);
    }
  }

  var productTables = $(".coleteonline-packages-table").ColeteOnlineProductTable();
  productTables === null || productTables === void 0 ? void 0 : (_productTables$ = productTables[0]) === null || _productTables$ === void 0 ? void 0 : _productTables$.subscribe('validityChanges', function () {
    checkFieldsValidity();
  });

  function validateAmountField(el) {
    var tar = $(el.target);
    var val = +tar.val();

    if (isNaN(val) || val < 0) {
      $(tar).addClass('coleteonline-invalid');
    } else {
      $(tar).removeClass('coleteonline-invalid');
    }

    checkFieldsValidity();
  }

  $("#coleteonline-repayment-amount, #coleteonline-insurance-amount").change(validateAmountField);
  $("#coleteonline-repayment-amount, #coleteonline-insurance-amount").trigger("change");
  $(".coleteonline-change-address").click(function () {
    $(".coleteonline-address-select-wrapper").show();
    $.post(ajaxurl, {
      action: "coleteonline_get_all_addresses"
    }, function (response) {
      response = JSON.parse(response);
      var element = $("#coleteonline-address-select");
      var options = [];

      for (var i = 0; i < response.addresses.length; ++i) {
        var o = response.addresses[i];
        options.push(new Option(o.shortName + " - " + o.address, o.id, false, false));
      }

      element.append(options);

      if (response.selected) {
        element.val(response.selected);
      }

      $(element).on("select2:select", function (ev) {
        var found = response.addresses.find(function (el) {
          return +el.id === +ev.params.data.id;
        });

        if (found === undefined) {
          return;
        }

        var json = found.addressObject;
        $(".coleteonline-address-short-name").html("<b>".concat(json.shortName, "</b>"));
        $(".coleteonline-address-name").text(json.contact.name);
        $(".coleteonline-address-company").text(json.contact.company ? json.contact.company : "");
        $(".coleteonline-address-phone").html("".concat(json.contact.phone, "\n             ").concat(json.contact.phone2 ? "<br> " + json.contact.phone2 : ""));
        $(".coleteonline-address-city-county").text("".concat(json.address.city, ", ").concat(json.address.county));
        $(".coleteonline-address-street-number").text("".concat(json.address.street, ", ").concat(json.address.number));
        $(".coleteonline-address-postal-country").text("".concat(json.address.postalCode, ", ").concat(json.address.countryCode));
        var data = [json.address.building, json.address.entrance, json.address.floor, json.address.intercom, json.address.entrance, json.address.apartment];
        var stringData = data.filter(function (d) {
          return d;
        }).join(", ");
        $(".coleteonline-address-other-data").text(stringData);
        $(".coleteonline-address-landmark").text(json.address.landmark ? json.address.landmark : "");
        $(".coleteonline-address-additional-info").text(json.address.additionalInfo ? json.address.additionalInfo : "");
        $(".coleteonline-address-table").attr("data-address-id", json.locationId);
      });
    });
  });

  function coleteonlineOrderGetExtraOptions() {
    var info = {
      repaymentAmount: +$("#coleteonline-repayment-amount").val(),
      insuranceAmount: +$("#coleteonline-insurance-amount").val(),
      openAtDelivery: $("#coleteonline-open-package").is(":checked")
    };
    return info;
  }

  function handleResponseError(response, element) {
    if (response.error === "ServerError" || response.error === "Error") {
      $(element).html("<div class=\"coleteonline-notice-error\">\n          ".concat(response.message, "\n        </div>"));
    }

    if (response.error === "BadRequestError") {
      var list = "";

      if (response.validationErrors) {
        list = "<ul>";

        var _iterator = _createForOfIteratorHelper(response.validationErrors),
            _step;

        try {
          for (_iterator.s(); !(_step = _iterator.n()).done;) {
            var error = _step.value;
            list += "<li>".concat(error.field, " - ").concat(error.message, "</li>");
          }
        } catch (err) {
          _iterator.e(err);
        } finally {
          _iterator.f();
        }

        list += "</ul>";
      }

      $(element).html("<div class=\"coleteonline-notice-error\">\n          ".concat(response.message, "\n          ").concat(list, "\n        </div>"));
    }
  }

  $('#coleteonline-do-create-courier-order').prop('disabled', true);
  $("#coleteonline-couriers-offers").hide();
  $('.coleteonline-offers-loading').hide();
  $(".coleteonline-do-fetch-services-list").click(function () {
    $("#coleteonline-show-offers-errors").empty();
    $("#coleteonline-show-order-errors").empty();
    $("#coleteonline-couriers-offers").show();
    $(".coleteonline-offers-loading").show();
    $(".coleteonline-do-fetch-services-list").hide();
    $("#coleteonline-couriers-offers").find('tbody').empty();
    var fromAddressId = $(".coleteonline-address-table")[0].dataset.addressId;
    $.post(ajaxurl, {
      action: "coleteonline_get_services_list",
      data: {
        orderId: $("#coleteonline-order-id").val(),
        fromAddressId: fromAddressId,
        packages: productTables[0].getPackagesTotals(),
        extraOptions: coleteonlineOrderGetExtraOptions()
      }
    }, function (response) {
      $('.coleteonline-offers-loading').hide();
      $(".coleteonline-do-fetch-services-list").show();
      response = JSON.parse(response);

      if (response.error) {
        handleResponseError(response, "#coleteonline-show-offers-errors");
      } else {
        var _$$0$dataset;

        var selectedId = (_$$0$dataset = $("#coleteonline-couriers-offers")[0].dataset) === null || _$$0$dataset === void 0 ? void 0 : _$$0$dataset.selectedCourierId;

        var _iterator2 = _createForOfIteratorHelper(response.list),
            _step2;

        try {
          for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
            var offer = _step2.value;
            var offerHtml = "<tr class=\"".concat(+selectedId === +offer.service.id ? 'coleteonline-selected-by-client' : '', "\">\n              <td>\n                <input type=\"checkbox\"\n                  class=\"coleteonline-selected-courier\"\n                  data-courier-id=").concat(offer.service.id, "\n                  ").concat(+selectedId === +offer.service.id ? 'checked' : '', "\n                >\n              </td>\n              <td>").concat(offer.service.courierName, "</td>\n              <td>").concat(offer.service.name, "</td>\n              <td><b>").concat(offer.price.total, " ron</b><br>\n                  (").concat(offer.price.noVat, " ron + TVA)\n              </td>\n            </tr>");
            $("#coleteonline-couriers-offers").find('tbody').append(offerHtml);
          }
        } catch (err) {
          _iterator2.e(err);
        } finally {
          _iterator2.f();
        }
      }

      if ($('.coleteonline-selected-courier:checked').length) {
        $('#coleteonline-do-create-courier-order').prop('disabled', false);
      }

      $('.coleteonline-selected-courier').unbind('change');
      $('.coleteonline-selected-courier').change(function (element) {
        $('.coleteonline-selected-courier').prop('checked', false);
        $(element.target).prop('checked', true);
        $('#coleteonline-do-create-courier-order').prop('disabled', false);
      });
    });
  });

  function initLabelDownloadListener() {
    $('.coleteonline-file-download-loading').hide();
    $('.coleteonline-do-download-awb').click(function (element) {
      $('.coleteonline-file-download-loading').show();
      $('.coleteonline-do-download-awb').hide();
      $.get(ajaxurl, {
        action: "coleteonline_get_order_label",
        data: {
          uniqueId: element.target.dataset.uniqueId
        }
      }, function (response) {
        response = JSON.parse(response);
        var name = "awb_".concat(element.target.dataset.uniqueId, ".pdf");
        var len = response.data.length;
        var buffer = new ArrayBuffer(len);
        var view = new Uint8Array(buffer);
        view.set(response.data);
        var blob = new Blob([view], {
          type: "application/pdf"
        }); // IE 11

        if (window.navigator.msSaveOrOpenBlob) {
          window.navigator.msSaveOrOpenBlob(blob, name);
        } else {
          var url = window.URL.createObjectURL(blob);
          var a = document.createElement("a");
          a.href = url;
          a.download = name;
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
        }

        $('.coleteonline-file-download-loading').hide();
        $('.coleteonline-do-download-awb').show();
      });
    });
  }

  $('.coleteonline-orders-loading').hide();
  $('#coleteonline-do-create-courier-order').click(function () {
    $("#coleteonline-show-offers-errors").empty();
    $("#coleteonline-show-order-errors").empty();
    $('.coleteonline-orders-loading').show();
    $('#coleteonline-do-create-courier-order').hide();
    var fromAddressId = $(".coleteonline-address-table")[0].dataset.addressId;
    var courierId = $('.coleteonline-selected-courier:checked')[0].dataset.courierId;
    $.post(ajaxurl, {
      action: "coleteonline_create_courier_order",
      data: {
        orderId: $("#coleteonline-order-id").val(),
        fromAddressId: fromAddressId,
        packages: productTables[0].getPackagesTotals(),
        content: $("#coleteonline-order-content-input").val(),
        selectedCourierId: courierId,
        extraOptions: coleteonlineOrderGetExtraOptions()
      }
    }, function (response) {
      $('.coleteonline-orders-loading').hide();
      $("#coleteonline-do-create-courier-order").show();
      response = JSON.parse(response);

      if (response.error) {
        handleResponseError(response, "#coleteonline-show-order-errors");
      } else {
        $("#coleteonline_order_shipping_wrapper").empty();
        $("#coleteonline_order_shipping_wrapper").html(response.fragments.courierOrders);
        initLabelDownloadListener();
      }
    });
  });
  initLabelDownloadListener();
})(jQuery);