<?php
/*
 * Plugin Name: Woo Product Category Discount
 * Author: WooExtend
 * Author URI: https://www.wooextend.com
 * Version: 3.0
 * Requires at least: 4.0
 * Tested up to: 5.0.2
 * Description: "Woo Product Category Discount" enables administrator to apply discount on product based on Woocommerce category product categories.
 * WC tested up to: 3.5.2
 */

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
    require_once ('cd-admin.php');

}

?>