<?php

namespace ColeteOnline;

defined( 'ABSPATH' ) || exit;

class ColeteOnlineWcOrderDataHelper {

  public function __construct($order_id, $order) {
    if (isset($order_id)) {
      $order = wc_get_order($order_id);
    }
    $this->order = $order;
  }

  public function is_colete_online_order() {
    foreach ( $this->order->get_shipping_methods() as $shipping_method ) {
      if ($shipping_method->get_method_id() === "coleteonline") {
        $meta_data = $shipping_method->get_formatted_meta_data();
      }
    }
    return isset($meta_data);
  }

  public function get_selected_service() {
    foreach ( $this->order->get_shipping_methods() as $shipping_method ) {
      if ($shipping_method->get_method_id() === "coleteonline") {
        $meta_data = $shipping_method->get_formatted_meta_data();
      }
    }
    if (isset($meta_data)) {
      foreach ($meta_data as $meta) {
        if ($meta->key === 'service_id') {
          return $meta->value;
        }
      }
    }
    return false;
  }

  public function get_packages($extended = false) {
    $packages = array();
    $packaging_option = get_option("coleteonline_packaging_method");
    $line_items = $this->order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
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

    $final_packages = array();
    $products = array();
    $stock_products = array();
    foreach ($packages as $contents) {
      $weight = 0;
      $width = 0;
      $length = 0;
      $height = 0;
      foreach ($contents as $item) {
        $product = $item->get_product();
        $weight += wc_get_weight($product->get_weight(false), "kg");
        $width = max($width, wc_get_dimension($product->get_dimensions(false)["width"], "cm"));
        $length = max($length, wc_get_dimension($product->get_dimensions(false)["length"], "cm"));
        $height = max($height, wc_get_dimension($product->get_dimensions(false)["height"], "cm"));
        $products[] = trim($item->get_name());
        $stock_products[] = trim($product->get_sku() ? $product->get_sku() : '#' + $product->get_id());
      }
      $final_packages[] = array(
        'weight' => $weight,
        'width' => $width,
        'length' => $length,
        'height' => $height
      );
    }
    $counts = array_count_values($products);
    $contents = array();
    foreach ($counts as $name => $count) {
      $multiplier = ($count > 1 ? $count . ' x ' : '');
      $contents[] =  $multiplier . $name;
    }

    $extended_info = array();
    if ($extended) {
      $counts = array_count_values($stock_products);
      $stock_contents = array();
      foreach ($counts as $name => $count) {
        $multiplier = ($count > 1 ? $count . ' x ' : '');
        $stock_contents[] =  $multiplier . $name;
      }
      $extended_info['stock_contents'] = implode(", ", $stock_contents);
    }

    return array_merge(
      $extended_info,
      array(
        "content" => implode(", ", $contents),
        "type" => 2,
        "packages" => $final_packages
      )
    );
  }

  public function get_open_at_delivery() {
    switch (get_option('coleteonline_order_open_at_delivery', 'no')) {
      case 'no':
        return false;
      case 'always':
        return true;
      case 'user_choice':
        if ($this->order->get_meta('_coleteonline_open_package') === 'on') {
          return true;
        }
    }
    return false;
  }

  public function get_repayment_amount() {
    if ($this->order->get_payment_method() === 'cod') {
      return $this->get_total();
    }
    return false;
  }

  public function get_insurance_amount() {
    switch (get_option('coleteonline_order_insurance', 'no')) {
      case 'no':
        return false;
      case "always":
        return $this->get_total();
      case "when_no_repayment":
        if ($this->order->get_payment_method() === "cod") {
          return false;
        } else {
          return $this->get_total();
        }
    }
    return false;
  }

  public function get_client_reference() {
    $reference = '';
    if (get_option('coleteonline_order_client_reference') !== '') {
      $reference = get_option('coleteonline_order_client_reference');

      $reference = str_replace(array('[orderId]'), array($this->order->get_id()), $reference);
    }

    return $reference;
  }

  public static function get_woocommerce_base_currency() {
    return get_woocommerce_currency();
  }

  public static function get_base_currency() {
    if (get_option('coleteonline_price_currency_type', 'shop_base') === 'custom') {
      return get_option('coleteonline_price_base_currency', 'RON');
    }
    return get_woocommerce_currency();
  }

  public function get_order_currency() {
    return $this->order->get_currency();
  }

  public function has_courier_order() {
    $shipping_meta = $this->order->get_meta('_coleteonline_courier_order', false);
    return count($shipping_meta) > 0;
  }

  public function get_unique_id() {
    $shipping_meta = $this->order->get_meta('_coleteonline_courier_order', false);
    $first = current($shipping_meta)->get_data();
    return $first['value']['uniqueId'];
  }

  public function fill_recipient_shipping_data($order_data,
                                               $validation_strategy = 'minimal') {
    $shipping = $this->order->get_address('shipping');

    $order_data->add_contact_data('recipient', 'name',
      $this->order->get_formatted_shipping_full_name());
    $order_data->add_contact_data('recipient', 'company', $shipping['company']);
    $order_data->add_contact_data('recipient', 'phone',
      $this->order->get_billing_phone());

    if (get_option('coleteonline_order_send_email_to_recipient', 'yes') === 'yes') {
      $order_data->add_contact_data('recipient', 'email',
        $this->order->get_billing_email());
    }

    if ($shipping["state"] === "B") {
      $shipping["state"] = $shipping["city"];
      $shipping["city"] = "Bucuresti";
    }

    $order_data->add_address_data('recipient', 'countryCode', $shipping['country']);
    $order_data->add_address_data('recipient', 'postalCode', $shipping['postcode']);
    $order_data->add_address_data('recipient', 'city', $shipping['city']);
    $order_data->add_address_data('recipient', 'countyCode', $shipping['state']);
    $order_data->add_address_data('recipient', 'street', $shipping['address_1']);
    $order_data->add_address_data('recipient', 'number', $shipping['street_number']);
    $order_data->add_address_data('recipient', 'landmark', $shipping['address_2']);
    $order_data->add_validation_strategy('recipient', $validation_strategy);

    $post = get_post($this->order->get_id());
    $order_data->add_address_data('recipient', 'additionalInfo', $post->post_excerpt);
  }

  public static function get_services_selection() {
    $selection = get_option("coleteonline_courier_selection_choice_type");
    $sort = get_option("coleteonline_courier_display_order_type");
    $service_selection = "bestPrice";
    if ($selection === "allowChoice") {
      if ($sort === "orderByPrice") {
        $service_selection = "bestPrice";
      } elseif ($sort === "orderByGrade") {
        $service_selection = "grade";
      } else {
        $service_selection = "directId";
      }
    } else if ($selection == "provided") {
      $service_selection = get_option("coleteonline_service_selection_type");
    }
    $services = json_decode(get_option("coleteonline_service_list_hidden"));
    return array(
      'service_selection' => $service_selection,
      'services' => $services,
      'sort' => $sort,
      'selection' => $selection
    );
  }

  public function fill_service_selection($order_data) {
    $result = self::get_services_selection();
    $order_data->add_service_selection($result['service_selection'], $result['services']);
  }

  public function set_final_service_selection($order_data) {
    $order_data->add_service_selection('directId',
                                       array($this->get_selected_service()));
  }

  public function set_packages($order_data) {
    $packages = $this->get_packages();
    $order_data->add_packages_content($packages['content']);
    $order_data->add_packages_data($packages['packages'], $packages['type']);
  }

  private function get_total() {
    return $this->order->get_total();
  }

}