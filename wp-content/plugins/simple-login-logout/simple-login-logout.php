<?php
/*
Plugin Name: Simple Login Logout
Plugin URI: https://ethioliquid.com/simple-login-logout/
Description: Simple plugin to automatically add Login and Logout link to navigation menu with WordPress nounce.
Version: 1.1.1
Author: Yahya Mohammed
Author URI: https://ethioliquid.com
*/


/* function starts here */

add_filter('wp_nav_menu_items','add_login_logout_link', 10, 2);

function add_login_logout_link ($items, $args) {
		  ob_start();
		  wp_loginout('index.php');
		  $loginoutlink = ob_get_contents();
		  ob_end_clean();
		  $items .= '<li>'. $loginoutlink .'</li>';
		  return $items;
		}
  
/* function ends here */
?>