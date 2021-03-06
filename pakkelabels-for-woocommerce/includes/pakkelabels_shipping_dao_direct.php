<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once('pakkelabels_shipping_main.php');
/**
 * Sample instance based method.
 */
class Pakkelabels_Shipping_DAO_Direct extends Pakkelabels_Shipping_Main
{

    /**
     * Constructor. The instance ID is passed to this.
     */
    public function __construct($instance_id = 0)
    {
        $this->id                 = 'pakkelabels_shipping_dao_direct';
        $this->instance_id        = absint($instance_id);
        $this->method_title       = __('DAO Direct', 'woocommerce-pakkelabels');
        $this->method_description = __('Adds the option to ship with the DAO direct in the checkout', 'woocommerce-pakkelabels');
        $this->supports = array(
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

        add_action('woocommerce_checkout_update_order_meta', array($this, 'pakkelabels_shipping_checkout_update_order_meta_dao_method_direct'));
    }


    function addFilters()
    {

    }


    function pakkelabels_shipping_checkout_update_order_meta_dao_method_direct($order_id)
    {
        global $woocommerce;
        $choosen_shipping_method1 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods[0] );
        $choosen_shipping_method2 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods );
        if ($choosen_shipping_method1 == 'pakkelabels_shipping_dao_direct' || $choosen_shipping_method2 == 'pakkelabels_shipping_dao_direct') {
            if ($_POST['billing_address_1'] && !$_POST['shipping_address_1']) {
                update_post_meta($order_id, '_shipping_address_1', $_POST['billing_address_1']);
                update_post_meta($order_id, '_shipping_city', $_POST["billing_city"]);
                update_post_meta($order_id, '_shipping_postcode', $_POST["billing_postcode"]);
            } elseif ($_POST['shipping_address_1']) {
                update_post_meta($order_id, '_shipping_address_1', $_POST['shipping_address_1']);
                update_post_meta($order_id, '_shipping_city', $_POST["shipping_city"]);
                update_post_meta($order_id, '_shipping_postcode', $_POST["shipping_postcode"]);
            }
            add_post_meta($order_id, 'DAO Direct', 'yes', true);
        }
    }

    /* Register the shipping method in WooCommerce*/
    function register_shipping_method($methods)
    {
        $methods['pakkelabels_shipping_dao_direct'] = 'Pakkelabels_Shipping_DAO_Direct';
        return $methods;
    }
}


$Pakkelabels_DAO_Direct = new Pakkelabels_Shipping_DAO_Direct();
$Pakkelabels_DAO_Direct->mainAddAction();
$Pakkelabels_DAO_Direct->addActions();
$Pakkelabels_DAO_Direct->addFilters();