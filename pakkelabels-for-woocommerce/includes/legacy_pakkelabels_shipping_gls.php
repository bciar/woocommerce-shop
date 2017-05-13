<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Flat Rate Shipping Method.
 *
 * This class is here for backwards commpatility for methods existing before zones existed.
 *
 * @deprecated  2.6.0
 * @version		2.4.0
 * @package		WooCommerce/Classes/Shipping
 * @author 		WooThemes
 */

require_once('legacy_pakkelabels_shipping_main.php');

class Legacy_Pakkelabels_Shipping_GLS extends Legacy_Pakkelabels_Shipping_Main
{

    public function __construct() {
        $this->id = 'legacy_pakkelabels_shipping_gls'; // Id for your shipping method. Should be unique.
        $this->method_title = __('GLS Pickup Point', 'woocommerce-pakkelabels'); // Title shown in admin
        $this->method_description = __('Adds the option to ship with the GLS pickup point to the checkout', 'woocommerce-pakkelabels'); // Description shown in admin
        $this->init();
    }

    /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */

    function addActions()
    {
        //adds the shipping method to the WooCommerce
        add_filter('woocommerce_shipping_methods', array($this, 'register_shipping_method'));
        //runs the code after the choosen shipping method
        add_action('woocommerce_after_shipping_rate', array($this, 'check_choosen_shipping_method') );
    }

    function addFilters()
    {

    }

    function register_shipping_method( $methods ) {
        $methods['legacy_pakkelabels_shipping_gls'] = 'Legacy_Pakkelabels_Shipping_GLS';
        return $methods;
    }

    function check_choosen_shipping_method($rate)
    {
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
        $chosen_shipping = preg_replace('/[0-9]+/', '', $chosen_methods[0]);;
        if($chosen_shipping === 'legacy_pakkelabels_shipping_gls')
        {
            if ($rate->method_id == 'legacy_pakkelabels_shipping_gls') {
                $this->HTML_zipAndFind('gls');
            }
        }
    }
}

$pakkelabels_LegacyGLS = new Legacy_Pakkelabels_Shipping_GLS();
$pakkelabels_LegacyGLS->mainAddActions();
$pakkelabels_LegacyGLS->addActions();
$pakkelabels_LegacyGLS->addFilters();
