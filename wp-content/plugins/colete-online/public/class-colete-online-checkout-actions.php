<?php

defined( 'ABSPATH' ) || exit;

require_once COLETE_ONLINE_ROOT . "/lib/class-colete-online-client.php";

class ColeteOnlineCheckoutActions {

  public function __construct() {}

  public function update_session($post_data) {
    $changed = false;
    $open_package = WC()->session->get('with_open_package');
    parse_str($post_data, $vars);
    WC()->session->set(
      'with_open_package',
      empty($vars['open_package']) ?
        'off' :
        wc_clean(wp_unslash($vars['open_package']))
    );

    if ($open_package !== WC()->session->get('with_open_package')) {
      $changed = true;
    }

    if ($_POST["payment_method"] !== WC()->session->get('chosen_payment_method')) {
      $changed = true;
    }

    if ($changed) {
      foreach (WC()->cart->get_shipping_packages() as $package_key => $package) {
        WC()->session->set('shipping_for_package_' . $package_key, null);
      }
    }
  }

  public function display_shipping_options() {
    if (is_checkout()) {
      $isChecked = WC()->session->get('with_open_package') === 'on' ? 'checked' : '';
      if (get_option("coleteonline_order_open_at_delivery") === "user_choice") {
      ?>
        <tr class="shipping-open-package">
          <th><strong><?php echo __('Open package', 'coleteonline') ?></strong></th>
          <td>
            <ul id="shipping_method" class="woocommerce-shipping-methods" style="list-style-type:none;">
              <li>
                <input type="checkbox" name="open_package" id="open_package" <?php echo $isChecked; ?>>
                <label for="open_package">
                  <?php echo get_option("coleteonline_order_open_at_delivery_text"); ?>
                </label>
              </li>
            </ul>
          </td>
        </tr>
    <?php
      }
    }
  }

  public function create_order_update_customer_note($order, $data) {
    try {
      $order_helper = new \ColeteOnline\ColeteOnlineWcOrderDataHelper(null, $order);
      wc_get_logger()->info("modific pe aici oare? 1");
      if ($order_helper->is_colete_online_order()) {
        $packages = $order_helper->get_packages(true);
        $note = get_option('coleteonline_order_custom_note');
        $note = str_replace(
          array('[content]', '[skuContent]'),
          array($packages['content'],
                $packages['stock_contents']),
          $note
        );
        $order->set_customer_note(
          $order->get_customer_note('view') . ' ' . $note
        );
      }
    } catch (Exception $e) {}
  }

  public function create_order_set_shipping_options($order, $data) {
    $order->add_meta_data(
      '_coleteonline_open_package',
      wc_clean(wp_unslash($_POST["open_package"])),
      true
    );
  }

}
