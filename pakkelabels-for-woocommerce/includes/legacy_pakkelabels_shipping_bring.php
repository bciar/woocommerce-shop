<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *
 * This class is here for backwards commpatility for methods existing before zones existed.
 
 */

require_once('legacy_pakkelabels_shipping_main.php');

class legacy_pakkelabels_shipping_bring extends Legacy_Pakkelabels_Shipping_Main
{

    public function __construct() {
        $this->id = 'legacy_pakkelabels_shipping_bring'; // Id for your shipping method. Should be unique.
        $this->method_title = __('Bring - Optional pickup location', 'woocommerce-pakkelabels');
        $this->method_description = __('Adds the option to ship with Bring - Optional pickup location to the checkout', 'woocommerce-pakkelabels');
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
        $methods['legacy_pakkelabels_shipping_bring'] = 'legacy_pakkelabels_shipping_bring';
        return $methods;
    }

    function check_choosen_shipping_method($rate)
    {
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
        $chosen_shipping = preg_replace('/[0-9]+/', '', $chosen_methods[0]);;
        if($chosen_shipping === 'legacy_pakkelabels_shipping_bring')
        {
            if ($rate->method_id == 'legacy_pakkelabels_shipping_bring') {
                $this->HTML_zipAndFind('bring');
            }
        }
    }
}

$pakkelabels_Legacybring = new legacy_pakkelabels_shipping_bring();
$pakkelabels_Legacybring->mainAddActions();
$pakkelabels_Legacybring->addActions();
$pakkelabels_Legacybring->addFilters();
