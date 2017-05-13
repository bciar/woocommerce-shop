<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once('legacy_pakkelabels_shipping_main.php');

class Legacy_Pakkelabels_Shipping_Bring_Business extends Legacy_Pakkelabels_Shipping_Main
{

    public function __construct($instance_id = 0)
    {
        $this->id = 'legacy_pakkelabels_shipping_Bring_business';
        $this->instance_id = absint($instance_id);
        $this->method_title = __('Bring Business ', 'woocommerce-pakkelabels');
        $this->method_description = __('Adds the option to ship with the Bring business to the checkout', 'woocommerce-pakkelabels');
        $this->init();
    }


    /* add the diffrent actions */
    function addActions()
    {
        //adds the shipping method to the WooCommerce
        add_filter('woocommerce_shipping_methods', array($this, 'register_shipping_method'));

        add_action('woocommerce_after_shipping_rate', array($this, 'pakkelabels_shipping_Bring_business_show_below_shipping'));

        add_action('woocommerce_checkout_process', array($this, 'pakkelabels_shipping_Bring_business_field_process'));
    }


    function addFilters()
    {

    }


    function pakkelabels_shipping_Bring_business_field_process() {

        global $woocommerce;
        $choosen_shipping_method1 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods[0] );
        $choosen_shipping_method2 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods );
        if((isset($_POST['ship_to_different_address']) &&  ($_POST['shipping_company'] == '' || !isset($_POST['shipping_company']))) && ($choosen_shipping_method1 == "legacy_pakkelabels_shipping_Bring_business" || $choosen_shipping_method2 == "legacy_pakkelabels_shipping_Bring_business")){
            if ( version_compare( $woocommerce->version, '2.1', '<' ) ) {
                $woocommerce->add_error(__('Please fill out the Shipping company', 'woocommerce-pakkelabels'));
            } else {
                wc_add_notice( __('Please fill out the Shipping company', 'woocommerce-pakkelabels') , 'error');
            }
        }
        if((!isset($_POST['ship_to_different_address']) && ($_POST['billing_company'] == '' || !isset($_POST['billing_company']))) && ($choosen_shipping_method1 == "legacy_pakkelabels_shipping_Bring_business" || $choosen_shipping_method2 == "legacy_pakkelabels_shipping_Bring_business")){
            if ( version_compare( $woocommerce->version, '2.1', '<' ) ) {
                $woocommerce->add_error(__('Please fill out the billing company', 'woocommerce-pakkelabels'));
            } else {
                wc_add_notice( __('Please fill out the billing company', 'woocommerce-pakkelabels') , 'error');
            }
        }
    }




    function pakkelabels_shipping_Bring_business_show_below_shipping($rate){
        global $woocommerce;

        global $woocommerce;
        $choosen_shipping_method1 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods[0] );
        $choosen_shipping_method2 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods );
        if($choosen_shipping_method1 == "legacy_pakkelabels_shipping_Bring_business" || $choosen_shipping_method2 == "legacy_pakkelabels_shipping_Bring_business"){
            if($rate->method_id == 'legacy_pakkelabels_shipping_Bring_business'){
                echo '<div class="Bring_shipping_method_text shipping_company_required">'  . __('The company name is required.', 'woocommerce-pakkelabels').'</div>';
            }
        }
    }


    /* Register the shipping method in WooCommerce*/
    function register_shipping_method($methods)
    {
        $methods['legacy_pakkelabels_shipping_Bring_business'] = 'Legacy_Pakkelabels_Shipping_Bring_Business';
        return $methods;
    }
}


$pakkelabels_Bring_Business_Legacy = new Legacy_Pakkelabels_Shipping_Bring_Business();
$pakkelabels_Bring_Business_Legacy->mainAddActions();
$pakkelabels_Bring_Business_Legacy->addActions();
$pakkelabels_Bring_Business_Legacy->addFilters();