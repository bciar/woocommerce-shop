<?php
/*
Plugin Name: Woocommerce - Infinite Scrolling Pro  
Plugin URI: http://phoeniixx.com/
Description: There is a tendency to scroll down till one reaches the end of a web page. Infinite Scrolling Plugin uses this insight. It allows the user to scroll down as much as he/she wants, on a category page of an ecommerce site. 
Author: Phoeniixx Team
Author URI: http://phoeniixx.com/
Version: 1.3

*/
ob_start();

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{
	
	include "backend_settings.php";
	
	add_action('wp_head', 'infinite_scroll_header_function');
	
	function infinite_scroll_header_function()
	{
	  
		if( get_option('scrolling_status') == 'on' && ( is_shop() || is_product_category() ) ) 
		{
		
			update_option('posts_per_page',12,'yes');

?>

			<script type="text/javascript">   
			
			var type_of_pagination = '<?php echo get_option("type_of_pagination"); ?>' ;
			
			var loading_effect = '<?php echo get_option("loading_effect"); ?>' ;
			
			var op_lm_button_text = '<?php echo get_option("op_lm_button_text"); ?>' ;
			
			var op_lm_button_hover_color = '<?php echo get_option("op_lm_button_hover_color"); ?>' ;
			
			var op_lm_button_text_color = '<?php echo get_option("op_lm_button_text_color"); ?>' ;
			 
			var op_lm_button_hover_text_color = '<?php echo get_option("op_lm_button_hover_text_color"); ?>' ;
			
			var op_lm_button_color = '<?php echo get_option("op_lm_button_color"); ?>' ;
			
			var next_Selector = '<?php 
										$next_seltor = get_option("scroll_nextSelector");
										if(!empty($next_seltor ))
										{
											echo $next_seltor;
										}else{
											echo ".next" ;
										}  
								?>';
			
			var item_Selector = '<?php 
									$item_selctor = get_option("scroll_itemSelector");
									if(!empty($item_selctor )){
										echo $item_selctor ;
									}else{
										echo ".product" ;
									} 
								?>';

			
			var content_Selector = '<?php 
										$content_selctor = get_option("scroll_contentSelector");
										if(!empty($content_selctor )){
											echo $content_selctor; 
										}else{ 
											echo ".products" ;
										} 
									?>';	


			var image_loader = '<?php echo get_option("image_url"); ?>' ;
			
			var plugin_url = '<?php echo get_option("plugin_url"); ?>' ;
			
			</script>
			
<?php
			
			wp_enqueue_script("scroll-js",plugins_url( '' , __FILE__ ).'/assets/js/wo_infinite_scroll.js',array('jquery'),'',true);	
			
			wp_localize_script("scroll-js","infi_scrol_ajaxurl",array('ajaxurl'=> admin_url('admin-ajax.php')) );
		
			wp_enqueue_style( 'pisp_custom_css', plugins_url( '/assets/css/custom_style.css', __FILE__ ) );
		}
		?>
			<div id="img_loader" style="display:none;" >
				<img src="<?php echo plugins_url( '' , __FILE__ ).'/assets/img/loader.gif'; ?>">
			</div> 
		<?php
	}

}
else
{ 
?>
    <div class="error notice is-dismissible " id="message"><p>Please <strong>Activate</strong> WooCommerce Plugin First, to use Infinite Scrolling - Woocommerce Plugin.</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
<?php 

}  
ob_clean(); 

?>