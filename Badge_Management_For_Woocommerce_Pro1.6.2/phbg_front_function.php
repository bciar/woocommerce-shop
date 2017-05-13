<?php
if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

/************************ this will handle all the task related to front end************************/

/***************************

this function will show the badge on product

************************/


function phbg_single_product( $val, $product_id )
{
	
	$dd=get_post_meta($product_id);
	
		
	if ( strpos( $val, 'phoe-badge' ) > 0 )
		
		return $val; 

	$badge_container = "<div class='phoe-badge' style='position:relative;'>" . $val;
	
	ob_start();
	
	$badge_content   = get_p_badge($product_id);
	
	$badge_content .= ob_get_clean();

	if ( empty( $badge_content ) )
		
	return $val;
 
	$badge_container .= $badge_content . "</div>";

	return $badge_container;
	
}


	
/************************* this function will return the badge for single product page*************************************/

function get_p_badge($prod_id)
{
		
	$badge_onsale=get_option('_phoe_badge_onsale');
	$badges='';
	if($badge_onsale=='1')
	{	
	?>
		
		<style>
			.onsale { display:none ; }
		</style>

	<?php }

else{
		
		?>
		
		<style>
		.woocommerce.post-type-archive-product.wc-pac-hide-sale-flash .product .onsale{
			display:block;
		}
			.onsale { z-index:9; }
		</style>

	<?php
		
	}

	if(get_option('_phoe_badge_on_single_product')=='1')
	{
		return '';
	}
		
	$res=get_post_meta($prod_id,'_phoe_badge_name');
	$row;
	
	$samp=array('none');
	if(empty($res))
	{
		
		array_push($res,$samp);
	}

	
		echo "<div class='phoen_phbg_parent_badge'>";
		foreach($res[0] as $key=>$val)
		{
		
		
		$check_sql=get_post_status($val);
		
		$badge_id=$val;
		
		$resa=get_post_meta($val,'_phoe_badge');
		
			
		if($check_sql!='trash')
		{

			if($resa[0])
			{

				$row=json_decode($resa[0]);
				
				
				if( phbgBadgeExpire($badge_id) )
				{
										
					if( phbg_is_product_get_badge($badge_id,$row->badge_expiary_days,$prod_id))
					{
						
						
						$badges.= phbg_show_badge($row);	
						
					}
					
				}
				
			}
			
		
		} 
	
		
	$badges .= phbg_show_extra_badge($row);
	
		}
		echo "</div>";
	
	
	
	
	return $badges;
		
}

/************************************ it will return the style of premium text badge***********************************/
function phbg_text_style($row)
{

	$style="position:absolute;box-sizing:content-box; background-color:".$row->bg_color."; text-align:center; color:".$row->txt_color."; font-size:".$row->font_size."px; height:".$row->height."px;  width:".$row->width."px; border-radius:".$row->r_top_left."px ".$row->r_top_left."px ".$row->r_bot_left."px ".$row->r_bot_right."px;top:".phbg_pos_value($row->pos_top).";bottom:".phbg_pos_value($row->pos_bottom).";left:".phbg_pos_value($row->pos_left).";right:".phbg_pos_value($row->pos_right).";padding-top:".$row->pad_top."px; padding-right:".$row->pad_right."px; padding-bottom:".$row->pad_bottom."px; padding-left:".$row->pad_left."px;opacity:".$row->phbg_opacity.";";

	return $style;
	
}

/************************************ it will return the styleof  premium image badge***********************************/
function phbg_img_style($row)
{
	
	if(strlen($row->img)>8)
	{
		$back_img=$row->img;
	}
	else
	{
		$back_img=PHBGPLUG_PATH."assets/images/image_badge/".$row->img;
	}
	
	list($width, $height) = getimagesize($back_img);

	$img_style="position:absolute;top:".phbg_pos_value($row->pos_top).";bottom:".phbg_pos_value($row->pos_bottom).";left:".phbg_pos_value($row->pos_left).";right:".phbg_pos_value($row->pos_right).";opacity:".$row->phbg_opacity.";width:".$width."px;height:".$height."px;background:url(".$back_img.");";

	return $img_style;
	
}

function phbg_pos_value( $val)
{
	if(!is_numeric($val))
		
	return $val;
	
	else
		
	return $val.'px';
	
}

/************************************ it will return the  free text badge***********************************/

function phbg_text_free_badge($row)
{

	$style=phbg_set_pos($row->position);
	
	return "<div class='img_badge'  style='".$style." background-color:".$row->bg_color."; text-align:center; color:".$row->txt_color."; height:".$row->height."px; width:".$row->width."px;'><p style='position: relative;   top: 50%;   transform: translateY(-50%);'>".$row->text."</p></div>";


}

/************************************ it will return the  free image badge***********************************/

function phbg_img_free_badge($row)
{

	$style=phbg_set_pos($row->position);
	
	list($width, $height) = getimagesize(PHBGPLUG_PATH."assets/images/".$row->img);
	
	$back_img=PHBGPLUG_PATH."assets/images/".$row->img;

	return "<div class='img_badge' id='' style='".$style."width:".$width."px;height:".$height."px; background:url(".$back_img.");'><input type='hidden' id='img_badge_src' value='".PHBGPLUG_PATH."assets/images/".$row->img."'></div>";

}

/************************************ it will return the  premium text badge***********************************/
function phbg_text_pre_badge($row)
{
	
	$s1=phbg_text_style($row);
	
	return "<div class='img_badge'  style='".$s1." text-align:center;'><p style='position: relative;   top: 50%;   transform: translateY(-50%);'>".$row->text."</p></div>";
	
	
}

/************************************ it will return the  premium image badge***********************************/
function phbg_img_pre_badge($row)
{
	$s1=phbg_img_style($row);
	
	$img_src= phbg_get_preimg($row);	


	if(($row->anchor_point=='top-right')||($row->anchor_point=='top-left')||($row->anchor_point=='bottom-right')||($row->anchor_point=='bottom-left'))
	{	
				
		return "<div class='img_badge' style='".$s1."'><input type='hidden' id='img_badge_src' value='".PHBGPLUG_PATH."assets/images/image_badge/".$row->img."'></div>";	
	}
	
	
	?>
	
	<style>
	.images {
		text-align: center;
		
	}
 
		
	
	.phoen_img_badge {
			display: inline-block;
			width: 30px;
			text-align: center;
			padding:2px;
			
		}
		
	</style>
	<?php 
	
}


 

/**************************** this function will return the badge for particular products***************************************/
function phbg_show_badge($row)
{
	
	
	if($row->type=='text')
	{
		if(isset($row->position))
		{
			
			return phbg_text_free_badge($row);
		}
		else
		{
			return	 $msg.phbg_text_pre_badge($row);	
		}
	}
	else
	{
		if(isset($row->position))
		{
			
			
			return phbg_img_free_badge($row);
			
		}
		else
		{
			
			return phbg_img_pre_badge($row);
		
		}
		
	}
}
/**************************************************************/
/*************************** it will show the all external badges****************************************/
function phbg_show_extra_badge($row)
{ 
	
	$product_cats=wp_get_post_terms( get_the_ID(), 'product_cat');
	
	$return_badge='';
	
	
	
	
	  foreach($product_cats as $product_cats )
	{
		
		
		if($product_cats->name!='')
		{
			$cats=json_decode(get_option('_phoe_cat_badges'));
			
			$val=$product_cats->name;
			
				$val=str_replace(" ","#@",$val);
				
			 if(get_post_status($cats->$val)!='trash')
			 {
				if((!$row->type=="img")||(!$row->type=="text"))
				{
				
				$return_badge.= phbg_category_badge($cats->$val,$val);
			 }
			
			}
		
		}
	}
	global $product;

	
		if((!$row->type=="img")||(!$row->type=="text"))
		{	
			if ( phbg_product_onsale(get_the_ID())) 
			{

				$id=get_option('_phoe_badge_for_onsale');
				
				
				if(get_post_status($id)!='trash')
				{
					 
					 if(($id=="none")||($id=="0"))
						 { 
							 
							$return_badge.=phbg_onsale_badge($id);
									
						}
						else{
							
							
							if($id!="")
								
							$return_badge.=phbg_onsale_badge($id);
						}
				}
				
			}
		
				
	
	$diff=phbg_day_diff();
	
	$id=get_option('_phoe_badge_for_recent');
	
	$new_day=get_option('_phoe_product_newer_than');
	
	  if($new_day>=$diff)
	{
		if(get_post_status($id)!='trash')
		 {
			
			$return_badge.=phbg_recent_product_badge($id);
				}
	
	} 
	 
	 if(phbg_is_featured(get_the_ID()))
		{
		$id=get_option('_phoe_badge_for_featured');
		
		if(get_post_status($id)!='trash')
		 {
				
				
					$return_badge.=phbg_featured_product_badge($id);
				}
			
		 }
		}		
	 
	return $return_badge;	
}


function phbg_category_badge($badge_id,$cat)
{
	if(phbgBadgeExpire($badge_id))
	{
		return phbg_get_badges($badge_id);
	}
	else
	{
		return '';
			
	}	

}

function phbg_onsale_badge($badge_id)
{
	if(phbgBadgeExpire($badge_id))
	{
		return phbg_get_badges($badge_id);
	}
	else
	{
		return '';
	}	
}

function phbg_recent_product_badge($badge_id)
{
	if(phbgBadgeExpire($badge_id))
	{
		return phbg_get_badges($badge_id);
	}
	else
	{
		return '';
	}	
	
}

function phbg_featured_product_badge($badge_id)
{
	if(phbgBadgeExpire($badge_id))
	{
		return phbg_get_badges($badge_id);
	}
	else
	{
		return '';
	}	  

	
}
/******************* it will find the day difference for the new badge*************************/
function phbg_day_diff()
{
	$min= ceil((current_time( 'timestamp' ) - get_the_time('U'))/60);
	$hr=ceil($min/60);
	$dy=ceil($hr/24);
	return $dy;
}
/************** it will check product is featured or not***********************/
function phbg_is_featured($product_id)
{
	$featured=get_post_meta($product_id,'_featured');
	if($featured[0]=='no')
	return 0;
	else
	return 1;
}
/********************** now this will give the badges to extra badges after check the status of badges********************/
function phbg_get_badges($badge_id)
{

	$res=get_post_meta($badge_id,'_phoe_badge');
	if($res)
	{
		$res=$res[0];
		
		$row=json_decode($res);
		
		return phbg_show_badge($row);
	}
	else
	{
		return '';
	}
}
function phbg_is_badge_expire($badge_id,$days)
{
	$pub_date= phbg_get_badge_publish_date($badge_id);
	
	$exp_date=phbg_get_badge_expiary_date($pub_date,$days);
	
	$today=date("Y-m-d h:i:s");
	
	if($exp_date<$today)
		
	return 1;
	else
	return  0;
}
/********************** it will return the publish date of any post ***********************************/
function phbg_get_badge_publish_date($badge_id)
{
	$date= get_post_field( 'post_date_gmt', $badge_id );
	
	 $badge_publish_date= date('Y-m-d h:i:s', strtotime($date));	
	 
		return $badge_publish_date;	
}
/************************ it will return the expiary date of any post*********************************/

function phbg_get_badge_expiary_date($publish_date,$days)
{
	$badge_exp_date= date('Y-m-d h:i:s', strtotime($publish_date. ' + '.$days.' hours'));
	
	return $badge_exp_date;	
}
/**************************** this function will decide that badge should display on 
product which selected during editing or adding of product*********************/

function phbg_is_product_get_badge($badge_id,$days,$product_id)
{
	$ep=get_post_meta($product_id,'_phoe_badge_expiry_on_product');
	
	if(!get_post_meta($product_id,'_phoe_badge_expiry_on_product'))
	{

		return 1;
		
	}
	else
	{

		$dates=get_post_meta($product_id,'_phoe_badge_expiry_on_product');
		
		$date=json_decode($dates[0]);
		
		date_default_timezone_set ( 'Asia/kolkata' ); 
		
		$from_date=strtotime($date->from);
		
		$to_date=strtotime($date->to);
		
		$current=strtotime(date("Y-m-d H:i"));
		
		if($date->from==''||$date->to=='')
		{
			return 1;
				
		}
		else if($current>=$from_date&&$to_date>=$current)
		{
			return 1;
		}
		else
		{
			return 0;
		}		

		
	}

	
}

function phbg_product_onsale($id)
{
	$selling=get_post_meta($id,'_sale_price');
	
	if($selling[0]=='')
	{
		
		return 0;
		
	}
	else
	{
		
		return 1;
		
	}
}

function phbgBadgeExpire($badge_id)
{
	date_default_timezone_set ( 'Asia/kolkata' ); 
	
	$badge=get_post_meta($badge_id,'_phoe_badge');
	
	$row=json_decode($badge[0]);
	
	$from=strtotime($row->badge_expiary_from);
	
	$to=strtotime($row->badge_expiary_to);
	
	$current=strtotime(date('Y-m-d H:i'));

	if($row->badge_expiary_from==''||$row->badge_expiary_to=='')
	{
		
		return 1;
		
	}
	else if($current>=$from&&$to>=$current)
	{
		
		return 1;
		
	}
	else
	{
		
		return 0;
		
	}
}
?>