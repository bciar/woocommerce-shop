(function ($, window, document) 
{
	"use strict";

        var scroll_options = $.extend(
			{
				scroll_item_selector   : false,               
				scroll_content_selector: false,
                scroll_next_selector   : false,
				is_shop        : false,
				loader         : false  
            }, 
			{
			'scroll_item_selector'      : item_Selector,	           
            'scroll_content_selector'   : content_Selector,
            'scroll_next_selector'      : next_Selector,
			'is_shop'           : true,  
			'loader'            : image_loader
			}
			),
        under_loading  = false,
        loading_finished = false,
        target_url  = $( scroll_options.scroll_next_selector ).attr( 'href' ); 
		
		if( !$( scroll_options.scroll_next_selector ).length  && !$( scroll_options.scroll_item_selector ).length && !$( scroll_options.scroll_content_selector ).length ) 
		{
        
			loading_finished = true;
        
		}
        
		var first_product_unit  = $( scroll_options.scroll_content_selector ).find( scroll_options.scroll_item_selector ).first(),
            columns = first_product_unit.nextUntil( '.first', scroll_options.scroll_item_selector ).length + 1;

        var call_ajax = function (z) 
		{

            var last_product_unit   = $( scroll_options.scroll_content_selector ).find( scroll_options.scroll_item_selector ).last();
            
			//console.log(last_product_unit);
			
            if( scroll_options.loader )
			{
				if(z == 0 )
				{
					
					$( scroll_options.scroll_content_selector ).after( '<div class="scroll-loader"><center><img src="' + scroll_options.loader + '"/></center><br></div>' );
					
				}
				else if(z == 1)
				{
					
					$( scroll_options.scroll_content_selector ).after( '<div class="scroll-loader"><center><img src="' + plugin_url + '/assets/img/loader.gif"/></center><br></div>' );
					
				}
				
			}
			
			under_loading = true;
            
            $.ajax({
               
                url         : target_url,
                dataType    : 'html',
                success     : function (response) {

                    var obj  = $( response),
                        product_unit = obj.find( scroll_options.scroll_item_selector ),
                        next = obj.find( scroll_options.scroll_next_selector );
				
                    if( next.length ) 
					{
						console.log( "this");
                        target_url = next.attr( 'href' );
				        $("#load_more").show(); 
						
					}
                    else 
					{
						
                        loading_finished = true;
						jQuery("#load_more").hide();
						jQuery("#Got_to_top").show();
						
					}
					
                    
                    if( ! last_product_unit.hasClass( 'last' ) && scroll_options.is_shop ) 
					{
						
                        position_product_unit( last_product_unit, columns, product_unit );
						
						                    
					}
					
					switch(loading_effect)
					{
						
						case "zoom_in":
							
							product_unit.css({
								'opacity':'0'
							}); 
							
							last_product_unit.after( product_unit );

							$( '.scroll-loader' ).remove();
							
							product_unit.animate({
													opacity:'1',
													padding: "+=30px"
													
													},0,function() { });
							
							product_unit.animate({
													opacity:'1',
													padding: "-=30px"
													
													},1000,function() { under_loading = false;});
									
							break;
						
						case "bounce_in":
							
							product_unit.css({
								'opacity':'0'
							}); 
							
							last_product_unit.after( product_unit );

							$( '.scroll-loader' ).remove();
							
							product_unit.animate({
													opacity:'1',
													padding: "+=15px"
													},1000,function() { });
							
							product_unit.animate({
													
													padding: "-=15px"
													},1000,function() {under_loading = false;});
									
							break;
					
						case "fade_in":
							
							product_unit.css({
								'opacity':'0'
							}); 
							
							last_product_unit.after( product_unit );

							$( '.scroll-loader' ).remove();
							
							product_unit.animate({ opacity: 1}, 2000,function() {under_loading = false;}); 
									
							break;
					
						case "fade_in_from_top_to_down":
							
							product_unit.css({
								'opacity':'0'
							}); 
							
							last_product_unit.after( product_unit );

							$( '.scroll-loader' ).remove();
							
							product_unit.animate({ bottom	: "+=150px", fontSize: "-=1em", opacity: 0 }, 0);
							
							product_unit.animate({ bottom: "-=150px", fontSize: "+=1em", opacity: 1 }, 1800);
							
							product_unit.animate({ }, 0,function() {under_loading = false;});
							
							break;
					
						case "fade_in_from_down_to_top":
							
							product_unit.css({
								'opacity':'0'
							}); 
							
							last_product_unit.after( product_unit );

							$( '.scroll-loader' ).remove();
							
							product_unit.animate({ top	: "+=100px", fontSize: "-=1em", opacity: 0 }, 0);
							
							product_unit.animate({ top: "-=100px", fontSize: "+=1em", opacity: 1 }, 1500);
							
							product_unit.animate({ }, 0,function() {under_loading = false;});
									
							break;
					
						case "fade_in_from_right_to_left":
							
							// Fade in right to left  effect 1
			
						    product_unit.css({
								'opacity':'0'
							}); 
							
							last_product_unit.after( product_unit );

							$( '.scroll-loader' ).remove();
							
							product_unit.animate({ paddingLeft: "+=100px", opacity: 0 }, 0);
							
							product_unit.animate({ paddingLeft: "-=100px", opacity: 1 }, 1200);
							
							product_unit.animate({ }, 0,function() {under_loading = false;});   
							
							break;
					
						case "fade_in_from_left_to_right":
							
							// Fade in left to right  effect 2
					
						 	product_unit.css({
								'opacity':'0'
							}); 
							
							last_product_unit.after( product_unit );

							 $( '.scroll-loader' ).remove();
							
							product_unit.animate({ marginRight: "-=100px", opacity: 0 }, 0);
							
							product_unit.animate({ marginRight: "+=100px", opacity: 1 }, 1500);
							
							product_unit.animate({ }, 0,function() {under_loading = false;});  
							
							break;
					
						default:
							
							product_unit.css({
								'opacity':'1'
							}); 
							
							last_product_unit.after( product_unit );
						
							$( '.scroll-loader' ).remove();
							
							product_unit.animate({},0,function() {under_loading = false;});  
							
					}						
				
					if(z == 1)
					{
						//$("#load_more").show();
						
						
					}
					
												
				}
            
			});
			
        
		};
      
        var position_product_unit = function( last, columns, product_unit ) {

            var off_set  = ( columns - last.prevUntil( '.last', scroll_options.scroll_item_selector ).length ),
                loop    = 0;

            product_unit.each(function () {

                var y = $(this);
                loop++;

                y.removeClass('first');
                y.removeClass('last');

                if ( ( ( loop - off_set ) % columns ) === 0 ) 
				{
                    y.addClass('first');
                }
                else if ( ( ( loop - ( off_set - 1 ) ) % columns ) === 0 ) 
				{
                    y.addClass('last');
									
                }
            });
        };
        
		
		var ajax_pagination_call = function (U_R_L,Pagination_ul_class,current_object) 
		{

			
			under_loading = true;
            
            $.ajax({
               
                url         : U_R_L,
                dataType    : 'html',
                success     : function (response) {
					
					var obj  = $( response);
					
					var pagination_response = obj.find("." +Pagination_ul_class).html();
					
					var content_response = obj.find(scroll_options.scroll_content_selector).html();
					
					var final_content = jQuery(scroll_options.scroll_content_selector).empty().html(content_response);
												
				    var product_unit = final_content.find( scroll_options.scroll_item_selector );
					
					var final_pagination = current_object.parent().parent().empty().html(pagination_response);
					
					jQuery(scroll_options.scroll_next_selector).parent().parent().find("a").removeClass("current");
					
					jQuery("#img_loader").hide();
					
					switch(loading_effect)
					{
						
						case "zoom_in":
							
							product_unit.css({
								'opacity':'0'
							}); 
							
							product_unit.animate({
													opacity:'1',
													padding: "+=30px"
													
													},0,function() { });
							
							product_unit.animate({
													opacity:'1',
													padding: "-=30px"
													
													},1000,function() { under_loading = false;});
									
							break;
						
						case "bounce_in":
							
							product_unit.css({
								'opacity':'0'
							}); 
							
							product_unit.animate({
													opacity:'1',
													padding: "+=15px"
													
													},1000,function() { });
							
							product_unit.animate({
													
													padding: "-=15px"
													
													},1000,function() {under_loading = false;});
									
							break;
					
						case "fade_in":
							
							product_unit.css({
								'opacity':'0'
							}); 
							
							product_unit.animate({ opacity: 1}, 2000,function() {under_loading = false;}); 
									
							break;
					
						case "fade_in_from_top_to_down":
							
							product_unit.css({
								'opacity':'0'
							}); 
				
							product_unit.animate({ bottom	: "+=150px", fontSize: "-=1em", opacity: 0 }, 0);
							
							product_unit.animate({ bottom: "-=150px", fontSize: "+=1em", opacity: 1 }, 1800);
							
							product_unit.animate({ }, 0,function() {under_loading = false;});
							
							break;
					
						case "fade_in_from_down_to_top":
							
							product_unit.css({
								'opacity':'0'
							}); 
							
							product_unit.animate({ top	: "+=100px", fontSize: "-=1em", opacity: 0 }, 0);
							
							product_unit.animate({ top: "-=100px", fontSize: "+=1em", opacity: 1 }, 1500);
							
							product_unit.animate({ }, 0,function() {under_loading = false;});
									
							break;
					
						case "fade_in_from_right_to_left":
							
							// Fade in right to left  effect 1
			
							product_unit.css({
								'opacity':'0'
							}); 
							
							product_unit.animate({ paddingLeft: "+=100px", opacity: 0 }, 0);
							
							product_unit.animate({ paddingLeft: "-=100px", opacity: 1 }, 1200);
							
							product_unit.animate({ }, 0,function() {under_loading = false;});   
							
							break;
					
						case "fade_in_from_left_to_right":
							
							// Fade in left to right  effect 2
					
						 	product_unit.css({
								'opacity':'0'
							}); 
						
							$( '.scroll-loader' ).remove();
							
							product_unit.animate({ marginRight: "-=100px", opacity: 0 }, 0);
							
							product_unit.animate({ marginRight: "+=100px", opacity: 1 }, 1500);
							
							product_unit.animate({ }, 0,function() {under_loading = false;});  
							
							break;
					
						default:
							
							product_unit.css({
								'opacity':'1'
							}); 
							
							product_unit.animate({},0,function() {under_loading = false;});  
							
					}	
					
					
				}
            
			});
			
        };
		$( window ).ajaxStop( function (e){
			
			 jQuery('li.product .image_loader, li.product .quick_view_insider').each(function () {
				
				jQuery(this).closest('li.product').find('.img-wrap').append(this);
				
			}); 
			
		});	
		switch(type_of_pagination)
		{
			
			case "infinite_scrolling" :
			
				$( window ).on( 'scroll touchstart', function (e){
					
					var y = $(this),
						
					off_set  = $( scroll_options.scroll_item_selector ).last().offset();

					if ( ! under_loading && ! loading_finished && y.scrollTop() >= Math.abs( off_set.top - ( y.height() - 150 ) ) ) 
					{
					
						var nextselector_parent = $( scroll_options.scroll_next_selector ).parent();
						
						var nextselector_parent_parent = nextselector_parent.parent();

						nextselector_parent_parent.empty();
						
						var z = 0;
						
						 if( nextselector_parent.length != 0 )
						{
							
							call_ajax(z);
						}
					}
				
				});
			
				break;
				
			case "load_more_button" :
			
				var nextselector_parent = $( scroll_options.scroll_next_selector ).parent();
				
				var nextselector_parent_parent = nextselector_parent.parent();
				
				nextselector_parent_parent.before( '<div class=""><center><input type="button" name="load_more" id="load_more" value="' + op_lm_button_text + '" /></center><br></div>' );
				
				nextselector_parent_parent.empty();
					
				break;
				
			case "ajax_pagination" :
	
					var nextselector_parent_parent_a = jQuery( scroll_options.scroll_next_selector ).parent().parent().parent().find("a").attr("class");
					
					
					jQuery("a." + nextselector_parent_parent_a).live('click',  function(e){		
						
						e.preventDefault();
						jQuery("#img_loader").show();
						
						
						var current_object = jQuery(this);
						
						var U_R_L =jQuery(this).attr("href");
						
						var Pagination_ul_class = jQuery(this).parent().parent().attr("class");
						
						jQuery('html, body').animate({
							
							scrollTop: jQuery(scroll_options.scroll_content_selector).offset().top -150
							}, 1000);
						
						ajax_pagination_call(U_R_L,Pagination_ul_class,current_object);
						
					});  
				
					
				break;	
			
			default:
				
				break;
				
		}
		jQuery("#load_more").before('<div class=""><center><input  type="button" style="display:none;" name="Go To Top" id="Got_to_top" value="Go To Top" /></center><br></div>');
		
		jQuery("#load_more").click(function(){
			
			var y = jQuery(this);
			y.before("<p id='no_more_data'></p>");
			if ( ! under_loading && ! loading_finished  ) 
			{
				var z = 1;
				call_ajax(z);
				
			}
			else
			{
				jQuery("#load_more").hide();
				
				jQuery("#no_more_data").animate({ opacity:0 },1000,function(){ jQuery(this).text("No More Data"); });
				
				jQuery("#no_more_data").animate({ opacity:1 },1000,function(){  });
				
				jQuery("#no_more_data").animate({ opacity:0 },1000,function(){ jQuery(this).remove(); });
				
			
			} 
			
		});
		jQuery(document).on('click','#Got_to_top',go_to_top);
		function go_to_top(){
		
			$(window).scrollTop(0);
			
		}	

		
		var button_color = jQuery("#load_more").css("background");
		
		var button_text_color = jQuery("#load_more").css("color");
		
		if(op_lm_button_color)
		{
			
			jQuery("#load_more").css("background",op_lm_button_color  );
			jQuery("#Got_to_top").css("background",op_lm_button_color  );
			
			var button_color = op_lm_button_color;
		
		}
		
		if(op_lm_button_text_color)
		{
			
			jQuery("#load_more").css("color",op_lm_button_text_color  );
			
			jQuery("#Got_to_top").css("color",op_lm_button_text_color  );
			
			button_text_color = op_lm_button_text_color;
		
		}
		else
		{
			
			jQuery("#load_more").css("color",button_text_color  );
			
			jQuery("#Got_to_top").css("color",button_text_color  );
		
		}
		
		jQuery("#load_more").hover(function(){ 
		
			jQuery("#load_more").css("background",op_lm_button_hover_color  );
			
			jQuery("#load_more").css("color",op_lm_button_hover_text_color  );
			
			 
		},function(){ 
		
			jQuery("#load_more").css("background",button_color  );
			
			jQuery("#load_more").css("color",button_text_color  );
		
		});
		jQuery("#Got_to_top").hover(function(){ 
		
			jQuery("#Got_to_top").css("background",op_lm_button_hover_color  );
			
			jQuery("#Got_to_top").css("color",op_lm_button_hover_text_color  );
			
			 
		},function(){ 
		
			jQuery("#Got_to_top").css("background",button_color  );
			
			jQuery("#Got_to_top").css("color",button_text_color  );
		
		});
	
})( jQuery, window, document );