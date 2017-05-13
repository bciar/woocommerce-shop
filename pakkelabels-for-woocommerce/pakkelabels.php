<?php
/**
 * Plugin Name: Pakkelabels.dk for WooCommerce
 * Plugin URI: http://www.pakkelabels.dk/
 * Description: Bring, DAO365, GLS and PostNord Shipping for WooCommerce
 * Version: 1.1.4
 * Text Domain: woocommerce-pakkelabels
 * Author:  pakkelabels.dk
 * Author URI: http://www.pakkelabels.dk
 * Requires at least: 4.5.2
 * Tested up to: 4.7
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
{
    /***********/
    /* Defines */
    /***********/
    define('PLUGIN_BASENAME_DIRNAME', plugin_basename(dirname(__FILE__)));
    define('PAKKELABELS_PLUGIN_URL', plugins_url('', __FILE__));
    define('PAKKELABELS_PLUGIN_DIR', dirname(__FILE__));

    load_plugin_textdomain( 'woocommerce-pakkelabels', false, PLUGIN_BASENAME_DIRNAME . '/languages' );

    /*************/
    /* Variables */
    /*************/
    $oConflictingThemes = array(
                                    '1' => 'avada',
                                    '2' => 'avada_child',
    );

    /*******************/
    /* Require/Include */
    /*******************/
    include_once('settings/settings-plugin.php');


    /***********/
    /* Methods */
    /***********/
    /* Inits the diffrent filters, actions, scripts and styles */
    function pakkelabels_init()
    {
        pakkelabels_addAction();
        pakkelabels_addFilter();
        load_plugin_textdomain('woocommerce-pakkelabels', false, dirname(plugin_basename(__FILE__)) . '/');
    }


    function pakkelabels_addAction()
    {
        //loads the shipping methods on init
        add_action('init', 'pakkelabels_load_shipping_methods_init');
        //loads the pakkelabel-modal window
        add_action("wp_footer", "pakkelabels_HTML_modalWindow");
        //loads the diffrent scripts
        add_action('wp_enqueue_scripts', 'pakkelabels_enqueueScripts', 99);
        //loads the diffrent styles
        add_action('wp_enqueue_scripts', 'pakkelabels_enqueueStyles');
        //Makes sure you cant buy without selecting a packet shop
        add_action('woocommerce_checkout_process', 'pakkelabels_checkout_process_shipping');
        //Notice if frontend key is not set
        add_action('admin_notices', 'no_frontend_key_admin_notice');
        add_action('admin_notices', 'no_google_api_key_admin_notice');
        register_activation_hook( __FILE__, 'installDB' );
    }


    function installDB()
    {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $charset_collate = $wpdb->get_charset_collate();

//        $sql_type_table = "CREATE TABLE {$wpdb->prefix}pakkelabel_shipping_types (
//                iId bigint(20) NOT NULL auto_increment,
//                sType varchar(200) NOT NULL UNIQUE KEY,
//                PRIMARY KEY  (iId),
//                UNIQUE (sType)
//                ) $charset_collate;";
//        dbDelta( $sql_type_table );

        $sql_diffrentiated_table = "CREATE TABLE {$wpdb->prefix}pakkelabel_shipping_diffrentiated (
                iId bigint(20) NOT NULL auto_increment,
                iId_shipping_method bigint(20) NOT NULL,
                iMin_price_range bigint(20) NOT NULL,
                iMax_price_range bigint(20) NOT NULL,
                sType varchar(200) NOT NULL,
                iShipping_price bigint(20) NOT NULL,
                PRIMARY KEY  (iId)
                ) $charset_collate;";
        dbDelta( $sql_diffrentiated_table );

//        $sql_type_quantity = "INSERT INTO {$wpdb->prefix}pakkelabel_shipping_types (sType) VALUES ('Quantity')";
//        dbDelta( $sql_type_quantity );
//
//        $sql_type_price = "INSERT INTO {$wpdb->prefix}pakkelabel_shipping_types (sType) VALUES ('Price')";
//        dbDelta( $sql_type_price );
//
//        $sql_type_weight = "INSERT INTO {$wpdb->prefix}pakkelabel_shipping_types (sType) VALUES ('Weight')";
//        dbDelta( $sql_type_weight );

    }

    function no_frontend_key_admin_notice()
    {
        $aOptions = get_option('Pakkelabel_settings');
        $frontend_key = $aOptions['Pakkelabel_text_field_0'];
        if (empty($frontend_key) || strlen($frontend_key) < 2) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <?php $pakkelabel_admin_url = '<a href="'. admin_url() .'/admin.php?page=pakkelabel">Pakkelabels.dk Settings</a>'; ?>
                    <?php printf( esc_html__('Please go to the WooCommerce -> %s, and set a valid frontend key', 'woocommerce-pakkelabels'), $pakkelabel_admin_url); ?>
                </p>
            </div>
        <?php }
    }


    function no_google_api_key_admin_notice()
    {
        $aOptions = get_option( 'Pakkelabel_settings' );
        $google_api_key = $aOptions['Pakkelabel_google_api_key'];
        if (empty($google_api_key) || strlen($google_api_key) < 5) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <?php $pakkelabel_admin_url = '<a href="'. admin_url() .'/admin.php?page=pakkelabel">Pakkelabels.dk Settings</a>'; ?>
                    <?php printf( esc_html__('Please go to the WooCommerce -> %s, and set a valid Google Map API key', 'woocommerce-pakkelabels'), $pakkelabel_admin_url); ?>
                </p>
            </div>
        <?php }
    }



    function pakkelabels_addFilter()
    {


    }

    //Checks if a packetshop is selected, and its one of the valid shipping methods
    function pakkelabels_checkout_process_shipping()
    {
        global $woocommerce;
        $choosen_shipping_method1 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods[0] );
        $choosen_shipping_method2 = preg_replace('/\d/', '', $woocommerce->session->chosen_shipping_methods );
        //wc_add_notice(__( $choosen_shipping_method1. '  ' . $choosen_shipping_method2, 'woocommerce-pakkelabels'), 'error');
        if (
		$choosen_shipping_method1 == "pakkelabels_shipping_gls" || 
		$choosen_shipping_method2 == "pakkelabels_shipping_gls" || 
		$choosen_shipping_method1 == "pakkelabels_shipping_pdk" || 
		$choosen_shipping_method2 == "pakkelabels_shipping_pdk" || 
		$choosen_shipping_method1 == "pakkelabels_shipping_dao" || 
		$choosen_shipping_method2 == "pakkelabels_shipping_dao" ||
		$choosen_shipping_method2 == "pakkelabels_shipping_bring" ||
		$choosen_shipping_method2 == "pakkelabels_shipping_bring" ||
        $choosen_shipping_method1 == "legacy_pakkelabels_shipping_gls" || 
		$choosen_shipping_method2 == "legacy_pakkelabels_shipping_gls" || 
		$choosen_shipping_method1 == "legacy_pakkelabels_shipping_pdk" || 
		$choosen_shipping_method2 == "legacy_pakkelabels_shipping_pdk" || 
		$choosen_shipping_method1 == "legacy_pakkelabels_shipping_dao" || 
		$choosen_shipping_method2 == "legacy_pakkelabels_shipping_dao" ||
		$choosen_shipping_method2 == "legacy_pakkelabels_shipping_bring" ||
		$choosen_shipping_method2 == "legacy_pakkelabels_shipping_bring"
		)
        {
            if (empty($_POST["pakkelabels"])) {
                wc_add_notice(__('Please select a pickup point before placing your order.', 'woocommerce-pakkelabels'), 'error');
            }
        }
    }

    function check_conflicting_themes()
    {
        //Conflicting known themes
        $bConflictingTheme = 0;
        $oConflictingThemes = array(
            '0' => 'avada',
            '1' => 'avada_child',

        );
        foreach($oConflictingThemes as $theme)
        {

            if(strtolower(wp_get_theme()) == strtolower($theme))
            {
                $bConflictingTheme = 1;

            }

        }
        return $bConflictingTheme;
    }


    function pakkelabels_enqueueScripts()
    {
		//load only in checkout or cart
		if(is_checkout() || is_cart()) {
			$params = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'plugin_root' => PAKKELABELS_PLUGIN_URL,
			'selected_shop_header' =>  __('Currently choosen pickup point:', 'woocommerce-pakkelabels'),
			'error_message_zipcode' =>  __('The zipcode must be 4 numbers long, and numeric - please try again', 'woocommerce-pakkelabels'),
			'error_no_cords_found' => __('* Couldnt mark this pickup point on the map', 'woocommerce-pakkelabels'),
			 );

			wp_enqueue_script('gmaps', 'https://maps.googleapis.com/maps/api/js?key='.get_option( 'Pakkelabel_settings' )['Pakkelabel_google_api_key'], array('jquery'), null, false);
			wp_enqueue_script('pakkelabels_script', PAKKELABELS_PLUGIN_URL . '/js/pakkelabels.js', array('jquery'), filemtime(PAKKELABELS_PLUGIN_DIR . '/js/pakkelabels.js'));
			wp_localize_script('pakkelabels_script', 'PakkelabelsParams', $params);
			
			if(!check_conflicting_themes())
			{
				wp_enqueue_script('modal_script', PAKKELABELS_PLUGIN_URL . '/js/pakkelabel-modal.js', array('jquery'), filemtime(PAKKELABELS_PLUGIN_DIR . '/js/pakkelabel-modal.js'), true);
			}
		}
    }


    function pakkelabels_enqueueStyles()
    {
		//load only in checkout or cart
		if(is_checkout() || is_cart()) {
			wp_enqueue_style('woocommerce-pakkelabels', PAKKELABELS_PLUGIN_URL . '/css/pakkelabels.css', array(), filemtime(PAKKELABELS_PLUGIN_DIR . '/css/pakkelabels.css'));
			wp_enqueue_style('modal_css', PAKKELABELS_PLUGIN_URL . '/css/pakkelabel-modal.css', array(), filemtime(PAKKELABELS_PLUGIN_DIR . '/css/pakkelabel-modal.css'));
		}
    }
    

    /* Gets the current version of WooCommerce */
    function pakkelabels_get_woo_version_number() {
        // If get_plugins() isn't available, require it
        if ( ! function_exists( 'get_plugins' ) )
        {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        // Create the plugins folder and file variables
        $plugin_folder = get_plugins( '/' . 'woocommerce' );
        $plugin_file = 'woocommerce.php';

        // If the plugin version number is set, return it
        if ( isset( $plugin_folder[$plugin_file]['Version'] ) )
        {
            return $plugin_folder[$plugin_file]['Version'];

        } else {
            // Otherwise return null
            return NULL;
        }
    }
    

    /* Loads the shipping methods */
    //Refactor to use a loop with an array of methods and class if time
    function pakkelabels_load_shipping_methods_init()
    {
        $frontend_key = get_option('Pakkelabel_settings')['Pakkelabel_text_field_0'];
        $google_api_key = get_option( 'Pakkelabel_settings' )['Pakkelabel_google_api_key'];
        if(!empty($frontend_key) && strlen($frontend_key) > 2 && !empty($google_api_key) && strlen($google_api_key) > 5)
        {
            if(pakkelabels_get_woo_version_number() >= 2.6)
            {
				//GLS
                if (!class_exists('Pakkelabels_Shipping_GLS'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_gls.php');
                }
                if (!class_exists('Pakkelabels_Shipping_GLS_Business'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_gls_business.php');
                }
                if (!class_exists('Pakkelabels_Shipping_GLS_Private'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_gls_private.php');
                }

				//Postnord
                if (!class_exists('Pakkelabels_Shipping_PDK'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_pdk.php');
                }
                if (!class_exists('Pakkelabels_Shipping_PostNord_Private'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_postnord_private.php');
                }
                if (!class_exists('Pakkelabels_Shipping_PostNord_Business'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_postnord_business.php');
                }

				//DAO
                if (!class_exists('Pakkelabels_Shipping_DAO_Direct'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_dao_direct.php');
                }
                if (!class_exists('Pakkelabels_Shipping_DAO'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_dao.php');
                }
				
				//Bring
				if (!class_exists('pakkelabels_shipping_bring'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_bring.php');
                }
                if (!class_exists('pakkelabels_shipping_bring_private'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_bring_private.php');
                }
				if (!class_exists('Pakkelabels_Shipping_Bring_Business'))
                {
                    require_once(dirname(__FILE__) . '/includes/pakkelabels_shipping_bring_business.php');
                }
            }
            else
            {
				//GLS
                if (!class_exists('Legacy_Pakkelabels_Shipping_GLS'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_gls.php');
                }
                if (!class_exists('Legacy_Pakkelabels_Shipping_GLS_Business'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_gls_business.php');
                }
                if (!class_exists('Legacy_Pakkelabels_Shipping_GLS_Private'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_gls_private.php');
                }

				//Postnord
                if (!class_exists('Legacy_Pakkelabels_Shipping_PDK'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_pdk.php');
                }
                if (!class_exists('Legacy_Pakkelabels_Shipping_PostNord_Business'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_postnord_business.php');
                }
                if (!class_exists('Legacy_Pakkelabels_Shipping_PostNord_Private'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_postnord_private.php');
                }

				//DAO
                if (!class_exists('Legacy_Pakkelabels_Shipping_DAO'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_dao.php');
                }
                if (!class_exists('Legacy_Pakkelabels_Shipping_DAO_Direct'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_dao_direct.php');
                }
				
				//Bring
				if (!class_exists('legacy_pakkelabels_shipping_bring'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_bring.php');
                }
                if (!class_exists('legacy_pakkelabels_shipping_bring_private'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_bring_private.php');
                }
				if (!class_exists('Legacy_Pakkelabels_Shipping_Bring_Business'))
                {
                    require_once(dirname(__FILE__) . '/includes/legacy_pakkelabels_shipping_bring_business.php');
                }
				
            }
        }
    }


    /* Returns/Echos the HTML that is used to make make the pakkelabel-modal window */
    function pakkelabels_HTML_modalWindow()
    {
		//load only in checkout or cart
		if(is_checkout() || is_cart()) {
        ob_start();
        ?>
     
        <div class="pakkelabel-modal" id="pakkelabel-modal" tabindex="-1" role="dialog" aria-labelledby="<?php print __('packetshop window', 'woocommerce-pakkelabels'); ?>">
            <div class="pakkelabel-modal-dialog"  role="document">
                <div class="pakkelabel-modal-content">
                    <div class="pakkelabel-modal-header">
                        <h4 class="pakkelabel-modal-title" id="pakkelabel-modal-header-h4"><?php echo __('Choose pickup point', 'woocommerce-pakkelabels') ?></h4>
                        <button id="pakkelabel-modal-header-button"type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<div class="pakkelabel-open-close-button-wrap">
							<div class="pakkelabel-open-close-button pakkelabel-open-map">
								<?php echo __('Show Map', 'woocommerce-pakkelabels') ?>
							</div>
							<div class="pakkelabel-open-close-button pakkelabel-hide-map">
								<?php echo __('Hide Map', 'woocommerce-pakkelabels') ?>
							</div>
						</div>

                    </div>
                    <div class="pakkelabel-modal-body">
                        <div id="pakkelabel-map-wrapper"></div>
                        <div id="pakkelabel-list-wrapper"></div>
                    </div>
                    <div class="pakkelabel-modal-footer">
                        <button id="choose-stop-btn" type="button" class="button alt" data-dismiss="modal"><?php echo __('Save', 'woocommerce-pakkelabels') ?></button>
						<div class="powered-by-pakkelabels"><?php echo __('Powered by', 'woocommerce-pakkelabels') ?></div>
                    </div>
                </div>
            </div>
        </div>
        <script>

            jQuery('.pakkelabel-open-map').on('click', function()
            {
                jQuery('.pakkelabel-hide-map').show();
                jQuery('.pakkelabel-open-map').hide();
                jQuery('#pakkelabel-map-wrapper').show();
                google.maps.event.trigger(map, 'resize');
                map.fitBounds(bounds);
            })

            jQuery('.pakkelabel-hide-map').on('click', function()
            {
                jQuery('.pakkelabel-hide-map').hide();
                jQuery('.pakkelabel-open-map').show();
                jQuery('#pakkelabel-map-wrapper').hide();
            })


            jQuery('#pakkelabel-modal').on('shown.bs.modal', function (e)
            {
                jQuery('#pakkelabel-map-wrapper').css('position', 'relative').append('<div class="blockUI blockOverlay pakkelabels-loader"></div>');
                jQuery(' div#order_review > table > tfoot > .shipping').parent().find('.blockUI').remove();
                jQuery('.pakkelabel-modal-body').scrollTop(0);
            });
            
			jQuery('#pakkelabel-modal').on('show.bs.modal', function (e) {
			 	jQuery('body').toggleClass('pakkelabels-modal-shown');
            });
			 jQuery('#pakkelabel-modal').on('hidden.bs.modal', function (e) {
			 	jQuery('body').toggleClass('pakkelabels-modal-shown');
            });
        </script>
        <?php
        $content = ob_get_clean();
        echo $content;
		}
    }

    pakkelabels_init();
}//end plugin is active if