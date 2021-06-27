jQuery('.wbo_button').on( 'click', function($) {

	jQuery(this).parent().next().fadeIn(200);

	var p_id = jQuery(this).attr('data-p_id');
	var v_id = jQuery(this).attr('data-v_id');

	// get quantity

	var qty = jQuery(document).find('.wbo_qty_' + p_id).find('input').val();


	var data2 = {

		'action': 'wbo_add_to_cart',
		'p_id': p_id,
		'v_id': v_id,
		'qty': qty

	};
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations

	jQuery.post(obj.ajax_url, data2, function(response) {

		jQuery(document).find('.complete_' + p_id).fadeIn(200);

		jQuery(document).find('.loader_' + p_id).fadeOut(50);



		refresh_fragments();

		setTimeout(function() {

        	jQuery(document).find('.complete_' + p_id).fadeOut(300);

        }, 3000);

	});

});

function refresh_fragments() {

    jQuery( document.body ).trigger( 'wc_fragment_refresh' );

}

if(jQuery != undefined) {

	jQuery(document).ready(function() {

		jQuery('.wbo-options').each(function() {

			jQuery(this).select2();

			wbo_check_variation_price(jQuery(this));	
			jQuery(this).change();
		});
		
	});

} else {

	alert('You may face difficulties using this page as your theme does not use jQuery.')

}

jQuery('.wbo-options').on('change', function() {

	wbo_check_variation_price(jQuery(this));
	var sel_id = jQuery(this).attr('id');
	var new_sel_id = sel_id.replace('sel_bulk_order[', '').replace(']', '');

	var var_id = jQuery(this).val();
	jQuery('#btn_' + new_sel_id).attr('data-v_id', var_id);
});
// Update prices for selected variation
function wbo_check_variation_price(objSelect) {
	var option = jQuery('option:selected', objSelect).attr('attr-price');
	objSelect.parent().next().html(option);
}
jQuery(document).ready(function(){
	jQuery("a[rel^='prettyPhoto']").prettyPhoto();
});