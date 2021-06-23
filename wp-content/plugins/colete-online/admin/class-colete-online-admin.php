<?php

defined( 'ABSPATH' ) || exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.colete-online.ro
 * @since      1.0.0
 *
 * @package    Colete_Online
 * @subpackage Colete_Online/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Colete_Online
 * @subpackage Colete_Online/admin
 * @author     Colete Online <alex@colete-online.ro>
 */
class Colete_Online_Admin {


  private $plugin_name;
  private $version;

  private $admin_notice;
  private $admin_order;
  private $admin_ajax;

  public function __construct($plugin_name, $version) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

    require_once COLETE_ONLINE_ROOT . "/lib/class-colete-online-client.php";

    require_once COLETE_ONLINE_ROOT . '/admin/class-colete-online-admin-notice.php';

    $this->admin_notice = new ColeteOnlineAdminNotice();

    require_once COLETE_ONLINE_ROOT . '/admin/class-colete-online-admin-order.php';

    $this->admin_order = new ColeteOnlineAdminOrder();

    require_once COLETE_ONLINE_ROOT . '/admin/class-colete-online-admin-ajax.php';

    $this->admin_ajax = new ColeteOnlineAdminAjax();
  }

  public function get_admin_notice() {
    return $this->admin_notice;
  }

  public function get_admin_order() {
    return $this->admin_order;
  }

  public function get_admin_ajax() {
    return $this->admin_ajax;
  }

  public function add_meta_boxes() {
    require_once COLETE_ONLINE_ROOT . '/admin/class-colete-online-meta-box-order.php';

    add_meta_box(
      'coleteonline-order-shipping',
      __('ColeteOnline order shipping', 'coleteonline'),
      'ColeteOnline_Meta_Box_Order::output',
      "shop_order",
      'normal',
      'default'
    );
  }

  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles($page) {
    $suffix = '';
    if (COLETE_ONLINE_STYLE_DEBUG === false) {
      $suffix = '.min';
    }
    if ($page === "post.php") {
      wp_enqueue_style($this->plugin_name,
                        plugin_dir_url( __FILE__ ) . "css/colete-online-admin-order$suffix.css",
                        array(),
                        $this->version,
                        'all');
    } else {
      wp_enqueue_style($this->plugin_name,
                        plugin_dir_url( __FILE__ ) . "css/colete-online-admin$suffix.css",
                        array(),
                        $this->version,
                        'all');
    }

  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts($page) {
    $suffix = '';
    if (COLETE_ONLINE_SCRIPT_DEBUG === false) {
      $suffix = '.min';
    }
    if ($page === "post.php") {
      wp_enqueue_script(
        $this->plugin_name . "product-table",
        plugin_dir_url( __FILE__ ) . "js/colete-online-admin-product-table$suffix.js",
        array(),
        $this->version,
        true);
      wp_enqueue_script(
        $this->plugin_name,
        plugin_dir_url( __FILE__ ) . "js/colete-online-admin-order$suffix.js",
        array($this->plugin_name . "product-table"),
        $this->version,
        true);
    } else if ($page === 'woocommerce_page_wc-settings') {
     parse_str(wc_clean($_SERVER['QUERY_STRING']), $query);
     if (isset($query['section']) && $query['section'] === 'coleteonline') {
       wp_enqueue_script(
         $this->plugin_name,
           plugin_dir_url( __FILE__ ) . "js/colete-online-admin-settings$suffix.js",
           array( 'jquery', 'jquery-ui-sortable' ),
           $this->version,
           true );
     }
    } else if ($page === "edit.php") {
      parse_str(wc_clean($_SERVER['QUERY_STRING']), $query);
      if (isset($query['post_type']) && $query['post_type']  === 'shop_order') {
        wp_enqueue_script(
          $this->plugin_name,
            plugin_dir_url( __FILE__ ) . "js/colete-online-admin-order-list$suffix.js",
            array( 'jquery' ),
            $this->version,
            true );
      }
    }

  }

}
