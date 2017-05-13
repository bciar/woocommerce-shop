<?php
/* 
Plugin Name: Badge Management For Woocommerce Premium
Plugin URI: www.phoeniixx.com
Description: This plugin allows you to add badges to products on your ecommerce site. Badges on a product could help you highlight special offers or new features of the products. 
Version: 1.6.2
Author: phoeniixx
Author URI:www.phoeniixx.com
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

global $wpdb; 

$pre=$wpdb->prefix; 

define("PHBGPRE", $pre);

define("PHBGPLUG_PATH",plugin_dir_url( __FILE__ ));

if(in_array('woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins'))))
{
	add_action('init',phoen_custom_func);
	
	function phoen_custom_func(){
		
		 add_filter( 'woocommerce_single_product_image_html', 'phbg_single_product', 10, 2 );
	}
	
	include("template/phbg_admin_template.php");
	
	include("phbg_admin_function.php");
	
	include("phbg_front_function.php");
	
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
	add_action( 'admin_init', 'phbg_admin' );
	
	add_action('init','phbg_post_type');
	
	add_action( 'admin_menu', 'phbg_remove_meta_boxes' );
	
	add_action('admin_menu' , 'phbg_admin_menu'); 
	
	add_action( 'save_post', 'phbg_posts' );

	add_action('wp_head', 'phbg_add_jquery');
	
	add_action('admin_head', 'phbg_front_add_jquery');
	
	wp_enqueue_script('jquery-ui-datepicker');
	
	wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

	wp_enqueue_style( 'my-custom-style', PHBGPLUG_PATH.'assets/css/phbg_my_admin.css'); 
	
	$badge_val=get_option('_phoe_badge_enable');

	if(($badge_val!='2'))
	{

		add_action("add_meta_boxes", "phbg_meta_boxes");
		
		if(!is_admin())
		{
			$ur= $_SERVER['REQUEST_URI'];
			
			$ur=explode("/",$ur);
	
			if (!in_array("product", $ur)&&!in_array("cart", $ur))
				
			//this action add badge on product page
			
			add_action('woocommerce_single_product_image_html', 'phbg_single_product', 10 , 2);	
			
			add_filter('post_thumbnail_html','phbg_single_post' , 999 , 2);
			
		}

	}
	
	
	function phbg_single_post( $thumb, $post_id ) {
		
				
		return phbg_single_product( $thumb, $post_id );
		
	}

	function phbg_add_jquery()
	{
		
		wp_enqueue_style( 'wp-color-picker' ); 
		wp_enqueue_style( 'phoe_mobile_front', PHBGPLUG_PATH.'assets/css/phbg_my_front.css');
		//wp_enqueue_script( 'custom-script-handle2', PHBGPLUG_PATH.'assets/js/phbg_front.js'); 	
		?>
			<style>
			
			</style>
<?php 
				
	}

	function phbg_front_add_jquery()
	{
			
		wp_enqueue_style( 'wp-color-picker' ); 	
		
		wp_enqueue_script( 'custom-script-handle', PHBGPLUG_PATH.'assets/js/phbg_script.js?ver=4.3.1', array( 'wp-color-picker' ) ); 		
	}

	add_action('admin_head','admin_assests_file_badge');

	function admin_assests_file_badge(){

		wp_enqueue_script('jquery-dt', PHBGPLUG_PATH.'assets/js/jquery.simple-dtpicker.js');
		
		wp_enqueue_style( 'my-custom-dtstyle', PHBGPLUG_PATH.'assets/css/jquery.simple-dtpicker.css');
		
		wp_enqueue_script( 'custom-admin-handle', PHBGPLUG_PATH.'assets/js/phbg_admin_js.js?ver=4.3.1', array( 'jquery' ) ); 		
		
	}

	function phbg_admin_menu() {
		
		add_submenu_page('edit.php?post_type=phoe_badge', 'badge_setting', 'Settings', 'manage_options', 'phoe_badge_set', 'phoe_badge_set');
	}
	
	
	
	function phbg_badge_activate() {

		if( update_option('_phoe_badge_enable', '1'))
		{
			
		}
		else
		{
			add_option( '_phoe_badge_enable', '1');	
		}
		
	}

	register_activation_hook( __FILE__, 'phbg_badge_activate' );

}
else
{
	
	add_action( 'admin_notices', 'phbg_notice' );	
	 
}


?>