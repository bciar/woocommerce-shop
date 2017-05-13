<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once('pakkelabels_shipping_main.php');
/**
 * Sample instance based method.
 */
class Pakkelabels_Shipping_PostNord_Private extends Pakkelabels_Shipping_Main
{

    /**
     * Constructor. The instance ID is passed to this.
     */
    public function __construct($instance_id = 0)
    {
        $this->id                 = 'pakkelabels_shipping_postnord_private';
        $this->instance_id        = absint($instance_id);
        $this->method_title       = __('PostNord Private', 'woocommerce-pakkelabels');
        $this->method_description = __('Adds the option to ship with the PostNord private in the checkout', 'woocommerce-pakkelabels');
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

        add_action('woocommerce_checkout_update_order_meta', array($this, 'pakkelabels_shipping_checkout_update_order_meta_postnord_method_private'));
    }


    function addFilters()
    {

    }


    function pakkelabels_shipping_checkout_update_order_meta_postnord_method_private($order_id)
    {
        global $woocommerce;
        $choosen_shipping_method1 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods[0] );
        $choosen_shipping_method2 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods );
        if ($choosen_shipping_method1 == 'pakkelabels_shipping_postnord_private' || $choosen_shipping_method2 == 'pakkelabels_shipping_postnord_private') {
            if ($_POST['billing_address_1'] && !$_POST['shipping_address_1']) {
                update_post_meta($order_id, '_shipping_address_1', $_POST['billing_address_1']);
                update_post_meta($order_id, '_shipping_city', $_POST["billing_city"]);
                update_post_meta($order_id, '_shipping_postcode', $_POST["billing_postcode"]);
            } elseif ($_POST['shipping_address_1']) {
                update_post_meta($order_id, '_shipping_address_1', $_POST['shipping_address_1']);
                update_post_meta($order_id, '_shipping_city', $_POST["shipping_city"]);
                update_post_meta($order_id, '_shipping_postcode', $_POST["shipping_postcode"]);
            }
            add_post_meta($order_id, 'PostNord Private', 'yes', true);
        }
    }

    /* Register the shipping method in WooCommerce*/
    function register_shipping_method($methods)
    {
        $methods['pakkelabels_shipping_postnord_private'] = 'Pakkelabels_Shipping_PostNord_Private';
        return $methods;
    }
}


$Pakkelabels_PostNord_Private = new Pakkelabels_Shipping_PostNord_Private();
$Pakkelabels_PostNord_Private->mainAddAction();
$Pakkelabels_PostNord_Private->addActions();
$Pakkelabels_PostNord_Private->addFilters();