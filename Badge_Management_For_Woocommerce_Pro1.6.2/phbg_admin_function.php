<?php
if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

/********************************** function which will handle all the task related to admin*************************************/

/* it will add our own meta box in admin panel*/
function phbg_admin() {

	
    add_meta_box( '',
        'Badge Options',
        'phbg_badge_create',
        'phoe_badge', 'normal', 'high'
    );
}
	
/*********************
it will show the form  as meta boxes when badge
 is going to be added or updated

**********************************/	
function phbg_badge_create()
{

  $prod_id=get_the_ID();	

	
	if($_GET['action']=='edit')
	{
		
		add_new_badge();
	}
	else
	{
		add_new_badge();
	}
}


/** it will remove metaboxes from admin panel*/
function phbg_remove_meta_boxes() {
remove_meta_box( 'postcustom', 'phoe_badge', 'normal' );
remove_meta_box( 'commentstatusdiv', 'phoe_badge', 'normal' );
remove_meta_box( 'commentsdiv', 'phoe_badge', 'normal' );
remove_meta_box( 'wpfooter', 'phoe_badge', 'normal' );
remove_meta_box( 'postdivrich', 'phoe_badge', 'advance' );
	
}

function phbg_meta_boxes()
	{
		
		
		add_meta_box("badge-meta-box", "Badge Box", "phbg_badge_meta_box", "product", "side", "low", null);
   
	}

/** it will show a panel on woocommerce add/ update product page***********/

function phbg_badge_meta_box()
	{
		global $wpdb;
		$action=sanitize_text_field($_GET['action']);

		if($action=='edit')
		{

			$prod_id=sanitize_text_field($_GET['post']);
			
			$validity=get_post_meta($prod_id,'_phoe_badge_expiry_on_product');

			$row=get_post_meta($prod_id,'_phoe_badge_name');	
			

			echo"<div class='option-group'><h4>Add Badge</h4>";
			
			echo"<select name='badge_name[]' multiple>";

			echo phbg_get_all_badges($row[0]);

			echo"</select></div>";

			$dt=get_post_meta($prod_id,'_phoe_badge_expiry_on_product');

			$validity=json_decode($dt[0]);

			echo "From:-<input type='text' id='from_date' value='".$validity->from."' name='from_date'><br>";

			echo "To:-<input type='text' id='to_date' value='".$validity->to."' name='to_date'>";

			date_default_timezone_set ( 'Asia/kolkata' );

			echo "<center><h4>Current Time</h4>";

			echo date("Y-m-d h:i")."</center>";

		}
		else
		{
			echo"<div class='option-group'><h4>Add Badge</h4>";

			echo"<select name='badge_name[]' multiple>";

			echo phbg_get_all_badges('0');

			echo"</select></div>";
			
			echo "From:-<input type='text' id='from_date' name='from_date'><br>";

			echo "To:-<input type='text' id='to_date' name='to_date'>";
		}

		
	}



/**********************************

this function is used to save the new badge 
or updated badge information. it will also stored
the information of badge related to product.

***********************************************/
function phbg_posts($post_id){
	
	
	if( $_POST['post_type'] == 'phoe_badge' )
	{
		
		$ch= sanitize_text_field($_POST['badge_type']);
		
		if($ch==1)
		{
			
			$row['type']='text';
			
			$row['title']=sanitize_text_field($_POST['post_title']);
			
			$row['text']=sanitize_text_field($_POST['b_text']);
			
			$row['font_size']=sanitize_text_field($_POST['font_size']);
			
			$row['height']=sanitize_text_field($_POST['size_h']);
			
			$row['width']=sanitize_text_field($_POST['size_w']);
			
			$row['txt_color']=sanitize_text_field($_POST['txt_color']);
			
			$row['bg_color']=sanitize_text_field($_POST['bg_color']);
			
			$row['anchor_point']=sanitize_text_field($_POST['badge_pos']);
			
			$row['pos_top']=sanitize_text_field($_POST['pos-top']);
			
			$row['pos_bottom']=sanitize_text_field($_POST['pos-bot']);
			
			$row['pos_left']=sanitize_text_field($_POST['pos-left']);
			
			$row['pos_right']=sanitize_text_field($_POST['pos-right']);
			
			$row['r_top_left']=sanitize_text_field($_POST['r-top-left']);
			
			$row['r_top_right']=sanitize_text_field($_POST['r-top-right']);
			
			$row['r_bot_left']=sanitize_text_field($_POST['r-bottom-left']);
			
			$row['r_bot_right']=sanitize_text_field($_POST['r-bottom-right']);
			
			$row['pad_top']=sanitize_text_field($_POST['p-top']);
			
			$row['pad_right']=sanitize_text_field($_POST['p-right']);
			
			$row['pad_bottom']=sanitize_text_field($_POST['p-bottom']);
			
			$row['pad_left']=sanitize_text_field($_POST['p-left']);
			
			$row['phbg_opacity']=sanitize_text_field($_POST['phbg_opacity']);
			
			$row['badge_expiary_from']=sanitize_text_field($_POST['badge_expiary_from']);
			
			$row['badge_expiary_to']=sanitize_text_field($_POST['badge_expiary_to']);

			$row=json_encode($row);
			
			update_post_meta($post_id,'_phoe_badge',$row);
		}
		
		else if($ch==2)
		{
			
			$row['type']='img';
			
			$row['title']=sanitize_text_field($_POST['post_title']);
			
			$row['img']=sanitize_text_field($_POST['img_path']);
			
			$row['anchor_point']=sanitize_text_field($_POST['badge_pos']);
			
			$row['pos_top']=sanitize_text_field($_POST['pos-top']);
			
			$row['pos_bottom']=sanitize_text_field($_POST['pos-bot']);
			
			$row['pos_left']=sanitize_text_field($_POST['pos-left']);
			
			$row['pos_right']=sanitize_text_field($_POST['pos-right']);
			
			$row['phbg_opacity']=sanitize_text_field($_POST['phbg_opacity']);
			
			$row['badge_expiary_from']=sanitize_text_field($_POST['badge_expiary_from']);
			
			$row['badge_expiary_to']=sanitize_text_field($_POST['badge_expiary_to']);

			$row=json_encode($row);
			
			update_post_meta($post_id,'_phoe_badge',$row);
				
		}
	}

/*************************** add the information when prodct select the any badge.*************************/	
  
	if(!empty($_POST['badge_name']))
	{
		
		$p_id=$post_id;
		
		$chk=get_post_meta($p_id,'_phoe_badge_name');
		
		if($chk[0]!=sanitize_text_field($_POST['badge_name']))
		{
			
			update_post_meta($p_id,'_phoe_badge_added_on',date("Y-m-d h:i:s"));
			
		}
		$bval=$_POST['badge_name'];
		
		update_post_meta($p_id,'_phoe_badge_name',$bval);
		
		$val['from']=$_POST['from_date'];
		
		$val['to']=$_POST['to_date'];
		
		$val1=json_encode($val);
		
		update_post_meta($p_id,'_phoe_badge_expiry_on_product',$val1);	
	}
	
}
/******************************

this function will generate custom,
post type.

***********************************/

	function phbg_post_type() {
	
		
		 register_post_type( 'phoe_badge',
        array(
            'labels' => array(
                'name' => 'Badge',
                'singular_name' => 'Badge',
                'add_new' => 'Add New',
                'add_new_item' => 'Add new badge',
                'edit' => 'Edit',
                'edit_item' => 'Edit Badge',
                'new_item' => 'New Badge',
                'view' => 'View',
                'search_items' => 'Search Badge',
                'not_found' => 'No Badge found',
                'not_found_in_trash' => 'No Badges found in Trash',
                'parent' => 'Parent Badge Review'
            ),
 
            'public' => true,
            'menu_position' => 12,
		'menu_icon'  => PHBGPLUG_PATH.'assets/images/phoenixx.png',
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'has_archive' => true
        )
    );
}

/******************

it will show notice if 
woocommerrce is not available

********************/
function phbg_notice()
{
	echo '<div id="message" class="error"><p>sorry you have to install woocommerce in order to use Badges</p></div>';
}

/**********************************************/
/*************************** it will return all the badge which is published*******************************/
function phbg_get_all_badges($badge_id)
{
//echo $badge_id;
	global $wpdb;
	
	$sql = $wpdb->prepare( 'SELECT * FROM '.PHBGPRE.'postmeta where meta_key=%s','_phoe_badge' );
	
	$res=$wpdb->get_results($sql);
	
	
	$return="<option ".phoen_chekone($badge_id)."  value='none' >none</option>";

	foreach($res as $res)
	{
		$data_meta=json_decode($res->meta_value);

	if(get_post_status($res->post_id)!='trash')

		$return.="<option ".phbg_get_selected($badge_id,$res->post_id)." value='".$res->post_id."'>".$data_meta->title."</option>";
	}

	return $return;
}


function phbg_get_all_badges1($badge_id)
{
//echo $badge_id;
	global $wpdb;
	
	$sql = $wpdb->prepare( 'SELECT * FROM '.PHBGPRE.'postmeta where meta_key=%s','_phoe_badge' );
	
	$res=$wpdb->get_results($sql);
	
	
	$return="<option ".phoen_chekone($badge_id)."  value='none' >none</option>";

	foreach($res as $res)
	{
		$data_meta=json_decode($res->meta_value);

	if(get_post_status($res->post_id)!='trash')

		$return.="<option ".phbg_get_selected1($badge_id,$res->post_id)." value='".$res->post_id."'>".$data_meta->title."</option>";
	}

	return $return;
}

function phbg_option_value($badge_id)
{
	if($badge_id!=0)
	{
		return 'none';
	}
	
	else
	{
		return '0';
	}
}

function phoen_chekone($val1)
{
		if(in_array('none',$val1))
	{
		return 'selected';
	}
	else
	{
		return '';
	}
}
/***********************************************/
/********************** it will selected current badge related to product/category/etc**************************/
function phbg_get_selected($val1,$val2)
{
	//if($val1==$val2)
		if(in_array($val2,$val1))
	{
		return 'selected';
	}
	else
	{
		return '';
	}
}
function phbg_get_selected1($val1,$val2)
{
	if($val1==$val2)
		
	{
		return 'selected';
	}
	else
	{
		return '';
	}
}

/********************** create form for settings*********************/
function phoe_badge_set()
{
	

	if(isset($_POST['set_badge']))
	{
		
		$name_onsale="_phoe_badge_onsale";
		
		if(sanitize_text_field($_POST['badge_able'])=='on')

			update_option('_phoe_badge_onsale', '1');

			else

				update_option('_phoe_badge_onsale', '2');
		
		if(sanitize_text_field($_POST['my_badge_enable'])=='on')

			update_option( '_phoe_badge_enable', '1');

			else

				update_option( '_phoe_badge_enable', '2');

		if(sanitize_text_field($_POST['badge_on_single_product'])=='on')

			update_option( '_phoe_badge_on_single_product', '1');

			else

				update_option( '_phoe_badge_on_single_product', '2');

		if(isset($_POST['badge_recent_products']))
		{

	$pre=get_option('_phoe_badge_for_recent');

	update_option('_phoe_badge_for_recent',sanitize_text_field($_POST['badge_recent_products']));
			
			$new=sanitize_text_field($_POST['badge_recent_products']);

			if($pre!=$new)
			{
				update_option("_phoe_recent_product_add_date",date("Y-m-d h:i:s"));
			}
		}
		if(isset($_POST['badge_recent_products']))

			update_option('_phoe_product_newer_than',sanitize_text_field($_POST['product_newer_than']));

			if(isset($_POST['badge_on_sale']))
		{
			$pre=get_option('_phoe_badge_for_onsale');

			update_option('_phoe_badge_for_onsale',sanitize_text_field($_POST['badge_on_sale']));
		
			$new=sanitize_text_field($_POST['badge_on_sale']);
			
			if($pre!=$new)
			{
				update_option("_phoe_onsale_add_date",date("Y-m-d h:i:s"));
				
			}
		}
		if(isset($_POST['badge_featured_product']))
		{
			$pre=get_option('_phoe_badge_for_featured');

			update_option('_phoe_badge_for_featured',sanitize_text_field($_POST['badge_featured_product']));

			$new=sanitize_text_field($_POST['badge_featured_product']);
			
			if($pre!=$new)
			{
				update_option("_phoe_featured_add_date",date("Y-m-d h:i:s"));
			}
		}	
		

	}


	if(isset($_POST['save_category_badges']))
	{
		
		phbg_save_category_badges();
	
		$check_div_display=1;
	}

	$value=get_option('_phoe_badge_onsale');
	
	$value_b=get_option('_phoe_badge_enable');
	
	$value_single_product=get_option('_phoe_badge_on_single_product');

	$cat_badges_val=json_decode(get_option('_phoe_cat_badges'));
		



?>


<?php
/*********************************************************/
/*********************** it will get all the categories of woocommerce product******************************/
$product_categories = get_terms('product_cat');
$i=0;
foreach($product_categories as $product_cat)
{
	foreach($product_cat as $product_cat1)
	{
		$p_cat[$i]= $product_cat->name;
		break;
		
	}
	$i=$i+1;
}
/*********************************************************/

?>


<div><form method="post" action="">

	<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
				<a  id="setting" class="nav-tab <?php echo(isset($check_div_display))?'':'nav-tab-active'; ?> " href="#">General</a>
				<a id="cat_badge" class="nav-tab  <?php echo(isset($check_div_display))?'nav-tab-active':''; ?> " href="#">Category Badges</a>	
	</h2>
	<div id="setting_div" style="display:<?php echo(isset($check_div_display))?'none':''; ?>;">
		<h2>General Setting</h2>

		<table class="form-table" style="background-color:#FFFFFF; padding:10px;max-width:1100px;">
			<tbody>
					<tr class="" valign="top">
						<th scope="row" class="titledesc">Enable Badge Management Plugin</th>
							<td class="forminp forminp-checkbox">
								<legend class="screen-reader-text"></legend>
								<label for="pzmp_plugin_enable">
									<input name="my_badge_enable" <?php echo ($value_b!='2')?'checked=""':''; ?> id="pzmp_plugin_enable"   type="checkbox"> 
								</label> 
							</td>
					</tr>
				<tr class="" valign="top">
					<th scope="row" class="titledesc">Hide Default "On Sale" Badge</th>
					<td class="forminp forminp-checkbox">
						<legend class="screen-reader-text"></legend>
						<label for="hide default on sale badge">
							<input name="badge_able"  <?php if($value=='1') echo "checked=''";?>id="pzmp_plugin_mobile_enable"  type="checkbox"> 

						</label> 
					</td>
				</tr>	
			</tbody>
		</table>

	<h2>Recent Products</h2>
		<table class="form-table" style="background-color:#FFFFFF; padding:10px;max-width:1100px;">
			<tbody>
				<tr class="" valign="top">
					<th scope="row" class="titledesc">Badge For Recent Products</th>
					<td class="forminp forminp-select">
						
							<legend class="screen-reader-text"></legend>
							<label for="badge_for_recent_products">
								<select name="badge_recent_products">
									<?php echo phbg_get_all_badges1(get_option('_phoe_badge_for_recent'));?>
								</select>Select the badge you want to apply to all recent products.
							</label> 
					</td>
				</tr>
				<tr class="" valign="top">
					<th scope="row" class="titledesc">Newer Than</th>
					<td class="forminp">
						
							<legend class="screen-reader-text"></legend>
							<label for="recent_product_newer_than">
								<input name="product_newer_than" id="product_newer_than" value="<?php echo get_option('_phoe_product_newer_than'); ?>" type="number"> Show the badge for products that are newer than X days.
							</label> 		
					</td>
				</tr>
				
			</tbody>
		</table>

	<h2>On Sale[Automatic]</h2>

		<table class="form-table" style="background-color:#FFFFFF; padding:10px;max-width:1100px;">
			<tbody>
			
				<tr class="" valign="top">
					<th scope="row" class="titledesc">On Sale Badge</th>
					<td class="forminp forminp-checkbox">
						<legend class="screen-reader-text"></legend>
						<label for="on_sale_badge">
						<select name="badge_on_sale">
							<?php echo phbg_get_all_badges1(get_option('_phoe_badge_for_onsale'));?>
						</select>Select the Badge for products on sale.
						</label> 
					</td>
				</tr>
	
			</tbody>
		</table>


	<h2>Featured[Automatic]</h2>

		<table class="form-table" style="background-color:#FFFFFF; padding:10px;max-width:1100px;">
			<tbody>
				<tr class="" valign="top">
					<th scope="row" class="titledesc">Featured Badge</th>
						<td class="forminp forminp-checkbox">
							<legend class="screen-reader-text"></legend>
							<label for="featured_badge_label">
							<select name="badge_featured_product">
								<?php echo phbg_get_all_badges1(get_option('_phoe_badge_for_featured'));?>
							</select>Select the badge for featured products.
						
								
							</label> 
						</td>
				</tr>

		</tbody>
	</table>

	<h2>Product Icon</h2>

		<table class="form-table" style="background-color:#FFFFFF; padding:10px;max-width:1100px;">
			<tbody>
				<tr class="" valign="top">
					<th scope="row" class="titledesc">Hide Badges</th>
						<td class="forminp forminp-checkbox">
							<legend class="screen-reader-text"></legend>
							<label for="badge_on single_product">
							
							<input name="badge_on_single_product"<?php echo($value_single_product==1)?'checked=""':'';?> id="badge_on_single_product"   type="checkbox">
								
							</label> 
						</td>
				</tr>
						
						
		</tbody>
	</table>

	<br>

	<input type="submit" name="set_badge" class="button button-primary" value="Save Changes"></div></form>
	<div id="badges_category" style="display:<?php echo(isset($check_div_display))?'':'none'; ?>">
		<h2>Select Category Badges</h2>
		<form method="post" action="">
		<table class="form-table" style="background-color:#FFFFFF; padding:10px;max-width:1100px;">
			<tbody>
			<?php foreach($p_cat as $p_cat){?>
				<tr class="" valign="top">
					<th scope="row" class="titledesc"><?php echo ucfirst($p_cat);?></th>
						<td class="forminp forminp-checkbox">
							<legend class="screen-reader-text"></legend>
							<label for="category_badges">
							<?php

							
								$p_cat=str_replace(" ","#@",$p_cat);
									
							?>
							<select name="<?php echo $p_cat;?>">
								<?php echo phbg_get_all_badges1($cat_badges_val->$p_cat);?>
							</select>Select the Badge for all products of category <?php echo ucfirst($p_cat);?>
						
								
							</label> 
						</td>
				</tr>
				<?php } ?>

			</tbody>
		</table>
		<br>
		<input type="submit" name="save_category_badges" class="button button-primary" value="Save Changes">	
	</div>
</form>
<style>
	.form-table th {
		width: 270px;
		padding: 25px;
	}
	.form-table td {
		
		padding: 20px 10px;
	}
	.form-table {
		background-color: #fff;
	}
	h3 {
		padding: 10px;
	}
</style>

<?php
	
}


/*********************
this is used to generate position 
of badge both in frontend and backend

***********************/

/********************* it will save the badges for categories******************************/
function phbg_save_category_badges()
{
	$cat_badge=json_decode(get_option('_phoe_cat_badges'));
	
	unset($_POST["save_category_badges"]);
	foreach( $_POST as $key=>$val)
	{
		$cat_data[$key]=$val;
		if($cat_badge->$key!=$val)
		{
			update_option("_phoe_badge_cat_".$key,date("Y-m-d h:i:s"));
		}
		

	}
	$cat_data=json_encode($cat_data);
	update_option('_phoe_cat_badges',$cat_data);
}
	
/****************************************************************/

function phbg_set_pos($my_pos)
{
	if($my_pos=='top-right')
	return 'position:absolute; right:0;left:auto;top:0;bottom:auto;';	
	else if($my_pos=='top-left')
	return 'position:absolute; left:0; right:auto;top:0;bottom:auto;';	
	else if($my_pos=='bottom-right')
	return 'position:absolute; left:auto; right:0;bottom:0;top:auto;';	
	else if($my_pos=='bottom-left')
	return 'position:absolute; left:0; right:auto; bottom:0;top:auto;';
       

	
}

?>