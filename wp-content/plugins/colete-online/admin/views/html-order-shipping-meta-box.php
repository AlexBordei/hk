<?php
/**
 * Order items HTML for meta box.
 */

defined( 'ABSPATH' ) || exit;

global $wpdb;

$payment_gateway     = wc_get_payment_gateway_by_order( $order );
$line_items          = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
$discounts           = $order->get_items( 'discount' );
$line_items_fee      = $order->get_items( 'fee' );
$line_items_shipping = $order->get_items( 'shipping' );
$order_total_amount  = $order->get_total();

if ( wc_tax_enabled() ) {
	$order_taxes      = $order->get_taxes();
	$tax_classes      = WC_Tax::get_tax_classes();
	$classes_options  = wc_get_product_tax_class_options();
	$show_tax_columns = count( $order_taxes ) === 1;
}

$coleteonline_shipping_meta = $order->get_meta('_coleteonline_courier_order', false);

?>
<div id="coleteonline_order_shipping_wrapper">
  <?php if (!count($coleteonline_shipping_meta)): ?>
    <h3 class="coleteonline-address-title">
      <?php echo __("Pick up address", "coleteonline"); ?>
    </h3>
    <button type="button" class="button coleteonline-change-address">
      <?php echo __("Change pick up address", "coleteonline"); ?>
    </button>
    <div class="coleteonline-address-select-wrapper">
      <select id="coleteonline-address-select"
        class="wc-enhanced-select"
      >
      </select>
    </div>
    <?php
      $address = get_option("coleteonline_default_shipping_address_full_data");
      $addressObj = json_decode($address, true);
    ?>
    <table class="coleteonline-address-table"
      data-address-id="<?php echo $addressObj["locationId"];?>">
      <thead></thead>
      <tbody>
        <tr>
          <td colspan="2" class="coleteonline-address-short-name">
            <b><?php echo $addressObj["shortName"]; ?></b>
          </td>
        </tr>
        <tr class="title contact-title">
          <td colspan="2">
            <b><?php echo __("Contact", "coleteonline"); ?></b>
          </td>
        </tr>
        <tr>
          <td class="coleteonline-address-name">
            <?php echo $addressObj["contact"]["name"]; ?>
          </td>
          <td class="coleteonline-address-company">
            <?php
              echo isset($addressObj["contact"]["company"]) ?
              $addressObj["contact"]["company"] :
              ""
              ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="coleteonline-address-phone">
            <?php
              echo $addressObj["contact"]["phone"];
              if (isset($addressObj["contact"]["phone2"])) {
                echo "<br>";
                echo $addressObj["contact"]["phone2"];
              }
            ?>
          </td>
        </tr>
        <tr class="title address-title">
          <td colspan="2">
            <b><?php echo __("Address", "coleteonline"); ?></b>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="coleteonline-address-city-county">
            <?php echo $addressObj["address"]["city"] . ", " .
                      $addressObj["address"]["county"]; ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="coleteonline-address-street-number">
            <?php echo $addressObj["address"]["street"] . ", " .
                      $addressObj["address"]["number"]; ?>
          </td>
        </tr>
        <tr>
        <td colspan="2" class="coleteonline-address-postal-country">
            <?php echo $addressObj["address"]["postalCode"] . ", " .
                      $addressObj["address"]["countryCode"]; ?>
          </td>
        </tr>
        <tr>
        <td colspan="2" class="coleteonline-address-other-data">
            <?php
              if (isset($addressObj["address"]["building"])) {
                echo __("Building", "coleteonline") . " "
                    . $addressObj["address"]["building"];
              }
              if (isset($addressObj["address"]["entrance"])) {
                echo ", " . __("Ent. ", "coleteonline") . " "
                    . $addressObj["address"]["entrance"];
              }
              if (isset($addressObj["address"]["floor"])) {
                echo ", " . __("Floor ", "coleteonline") . " "
                    . $addressObj["address"]["floor"];
              }
              if (isset($addressObj["address"]["intercom"])) {
                echo ", " . __("Intercom ", "coleteonline") . " "
                    . $addressObj["address"]["intercom"];
              }
              if (isset($addressObj["address"]["entrance"])) {
                echo ", " . __("Ent. ", "coleteonline") . " "
                    . $addressObj["address"]["entrance"];
              }
              if (isset($addressObj["address"]["apartment"])) {
                echo ", " . __("Ap. ", "coleteonline") . " "
                    . $addressObj["address"]["apartment"];
              }
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="coleteonline-address-landmark">
            <?php
              if (isset($addressObj["address"]["landmark"])) {
                echo $addressObj["address"]["landmark"];
              }
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="coleteonline-address-additional-info">
            <?php
              if (isset($addressObj["address"]["additionalInfo"])) {
                echo $addressObj["address"]["AdditionalInfo"];
              }
            ?>
          </td>
        </tr>
      </tbody>
    </table>

    <h3 class="coleteonline-address-title">
      <?php echo __("Delivery address", "coleteonline");?>
    </h3>
    <?php
      if ( $order->get_formatted_shipping_address() ) {
        echo '<p>' . wp_kses( $order->get_formatted_shipping_address(), array( 'br' => array() ) ) . '</p>';
      } else {
        echo '<p class="none_set"><strong>' . __( 'Address:', 'woocommerce' ) . '</strong> ' . __( 'No shipping address set.', 'woocommerce' ) . '</p>';
      }
    ?>

    <input
      id="coleteonline-order-id"
      style="display: none;"
      value=<?php echo $order->get_id();?>
    >
    <h3>
      <?php echo __("Extra options", "coleteonline"); ?>
    </h3>
    <table>
      <tbody>
        <tr>
          <td>
            <label for="coleteonline-repayment-amount">
              <?php echo wc_help_tip( __( 'The amount that will be requested at delivery', 'coleteonline' ) ); ?>
              <?php esc_html_e( 'Repayment amount:', 'coleteonline' ); ?>
            </label>
          </td>
          <td>
            <input type="text" id="coleteonline-repayment-amount" name="coleteonline-repayment-amount"
            value="<?php echo $order->get_payment_method() === "cod" ? $order_total_amount : 0;?>"/>
            <?php echo $order->get_currency(); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="coleteonline-insurance-amount">
              <?php echo wc_help_tip( __( 'The amount for which the order will be insured', 'coleteonline' ) ); ?>
              <?php esc_html_e( 'Insurance amount:', 'coleteonline' ); ?>
            </label>
          </td>
          <td>
            <input type="text" id="coleteonline-insurance-amount" name="coleteonline-insurance-amount"
            value="<?php
              switch (get_option("coleteonline_order_insurance")) {
                case "no":
                  echo 0;
                  break;
                case "always":
                  echo $order_total_amount;
                  break;
                case "when_no_repayment":
                  if ($order->get_payment_method() === "cod") {
                    echo 0;
                  } else {
                    echo $order_total_amount;
                  }
                  break;
              }?>"/>
              <?php echo $order->get_currency(); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label for="coleteonline-open-package">
              <?php echo wc_help_tip( __( 'Use the extra service open at delivery', 'coleteonline' ) ); ?>
              <?php esc_html_e( 'Open package at delivery:', 'coleteonline' ); ?>
            </label>
          </td>
          <td>
            <input type="checkbox" id="coleteonline-open-package" name="coleteonline-open-package"
            <?php
              switch (get_option("coleteonline_order_open_at_delivery")) {
                case "no":
                  break;
                case "always":
                  echo "checked";
                  break;
                case "user_choice":
                  if ($order->get_meta("_coleteonline_open_package") === "on") {
                    echo "checked";
                  }
                  break;
              }?>>
          </td>
        </tr>
      </tbody>
    </table>
    <h3><?php echo __("Packages", "coleteonline"); ?></h3>
    <table class="coleteonline-packages-table">
      <thead style="text-align: center;">
        <td colspan="2">
          <?php echo __("Product", "coleteonline"); ?>
        </td>
        <td colspan="1">
          <?php echo __("Package", "coleteonline"); ?>
        </td>
        <td colspan="1">
          <?php echo __("Weight", "coleteonline"); ?>
        </td>
        <td colspan="3">
          <?php echo __("Dimensions", "coleteonline"); ?>
        </td>
        <td colspan="1">
          <?php echo __("Remove", "coleteonline"); ?>
        </td>
      </thead>
      <tbody>
        <?php
          $packages = array();
          $packaging_option = get_option("coleteonline_packaging_method");
          if ($packaging_option === "all_in_package") {
            $packages[1] = array();
            foreach ( $line_items as $item_id => $item ) {
              for ($i = 0; $i < $item->get_quantity(); ++$i) {
                $packages[1][] = $item;
              }
            }
          } else if ($packaging_option === "each_in_package") {
            $cnt = 0;
            foreach ( $line_items as $item_id => $item ) {
              for ($i = 0; $i < $item->get_quantity(); ++$i) {
                $packages[$cnt++] = array($item);
              }
            }
          }
          if (!count($packages)) {

          }
        ?>
        <?php foreach ($packages as $package => $contents): ?>
          <?php foreach ($contents as $item): ?>
            <?php
              $product = $item->get_product();
              $thumbnail = $product ?
                          apply_filters( 'woocommerce_admin_order_item_thumbnail', $product->get_image( 'thumbnail', array( 'title' => '' ), false ), $item_id, $item ) :
                          '';
              $product_weight = wc_get_weight($product->get_weight(false), "kg");
              $product_dimensions = array(
                "width" => wc_get_dimension($product->get_dimensions(false)["width"], "cm"),
                "length" => wc_get_dimension($product->get_dimensions(false)["length"], "cm"),
                "height" => wc_get_dimension($product->get_dimensions(false)["height"], "cm")
              );
            ?>
            <tr data-package="<?php echo $package;?>" class="product-row">
              <td class="coleteonline-thumb">
                <?php
                  echo '<div class="wc-order-item-thumbnail">' . wp_kses_post( $thumbnail ) . '</div>';
                ?>
              </td>
              <td class="coleteonline-product-name"><?php echo trim($item->get_name()); ?></td>
              <td class="coleteonline-package-select">
              </td>
              <td class="coleteonline-package-weight"
                data-package-weight="<?php echo $product_weight?>"
              >
                <?php echo $product_weight; ?> kg
              </td>
              <td class="coleteonline-package-dimensions width"
                data-package-width="<?php echo $product_dimensions["width"];?>"
              >
                <?php echo $product_dimensions["width"]; ?> cm
              </td>
              <td class="coleteonline-package-dimensions length"
              data-package-length="<?php echo $product_dimensions["length"];?>"
              >
                <?php echo $product_dimensions["length"]; ?> cm
              </td>
              <td class="coleteonline-package-dimensions height"
                data-package-height="<?php echo $product_dimensions["height"];?>"
              >
                <?php echo $product_dimensions["height"] ?> cm
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2">
            <button type="button" class="button coleteonline-add-package">
              <?php echo __("Add package", "coleteonline"); ?>
            </button>
          </td>
          <td colspan="4"></td>
          <td colspan="2">
            <button type="button" class="button coleteonline-reset-packages">
              <?php echo __("Reset packages", "coleteonline"); ?>
            </button>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <label for="coleteonline-order-content-input">
              <?php _e('Order content:', 'coleteonline'); ?>
            </label>
            <input type="text"
              id="coleteonline-order-content-input">
          </td>
        </tr>
      </tfoot>
    </table>

    <div id="coleteonline-show-offers-errors">
    </div>

    <?php
      foreach ( $order->get_shipping_methods() as $shipping_method ) {
        if ($shipping_method->get_method_id() === "coleteonline") {
          $meta_data = $shipping_method->get_formatted_meta_data();
        }
      }
      if (isset($meta_data)) {
        foreach ($meta_data as $meta) {
          if ($meta->key === 'service_id') {
            $selected_shipping_id = $meta->value;
          }
        }
      }
    ?>

    <h3 class="coleteonline-courier-list-title">
      <?php echo __("Available services", "coleteonline"); ?>
    </h3>

    <button type="button" class="button button-primary coleteonline-do-fetch-services-list">
      <?php _e('Show offers', 'coleteonline'); ?>
    </button>
    <div class="coleteonline-offers">
      <div class="coleteonline-offers-loading">
        <div class="coleteonline-lds-ring">
          <div></div><div></div><div></div><div></div>
        </div>
      </div>
      <table
        id="coleteonline-couriers-offers"
        <?php
          if (isset($selected_shipping_id)) {
            echo "data-selected-courier-id='{$selected_shipping_id}'";
          }
        ?>
      >
        <thead>
          <tr>
            <td></td>
            <td><?php _e("Courier", "coleteonline") ?></td>
            <td><?php _e("Service", "coleteonline") ?></td>
            <td><?php _e("Price", "coleteonline") ?></td>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    <div id="coleteonline-show-order-errors">
    </div>
    <div class="coleteonline-orders-loading">
      <div class="coleteonline-lds-ring">
        <div></div><div></div><div></div><div></div>
      </div>
    </div>
    <button type="button" class="button button-primary"
      id="coleteonline-do-create-courier-order"
    >
      <?php _e('Create courier order', 'coleteonline'); ?>
    </button>
  <?php else:
    require_once COLETE_ONLINE_ROOT .
      "/admin/views/html-order-shipping-meta-box-courier-orders.php";
  ?>
  <?php endif; ?>
</div>