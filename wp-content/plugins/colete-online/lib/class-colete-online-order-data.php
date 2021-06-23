<?php

namespace ColeteOnline;

defined( 'ABSPATH' ) || exit;

class ColeteOnlineOrderData {
  private $order_data = array(
    'extraOptions' => array()
  );
  public function __construct() {}

  public function add_address_id($type, $location_id) {
    if ($type !== "sender" && $type !== "recipient") {
      throw "Wrong type provided";
    }
    $this->order_data[$type]["addressId"] = $this->sanitize($location_id);
  }

  public function add_contact_data($type, $field, $value) {
    if (empty($value)) {
      return;
    }
    $this->order_data[$type]["contact"][$field] = $this->sanitize($value);
  }

  public function add_address_data($type, $field, $value) {
    if (empty($value)) {
      return;
    }
    $this->order_data[$type]["address"][$field] = $this->sanitize($value);
  }

  public function add_validation_strategy($type, $validation_strategy) {
    $this->order_data[$type]['validationStrategy'] =
      $this->sanitize($validation_strategy);
  }

  public function add_packages_content($content) {
    $content = substr($content, 0, 50);
    $this->order_data['packages']['content'] = $content;
  }

  public function add_packages_data($packages, $package_type = 2) {
    $this->order_data['packages']['list'] = [];
    $this->order_data['packages']['type'] = $package_type;
    foreach ($packages as $package) {
      $this->order_data['packages']['list'][] = array(
        'weight' => $this->sanitize($package['weight']),
        'width' => $this->sanitize($package['width']),
        'length' => $this->sanitize($package['length']),
        'height' => $this->sanitize($package['height'])
      );
    }
  }

  public function add_service_selection($selection, $services) {
    $this->order_data['service'] = array(
      'selectionType' => $selection,
      'serviceIds' => $services
    );
  }

  public function add_repayment_option($amount) {
    $this->order_data['extraOptions'][] = array(
      'id' => 5,
      'amount' => $amount,
    );
  }

  public function add_insurance_option($amount) {
    $this->order_data['extraOptions'][] = array(
      'id' => 4,
      'amount' => $amount,
    );
  }

  public function add_open_at_delivery_option() {
    $this->order_data['extraOptions'][] = array(
      'id' => 2
    );
  }

  public function add_client_reference_option($reference) {
    $this->order_data['extraOptions'][] = array(
      'id' => 9,
      'clientReference' => $reference,
    );
  }

  public function add_base_currency_option($currency, $priceCurrency = NULL) {
    $this->order_data['extraOptions'][] = array(
      'id' => 10,
      'baseCurrency' => $currency,
      'priceCurrency' => isset($priceCurrency) ? $priceCurrency : $currency
    );
  }

  public function get_data() {
    return $this->order_data;
  }

  private static function sanitize($val) {
    return wc_clean(wp_unslash($val));
  }

}