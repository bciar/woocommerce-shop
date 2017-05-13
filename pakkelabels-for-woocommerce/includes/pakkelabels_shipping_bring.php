<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once('pakkelabels_shipping_main.php');
/**
 * Sample instance based method.
 */
class pakkelabels_shipping_bring extends Pakkelabels_Shipping_Main
{

    /**
     * Constructor. The instance ID is passed to this.
     */
    public function __construct( $instance_id = 0 ) 
    {
        $this->id                    = 'pakkelabels_shipping_bring';
        $this->instance_id 			 = absint( $instance_id );
        $this->method_title = __('Bring - Optional pickup location', 'woocommerce-pakkelabels');
        $this->method_description = __('Adds the option to ship with Bring - Optional pickup location to the checkout', 'woocommerce-pakkelabels');
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
        add_action('woocommerce_after_shipping_rate', array($this, 'check_choosen_shipping_method') );
    }


    function addFilters()
    {

    }





    /* Register the shipping method in WooCommerce*/
    function register_shipping_method( $methods ) {
        $methods['pakkelabels_shipping_bring'] = 'pakkelabels_shipping_bring';
        return $methods;
    }

    /* Checks the choosen shipping method, and adds */
    function check_choosen_shipping_method($rate)
    {
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
        $chosen_shipping = preg_replace('/[0-9]+/', '', $chosen_methods[0]);;
        if($chosen_shipping === 'pakkelabels_shipping_bring')
        {
            if ($rate->method_id == 'pakkelabels_shipping_bring') {
                $this->HTML_zipAndFind('bring');
            }
        }
    }
}

$pakkelabels_bring = new pakkelabels_shipping_bring();
$pakkelabels_bring->mainAddAction();
$pakkelabels_bring->addActions();
$pakkelabels_bring->addFilters();
