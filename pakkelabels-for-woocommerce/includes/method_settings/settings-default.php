<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// $this->instance_form_fields = include( 'settings/settings-default.php' );

/**
 * Settings for flat rate shipping.
 */
// global $woocommerce;
$settings_default = array(
    'hidden_post_field' => array(
        'type' 			=> 'hidden',
        'class'         => 'hidden_post_field',
    ),
    'title' => array(
        'title' 		=> __( 'Method Name', 'woocommerce-pakkelabels'),
        'type' 			=> 'text',
        'description' 	=> __( 'This controls the title which customer will be presented for during checkout.', 'woocommerce-pakkelabels' ),
        'default'		=> $this->method_title,
        'desc_tip'		=> true
    ),
    'tax_status' => array(
        'title' 		=> __( 'Tax Status', 'woocommerce' ),
        'type' 			=> 'select',
        'class'         => 'wc-enhanced-select',
        'default' 		=> 'taxable',
        'options'		=> array(
            'taxable' 	=> __( 'Taxable', 'woocommerce' ),
            'none' 		=> _x( 'None', 'Tax status', 'woocommerce' )
        )
    ),
    'differentiated_price_type' => array(
        'title' 		=> __( 'Differentiated Price Type', 'woocommerce-pakkelabels'),
        'type' 			=> 'select',
        'description' 	=> __( 'Choose what the shipping price is based on', 'woocommerce-pakkelabels' ),
        'default' 		=> 'Normal',
        'class'         => 'differentiated_price_type',
        'options'		=> array(
            'Quantity' 		=> __( 'Normal', 'woocommerce-pakkelabels'),
            'Weight' 	    => __( 'Weight', 'woocommerce-pakkelabels'),
            'Price' 	    => __( 'Price', 'woocommerce-pakkelabels'),
        ),
    ),
    'shipping_price' => array(
        'title' 		=> __( 'Shipping Price', 'woocommerce-pakkelabels'),
        'type' 			=> 'text',
        'description' 	=> __( 'This controls what the customer will have to pay for this shipping method.<br/><br/> Simple math functions can also be inserted instead and will be calculated.<br/><br/>Using [qty] is a placeholder for the quantity of items in the cart.', 'woocommerce-pakkelabels' ),
        'class'         => 'shipping_price',
        'default'		=> 0,
        'desc_tip'		=> true
    ),
    'enable_free_shipping' => array(
        'title'         => __( 'Enable Free Shipping', 'woocommerce-pakkelabels'),
        'type'          => 'select',
        'class'         =>  'enable_free_shipping',
        'default'       => 'taxable',
        'options'       => array(
            'No'        => __( 'No', 'woocommerce-pakkelabels'),
            'Yes'       => __( 'Yes', 'woocommerce-pakkelabels'),
        ),
    ),
    'free_shipping_total' => array(
        'title'         => __( 'Minimum Purchase For Free Shipping', 'woocommerce-pakkelabels'),
        'type'          => 'text',
        'class'         => 'free_shipping_total',
        'description'   => __( 'This control the minimum amount the customer will have to purchase (subtotal) for to get free shipping. <br/><br/><strong>This rule will overrule any differentiated price ranges if the condition is met.</strong>', 'woocommerce-pakkelabels' ),
        'default'       => 0,
        'desc_tip'      => true
    ),
);
return $settings_default;