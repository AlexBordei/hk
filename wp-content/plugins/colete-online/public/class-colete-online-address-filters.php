<?php

defined( 'ABSPATH' ) || exit;

require_once COLETE_ONLINE_ROOT . "/lib/class-colete-online-client.php";

class ColeteOnlineAddressFilters {

  public function __construct() {}

  private function change_fields($type, $fields, $country, $county, $city) {
    // if ($country !== 'RO') {
    //   return $fields;
    // }

    $countyName = "";
    try {
      if ($country === "RO") {
        if (strlen($city) === 2) {
          // TODO move in helper function
          if ($city[0] === "S" && is_numeric($city[1]) && $county === "B") {
            $countyName = "Sectorul " . $city[1];
            $city = "Bucuresti";
          } else {
            if (isset(WC()->countries->get_states()[$country])) {
              if (isset(WC()->countries->get_states()[$country][$county])) {
                $countyName = WC()->countries->get_states()[$country][$county];
              }
            }
          }
        } else {
          if (isset(WC()->countries->get_states()[$country])) {
            if (isset(WC()->countries->get_states()[$country][$county])) {
              $countyName = WC()->countries->get_states()[$country][$county];
            }
          }
        }
      }
    } catch (Exception $e) {}

    $separate_fields = get_option("coleteonline_address_separate_fields") === "yes";
    if ($separate_fields) {
      $cityOptions = array("" => "");
      if (
        isset($city) && isset($countyName) && $city === "Bucuresti" &&
        $countyName && $country === "RO"
      ) {
        $cityOptions = array($countyName => $countyName);
      } else if (isset($city) && $city && $country === "RO") {
        $cityOptions = array($city => $city);
      }

      $fields[$type . '_locality'] = array(
        'type' => 'select',
        'type' => 'select',
        'label'     => __('City / Locality', 'coleteonline'),
        'placeholder'   => _x('City / Locality', 'placeholder', 'coleteonline'),
        'required'  => true,
        'class' => array('form-row-wide wc-enhanced-select hidden-field'),
        'priority' => 45,
        'options' => $cityOptions
      );
    } else {
      $cityStateOptions = array("" => "");
      if (
        isset($countyName) && $countyName && isset($city) && $city &&
        $country === "RO"
      ) {
        $op = "$city, $countyName";
        $cityStateOptions = array($op => $op);
      }

      $fields[$type . '_city_state'] = array(
        'type' => 'select',
        'type' => 'select',
        'label'     => __('City and county', 'coleteonline'),
        'placeholder'   => _x('City and county', 'placeholder', 'coleteonline'),
        'required'  => true,
        'class' => array('form-row-wide wc-enhanced-select hidden-field'),
        'priority' => 44,
        'options' => $cityStateOptions
      );
      if ($type === 'shipping') {
        $fields['shipping_state']['required'] = false;
      }
    }

    $fields[$type . '_street'] = array(
      'type' => 'select',
      'type' => 'select',
      'label'     => __('Street', 'coleteonline'),
      'placeholder'   => _x('Street', 'placeholder', 'coleteonline'),
      'required'  => true,
      'class' => array('form-row-wide wc-enhanced-select hidden-field'),
      'priority' => 50,
      'options' => array("" => ""),
      'autocomplete' => 'address-level1'
    );

    $fields[$type . '_street_number'] = array(
      'label'     => __('Number', 'coleteonline'),
      'maxlength' => 10,
      'placeholder'   => _x('Number', 'placeholder', 'coleteonline'),
      'required'  => true,
      'class' => array('form-row-last hidden-field'),
      'priority' => 51,
      'autocomplete' => 'none'
    );

    return $fields;
  }

  public function change_billing_fields($fields) {
    $country = WC()->customer->get_billing_country();
    $city = WC()->customer->get_billing_city();
    $county = WC()->customer->get_billing_state();

    return $this->change_fields("billing", $fields, $country, $county, $city);
  }

  public function change_shipping_fields($fields) {
    $country = WC()->customer->get_shipping_country();
    $city = WC()->customer->get_shipping_city();
    $county = WC()->customer->get_shipping_state();

    return $this->change_fields("shipping", $fields, $country, $county, $city);
  }

  public function change_country_locale($fields) {
    $fields['RO']['state']['priority'] = 44;

    if (get_option("coleteonline_address_postal_code_show") === "before") {
      $fields['RO']['postcode']['priority'] = 43;
    } else if (get_option("coleteonline_address_postal_code_show") === "no") {
      $fields['RO']['postcode']['class'][] = 'hidden-field';
    }

    return $fields;
  }

  public function localize_address_format($formats) {
    $formats['RO'] = "{name}\n{company}\n{address_1}, {street_number}\n" .
                     "{address_2}\n{city}\n{state}\n{postcode}\n{country}";
    return $formats;
  }

  public function formatted_address_replacements($replace, $args) {
    $replace['{street_number}'] = isset($args['street_number']) ?
    $args['street_number'] : '';
    $city = $args['city'];
    if (in_array($city, array('S1', 'S2', 'S3', 'S4', 'S5', 'S6'))) {
      $city = str_replace("S", "Sectorul ", $city);
    }
    $replace['{city}'] = $city;
    return $replace;
  }

  public function complete_order_address_data($fields, $type, WC_Order $order) {
    $order_id = $order->get_id();
    if ($type === 'billing') {
      $fields['street'] = get_post_meta($order_id, '_billing_street', true);
      $fields['street_number'] = get_post_meta($order_id, '_billing_street_number', true);
    } else {
      $fields['street'] = get_post_meta($order_id, '_shipping_street', true);
      $fields['street_number'] = get_post_meta($order_id, '_shipping_street_number', true);
    }
    return $fields;
  }

  public function complete_my_account_address_data($fields, $customer_id, $type) {

    if ($type === 'billing') {
      $fields['street_number'] = get_user_meta($customer_id, 'billing_street_number', true);
    } else {
      $fields['street_number'] = get_user_meta($customer_id, 'shipping_street_number', true);;
    }

    return $fields;
  }


}
