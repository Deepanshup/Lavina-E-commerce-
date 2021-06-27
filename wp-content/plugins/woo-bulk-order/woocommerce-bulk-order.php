<?php
/*
 * Plugin Name: Woocommerce Bulk Order
 * Author: WooExtend
 * Author URI: https://www.wooextend.com
 * Version: 2.0
 * Requires at least: 4.0
 * Tested up to: 5.2
 * Description: "Woocommerce Bulk Order" allows your customers to order multiple products on single page.
 * Text Domain: woo-bulk-order
 * Domain Path: /languages/
 */

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
    require_once('wbo-menu.php');
    require_once('wbo-admin.php');    

    /*
     * This function will setup activation.
     * Date: 29-11-2018
     * Author: Vidish Purohit
     */
    function wbo_plugin_activate() {
	    
	    $my_post = array(
		  'post_title'    => __("Order Now", 'woo-bulk-order'),
		  'post_content'  => '[wbo_woo_bulk_order]',
		  'post_status'   => 'publish',
		  'post_type'	  => 'page',
		  'post_author'   => 1,
		);
		 
		// Insert the post into the database
		$page_id = wp_insert_post( $my_post );

		// Save page
		update_option( 'wbo_order_now_page', $page_id);

		wp_update_nav_menu_item($menu_id, 0, 
	      	array(  
	      		'menu-item-title' => __("Order Now", 'woo-bulk-order'),
	            'menu-item-object' => 'page',
	            'menu-item-object-id' => $page_id,
	            'menu-item-type' => 'post_type',
	            'menu-item-status' => 'publish',
	            'menu-item-parent-id' => 0
         	)
	  	);
	}
	register_activation_hook( __FILE__, 'wbo_plugin_activate' );

	/*
	 * This function will remove cron schedule for discounts when plugin is deactivated.
	 * Date: 29-11-2018
	 * Author: Vidish Purohit
	 */
	function wbo_deactivate() {

	    wp_delete_post( get_option( 'wbo_order_now_page' ));
	    delete_option( 'wbo_order_now_page' );
	}
	register_deactivation_hook( __FILE__, 'wbo_deactivate' );

	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wbo_action_links' );
	function wbo_action_links( $links ) {
	   	
	   	$links[] = '<a href="https://www.wooextend.com/woocommerce-expert">Woocommerce Expert</a>';
	   	$links[] = '<a href="https://www.wooextend.com/about-me">Vidish Purohit</a>';

	   	return $links;
	}
}

?>