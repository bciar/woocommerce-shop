jQuery(document).ready(function(){
var x=jQuery("div.img_badge").parents("a");;
	for(var i=0;i<x.length;i++)
	{
		//jQuery(x[i]).children("span.onsale").hide();
		//console.log(x[i]);
		//alert();
		
	}
	
	
	
	
});

(function($) {
    $(document).ready(function() {
		//alert();

      // Element custom event
      $('body.woocommerce .product.has-post-thumbnail .woocommerce-main-image .wp-post-image')
        .on('woocommerce-elevateZoomUpdate', function() {
          $('.zoomContainer .zoomWindowContainer .zoomWindow')
            .css('background-image', 'url(' + $(this).parent('a').attr('href') + ')');
        })
        .on('woocommerce-elevateZoom', function () {
          $(this)
            .attr('data-zoom-image', $(this).parent('a').attr('href'))
            .elevateZoom({
              
              // You could play with the options from examples
              // on http://www.elevateweb.co.uk/image-zoom/examples
              responsive: true

            });
        })
        .trigger('woocommerce-elevateZoom');

      // Hook on image change because the variation
      $('.variations_form')
        .on('wc_variation_form show_variation reset_image', function() {
          $('body.woocommerce .product.has-post-thumbnail .woocommerce-main-image .wp-post-image')
            .trigger('woocommerce-elevateZoomUpdate');
        });

    });
  }(jQuery));
