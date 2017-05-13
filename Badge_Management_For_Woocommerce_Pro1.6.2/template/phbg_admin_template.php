<?php
if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}

function add_new_badge()
{
	if(sanitize_text_field($_GET['action'])=='edit')
	{

		$type=get_post_meta(sanitize_text_field($_GET['post']),'_phoe_badge');
		$row=json_decode($type[0]);
		$active_class='badge-tab-active';
		$deactive_class='badge-tab-deactive';
			
	}
	$active_class='badge-tab-active';
		$deactive_class='badge-tab-deactive';
	
	/**********************************form to add badges**************************************************/
	?>
	<div class="area_badge"><div style="width:70%;" class="design_area"><h3 class="hndle"></h3> <div class="tab-container">
	<input type="hidden" value="1" id="check_post">
				<ul class="custom-post">
					<li><a id="btn-text"  class="<?php echo (!isset($row->type)?$active_class:phbg_tab_class($row)); ?>" href="#tab-text">Text Badge</a></li>
					<li><a id="btn-image" class="<?php echo (($row->type=='img')?$active_class:$deactive_class); ?>" href="#tab-image">Image Badge</a></li>
				</ul></div><br />
		<div id="txt-content" style="display:<?php echo  (!isset($row->type)?'':phbg_tab_text_display($row));  ?>;">

		<fieldset style=" float:left;"><legend>Text Option</legend><table>
	<tr><td>Text</td><td><input type="text" id="b_text"  name="b_text" value="<?php echo ((!isset($row))?'':(($row->type=='text')?$row->text:'')); ?>"></td></tr>
	<tr><td>Text Color</td><td id="t_color"><input type="text" class="color-field-txt"  value="<?php echo ((!isset($row))?'#FFFFFF':(($row->type=='text')?$row->txt_color:'#FFFFFF')); ?>" id="txt_color" name="txt_color"></td></tr><tr><td>Font Size(Pixel)</td><td><input type="text" name="font_size" id="font_size" value="<?php echo (!isset($row->font_size))?'12':$row->font_size; ?>"></td></tr><!--<tr><td>Line Height(Pixel)</td><td><input type="text" name="line_height" id="line_height" value="-1"></td></tr>-->


	</table></fieldset>



	<br /><br /><br /><br /><br /><br />

	<fieldset><legend>Style Option</legend><table>
	<tr><td>Background Color</td><td id="bg_color"><input type="text" value="<?php echo ((!isset($row))?'#0000FF':(($row->type=='text')?$row->bg_color:'#0000FF')); ?>" id="b_color" class="color-field" name="bg_color"></td></tr>
	<tr><td>Size(pixels)</td><td><input type="text" style="width:70px;" value="<?php echo ((!isset($row))?'50':(($row->type=='text')?$row->width:'50')); ?>" placeholder="width" id="size_w" name="size_w">*<input type="text"  placeholder="height"  id="size_h" value="<?php echo ((!isset($row))?'50':(($row->type=='text')?$row->height:'50')); ?>" name="size_h" style="width:70px"></td></tr>

	<tr><td>Border-Radius</td><td><input type="text" style="width:70px;" value="<?php echo (isset($row->r_top_left)&&$row->type=='text')?$row->r_top_left:'0'?>" placeholder="Top Left" id="r-top-left" name="r-top-left"><input type="text" style="width:70px;" value="<?php echo (isset($row->r_top_right)&&$row->type=='text')?$row->r_top_right:'0'?>" placeholder="Top Right" Left" id="r-top-right" name="r-top-right"><input type="text" style="width:70px;" value="<?php echo (isset($row->r_bot_left)&&$row->type=='text')?$row->r_bot_left:'0'?>" placeholder="Bottom Left" id="r-bottom-left" name="r-bottom-left"><input type="text" style="width:70px;" value="<?php echo (isset($row->r_bot_right)&&$row->type=='text')?$row->r_bot_right:'0'?>" placeholder="Bottom Right" id="r-bottom-right" name="r-bottom-right"></td></tr>


	<tr><td>Padding</td><td><input type="text" style="width:70px;" value="<?php echo ((!isset($row->pad_top))?'0':(($row->type=='text')?$row->pad_top:'0')); ?> " placeholder="Top" id="p-top" name="p-top"><input type="text" style="width:70px;" value="<?php echo ((!isset($row->right))?'0':(($row->type=='text')?$row->pad_right:'0')); ?>" placeholder="Right" Left" id="p-right" name="p-right"><input type="text" style="width:70px;" value="<?php echo ((!isset($row->pad_bottom))?'0':(($row->type=='text')?$row->pad_bottom:'0')); ?>" placeholder="Bottom" id="p-bottom" name="p-bottom"><input type="text" style="width:70px;" value="<?php echo ((!isset($row->pad_left))?'0':(($row->type=='text')?$row->pad_left:'0')); ?>" placeholder="left" id="p-left" name="p-left"></td></tr>



	</table></fieldset>
	<br />

	</div>

	<div id="img-content"style="display:<?php echo  (($row->type=='img')?'':'none');  ?>;">

		<fieldset style="float:left"><legend>Select Images</legend>
			<div class="img-badge-img">

				<div>
				<img id="lets" src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/1.png" alt='1.png'></div>
				<div><img src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/2.png"  alt='2.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/3.png" alt='3.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/4.png" alt='4.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/5.png" alt='5.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/6.png" alt='6.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/7.png" alt='7.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/8.png" alt='8.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/9.png" alt='9.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/10.png" alt='10.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/11.png" alt='11.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/12.png" alt='12.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/13.png" alt='13.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/14.png" alt='14.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/15.png" alt='15.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/16.png" alt='16.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/17.png" alt='17.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/18.png" alt='18.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/19.png" alt='19.png'></div>
				<div><img  src="<?php echo PHBGPLUG_PATH;?>assets/images/image_badge/20.png" alt='20.png'></div>
				
				<?php
					if($row->type=='img')
					{
						if(strlen($row->img)>=10)
						{
							?>
							<div><img  src="<?php echo $row->img; ?>" alt="<?php echo $row->img; ?>" ></div>
						<?php
						}
					}
				?>
			</div>

		</fieldset>	
	<fieldset style="float:left;"><legend>Upload Custom Image</legend>

	<input name="upload-img" id="phoe-upload-img" class="button-secondary" value="Upload" type="button">
	<input type="text" id="img_val">
		
	</fieldset>


	<br /><br /><br /><br /><br /><br />


	<input type="hidden" name="img_path" id="img_path" value="<?php echo  (($row->type=='img')?$row->img:'1.png');  ?>">
	<input type="hidden" name="b_title" id="b_title" >
	<input type="hidden" name="badge_type" id="badge_type" value="<?php echo  (($row->type=='img')?'2':'1');  ?>">

	</div>
	<!----------------------------------------------- image badge body end------------------------------------------------------------------>

	<fieldset id="bg_pos_area"><legend>Position</legend><table>
	<tr><td>Opacity</td><td><input type="range" id="opacity" min="0" max="100" value="<?php echo (isset($row->phbg_opacity))?$row->phbg_opacity*100:'100';?>"></td></tr>

	<tr><td>Anchor Point</td><td><select class="update-preview" name= "badge_pos" id="badge_pos">
											<option value="top-left" <?php echo(isset($row->anchor_point)&&$row->anchor_point=='top-left')?'selected':($row->position=='top-left')?'selected':'';  ?>>top-left</option>;
											<option value="top-right" <?php echo(isset($row->anchor_point)&&$row->anchor_point=='top-right')?'selected':($row->position=='top-right')?'selected':'';  ?>>top-right</option>;
											<option value="bottom-left" <?php echo(isset($row->anchor_point)&&$row->anchor_point=='bottom-left')?'selected':($row->position=='bottom-left')?'selected':''; ?>>bottom-left</option>;
											<option value="bottom-right"<?php echo(isset($row->anchor_point)&&$row->anchor_point=='bottom-right')?'selected':($row->position=='bottom-right')?'selected':'';  ?> >bottom-right</option>;
										
										
										
										</select></td></tr>


				
					<tr><td>Position</td><td><input type="text" style="width:70px;" value="<?php echo ((!isset($row))?'0':$row->pos_top); ?>" placeholder="Top" id="pos-top" name="pos-top"><input type="text" style="width:70px;" value="<?php echo ((!isset($row))?'auto':$row->pos_bottom); ?>" placeholder="Bottom" id="pos-bot" name="pos-bot"><input type="text" style="width:70px;" value="<?php echo ((!isset($row))?'0':$row->pos_left); ?>" placeholder="Left" id="pos-left" name="pos-left"><input type="text" style="width:70px;" value="<?php echo ((!isset($row))?'auto':$row->pos_right); ?>" placeholder="right" id="pos-right" name="pos-right"></td></tr>
	</table>




	<table cellspacing=5>


	<tr><td>Center Positioning</td><td><img id="top-c" src="<?php echo PHBGPLUG_PATH;?>assets/images/icons/top-center.png" height=30 width=30></td><td> <img  id="bot-c" src="<?php echo PHBGPLUG_PATH;?>assets/images/icons/bottom-center.png" height=30 width=30></td><td> <img src="<?php echo PHBGPLUG_PATH;?>assets/images/icons/left-center.png" id="left-c" height=30 width=30></td><td> <img id="right-c" src="<?php echo PHBGPLUG_PATH;?>assets/images/icons/right-center.png" height=30 width=30></td><td><img id="cen" src="<?php echo PHBGPLUG_PATH;?>assets/images/icons/center.png" height=30 width=30></td></tr>

	</table></fieldset><br />

	<fieldset style="border:solid 1px; width:500px;"><legend style="margin-left:20px;"> Validity Of Badge</legend>
		<table>
			<tr><td>From:-</td><td><input type="text"  value="<?php echo $row->badge_expiary_from; ?>" name="badge_expiary_from" id="badge_expiary_from"></td></tr>
			<tr><td>To:-</td><td><input type="text"  value="<?php echo $row->badge_expiary_to; ?>" name="badge_expiary_to" id="badge_expiary_to"></td></tr>
		
		</table>
	</fieldset>

	</div>


	<div  style="width:30%; height:400px; float:right;" class="design_area" id="preview_area">

	<div id="pre_pane" style="width:150px; top:100px; height:150px; background-color:#ccc; float:right; position:relative;">
	<div id="inner_pre" style="<?php echo  ((!isset($row)?'background-color:blue;height:50px;width:50px;':phbg_inner_style($row)));?>  text-align:center; color:#ffffff; position: absolute;"><p style="position: relative;margin:0px;font-size:<?php echo (isset($row->font_size))?$row->font_size:'12' ?>px;   top: 50%;   transform: translateY(-50%);display:<?php echo  (($row->type=='img')?'none':'');?>;color:<?php echo ((!isset($row))?'#FFFFFF':(($row->type=='text')?$row->txt_color:'#FFFFFF')); ?> "><?php  echo (isset($row->text)?$row->text:''); ?></p><img id="pre_img" style="display:<?php echo  (($row->type=='img')?'':'none'); ?>" src="<?php echo  (($row->type=='img')?phbg_get_preimg($row): PHBGPLUG_PATH.'assets/images/image_badge/1.png');  ?>"> </div>
	</div>
	</div>

	<input type="hidden" id="tab-text-value" value="<?php echo(!isset($row)?'50|50|blue':phbg_tab_text_val($row)); ?>">
	<input type="hidden" id="tab-text-pad" value="">
	<input type="hidden" id="tab-img-value">
	<input type="hidden" id="phbg_opacity" name="phbg_opacity" value="<?php  echo (isset($row->phbg_opacity))?$row->phbg_opacity:'1'; ?>">
	<?php if(isset($row->position)) { ?>
	<input type="hidden" id="free_badge" value="1">
	<?php }?>

	</div>

	<?php

}

/************************ this function will used to manage the css of inner_pre on edit template**********************************/
function phbg_inner_style($row)
{

	if($row->type=='text')
	{
		if(isset($row->position))
		{
			$pos=phbg_set_pos($row->position);
			return "height:".$row->height."px;width:".$row->width."px;background-color:".$row->bg_color.";".$pos;
		}
		else
		{	
		return "height:".$row->height."px;width:".$row->width."px;background-color:".$row->bg_color.";top:".phbg_pos_value($row->pos_top).";bottom:".phbg_pos_value($row->pos_bottom).";left:".phbg_pos_value($row->pos_left).";right:".phbg_pos_value($row->pos_right).";border-radius:".$row->r_top_left."px ".$row->r_top_right."px ".$row->r_bot_left."px ".$row->r_bot_right."px;opacity:".$row->phbg_opacity.";padding-top:".$row->pad_top."px;padding-bottom:".$row->pad_bottom."px;padding-right:".$row->pad_right."px;padding-left:".$row->pad_left."px;";
		}
		
	}
	if($row->type=='img')
	{
		if(isset($row->position))
		{
			return phbg_set_pos($row->position);
		}
		else
		{
			return "top:".phbg_pos_value($row->pos_top).";bottom:".phbg_pos_value($row->pos_bottom).";left:".phbg_pos_value($row->pos_left).";right:".phbg_pos_value($row->pos_right).";opacity:".$row->phbg_opacity.";";
		}
	}

}
/******************************************************************************/

function phbg_tab_text_display($row)
{
	if($row->type=='text')
	return '';
	else
	return 'none';
}

/****************** it will show the height width and color of inner_pre when badge type is text during editing*************************/

function phbg_tab_text_val($row)
{
	if($row->type=='text')
	{
		return $row->height.'|'.$row->width.'|'.$row->bg_color;
	}
	else
	{
		return '50|50|blue';
	}
}
/*************************************************************/

/******************** return the css class during editing for tabs**************************/
function phbg_tab_class($row)
{

	$active_class='badge-tab-active';
	$deactive_class='badge-tab-deactive';

	if($row->type=='text')
	{
		return $active_class;
	}

	else
	{		
		return $deactive_class;
	}

}

/************************* get the image path for premium image badge ****************************************/
function phbg_get_preimg($row)
{

	if(strlen($row->img)>=10)
	{
		return $row->img;
	}
	else
	{

		return PHBGPLUG_PATH."assets/images/image_badge/".$row->img;
	}

	
}
?>