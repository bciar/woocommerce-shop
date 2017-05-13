<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *
 * @class 		Pakkelabels_Shipping_Main
 * @version		0.1.0
 * @author 		Magnus Vejlø - Boostr
 */
abstract class Legacy_Pakkelabels_Shipping_Main extends WC_Shipping_Method
{

    /* Abstract Methods */
    /* All class's exentind this class, wil be forced to load these methods! */
    abstract protected function register_shipping_method($methods);
    abstract protected function addActions();
    abstract protected function addFilters();

    /* Variabels */


    /* Methods */
    function init() {
        //Load the settings API default-settings.php
        $this->form_fields                                  = include( 'method_settings/legacy-settings-default.php' );
        $this->title		  		                        = $this->get_option('title');
        $this->shipping_price 		                        = $this->get_option('shipping_price');
        $this->availability 		                        = $this->get_option('availability');
        $this->countries	 		                        = $this->get_option('countries');
        $this->tax_status                                   = $this->get_option( 'tax_status' );
        $this->enable_free_shipping                         = $this->get_option('enable_free_shipping');
        $this->free_shipping_total                          = $this->get_option('free_shipping_total');
        $this->differentiated_price_type                    = $this->get_option('differentiated_price_type');

        //This is part of the settings API. Loads settings you previously init.
        $this->init_settings();
        // Save settings in admin if you have any defined
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );


        if (!class_exists('Legacy_Pakkelabels_Shipping_Admin'))
        {
            include_once('legacy_pakkelabels_shipping_admin.php');
            $oLegacy_Pakkelabels_Shipping_Admin = new Legacy_Pakkelabels_Shipping_Admin($this);
        }
    } // function init ends

    function mainAddActions()
    {
        //ajax for the pakkeshop list
        add_action('wp_ajax_pakkelabels_get_shop_list', array($this, 'get_shop_list_callback'));
        add_action('wp_ajax_nopriv_pakkelabels_get_shop_list', array($this, 'get_shop_list_callback'));

        add_action('woocommerce_checkout_update_order_meta', array($this, 'woocommerce_checkout_update_order_meta_method_shipping_pakkelabels'));
    }

    //Updates the order with the shipping information of the choosen packetshop
    function woocommerce_checkout_update_order_meta_method_shipping_pakkelabels($order_id)
    {
        if (!empty($_POST["pakkelabels"])) {
            update_post_meta($order_id, '_shipping_first_name', $_POST['billing_first_name']);
            update_post_meta($order_id, '_shipping_last_name', $_POST['billing_last_name']);

            $shopnumber = $_POST['pakkelabels'];
            update_post_meta($order_id, '_shipping_company', $_POST["shop_name"][$shopnumber]);
            update_post_meta($order_id, '_shipping_address_1', $_POST["shop_address"][$shopnumber]);
            update_post_meta($order_id, '_shipping_address_2', $_POST["shop_ID"][$shopnumber]);
            update_post_meta($order_id, '_shipping_city', $_POST["shop_city"][$shopnumber]);
            update_post_meta($order_id, '_shipping_postcode', $_POST["shop_zip"][$shopnumber]);
            add_post_meta($order_id, __('Pakkeshop', 'woocommerce-pakkelabels'), $shopnumber, true);
        }
    }





    function get_shop_list_callback()
    {
        $method = 'GET';
        $url = 'https://service-points.pakkelabels.dk/pickup-points.json';
        $frontend_key = get_option('Pakkelabel_settings')['Pakkelabel_text_field_0'];
        $country = 'DK';
        $zipcode = $_POST['zipcode'];
        $agent = $_POST['agent'];
        $number = '10';
        $data = array(  'frontend_key' => $frontend_key,
            'agent' => $agent,
            'zipcode' => $zipcode,
            'country' => $country,
            //'address' => '',
            'number' => $number
        );
        if (!empty($zipcode) && is_numeric($zipcode) && !empty($frontend_key))
        {
            //Calls the Curl method, from the main class
            $tempShopList = (array) json_decode($this->CallPakkelabelsAPI($method, $url, $data));
			
            if (empty($tempShopList['message']) || $tempShopList['message'] == null)
            {
                if (!empty($tempShopList))
                {
                    $response['shoplist_json'] = $tempShopList;
                    $response['status'] = true;

                    //makes the map
                    ob_start();
                    ?>
                    <div id="map-canvas"></div>
                    <?php
                    $response['map'] = ob_get_clean();

                    //makes the shop list
                    ob_start();
                    ?>
                    <div class="pakkelabels-shoplist">
                        <ul class="pakkelabels-shoplist-ul">
                            <?php
                            foreach ($tempShopList as $shop)
                            {
                                ?>
                                <li data-shopid="<?php echo $shop->number; ?>" class="pakkelabels-shop-list">
                                    <div class="pakkelabels-single-shop">
                                        <div class="pakkelabels-radio-button"></div>
                                        <div class="pakkelabels-company-name"><?php echo trim($shop->company_name); ?></div>
                                        <div class="pakkelabels-Address"><?php echo trim($shop->address); ?></div>
                                        <div class="pakkelabels-Packetshop"><?php echo 'ID: ' . strtoupper($agent) .'-' . trim($shop->number); ?></div>
                                        <div class="pakkelabels-ZipAndCity"><?php echo   trim($shop->zipcode). ', <span class="zipcode">' . ucwords(mb_strtolower(trim($shop->city), 'UTF-8')) .'</span>'; ?></div>
                                    </div>
                                    <?php
                                    ?>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <script>
                        jQuery('.pakkelabels-shop-list').each(function()
                        {
                            jQuery(this).on('click', function()
                            {
                                jQuery('#shop_radio_'+jQuery(this).attr('data-shopid')).trigger('click')
                                li_addlistener_open_marker(jQuery(this));
                                //remove all the class selected, from previous li's
                                jQuery('.pakkelabels-shop-list').removeClass('selected');
                                //adds the selected class to the newly selected li
                                jQuery(this).addClass('selected');
                                //sets the checked = true on the newly selected LI
                                jQuery(this).children().children(':radio').prop('checked', true);
                            })
                            
                        })
                    </script>
                    <?php
                    $response['shoplist'] = ob_get_clean();

                    ob_start();
                    foreach ($tempShopList as $shop)
                    {
                        ?>
                        <div class="pakkelabels-hidden">
                            <input type="radio"  name="pakkelabels" value="<?php echo '' . trim($shop->number); ?>" id="<?php echo 'shop_radio_' . trim($shop->number); ?>">
                            <input name="shop_name[<?php echo trim($shop->number); ?>]" value="<?php echo trim($shop->company_name); ?>" type="hidden">
                            <input name="shop_address[<?php echo trim($shop->number); ?>]" value="<?php echo trim($shop->address); ?>" type="hidden">
                            <input name="shop_zip[<?php echo trim($shop->number); ?>]" value="<?php echo trim($shop->zipcode); ?>" type="hidden">
                            <input name="shop_city[<?php echo trim($shop->number); ?>]" value="<?php echo trim($shop->city); ?>" type="hidden">
                            <input name="shop_ID[<?php echo trim($shop->number); ?>]" value="<?php echo 'ID: ' . strtoupper($agent) .'-' . trim($shop->number); ?>" type="hidden">
                        </div>
                        <?php
                    }
                    $response['hidden_pakkelabels'] = ob_get_clean();


                } else
                {
                    $response['status'] = false;
                    $response['error'] = __('Couldnt find any pickup point close to the choosen zipcode', 'woocommerce-pakkelabels');
                }
            }
            else
            {
                $response['status'] = false;
                $response['error'] = __('Please enter a valid shipping module key', 'woocommerce-pakkelabels');
            }

        }
        else
        {
            $response['status'] = false;
            $response['error'] = __('The zipcode must be 4 numbers long, and numeric - please try again', 'woocommerce-pakkelabels');
        }
        echo json_encode($response);
        wp_die();
    }



    /* Method: POST, GET etc */
    /* Data: array("param" => "value") ==> index.php?param=value */
    function CallPakkelabelsAPI($method, $url, $data = false)
    {
        $curl = curl_init();
        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }


    /* Returns/Echo's the HTML that is used to make a zipcode textarea & the find shop button */
    function HTML_zipAndFind($shipping_type)
    {

        ob_start();
        if(is_checkout())
        {
            ?>
            <div class="pakkelabels_shipping_field-wrap">
                <div class="pakkelabels_shipping_field">
                    <div class="pakkelabels-clearfix" id="pakkelabels_shipping_button">
                        <div class="pakkelabels_stores">
                            <div>
                                <input type="text" id="Pakkelabels_zipcode_field" name="pakkelabels_zipcode" class="input"
                                       placeholder="<?php echo __('Zipcode', 'woocommerce-pakkelabels'); ?>">
                            </div>
                            <div>
                                <input disabled type="button"
                                       onClick="getShopList('<?php echo $shipping_type; ?>', jQuery('#Pakkelabels_zipcode_field').val());"
                                       id="pakkelabels_find_shop_btn" name="pakkelabel_find_shop" class="button alt"
                                       value="<?php echo __('Find nearest pickup point', 'woocommerce-pakkelabels'); ?>">
                            </div>
                        </div>
                    </div>
                    <div id="hidden_choosen_shop" type="hidden"></div>
                    <div class="pakkelabels-clearfix" id="selected_shop_wrapper">
                        <div id="pakkelabels-hidden-shop"> </div>
                        <div class="pakkelabels-clearfix" id="selected_shop_header">
                            <?php echo __('Please enter a zipcode to select a pickup point', 'woocommerce-pakkelabels'); ?>
                        </div>
                        <div class="pakkelabels-clearfix" id="selected_shop_context">

                        </div>
                    </div>
                </div>
            </div>
            <div id="shops-list"></div>
            <script>
                jQuery(document).ready(function () {

                    jQuery(document).on('keypress', function(event)                   {
                        if(jQuery('input[type="radio"][name="pakkelabels"]:checked').is(':checked')  && event.keyCode == 13 && jQuery('#pakkelabel-modal:visible').length != 0)
                        {
                            jQuery('#choose-stop-btn').trigger( "click" );
                            jQuery('#choose-stop-btn').blur();
                        }
                    });


                    jQuery('#Pakkelabels_zipcode_field').on('keypress', function(event)
                    {
                        if (event.keyCode == 13) {
                            event.preventDefault();
                            jQuery('#pakkelabels_find_shop_btn').click();
                            jQuery('#Pakkelabels_zipcode_field').blur();
                        }
                    })


                    jQuery('#pakkelabels_find_shop_btn').on('click', function()
                    {
                        jQuery(' div#order_review > table > tfoot > .shipping > td').css('position', 'relative').append('<div class="blockUI blockOverlay pakkelabels-loader"></div>');
                    })

                    //adds 3 events to the zipcode text field, that will disable the "find shop button", until a zipcode thats 4 in lentgh % numeric is choosen!
                    jQuery('#Pakkelabels_zipcode_field').on('keyup focusout input', function () {
                        if (jQuery('#Pakkelabels_zipcode_field').val().length == 4 && jQuery.isNumeric(jQuery('#Pakkelabels_zipcode_field').val())) {
                            jQuery('#pakkelabels_find_shop_btn').prop("disabled", false);
                        }
                        else {
                            jQuery('#pakkelabels_find_shop_btn').prop("disabled", true);
                        }
                    });
                });
            </script>
            <?php
        }
        else
        {
            echo '<br/><div class="shipping_pickup_cart">' . __('Pickup point is selected during checkout', 'woocommerce-pakkelabels') . '</div>';
        }
        $content = ob_get_clean();
        echo $content;
    }





    protected function evaluate_cost( $sum, $args = array() )
    {
        include_once( PAKKELABELS_PLUGIN_DIR.'/lib/php/class-wc-eval-math.php' );

        // Allow 3rd parties to process shipping cost arguments
        $args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
        $locale         = localeconv();
        $decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );

        // Remove whitespace from string
        $sum = preg_replace( '/\s+/', '', $sum );
        // Remove locale from string
        $sum = str_replace( $decimals, '.', $sum );
        // Trim invalid start/end characters
        $sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );
        // Do the math
        return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
    }


    public function calculate_shipping( $package = array() ) 
    {
        require_once(PAKKELABELS_PLUGIN_DIR.'/lib/php/field_calculator.php');
        global $woocommerce;
        if (wc_tax_enabled())
        {
            $iCart_total = $woocommerce->cart->cart_contents_total + array_sum($woocommerce->cart->taxes);
        }
        else
        {
            $iCart_total = $woocommerce->cart->cart_contents_total;
        }

        if($this->enable_free_shipping == "Yes" && $iCart_total >= $this->free_shipping_total)
        {
            $return_price = 0;
        }
        else
        {
            //Price based calculations of the shipping price
            if($this->get_option('differentiated_price_type') == "Price")
            {
                $oRows = get_option('Price_'.strtolower(static::class));
                foreach ($oRows as $oRow)
                {
                    if($oRow->minimum < $iCart_total && $iCart_total <= $oRow->maximum)
                    {
                        $return_price = (float)$oRow->shipping_price;
                    }
                }

                //if the price is not within the ranges, it will pick the highest priced range!
                if(!isset($return_price))
                {
                    $return_price = 0;
                    foreach ($oRows as $oRow)
                    {
                        if ((float)$oRow->shipping_price > $return_price)
                        {
                            $return_price = (float)$oRow->shipping_price;
                        }
                    }
                }
            }
            //Weight based calculation of shipping price
            else if ($this->get_option('differentiated_price_type') == "Weight")
            {
                $iCartWeight = $woocommerce->cart->cart_contents_weight;
                $oRows = get_option('Weight_'.strtolower(static::class));
                foreach ($oRows as $oRow)
                {
                    if($oRow->minimum < $iCartWeight && $iCartWeight <= $oRow->maximum)
                    {
                        $return_price = (float)$oRow->shipping_price;
                    }

                }
                //if the price is not within the ranges, it will pick the highest priced range!
                if(!isset($return_price))
                {
                    $return_price = 0;
                    foreach ($oRows as $oRow)
                    {
                        if ((float)$oRow->shipping_price > $return_price)
                        {
                            $return_price = (float)$oRow->shipping_price;
                        }

                    }
                }
            }
            //$this->get_option('differentiated_price_type') == "Quantity"
            else
            {
                $Cal = new Field_calculate();
                $return_price = (float)$Cal->calculate(str_replace("[qty]",WC()->cart->get_cart_contents_count(),$this->shipping_price));
            }

        }

        $return_price = $this->evaluate_cost( $return_price, array());

        $rate = array(
            'id' => $this->id,
            'label' => $this->title,
            'cost' => $return_price,
        );

        // Register the rate
        $this->add_rate( $rate );
    }
}


