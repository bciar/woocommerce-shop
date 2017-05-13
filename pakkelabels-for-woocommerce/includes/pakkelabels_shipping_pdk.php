<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once('pakkelabels_shipping_main.php');
/**
 * Sample instance based method.
 */
class Pakkelabels_Shipping_PDK extends Pakkelabels_Shipping_Main
{

    /**
     * Constructor. The instance ID is passed to this.
     */
    public function __construct( $instance_id = 0 )
    {
        $this->id                    = 'pakkelabels_shipping_pdk';
        $this->instance_id 			 = absint( $instance_id );
        $this->method_title          = __('PostNord Pickup Point', 'woocommerce-pakkelabels');
        $this->method_description    = __( 'Adds the option to ship with the PostNord pickup point to the checkout', 'woocommerce-pakkelabels');
        $this->supports              = array(
            'shipping-zones',
            'instance-settings',
        );
        $this->init();
    }

    /* add the diffrent actions */
    function addActions()
    {
        //adds the shipping method to the WooCommerce
        add_filter('woocommerce_shipping_methods', array($this, 'register_shipping_method'));
        //runs the code after the choosen shipping method
        add_action('woocommerce_after_shipping_rate', array($this, 'check_choosen_shipping_method'));
    }


    function addFilters()
    {

    }


    /* Register the shipping method in WooCommerce*/
    function register_shipping_method( $methods ) {
        $methods['pakkelabels_shipping_pdk'] = 'Pakkelabels_Shipping_pdk';
        return $methods;
    }
    
    
    /* Checks the choosen shipping method, and adds */
    function check_choosen_shipping_method($rate)
    {
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
        $chosen_shipping = preg_replace('/[0-9]+/', '', $chosen_methods[0]);;
        if($chosen_shipping === 'pakkelabels_shipping_pdk')
        {
            if ($rate->method_id == 'pakkelabels_shipping_pdk') {
                $this->HTML_zipAndFind('pdk');
            }
        }
    }
}

$pakkelabels_PDK = new Pakkelabels_Shipping_PDK();
$pakkelabels_PDK->mainAddAction();
$pakkelabels_PDK->addActions();
$pakkelabels_PDK->addFilters();

