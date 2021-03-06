if (typeof PakkelabelsAdminParams !== 'undefined') {
	var ajax_admin_url = PakkelabelsAdminParams.ajax_url;
	var sWeightTranslation = PakkelabelsAdminParams.sWeightTranslation;
	var sPriceTranslation = PakkelabelsAdminParams.sPriceTranslation;
	var sQuantityTranslation = PakkelabelsAdminParams.sQuantityTranslation;
	var sTitleTranslation = PakkelabelsAdminParams.sTitleTranslation;
	var sMinimumTranslation = PakkelabelsAdminParams.sMinimumTranslation;
	var sMaximumTranslation = PakkelabelsAdminParams.sMaximumTranslation;
	var sShippingPriceTranslation = PakkelabelsAdminParams.sShippingPriceTranslation;
	var sBtnAddNewPriceRangeRowTranslation = PakkelabelsAdminParams.sBtnAddNewPriceRangeRowTranslation;
	var sCartTotalTranslation = PakkelabelsAdminParams.sCartTotalTranslation;
	var sCurrencySymbol = PakkelabelsAdminParams.sCurrencySymbol;
	var sWeightUnit = PakkelabelsAdminParams.sWeightUnit;
	var sShippingPriceTranslation = PakkelabelsAdminParams.sShippingPriceTranslation;
	var sShippingRangeHelperTextTranslation = PakkelabelsAdminParams.sShippingRangeHelperTextTranslation;


	jQuery(document).ready(function()
	{
		if(jQuery.urlParam('page') == "wc-settings" && jQuery.urlParam('tab') == "shipping") {
			addHtmlAfterTarget('.shipping_price', 'Weight', sWeightUnit)
			addHtmlAfterTarget('.shipping_price', 'Price', sCurrencySymbol)

			/****************
			 *  Executed on page load
			 ****************/
			if (jQuery('.differentiated_price_type').val() == 'Weight') {
				jQuery('.shipping_price').parents().eq(2).hide()
				jQuery('.Price_tr').hide();
				jQuery('.Weight_tr').show();
			}
			else if (jQuery('.differentiated_price_type').val() == 'Price') {
				jQuery('.shipping_price').parents().eq(2).hide()
				jQuery('.Weight_tr').hide();
				jQuery('.Price_tr').show();
			}

			if (jQuery('.enable_free_shipping').val() == "No") {
				jQuery('.free_shipping_total').parents().eq(2).hide()
			} else {
				jQuery('.free_shipping_total').parents().eq(2).show()
			}


			sRangeType = jQuery('.differentiated_price_type').val();
			var aShippingData = {
				'action': 'pakkelabels_get_price_ranges',
				'sShippingSection': jQuery.urlParam('section'),
				'sRangeType': sRangeType,
			};
			//console.log(aShippingData);

			jQuery.post(ajax_admin_url, aShippingData, function (response) {
				if (response) {
					var returned = JSON.parse(response);
					if (returned.status == "success") {
						//  console.log(returned);
						if (returned.oData != null && returned.oData != "undefined") {
							if (sRangeType == "Price") {
								sUnit = sCurrencySymbol
							}
							else if (sRangeType == "Weight") {
								sUnit = sWeightUnit
							}
							if (returned.oData != false) {
								jQuery('.' + sRangeType + '_div > table > tbody > .pakkelabels_tr').remove()
								for (oRow in returned.oData) {
									jQuery('.' + sRangeType + '_div > table > tbody').append(RowToTablehtml(returned.oData[oRow]['minimum'], returned.oData[oRow]['maximum'], returned.oData[oRow]['shipping_price'], sUnit));
								}
							}

						}
					}
					else if (returned.status == "error") {
					}
				}
			});


			/****************
			 *  On('change')
			 ****************/
			jQuery('.differentiated_price_type').on('change', function () {
				if (jQuery('.differentiated_price_type').val() == "Quantity") {
					jQuery('.Weight_tr').hide();
					jQuery('.Price_tr').hide();
					jQuery('.shipping_price').parents().eq(2).show()
				}
				else if (jQuery('.differentiated_price_type').val() == 'Weight') {
					jQuery('.shipping_price').parents().eq(2).hide()
					jQuery('.Price_tr').hide();
					jQuery('.Weight_tr').show();
				}
				else if (jQuery('.differentiated_price_type').val() == 'Price') {
					jQuery('.shipping_price').parents().eq(2).hide()
					jQuery('.Weight_tr').hide();
					jQuery('.Price_tr').show();
				}
			});

			jQuery('.differentiated_price_type').on('change', function () {
				sRangeType = jQuery('.differentiated_price_type').val();
				var aShippingData = {
					'action': 'pakkelabels_get_price_ranges',
					'sShippingSection': jQuery.urlParam('section'),
					'sRangeType': sRangeType,
				};

				jQuery.post(ajax_admin_url, aShippingData, function (response) {
					if (response) {
						var returned = JSON.parse(response);
						if (returned.status == "success") {
							if (returned.oData.oRows != null && returned.oData.oRows != "undefined") {
								if (sRangeType == "Price") {
									sUnit = sCurrencySymbol
								}
								else if (sRangeType == "Weight") {
									sUnit = sWeightUnit
								}
								jQuery('.' + sRangeType + '_div > table > tbody > .pakkelabels_tr').remove()
								for (oRow in returned.oData.oRows) {
									//console.log(jQuery('.' + jQuery('#woocommerce_pakkelabels_shipping_gls_private_differentiated_price_type').val() + '_div > table > tbody'));
									jQuery('.' + sRangeType + '_div > table > tbody').append(RowToTablehtml(returned.oData.oRows[oRow]['minimum'], returned.oData.oRows[oRow]['maximum'], returned.oData.oRows[oRow]['shipping_price'], sUnit));
								}
							}
						}
						else if (returned.status == "error") {
							// console.log("error");
						}
					}
				});
			})


			/****************
			 *  On('click')
			 ****************/
			jQuery('.button-add-new-price-range-row').on('click', function () {
				sRangeType = jQuery('.differentiated_price_type').val();
				// console.log(sRangeType);
				if (sRangeType == "Price") {
					sUnit = sCurrencySymbol
				}
				else if (sRangeType == "Weight") {
					sUnit = sWeightUnit
				}
				else {
					sUnit = "";
				}

				// console.log(sUnit);
				jQuery(this).parent().siblings('.pakkelabels_table').append(RowToTablehtml("", "", "", sUnit));
			})

			jQuery(document).on('click', '.button-delete-row', function () {
				if (jQuery(this).parent().parent().parent().children('.pakkelabels_tr').length > 1) {
					jQuery(this).parents().eq(1).remove();
				}
			})

			jQuery(document).on('change, click', '.enable_free_shipping', function () {
				// console.log(jQuery('.enable_free_shipping').val());
				if (jQuery('.enable_free_shipping').val() == "No") {
					jQuery('.free_shipping_total').parents().eq(2).hide()
				} else {
					jQuery('.free_shipping_total').parents().eq(2).show()
				}
			})


			jQuery(document).on('change', '.pakkelabels_input_shipping_price, .pakkelabels_input_minimum, .pakkelabels_input_maximum', function () {
				iMinimumPrice = jQuery(this).parent().parent().find('.pakkelabels_input_minimum').val();
				iMaximumPrice = jQuery(this).parent().parent().find('.pakkelabels_input_maximum').val();
				iShippingPrice = jQuery(this).parent().parent().find('.pakkelabels_input_shipping_price').val();
				var bErrors = false;

				if (iShippingPrice) {
					if (!isNumeric(iShippingPrice) || parseInt(iShippingPrice) < 0) {
						jQuery(this).parent().parent().addClass('error-range-row');
						bErrors = true;
					}
				}
				if (iMaximumPrice && iMinimumPrice) {
					if (parseInt(iMinimumPrice) >= parseInt(iMaximumPrice) || !isNumeric(iMinimumPrice) || !isNumeric(iMaximumPrice)) {
						jQuery(this).parent().parent().addClass('error-range-row');
						bErrors = true;
					}
				}
				if (!bErrors) {
					jQuery(this).parent().parent().removeClass('error-range-row');
				}
			})


			jQuery('.button-primary[name="save"]').on('click', function (event) {
				i = 1;
				pakkelabels_price_rows = jQuery('.pakkelabels_tr:visible');
				jQuery('.pakkelabels_input_maximum:visible, .pakkelabels_input_minimum:visible, .pakkelabels_input_shipping_price:visible').each(function () {
					if (!isNumeric(jQuery(this).val())) {
						jQuery(this).parent().parent().addClass('error-range-row')
					}
					if (jQuery(this).parent().parent().hasClass('error-range-row')) {
						event.preventDefault();
						return;
					}
				})


				oShippingRows = '{"oRows":[';
				jQuery(pakkelabels_price_rows).each(function () {
					minimum_range = jQuery(this).find('.pakkelabels_td_minimum > input').val();
					maximum_range = jQuery(this).find('.pakkelabels_td_maximum > input').val();
					shipping_price_range = jQuery(this).find('.pakkelabels_td_shipping_price > input').val();
					oShippingRows += '{"minimum": "' + minimum_range + '", "maximum": "' + maximum_range + '", "shipping_price": "' + shipping_price_range + '"}';
					if (pakkelabels_price_rows.length > i) {
						oShippingRows += ',';
					}
					i = i + 1;
				})
				oShippingRows += ']}';

				var data = {
					'sRangeType': jQuery('.differentiated_price_type').val(),
					'sShippingSection': jQuery.urlParam('section'),
					'oShippingRows': oShippingRows,
				};

				jQuery('.hidden_post_field').val(JSON.stringify(data))
			})
		}
	}) // End on doc ready




	jQuery.urlParam = function(name){
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
		if (results==null){
			return null;
		}
		else{
			return results[1] || 0;
		}
	}


	function isNumeric(n)
	{
		return !isNaN(parseFloat(n)) && isFinite(n);
	}


	function RowToTablehtml(minimum, maximum, shipping_price, symbol)
	{
		var range_row_html =
			'<tr class="pakkelabels_tr">' +
				'<td class="pakkelabels_td_minimum">' +
					'<input class="pakkelabels_input_minimum" type="text" placeholder="'+symbol+'" value="'+minimum+'" >' +
				'</td>' +
				'<td clas="pakkelabels_unit_td_wrapper">' +
					'<div class="pakkelabels_unit_wrapper">' +
						'<div class="pakkelabels_minimum_sign_wrapper"><</div><div class="pakkelabels_cart_total_wrapper">' + sCartTotalTranslation + '</div><div class="pakkelabels_maximum_sign_wrapper"><=</div>' +
					'</div>' +
				'</td>' +

				'<td class="pakkelabels_td_maximum">' +
					'<input class="pakkelabels_input_maximum" type="text" placeholder="'+symbol+'" value="'+maximum+'">' +
				'</td>' +
				'<td class="pakkelabels_td_shipping_price">' +
					'<input class="pakkelabels_input_shipping_price" type="text" placeholder="'+sShippingPriceTranslation+'" value="'+shipping_price+'">' +
				'</td>' +
				'<td class="pakkelabels_td_delete_row">' +
					'<input class="button-secondary button-delete-row" value="x" type="button">' +
				'</td>' +
			'</tr>';
		return range_row_html;
	}

	function addHtmlAfterTarget(target, html_class, symbol)
	{
		if(html_class == "Price")
		{
			th_text = sPriceTranslation;
		}
		else
		{
			th_text = sWeightTranslation;
		}

		var tr_html =

			'<tr style="display: none;" class="' + html_class + '_tr" valign="top">' +

						   '<th></th>' +
							'<td class="' + html_class + '_td">' +
								//'<div>' + sTitleTranslation + ' ' +th_text+'</div>' +
								'<div class="'+html_class+'_div">' +
									'<table class="'+html_class+'_pakkelabels_table pakkelabels_table">' +
										'<tr>' +
											'<th>' +
												sMinimumTranslation + ' ' + th_text + ' (' + symbol + ')' +
											'</th>' +
											'<th>' +
											'</th>' +
											'<th>' +
												sMaximumTranslation + ' ' + th_text + ' (' + symbol + ')' +
											'</th>' +
											'<th>' +
												sShippingPriceTranslation +
											'</th>' +
										'</tr>' +
										RowToTablehtml("", "", "", symbol) +
									'</table>' +
									'<div class="save_and_addnew_wrapper">' +
										'<input class="pakkelabels_range_type" type="hidden" value="' + html_class + '" ></input>' +
										'<input class="button-secondary button-add-new-price-range-row" value="'+ sBtnAddNewPriceRangeRowTranslation +'" type="button">' +
									'</div>'+
								'</div>' +
							'</td>' +
						'</tr>';
		jQuery(tr_html).insertAfter(jQuery(target).parents().eq(2));
		//      jQuery('.' + sRangeType + '_div > table > tbody').append()
		jQuery('.'+html_class+'_td').prepend('<div class="pakkelabels_range_helper_text">' + sShippingRangeHelperTextTranslation + '</div>')
	}
}