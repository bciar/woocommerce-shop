<?php

	/* function add_backend_menu()
	{
		
		$page_title = "infinite scrolling settings";
		
		$menu_title = "Infinite Scrolling";
		
		$capability = "manage_options";
		
		$menu_slug = "infinite_scrolling_settings";
		
		$function = "setting";
		
		$icon_url = "dashicons-media-document";
		
		$position = "30";
		
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
		
	}
	
	add_action("admin_menu","add_backend_menu"); */	
	
	function ph_infinite_add_menu()
	{
		
		$page_title='Infinite Scrolling Settings';
		
		$menu_title='Infinite Scrolling Pro';
		
		$capability='manage_options';
		
		$menu_slug='infinite_scrolling_settings';
		
		$function='infi_settings';
        
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug,$function , $icon_url, $position );
		
		add_menu_page( 'phoeniixx', __( 'Phoeniixx', 'phe' ), 'nosuchcapability', 'phoeniixx', NULL, plugin_dir_url( __FILE__ ).'assets/img/logo-wp.png', 57 );
        
		add_submenu_page( 'phoeniixx', $page_title, $menu_title, $capability, $menu_slug, $function );

	}
	
    add_action("admin_menu","ph_infinite_add_menu",99);
	 
	function infi_settings()
	{
		
		echo"<h3>Infinite Scrolling Plugin Settings </h3>";
	
		if(isset($_POST['submit']))
		{
			
			$checkbox_status = $_POST['scrolling'];
			
			$img_url = ($_POST['loader_img'])? $_POST['loader_img'] : plugins_url( '' , __FILE__ ).'/assets/img/loader.gif' ;
			
			$plugin_url = plugins_url( '' , __FILE__ );
			
			update_option("plugin_url",$plugin_url, "yes");
			
			update_option("image_url", $img_url, "yes");
						
			update_option("loading_effect",$_POST['loading_effect']);
			
			update_option("type_of_pagination",$_POST['pagination_type']);
			
			update_option("op_lm_button_text",$_POST['lm_button_text']);
			
			update_option("op_lm_button_hover_color",$_POST['lm_button_hover_color']);
			
			update_option("op_lm_button_text_color",$_POST['lm_button_text_color']);
			
			update_option("op_lm_button_hover_text_color",$_POST['lm_button_hover_text_color']);
			
			update_option("op_lm_button_color",$_POST['lm_button_color']);
			
			update_option("scroll_contentSelector", $_POST['scroll_infinite_contentSelector'], "yes");
			
			update_option("scroll_nextSelector", $_POST['scroll_infinite_nextSelector'], "yes");
			
			update_option("scroll_itemSelector", $_POST['scroll_infinite_itemSelector'], "yes");
			
			if($checkbox_status == 'on')
			{
				
				$option = "scrolling_status";
				
				$value = "on";
				
				$autoload = "yes";
				
				update_option($option, $value, $autoload);
				
				echo '<div class="updated notice is-dismissible below-h2" id="message"><p>Successfully saved. </p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
			
			}
			else
			{
				
				$option = "scrolling_status";
				
				$value = "off";
				
				$autoload = "yes";
				
				update_option($option, $value, $autoload);
				
				echo '<div class="updated notice is-dismissible below-h2" id="message"><p>Successfully saved. </p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
				
			}
			
		}
		
?>

			<div class="wrap" id="profile-page">
				<form action="" id="form7" method="post">
				<table class="form-table">
				<tbody>	
				
				<tr class="user-nickname-wrap">
					<th><label>Enable Infinite Scrolling </label></th><td><input type="checkbox" id="scrolling" name="scrolling"  <?php echo (get_option("scrolling_status") == "on")? 'checked' : ''  ?>  /></td>
				</tr>
				
				<tr class="user-nickname-wrap">
					<th><label>Types of pagination </label></th>
					<td>
						<label><input type="radio" name='pagination_type' id="infinite_scrolling" value="infinite_scrolling" <?php echo (get_option('type_of_pagination') == 'infinite_scrolling')? 'checked' : '' ?> /> Infinite Scrolling     </label>
						
						<label><input type="radio" name='pagination_type' id="load_more_button" value="load_more_button" 
						<?php echo (get_option('type_of_pagination') == 'load_more_button')? 'checked' : '' ?> />   Load More Button     </label>
						
						<label><input type="radio" name='pagination_type' id="ajax_pagination" value="ajax_pagination" 
						<?php echo (get_option('type_of_pagination') == 'ajax_pagination')? 'checked' : '' ?> />   Ajax Pagination     </label>
					</td>
				
				</tr>
				
				<tr class="user-nickname-wrap load_more_options">
					<th><label>Button text </label></th>
					<td><input type="text" class="regular-text" id="lm_button_text" name="lm_button_text" value ="<?php echo (get_option('op_lm_button_text'))? get_option('op_lm_button_text'): '' ; ?>" ><br><span class="description">For "Load more" button. </span>
					</td>
				</tr>
				
				<tr class="user-nickname-wrap load_more_options">
					<th><label>Button color </label></th>
					<td><input type="text" class="regular-text" id="lm_button_color" name="lm_button_color" value ="<?php echo (get_option('op_lm_button_color'))? get_option('op_lm_button_color'): '' ; ?>" ><br><span class="description">For "Load more" button. </span>
					</td>
				</tr>
				
				<tr class="user-nickname-wrap load_more_options">
					<th><label>Button hover color </label></th>
					<td><input type="text" class="regular-text" id="lm_button_hover_color" name="lm_button_hover_color" value ="<?php echo (get_option('op_lm_button_hover_color'))? get_option('op_lm_button_hover_color'): '' ; ?>" ><br><span class="description">For "Load more" button. </span>
					</td>
				</tr>
				
				<tr class="user-nickname-wrap load_more_options">
					<th><label>Button text color </label></th>
					<td><input type="text" class="regular-text" id="lm_button_text_color" name="lm_button_text_color" value ="<?php echo (get_option('op_lm_button_text_color'))? get_option('op_lm_button_text_color'): '' ; ?>" ><br><span class="description">For "Load more" button. </span>
					</td>
				</tr>
				
				<tr class="user-nickname-wrap load_more_options">
					<th><label>Button hover text color </label></th>
					<td><input type="text" class="regular-text" id="lm_button_hover_text_color" name="lm_button_hover_text_color" value ="<?php echo (get_option('op_lm_button_hover_text_color'))? get_option('op_lm_button_hover_text_color'): '' ; ?>" ><br><span class="description">For "Load more" button. </span>
					</td>
				</tr>
				
				<tr class="user-nickname-wrap hide_loader" >
					<th><label>Loader Image </label></th><td><input type="text" class="regular-text"  id="loader_img" name="loader_img" value ="<?php echo (get_option('image_url'))? get_option('image_url'): '' ; ?>"  />  
					<input type="button" id="img_upload_button" class="button" name="img_upload_button" value="Upload"  /></td>
				</tr> 
				
				<tr class="user-nickname-wrap">
					<th><label>Loading Effect </label></th>
					
					<td><select id="loading_effect" name="loading_effect">
					
					<option value="none" <?php echo (get_option('loading_effect') == 'none')? 'selected' : '' ?> >None </option> 
					
					<option value="zoom_in" <?php echo (get_option('loading_effect') == 'zoom_in')? 'selected' : '' ?> >Zoom in </option> 
					
					<option value="bounce_in" <?php echo (get_option('loading_effect') == 'bounce_in')? 'selected' : '' ?> >Bounce in </option>
					
					<option value="fade_in" <?php echo (get_option('loading_effect') == 'fade_in')? 'selected' : '' ?>  >Fade in </option>
					
					<option  value="fade_in_from_top_to_down" <?php echo (get_option('loading_effect') == 'fade_in_from_top_to_down')? 'selected' : '' ?>  >Fade in from top to down </option>
					
					<option value="fade_in_from_down_to_top" <?php echo (get_option('loading_effect') == 'fade_in_from_down_to_top')? 'selected' : '' ?>  >Fade in from down to top </option>
					
					<option value="fade_in_from_right_to_left" <?php echo (get_option('loading_effect') == 'fade_in_from_right_to_left')? 'selected' : '' ?>  >Fade in from right to left </option>
					
					<option value="fade_in_from_left_to_right" <?php echo (get_option('loading_effect') == 'fade_in_from_left_to_right')? 'selected' : '' ?>  >Fade in from left to right </option> 
					
					</select></td>
				</tr>
				
				<tr class="user-nickname-wrap">
					<th colspan=2><label>Note: </label>"Selectors can be ID or Class: If ID use '#id_name' and if Class use '.class_name' " </th>
				</tr>
				
				<tr class="user-nickname-wrap">
					<th><label>Content Selector	</label></th>
					<td><input type="text" class="regular-text" id="scroll_infinite_contentSelector" name="scroll_infinite_contentSelector" value ="<?php echo (get_option('scroll_contentSelector'))? get_option('scroll_contentSelector'): '' ; ?>" ><br>
						<span class="description">The Selector of section that contain's your theme's content. </span>
					</td>
				</tr>
				
				<tr class="user-nickname-wrap">
					<th><label>Next Selector </label></th>
					<td><input type="text" class="regular-text" id="scroll_infinite_nextSelector" name="scroll_infinite_nextSelector" value ="<?php echo (get_option('scroll_nextSelector'))? get_option('scroll_nextSelector'): '' ; ?>" ><br>
						<span class="description">The Selector of section that contain's Link to next page of content. </span>
					</td>
				</tr>
				
				<tr class="user-nickname-wrap">
					<th><label>Item Selector</label></th>
					<td><input type="text" class="regular-text" id="scroll_infinite_itemSelector" name="scroll_infinite_itemSelector" value ="<?php echo (get_option('scroll_itemSelector'))? get_option('scroll_itemSelector'): '' ; ?>" ><br>
						<span class="description">The Selector of section that contain's an individual post.</span>
					</td>
				</tr>
				
				<tr class="user-nickname-wrap">
					<td colspan=2 ><input type="submit" class="button button-primary" id="submit" name="submit" value="Save " />	<input type="button" class="button button-primary" id="reset_button" name="reset_button" value="Reset " /> </td>
					
				</tr>
				
				</tbody>	
				</table>
				</form>
			</div>       
			
<?php	
		wp_enqueue_style( 'wp-color-picker' ); 
		
		wp_enqueue_script("conditions-js",plugins_url( '' , __FILE__ ).'/assets/js/custom.js',array('jquery','wp-color-picker'),'',true);
		
	} 
		
    add_action('admin_enqueue_scripts', 'script_for_upload');
 
	function script_for_upload() 
	{
		
		if (isset($_GET['page']) && $_GET['page'] == 'infinite_scrolling_settings') 
		{
			
			wp_enqueue_media();
			
		}
		
	}
	
?>
