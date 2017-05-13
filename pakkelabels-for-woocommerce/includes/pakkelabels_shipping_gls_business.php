<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once('pakkelabels_shipping_main.php');
/**
 * Sample instance based method.
 */
class Pakkelabels_Shipping_GLS_Business extends Pakkelabels_Shipping_Main
{

    /**
     * Constructor. The instance ID is passed to this.
     */
    public function __construct($instance_id = 0)
    {
        $this->id                    = 'pakkelabels_shipping_gls_business';
        $this->instance_id           = absint($instance_id);
        $this->method_title          = __('GLS Business', 'woocommerce-pakkelabels');
        $this->method_description    = __('Adds the option to ship with the GLS business to the checkout', 'woocommerce-pakkelabels');
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

        add_action('woocommerce_after_shipping_rate', array($this, 'pakkelabels_shipping_gls_business_show_below_shipping'));

        add_action('woocommerce_checkout_process', array($this, 'pakkelabels_shipping_gls_business_field_process'));
    }


    function addFilters()
    {

    }


    function pakkelabels_shipping_gls_business_field_process()
    {
        global $woocommerce;
        $choosen_shipping_method1 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods[0] );
        $choosen_shipping_method2 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods );
        if((isset($_POST['ship_to_different_address']) &&  ($_POST['shipping_company'] == '' || !isset($_POST['shipping_company']))) && ($choosen_shipping_method1 == "pakkelabels_shipping_gls_business" || $choosen_shipping_method2 == "pakkelabels_shipping_gls_business")){
            if ( version_compare( $woocommerce->version, '2.1', '<' ) ) {
                $woocommerce->add_error(__('Please fill out the Shipping company', 'woocommerce-pakkelabels'));
            } else {
                wc_add_notice( __('Please fill out the Shipping company', 'woocommerce-pakkelabels') , 'error');
            }
        }
        if((!isset($_POST['ship_to_different_address']) && ($_POST['billing_company'] == '' || !isset($_POST['billing_company']))) && ($choosen_shipping_method1 == "pakkelabels_shipping_gls_business" || $choosen_shipping_method2 == "pakkelabels_shipping_gls_business")){
            if ( version_compare( $woocommerce->version, '2.1', '<' ) ) {
                $woocommerce->add_error(__('Please fill out the billing company', 'woocommerce-pakkelabels'));
            } else {
                wc_add_notice( __('Please fill out the billing company', 'woocommerce-pakkelabels') , 'error');
            }
        }
    }





    function pakkelabels_shipping_gls_business_show_below_shipping($rate){
        global $woocommerce;

        global $woocommerce;
        $choosen_shipping_method1 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods[0] );
        $choosen_shipping_method2 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods );
        if($choosen_shipping_method1 == "pakkelabels_shipping_gls_business" || $choosen_shipping_method2 == "pakkelabels_shipping_gls_business"){
            if($rate->method_id == 'pakkelabels_shipping_gls_business'){
                echo '<div class="gls_shipping_method_text shipping_company_required">' . __('The company name is required.', 'woocommerce-pakkelabels').'</div>';
            }
        }
    }


    /* Register the shipping method in WooCommerce*/
    function register_shipping_method($methods)
    {
        $methods['pakkelabels_shipping_gls_business'] = 'Pakkelabels_Shipping_GLS_Business';
        return $methods;
    }
}


$pakkelabels_GLS_Business = new Pakkelabels_Shipping_GLS_Business();
$pakkelabels_GLS_Business->mainAddAction();
$pakkelabels_GLS_Business->addActions();
$pakkelabels_GLS_Business->addFilters();