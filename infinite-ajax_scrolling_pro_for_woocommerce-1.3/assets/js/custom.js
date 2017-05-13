jQuery(document).ready(function(){
	
	jQuery("#reset_button").click(function(){
		
		
		jQuery("#form7").find("[type=text],textarea").val("");
		
		jQuery("#loading_effect").val("none");
		
		jQuery("#lm_button_text").val("Load More");
		
		jQuery("#submit").click();
		
		
		
	});

	var custom_uploader;
	
	jQuery('#img_upload_button').click(function(e) {
	
		e.preventDefault();
		
		if (custom_uploader) 
		{
			
			custom_uploader.open();
			
			return;
		
		}

		custom_uploader = wp.media.frames.file_frame = wp.media({
			
			title: 'Choose Image',
			
			button: {
			
			text: 'Choose Image'
			
			},
			
			multiple: false
		
		});

		custom_uploader.on('select', function() {
			
			attachment = custom_uploader.state().get('selection').first().toJSON();
			
			jQuery('#loader_img').val(attachment.url);
		
		});

		custom_uploader.open();

	});
	
	jQuery("#lm_button_hover_color, #lm_button_text_color, #lm_button_hover_text_color,#lm_button_color").wpColorPicker();

	if( jQuery('#load_more_button').attr('checked'))
	{ 
		jQuery('.load_more_options').show();
	
	}
	else
	{
		
		jQuery('.load_more_options').hide();
	
	}
	
	jQuery('#load_more_button').click(function(){
		
		jQuery('.load_more_options').show();
	
	}); 
	
	jQuery('#infinite_scrolling').click(function(){
		
		jQuery('.load_more_options').hide();
		
		jQuery('.hide_loader').show();
			
			
	});
	jQuery('#ajax_pagination').click(function(){
		
		jQuery('.load_more_options').hide();
		
		jQuery('.hide_loader').hide();
		 
	});
	if(jQuery('#ajax_pagination').is(':checked')){
	
		jQuery('.hide_loader').hide();
	}
	
	
});