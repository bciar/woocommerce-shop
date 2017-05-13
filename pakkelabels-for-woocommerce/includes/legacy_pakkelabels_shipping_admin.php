<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 *
 * @class 		boostr_shipping_admin
 * @version		0.1.0
 * @author 		Magnus Vejlø - Boostr
 */
class Legacy_Pakkelabels_Shipping_Admin
{
    function __construct()
    {
        $this->init();
    }

    function init()
    {

        $this->addActions();
        $this->addFilters();
    }

    function addActions()
    {
        global  $woocommerce;

        //Save the shipping range data, add hook for AJAX call
        //add_action('wp_ajax_pakkelabels_save_price_ranges', array($this, 'save_price_ranges_callback'));
        //add_action('wp_ajax_nopriv_pakkelabels_save_price_ranges', array($this, 'save_price_ranges_callback'));

        //Get the shipping range data, add hook for AJAX call
        add_action('wp_ajax_pakkelabels_get_price_ranges', array($this, 'get_price_ranges_callback'));
        add_action('wp_ajax_nopriv_pakkelabels_get_price_ranges', array($this, 'get_price_ranges_callback'));


        $aAdminParams = array(
            'ajax_url'                              => admin_url('admin-ajax.php'),
            'sWeightTranslation'                    => __('Weight', 'woocommerce-pakkelabels'),
            'sPriceTranslation'                     => __('Price', 'woocommerce-pakkelabels'),
            'sQuantityTranslation'                  => __('Quantity', 'woocommerce-pakkelabels'),
            'sTitleTranslation'                     => __('Title for Pakkelabels', 'woocommerce-pakkelabels'),
            'sMinimumTranslation'                   => __('Minimum cart total', 'woocommerce-pakkelabels'),
            'sMaximumTranslation'                   => __('Maximum cart total', 'woocommerce-pakkelabels'),
            'sShippingPriceTranslation'             => __('Shipping Price', 'woocommerce-pakkelabels'),
            'sBtnAddNewPriceRangeRowTranslation'    => __('Add row', 'woocommerce-pakkelabels'),
            'sCartTotalTranslation'                 => __('Cart Total', 'woocommerce-pakkelabels'),
            'sCurrencySymbol'                       => get_woocommerce_currency(),
            'sWeightUnit'                           => get_option('woocommerce_weight_unit'),
            'sShippingPriceTranslation'             => __('Shipping Price', 'woocommerce-pakkelabels'),
            'sShippingRangeHelperTextTranslation'   => __('In the price table below, you can choose to setup different shipping prices, that will be based on the carts total of your choosen type.<br/>If the cart total falls outside of any of the choosen ranges, the shipping price will default to the highest shipping price.<br/>Please make sure to follow the woocommerce standard, and use a period (.) as a decimal seperator.', 'woocommerce-pakkelabels'),
        );

        wp_enqueue_style('pakkelabels-admin-shipping-settings.css', PAKKELABELS_PLUGIN_URL . '/css/pakkelabels-admin-shipping-settings.css', array(), filemtime(PAKKELABELS_PLUGIN_DIR . '/css/pakkelabels-admin-shipping-settings.css'));
        wp_enqueue_script('legacy-pakkelabels-admin-shipping-settings.js', PAKKELABELS_PLUGIN_URL . '/js/legacy-pakkelabels-admin-shipping-settings.js', array('jquery'), filemtime(PAKKELABELS_PLUGIN_DIR . '/js/legacy-pakkelabels-admin-shipping-settings.js'));
        wp_localize_script('legacy-pakkelabels-admin-shipping-settings.js', 'PakkelabelsAdminParams', $aAdminParams);
        add_action('woocommerce_update_options', array($this, "update_range_prices"));
    }








    function addFilters()
    {

    }

    function update_range_prices()
    {
        $aPostKeys = array_keys($_POST);
        $oShippingData = json_decode(stripslashes_deep($_POST[$aPostKeys[0]]));
        if(isset($oShippingData->sShippingSection))
        {
            $sShippingSection = $oShippingData->sShippingSection;
            $sRangeType = $oShippingData->sRangeType;
            $oShippingRangeRow = json_decode($oShippingData->oShippingRows)->oRows;

            update_option($sRangeType . '_' . $sShippingSection, $oShippingRangeRow);


        }
    }


    function get_price_ranges_callback()
    {
        $sShippingSection = $_POST['sShippingSection'];
        $sRangeType = $_POST['sRangeType'];


        $response['oData'] = get_option($sRangeType . '_' . $sShippingSection);
        $response['status'] = "success";
        echo json_encode($response);
        wp_die();
    }
}