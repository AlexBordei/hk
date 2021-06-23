<?php

namespace ColeteOnline;

defined( 'ABSPATH' ) || exit;

class ShippingMethodOptions {

  public static function get_options($section_name) {
    if ($section_name == "service_selection") {
      $settings =
        array(
          array(
            'title' => __('Service selection', 'coleteonline' ),
            'desc'  => __('The options to choose how the shipping method is displayed to the user',
                          'coleteonline'),
            'type'  => 'title',
            'id'    => 'service_selection_options',
          ),
          array(
            'title'    => __('Testing mode', 'coleteonline'),
            'desc'     => __('Use the testing server to create orders.',
                            'coleteonline'),
            'id'       => 'coleteonline_courier_testing',
            'type'     => 'select',
            'default'  => 'no',
            'class'    => 'wc-enhanced-select',
            'css'      => 'min-width:300px;',
            'options' => array(
              'no' => __('No', 'coleteonline'),
              'yes' => __('Yes', 'coleteonline')
            )
          ),
          array(
            'title'    => __('Selection type', 'coleteonline'),
            'desc'     => __('Provide a shipping method or allow the user to choose',
                            'coleteonline'),
            'id'       => 'coleteonline_courier_selection_choice_type',
            'type'     => 'select',
            'default'  => '',
            'class'    => 'wc-enhanced-select',
            'css'      => 'min-width:300px;',
            'options' => array(
              'allowChoice' => __('Allow user choice', 'coleteonline'),
              'provided' => __('Provide a shipping method', 'coleteonline')
            )
          ),
          array(
            'title'    => __('Display order', 'coleteonline'),
            'desc'     => __('Configure the order in which the services are displayed to the client',
                            'coleteonline'),
            'id'       => 'coleteonline_courier_display_order_type',
            'type'     => 'select',
            'default'  => '',
            'class'    => 'wc-enhanced-select',
            'css'      => 'min-width:300px;',
            'options' => array(
              'orderByPrice' => __('Order by price', "coleteonline"),
              'orderByGrade' => __('Order by grade', "coleteonline"),
              'orderByName' => __('Order by name', "coleteonline"),
              'orderByServiceTable' => __('Use the order in the services table below', "coleteonline"),
            )
          ),
          array(
            'title'    => __('Service selection', 'coleteonline'),
            'desc'     => __('Configure how the services are displayed',
                            'coleteonline'),
            'id'       => 'coleteonline_service_selection_type',
            'type'     => 'select',
            'default'  => '',
            'class'    => 'wc-enhanced-select',
            'css'      => 'min-width:300px;',
            'options' => array(
              'directId' => __('Direct select', "coleteonline"),
              'bestPrice' => __('Best price', "coleteonline"),
              'grade' => __('Grade', "coleteonline")
            )
          ),
          array(
            'title'    => __('Display only the first services', 'coleteonline'),
            'desc'     => __('Indicate how many courier services should be presented to the user. For example if the previous column has "bestPrice" and here the input is 2, only the first 2 cheapest couriers will be shown. Leave 0 or empty for all',
                            'coleteonline'),
            'id'       => 'coleteonline_service_selection_display_count',
            'type'     => 'text',
            'default'  => '',
            'class'    => 'wc-enhanced-input',
            'css'      => 'min-width:300px;'
          ),
          array(
            'title'    => __('Show custom name for available services', 'coleteonline'),
            'desc'     => __('Enable the display of a custom name for the available shipping services',
                            'coleteonline'),
            'id'       => 'coleteonline_service_selection_custom_name_toggle',
            'type'     => 'checkbox',
            'default'  => '',
            'class'    => 'wc-enhanced-input',
            'css'      => 'min-width:300px;'
          ),
          array(
            'title'    => __('Custom display name', 'coleteonline'),
            'desc'     => __('For the first matched service display a custom name. [courierName] and [serviceName] can be used and it will be replaced with the corresponding values',
                            'coleteonline'),
            'id'       => 'coleteonline_service_selection_custom_name',
            'type'     => 'text',
            'default'  => '',
            'class'    => 'wc-enhanced-input',
            'css'      => 'min-width:300px;'
          ),
          array(
            'title'    => __('Show custom name for first shown service', 'coleteonline'),
            'desc'     => __('Enable the display of a custom name for the first matched service',
                            'coleteonline'),
            'id'       => 'coleteonline_service_selection_first_custom_name_toggle',
            'type'     => 'checkbox',
            'default'  => '',
            'class'    => 'wc-enhanced-input',
            'css'      => 'min-width:300px;'
          ),
          array(
            'title'    => __('Custom display name', 'coleteonline'),
            'desc'     => __('For the first matched service display a custom name. [courierName] and [serviceName] can be used and it will be replaced with the corresponding values',
                            'coleteonline'),
            'id'       => 'coleteonline_service_selection_custom_name_first',
            'type'     => 'text',
            'default'  => '',
            'class'    => 'wc-enhanced-input',
            'css'      => 'min-width:300px;'
          ),
          array(
            'id'       => 'coleteonline_service_list_hidden',
            'type'     => 'text',
            'css'      => 'display: none;'
          ),
          array(
            'type' => 'sectionend',
            'id'   => 'account_endpoint_options',
          ),
      );
    } else if ($section_name == "price") {

      $currency_code_options = get_woocommerce_currencies();

      foreach ( $currency_code_options as $code => $name ) {
        $currency_code_options[ $code ] = $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')';
      }
      $settings =
        array(
          array(
            'title' => __('Price settings', 'coleteonline' ),
            'desc'  => __('Configure how the price is calculated',
                          'coleteonline'),
            'type'  => 'title',
            'id'    => 'service_quotation_fallback_price',
          ),
          array(
            'title'    => __('Display price', 'coleteonline'),
            'desc'     => __('Show a fixed price or a price computed from the address',
                            'coleteonline'),
            'id'       => 'coleteonline_price_type',
            'type'     => 'select',
            'default'  => 'calculated_price',
            'class'    => 'wc-enhanced-select',
            'options'   => array(
              "fixed_price" => __("Fixed price", "coleteonline"),
              "calculated_price" => __("Calculated price", "coleteonline")
            )
          ),
          array(
            'title'    => __('Fixed price', 'coleteonline'),
            'desc'     => __('Price without tax', 'coleteonline'),
            'id'       => 'coleteonline_price_fixed_price_amount',
            'type'     => 'text',
            'default'  => '',
            'select'   => array(
              "fixed_price" => __("Fixed price", "coleteonline"),
              "calculated_price" => __("Calculated price", "coleteonline")
            )
          ),
          array(
            'title'    => __( 'Calculate in custom currency', 'coleteonline' ),
            'desc'     => __( 'This controls whether to use the shop base currency for calculation or a custom currency', 'coleteonline' ),
            'id'       => 'coleteonline_price_currency_type',
            'default'  => 'shop_base',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'options'  => array(
              'shop_base' => __('Use shop base currency', 'coleteonline'),
              'custom' => __('Use custom currency (advanced option, use with care)', 'coleteonline')
            )
          ),
          array(
            'title'    => __( 'Custom base currency', 'coleteonline' ),
            'desc'     => __( 'This controls in what currency prices should be calculated.', 'coleteonline' ),
            'id'       => 'coleteonline_price_base_currency',
            'default'  => 'RON',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'options'  => $currency_code_options,
          ),
          array(
            'title'    => __('Display a fallback price', 'coleteonline'),
            'desc'     => __('Display a fallback price, if a quote cannot be calculated',
                            'coleteonline'),
            'id'       => 'coleteonline_display_fallback_price',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'no',
            'options'  => array(
              'no' => __('Do not display a fallback price', 'coleteonline'),
              'critical' => __('Display a fallback price if there are issues', 'coleteonline'),
              'yes' => __('Display a fallback always (even if wrong address)', 'coleteonline'),
            )
          ),
          array(
            'title'    => __('Fallback price', 'coleteonline'),
            'desc'     => __('Price to display if a quotation cannot be calculated (without tax)',
                            'coleteonline'),
            'id'       => 'coleteonline_fallback_price_amount',
            'type'     => 'text',
            'default'  => '',
            'class'    => 'wc-enhanced-input',
            'css'      => 'min-width:300px;'
          ),
          array(
            'title'    => __('Fallback service name', 'coleteonline'),
            'desc'     => __('The display name of the fallback service',
                            'coleteonline'),
            'id'       => 'coleteonline_fallback_service_name',
            'type'     => 'text',
            'default'  => 'ColeteOnline',
            'class'    => 'wc-enhanced-input',
            'css'      => 'min-width:300px;'
          ),
          array(
            'title'    => __('Add fixed amount to price', 'coleteonline'),
            'desc'     => __('Add a fixed price to shipping costs (before tax)',
                            'coleteonline'),
            'id'       => 'coleteonline_price_add_fixed_amount',
            'type'     => 'text',
            'default'  => '0'
          ),
          array(
            'title'    => __('Add percent amount to price', 'coleteonline'),
            'desc'     => __('Add a percent amount to shipping costs (0 - 100%, before tax)',
                            'coleteonline'),
            'id'       => 'coleteonline_price_add_percent_amount',
            'type'     => 'text',
            'default'  => '0'
          ),
          array(
            'title'    => __('Round the price', 'coleteonline'),
            'desc'     => __('Round the price to nearest amount (before tax). For example if price is 13.2 and "round price" is 1, the amount will be 14. If "round" price is 5, the displayed amount will be 15.',
                            'coleteonline'),
            'id'       => 'coleteonline_price_round_before_tax',
            'type'     => 'select',
            'default'  => '0',
            'class'    => 'wc-enhanced-select',
            'options'  => array(
              "0" => "0",
              "0.5" => "0.5",
              "1" => "1",
              "5" => "5",
              "10" => "10"
            )
          ),
          array(
            'title'    => __('Allow free shipping', 'coleteonline'),
            'desc'     => __('Allow free shipping and configure the conditions for it',
                            'coleteonline'),
            'id'       => 'coleteonline_price_free_shipping',
            'type'     => 'select',
            'default'  => '0',
            'class'    => 'wc-enhanced-select',
            'options'  => array(
              "0" => __("No free shipping", "coleteonline"),
              "1" => __("By amount", "coleteonline"),
              "2" => __("By shipping class", "coleteonline"),
              "3" => __("By amount or shipping class", "coleteonline")
            )
          ),
          array(
            'title'    => __('For order amount bigger than', 'coleteonline'),
            'desc'     => __('Free shipping if order price (without transport) is bigger than amount',
                            'coleteonline'),
            'id'       => 'coleteonline_price_free_shipping_min_amount',
            'type'     => 'text',
            'default'  => '0',
            'class'    => 'wc-enhanced-input',
            'show_if_checked' => 'no'
          ),
          array(
            'title'    => __('For shipping classes', 'coleteonline'),
            'desc'     => __('For products with specific shipping class',
                            'coleteonline'),
            'id'       => 'coleteonline_price_free_shipping_classes',
            'type'     => 'multiselect',
            'class'    => 'wc-enhanced-select',
            'options' => self::get_shipping_classes_options()
          ),
          array(
            'title'    => __('Append text for free shipping', 'coleteonline'),
            'desc'     => __('Text to be added after courier name for free shipping',
                            'coleteonline'),
            'id'       => 'coleteonline_price_free_shipping_after_name_text',
            'type'     => 'text'
          ),
          array(
            'type' => 'sectionend',
            'id'   => 'coleteonline_price_options',
          ),
      );
    } else if ($section_name == "packaging") {
      $settings =
        array(
          array(
            'title' => __('Packaging settings', 'coleteonline' ),
            'desc'  => __('Configure the packaging settings',
                          'coleteonline'),
            'type'  => 'title',
            'id'    => 'service_quotation_fallback_price',
          ),
          array(
            'title'    => __('Packaging method', 'coleteonline'),
            'desc'     => __('How the products will be packaged',
                            'coleteonline'),
            'id'       => 'coleteonline_packaging_method',
            'type'     => 'radio',
            'default'  => 'all_in_package',
            'options'   => array(
              "all_in_package" => __("All in a package", "coleteonline"),
              "each_in_package" => __("Each in a package", "coleteonline")
            )
          ),
          array(
            'type' => 'sectionend',
            'id'   => 'coleteonline_packaging_options',
          ),
        );
    } else if ($section_name == "order") {
      $settings =
        array(
          array(
            'title' => __('Order settings', 'coleteonline' ),
            'desc'  => __('Order specific settings',
                          'coleteonline'),
            'type'  => 'title',
            'id'    => 'service_quotation_fallback_price',
          ),
          array(
            'title'    => __('Trigger courier order on customer order', 'coleteonline'),
            'desc'     => __('Automatically create the AWB for a order when the customer completes the checkout',
                            'coleteonline'),
            'id'       => 'coleteonline_order_auto_create_order',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'no',
            'options'   => array(
              'no' => __('No', 'coleteonline'),
              'yes' => __('Yes', 'coleteonline')
            )
          ),
          array(
            'title'    => __('Trigger courier order on change from On Hold to Processing', 'coleteonline'),
            'desc'     => __('If an order has a payment method that needs manual processing, when the state is changed, automatically create the courier order',
                            'coleteonline'),
            'id'       => 'coleteonline_order_auto_create_on_status_on-hold_to_processing',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'no',
            'options'   => array(
              'no' => __('No', 'coleteonline'),
              'yes' => __('Yes', 'coleteonline')
            )
          ),
          array(
            'title'    => __('Create a custom Shipping status for orders', 'coleteonline'),
            'desc'     => __('Add a new Shipping status that orders can use.',
                            'coleteonline'),
            'id'       => 'coleteonline_order_add_custom_shipping_status',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'yes',
            'options'   => array(
              'no' => __('No', 'coleteonline'),
              'yes' => __('Yes', 'coleteonline')
            )
          ),
          array(
            'title'    => __('Change order status when AWB is created', 'coleteonline'),
            'desc'     => __('When the courier order is created, change the status to Shipping from Processing.',
                            'coleteonline'),
            'id'       => 'coleteonline_order_change_to_shipping_status',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'yes',
            'options'   => array(
              'no' => __('No', 'coleteonline'),
              'yes' => __('Yes', 'coleteonline')
            )
          ),
          array(
            'title'    => __('Default pick up location', 'coleteonline'),
            'desc'     => __('Set the default pick up location',
                            'coleteonline'),
            'id'       => 'coleteonline_default_shipping_address',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'options'   => array()
          ),
          array(
            'title'    => "",
            'desc'     => "",
            'id'       => 'coleteonline_default_shipping_address_id',
            'type'     => 'text'
          ),
          array(
            'title'    => "",
            'desc'     => "",
            'id'       => 'coleteonline_default_shipping_address_full_data',
            'type'     => 'text'
          ),
          // array(
          //   'title'    => __('Allow per product pick up locaiton', 'coleteonline'),
          //   'desc'     => __('Set different product locations for each product (if not set the default will be used)',
          //                   'coleteonline'),
          //   'id'       => 'coleteonline_price_amount_add_id',
          //   'type'     => 'checkbox',
          //   'default'  => 'no'
          // ),
          // array(
          //   'title'    => __('Calculate shipping as', 'coleteonline'),
          //   'desc'     => __('Set how to calculate the shipment price if the cart has products from multiple locaitons',
          //                   'coleteonline'),
          //   'id'       => 'coleteonline_price_amount_add_id',
          //   'type'     => 'select',
          //   'class'    => 'wc-enhanced-slect',
          //   'options'  => array(
          //     "all" => "Total of all shipments",
          //     "cheapest" => "Display the cheapest option",
          //     "most_expensive" => "Display the most expensive option"
          //   )
          // ),
          array(
            'title'    => __('Open at delivery', 'coleteonline'),
            'desc'     => __('Set open at delivery settings',
                            'coleteonline'),
            'id'       => 'coleteonline_order_open_at_delivery',
            'type'     => 'select',
            'default'  => 'calculated_price',
            'options'   => array(
              "no" => __("No", "coleteonline"),
              "always" => __("Always", "coleteonline"),
              "user_choice" => __("Let the user decide", "coleteonline")
            )
          ),
          array(
            'title'    => __('Open at delivery user text', 'coleteonline'),
            'desc'     => __('Set open at delivery user text',
                            'coleteonline'),
            'id'       => 'coleteonline_order_open_at_delivery_text',
            'type'     => 'text',
            'default'  => 'Deschidere la livrare'
          ),
          array(
            'title'    => __('Insurance', 'coleteonline'),
            'desc'     => __('Add insurance for orders',
                            'coleteonline'),
            'id'       => 'coleteonline_order_insurance',
            'type'     => 'select',
            'default'  => 'calculated_price',
            'options'   => array(
              "no" => __("No", "coleteonline"),
              "always" => __("Yes", "coleteonline"),
              "when_no_repayment" => __("Only when no repayment", "coleteonline"),
            )
          ),
          array(
            'title'    => __('Send email to recipient', 'coleteonline'),
            'desc'     => __('When the order is created send an email to the recipient with tracking information and order datails',
                            'coleteonline'),
            'id'       => 'coleteonline_order_send_email_to_recipient',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'yes',
            'options'   => array(
              'no' => __('No', 'coleteonline'),
              'yes' => __('Yes', 'coleteonline')
            )
          ),
          array(
            'title'    => __('Client order reference', 'coleteonline'),
            'desc'     => __('Add a client reference to the order. ([orderId] will be replaced with the order id)',
                            'coleteonline'),
            'id'       => 'coleteonline_order_client_reference',
            'type'     => 'text',
            'default'  => '#[orderId]'
          ),
          array(
            'title'    => __('Add custom order note', 'coleteonline'),
            'desc'     => __('Add a custom note on order creation. ([content] or [skuContent] can be used)',
                            'coleteonline'),
            'id'       => 'coleteonline_order_custom_note',
            'type'     => 'text',
            'default'  => ''
          ),
          array(
            'type' => 'sectionend',
            'id'   => 'coleteonline_order_options',
          ),
        );
    } else if ($section_name == "address_validation") {
      $settings =
        array(
          array(
            'title' => __('Address validation', 'coleteonline' ),
            'desc'  => __('Checkout fields address validation',
                          'coleteonline'),
            'type'  => 'title',
            'id'    => 'coleteonline_address_validation_title',
          ),
          array(
            'title'    => __('Use separate fields for county and city', 'coleteonline'),
            'desc'     => __('ColeteOnline uses the same field for city and county, but if you want to use separate dropdowns enable this option',
                            'coleteonline'),
            'id'       => 'coleteonline_address_separate_fields',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'no',
            'options'   => array(
              "no" =>  __("No", "coleteonline"),
              "yes" =>  __("Yes", "coleteonline")
            )
          ),
          array(
            'title'    => __('Auto select city on search close', 'coleteonline'),
            'desc'     => __('If the city select is closed, automatically select the highlighted option, otherwise the user needs to explicitly select it',
                            'coleteonline'),
            'id'       => 'coleteonline_address_auto_select_city',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'yes',
            'options'   => array(
              "no" =>  __("No", "coleteonline"),
              "yes" =>  __("Yes", "coleteonline")
            )
          ),
          array(
            'title'    => __('Auto select street on search close', 'coleteonline'),
            'desc'     => __('If the street select is closed, automatically select the highlighted option, otherwise the user needs to explicitly select it',
                            'coleteonline'),
            'id'       => 'coleteonline_address_auto_select_street',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'yes',
            'options'   => array(
              "no" =>  __("No", "coleteonline"),
              "yes" =>  __("Yes", "coleteonline")
            )
          ),
          array(
            'title'    => __('Show postal code', 'coleteonline'),
            'desc'     => __('Where to show the postal code on the cart and checkout page',
                            'coleteonline'),
            'id'       => 'coleteonline_address_postal_code_show',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'before',
            'options'   => array(
              "before" =>  __("Before address", "coleteonline"),
              "after"  => __("After address", "coleteonline"),
              "no"     => __("Don't show", "coleteonline")
            )
          ),
          array(
            'title'    => __('Validate postal code, city and county combination before submit', 'coleteonline'),
            'desc'     => __('Existing users that have the address completed might be able to place orders with an incorrect address. Using this option, ensures they enter a valid address',
                            'coleteonline'),
            'id'       => 'coleteonline_address_validate_address_checkout',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'yes',
            'options'   => array(
              "no" =>  __("Don't validate", "coleteonline"),
              "yes"  => __("Validate", "coleteonline"),
            )
          ),
          array(
            'title'    => __('Validate phone', 'coleteonline'),
            'desc'     => __('Validate the phone during the checkout process.',
                            'coleteonline'),
            'id'       => 'coleteonline_address_validate_phone',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'options'  => array(
              "yes" => __("Yes", "coleteonline"),
              "no"  => __("No", "coleteonline")
            )
          ),
          array(
            'title'    => __('Use optimized search', 'coleteonline'),
            'desc'     => __('This option optimizes the speed for checkout auto complete fields. This option is experimental and might not work on all installations. If you activate this and see any problems with the checkout fields, please disable it and contact us!',
                            'coleteonline'),
            'id'       => 'coleteonline_address_optimized_search',
            'type'     => 'select',
            'class'    => 'wc-enhanced-select',
            'default'  => 'no',
            'options'  => array(
              "yes" => __("Yes", "coleteonline"),
              "no"  => __("No", "coleteonline")
            )
          ),
          array(
            'type' => 'sectionend',
            'id'   => 'coleteonline_address_validation_options',
          ),
        );
    }

    return $settings;
  }

  private static function get_shipping_classes_options() {
    $shipping_classes = get_terms(
      array(
        'taxonomy' => 'product_shipping_class',
        'hide_empty' => false
      )
    );

    $data = array();
    foreach ($shipping_classes as $class) {
      $data[$class->term_id] = $class->name . " - " . $class->slug;
    }

    return $data;
  }

}