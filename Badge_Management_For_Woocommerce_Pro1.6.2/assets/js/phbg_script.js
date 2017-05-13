jQuery(document).ready(function(){


/************************************ get the position when inner pre dragged************************************/	
		jQuery('#inner_pre').draggable(
    {
	
        drag: function(){
             var top = jQuery(this).position().top;
        	var left = jQuery(this).position().left;
		
		if(jQuery("#badge_pos").val()=='top-right')
		{
			
			var right=150-parseInt(jQuery("#inner_pre").width())-parseInt(left);
			jQuery('#pos-top').val(top);
			jQuery('#pos-right').val(right);

			jQuery('#pos-left').val('auto');
			jQuery('#pos-bottom').val('auto');

		}
		else if(jQuery("#badge_pos").val()=='bottom-left')
		{
			var bottom=150-parseInt(jQuery("#inner_pre").height())-parseInt(top);
			jQuery('#pos-bot').val(bottom);
			jQuery('#pos-left').val(left);
			
			jQuery('#pos-right').val('auto');
			jQuery('#pos-top').val('auto');

		}
		else if(jQuery("#badge_pos").val()=='bottom-right')
		{
			var bottom=150-parseInt(jQuery("#inner_pre").height())-parseInt(top);
			var right=150-parseInt(jQuery("#inner_pre").width())-parseInt(left);
			jQuery('#pos-bot').val(bottom);
			jQuery('#pos-right').val(right);


			jQuery('#pos-left').val('auto');
			jQuery('#pos-top').val('auto');
		}
		else
		{
			
			jQuery('#pos-top').val(top);
			jQuery('#pos-left').val(left);

			jQuery('#pos-right').val('auto');
			jQuery('#pos-bottom').val('auto');
		}
		
		

	
        }
    });


	

//jQuery("#inner_pre p").css({"top":"calc(50% - 25px)"});


	
/****************************** align the inner_pre according to alignment icons*************************************************/

	jQuery('#top-c').click(function(){
	
		var per=jQuery('#inner_pre').width();
		per=parseInt(per)/2;
		jQuery("#inner_pre").css({"top":'0',"left":"calc(50% - "+per+"px)"});
		
		/********************* set the value in position boxes*******************************/
		jQuery("#pos-left").val("calc(50% - "+per+"px)");
		jQuery("#pos-top").val("0");
		jQuery("#pos-bottom").val("auto");
		jQuery("#pos-right").val("auto");
		
		var pos_arr={};

				pos_arr['top']="0";
				pos_arr['bottom']='auto';
				pos_arr['left']="calc(50% - "+per+"px)";
				pos_arr['right']='auto';

				set_badge_pos(pos_arr);
		
		

	});

	jQuery('#bot-c').click(function(){

		//alert(jQuery("#pre_img").height());
	
		//var per=jQuery('#size_w').val();
		var per=jQuery("#inner_pre").width();
		per=parseInt(per)/2;
		jQuery("#inner_pre").css({"top":"auto","bottom":'0px',"left":"calc(50% - "+per+"px)",'right':'auto'});
		
		/********************* set the value in position boxes*******************************/
		
		var pos_arr={};

				pos_arr['top']="auto";
				pos_arr['bottom']='0';
				pos_arr['left']="calc(50% - "+per+"px)";
				pos_arr['right']='auto';

				set_badge_pos(pos_arr);
		
		

	});
	jQuery('#left-c').click(function(){

		var per=jQuery('#inner_pre').height();
		per=parseInt(per)/2;
		jQuery("#inner_pre").css({"top":"calc(50% - "+per+"px)","bottom":'auto',"left":"0",'right':'auto'});
		//jQuery("#inner_pre p").css({"top":"calc(50% - "+per+"px)"});
		/********************* set the value in position boxes*******************************/

		var pos_arr={};

				pos_arr['top']="calc(50% - "+per+"px)";
				pos_arr['bottom']='auto';
				pos_arr['left']="0";
				pos_arr['right']='auto';

				set_badge_pos(pos_arr);

		
	});

	jQuery('#right-c').click(function(){

		var per=jQuery('#inner_pre').height();
		per=parseInt(per)/2;
		jQuery("#inner_pre").css({"top":"calc(50% - "+per+"px)","bottom":'auto',"left":"auto",'right':'0'});
		//jQuery("#inner_pre p").css({"top":"calc(50% - "+per+"px)"});	

		/********************* set the value in position boxes*******************************/

		var pos_arr={};

				pos_arr['top']="calc(50% - "+per+"px)";
				pos_arr['bottom']='auto';
				pos_arr['left']="auto";
				pos_arr['right']='0';

				set_badge_pos(pos_arr);


		
	});

jQuery('#cen').click(function(){

		var per=jQuery('#inner_pre').height();
		per=parseInt(per)/2;
		var per1=jQuery('#inner_pre').width();
		per1=parseInt(per1)/2;
		jQuery("#inner_pre").css({"top":"calc(50% - "+per+"px)","bottom":'auto',"left":"calc(50% - "+per1+"px)",'right':'auto'});		

//jQuery("#inner_pre p").css({"top":"calc(50% - "+per+"px)"});


		/********************* set the value in position boxes*******************************/

				var pos_arr={};

				pos_arr['top']="calc(50% - "+per+"px)";
				pos_arr['bottom']='auto';
				pos_arr['left']="calc(50% - "+per1+"px)";
				pos_arr['right']='auto';

				set_badge_pos(pos_arr);

		
	});

/******************************************************************/
	jQuery('.images').css('position','relative');
	 jQuery('.thumbnails.columns-3 a:first-child').hide();
	//jQuery( "<p>Test</p>" ).appendTo( "img" );

	if(jQuery('#check_post').val()==1||jQuery('#edit_value').val()==1)
	{
	jQuery("#postdivrich").hide();
	jQuery("#postimagediv").hide();
	jQuery("#edit-slug-box").hide();
	}
	

	/******************add color picker in textboxes****************/
        jQuery('.color-field').wpColorPicker({
    	change: function(event, ui) {
      	jQuery("#inner_pre").css("background-color",ui.color.toString()); 
       
   	 }});



	jQuery('.color-field-txt').wpColorPicker({
    	change: function(event, ui) {
      	jQuery("#inner_pre p").css("color",ui.color.toString()); 
       
   	 }});
	
	
	jQuery("h2 a").click(function(){


	jQuery("h2 a").removeClass("nav-tab-active");
	jQuery(this).addClass("nav-tab nav-tab-active");
});


	
	/*************************************** show the text in preview pane*************************************/

	jQuery("#b_text").keyup(function(){
			jQuery("#inner_pre p").text(jQuery(this).val());

		});
	


		jQuery("#font_size").keyup(function(){
			jQuery("#inner_pre p").css('font-size',jQuery(this).val()+'px');

		});
	/************************************** show the radius of border of preview pane text***********************************************/

		jQuery("#r-top-left, #r-top-right, #r-bottom-left ,#r-bottom-right").keyup(function(){
			jQuery("#inner_pre").css('border-radius',(jQuery('#r-top-left').val()+'px'+" "+jQuery('#r-top-right').val()+'px'+" "+jQuery('#r-bottom-left').val()+'px'+" "+jQuery('#r-bottom-right').val()+'px'));
			

		});

	/************************************** show the padding of preview pane text***********************************************/
		jQuery("#p-top, #p-right, #p-bottom ,#p-left").keyup(function(){
			jQuery("#inner_pre").css('padding',(jQuery('#p-top').val()+'px'+" "+jQuery('#p-right').val()+'px'+" "+jQuery('#p-bottom').val()+'px'+" "+jQuery('#p-left').val()+'px'));
			

		});


	/****************************************************change the size of inner pane********************************************/

		jQuery("#size_w").keyup(function(){
			jQuery("#inner_pre").css("width",jQuery(this).val());

		});


		jQuery("#size_h").keyup(function(){
			jQuery("#inner_pre").css("height",jQuery(this).val());
			jQUery("#inner_pre p").css({"top": "50%","transform": "translateY(-50%)"});

		});
	/***************************************change the position of inner pane**************************************************/
		
		jQuery('#badge_pos').on("change",function(){

			var pos=jQuery('#badge_pos').val();
			var pos_arr={};

			if(pos=='bottom-left')
			{

				jQuery('#inner_pre').css({"bottom":"0","top":'auto',"left":'0',"right":'auto'});


				pos_arr['top']='auto';
				pos_arr['bottom']='0';
				pos_arr['left']='0';
				pos_arr['right']='auto';

				set_badge_pos(pos_arr);

				
			}
			else if(pos=='top-left')
			{

				jQuery('#inner_pre').css({"top":"0","bottom":'auto',"left":'0',"right":'auto'});

				pos_arr['top']='0';
				pos_arr['bottom']='auto';
				pos_arr['left']='0';
				pos_arr['right']='auto';

				set_badge_pos(pos_arr);
			}
			else if(pos=='top-right')
			{

				jQuery('#inner_pre').css({"top":"0","right":'0',"left":'auto',"bottom":'auto'});


				pos_arr['top']='0';
				pos_arr['bottom']='auto';
				pos_arr['left']='auto';
				pos_arr['right']='0';

				set_badge_pos(pos_arr);
				
			}
			else if(pos=='bottom-right')
			{

				jQuery('#inner_pre').css({"bottom":"0","right":'0',"left":'auto','top':'auto'});

				pos_arr['top']='auto';
				pos_arr['bottom']='0';
				pos_arr['left']='auto';
				pos_arr['right']='0';

				set_badge_pos(pos_arr);

				
			}



			
		

		});

/**************************************** this will switch between image,text,css and advance tab*******************************/

	jQuery("#btn-image").click(function(){
				
			
			
			jQuery("#badge_type").val("2");
		/****************************** hide other template*************************************/
			jQuery("#txt-content").hide();
			jQuery("#css-content").hide();
			jQuery("#advance-content").hide();
		/*********************************** showing image content**********************************************/
			jQuery("#img-content").show();
		/***************************** hide other element within the inner pre div***************/
			jQuery("#inner_pre p").css("display","none");

		/************************************* store the value of previous inner pre  value**********************************/
			
			var text_val=jQuery("#size_w").val()+"|"+jQuery("#size_h").val()+"|"+jQuery('#b_color').val();
			jQuery("#tab-text-value").val(text_val);

			var text_pad=jQuery("#p-top").val()+"|"+jQuery("#p-right").val()+"|"+jQuery("#p-bottom").val()+"|"+jQuery("#p-left").val();
			jQuery("#tab-text-pad").val(text_pad);
			
		/******************************************* show new values in inner pre***************************/			
			jQuery("#inner_pre").css("background-color","");
			jQuery("#pre_img").show();
			
		});
	jQuery("#btn-text").click(function(){
			
			
			jQuery("#badge_type").val("1");
			jQuery("#img-content").hide();
			jQuery("#txt-content").show();
			jQuery("#pre_img").hide();
			jQuery("#inner_pre p").show();

			set_text_badge_value( jQuery("#tab-text-value").val(),jQuery("#tab-text-pad").val());
			
		});
		
	
/***************************** chnage the opacity of inner_pre*************************************/

	jQuery("#opacity").change(function(){

			var opa=parseInt(jQuery("#opacity").val())/100;
			jQuery("#inner_pre").css("opacity",opa);
			jQuery("#phbg_opacity").val(opa);
	});
/*************************************************************************/

	/***************************** css for active and deactive tab*********************************/	
		jQuery('.custom-post a').click(function(){
		

			jQuery('.custom-post a').removeClass('badge-tab-active');
			jQuery('.custom-post a').addClass('badge-tab-deactive');
			jQuery(this).removeClass('badge-tab-deactive');
			jQuery(this).addClass('badge-tab-active');
			
		});
	/*******************************************************************************/

/***********************************it will show the preview of images whtaever u choose***********************/
    jQuery(document).on('click', '.img-badge-img div img', function(){
		
				jQuery("#pre_img").attr("src",jQuery(this).attr("src"));
				jQuery("#pre_img").attr("height",jQuery(this).attr("height"));
				jQuery("#pre_img").attr("width",jQuery(this).attr("width"));
				jQuery("#img_path").val(jQuery(this).attr("alt"));
				jQuery("#inner_pre").css({"height":jQuery("#pre_img").height(),"width":jQuery("#pre_img").width()});

				jQuery("#inner_pre").css("padding","0px 0px 0px 0px");
				
			
			
	});



/************************************************************************/
 /*********************** store the title of badge in hidden field**********************************************/   
jQuery('#title').keyup(function(){


	var a=jQuery("#title").val();
	jQuery("#b_title").val(a);

});

/******************************* it will open wp media uploader on custom upload image************************************/	
	  jQuery(document).on("click","#phoe-upload-img",uploadimage_button);

    function uploadimage_button(){

        textid = this.id+'1';

        var custom_upload = wp.media({

        title: 'Add Media',

        button: {

            text: 'Insert Image'

        },

        multiple: false  // Set this to true to allow multiple files to be selected

    })

    .on('select', function() {

        var attachment = custom_upload.state().get('selection').first().toJSON();

        jQuery('.custom_media_image').attr('src', attachment.url);

        jQuery('#img_val').val(attachment.url);
	jQuery(".img-badge-img").append("<div><img class='ex_img' src='"+attachment.url+"' alt='"+attachment.url+"'></div>");

       

    })

    .open();

 

    }
/**********************************************************************************************/
 /********************* it will add the value in position boxes  when free badge is going to be dited**************************/ 

	if(jQuery("#free_badge").val()==1)
	{

		var pos=jQuery('#badge_pos').val();
			var pos_arr={};

			if(pos=='bottom-left')
			{

				jQuery('#inner_pre').css({"bottom":"0","top":'auto',"left":'0',"right":'auto'});


				pos_arr['top']='auto';
				pos_arr['bottom']='0';
				pos_arr['left']='0';
				pos_arr['right']='auto';

				set_badge_pos(pos_arr);

				
			}
			else if(pos=='top-left')
			{

				jQuery('#inner_pre').css({"top":"0","bottom":'auto',"left":'0',"right":'auto'});

				pos_arr['top']='0';
				pos_arr['bottom']='auto';
				pos_arr['left']='0';
				pos_arr['right']='auto';

				set_badge_pos(pos_arr);
			}
			else if(pos=='top-right')
			{

				jQuery('#inner_pre').css({"top":"0","right":'0',"left":'auto',"bottom":'auto'});


				pos_arr['top']='0';
				pos_arr['bottom']='auto';
				pos_arr['left']='auto';
				pos_arr['right']='0';

				set_badge_pos(pos_arr);
				
			}
			else if(pos=='bottom-right')
			{

				jQuery('#inner_pre').css({"bottom":"0","right":'0',"left":'auto','top':'auto'});

				pos_arr['top']='auto';
				pos_arr['bottom']='0';
				pos_arr['left']='auto';
				pos_arr['right']='0';

				set_badge_pos(pos_arr);

				
			}
	
		
	}     
/*************************************************************************************************/	
 


jQuery('#cat_badge').click(function(){

	
	jQuery('#setting_div').hide();
	jQuery('#badges_category').show();
});
jQuery('#setting').click(function(){

	
	jQuery('#setting_div').show();
		jQuery('#badges_category').hide();
});


/************************** set the position of inner pre if any one put the position by keyboard**********************/


/*jQuery("#pos-top").keyup(function(){
			var top=jQuery("#pos-top").val();
			var bot=jQuery("#pos-bot").val();
			var left=jQuery("#pos-left").val();
			var right=jQuery("#pos-top").val();
			jQuery('#inner_pre').css({"bottom":bot,"right":right,"left":left+"px;",'top':top+"px;"});
		
	});*/
/***************************************************************/

	/* jQuery('#badge_expiary_date').datepicker({
        dateFormat : 'dd-mm-yy'
    });*/
	
	
	jQuery("#publish").click(function(){
									  
									  
									 	var from=jQuery('#badge_expiary_from').val();
										var to=jQuery('#badge_expiary_to').val();
										if((from==''&&to!='')||(to==''&&from!=''))
										{
												alert("please choose both dates or keep it blank");
											return false;
										}
										
										var from_date=jQuery('#from_date').val();
										var to_date=jQuery('#to_date').val();
										
										if((from_date==''&&to_date!='')||(to_date==''&&from_date!=''))
										{
												alert("please choose both product badge dates or keep it blank");
											return false;
										}
										
										
									  });
   
});

/****************************** it will set the value in position boxes when ever nachor point change**************************/

function set_badge_pos( my_pos)
{
		jQuery("#pos-left").val(my_pos['left']);
		jQuery("#pos-top").val(my_pos['top']);
		jQuery("#pos-bot").val(my_pos['bottom']);
		jQuery("#pos-right").val(my_pos['right']);	

		
} 
/********************************** it will restore the value of text preview content when it swithches by image tab**************************/
function set_text_badge_value(text_val,text_pad)
{

			var inn_val=text_val.split("|");
			jQuery("#inner_pre").height(inn_val[1]);
			jQuery("#inner_pre").width(inn_val[0]);
			jQuery("#inner_pre").css("background-color",inn_val[2]);

			var pad_val=text_pad.split("|");
			jQuery("#inner_pre").css({"padding-top":pad_val[0]+"px","padding-right":pad_val[1]+"px","padding-bottom":pad_val[2]+"px","padding-left":pad_val[3]+"px"});
			
}
/*******************************************************************/


