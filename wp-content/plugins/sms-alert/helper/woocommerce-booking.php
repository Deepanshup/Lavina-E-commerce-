<?php

if (! defined( 'ABSPATH' )) exit;

class SmsAlertWcBooking
{
		
		public function __construct() {
			require_once WP_PLUGIN_DIR.'/woocommerce-bookings/includes/wc-bookings-functions.php';
			
		}
		
		/*
			display woocommerce booking variable at smsalert setting page
		*/	
	   public static function getWCBookingvariables()
	   {			
			 $variables = array(
							'[order_id]' 		=> 'Order Id',
							'[store_name]' 		=> 'Store Name',
							'[booking_id]' 		=> 'Booking Id',
							'[booking_status]' 	=> 'Booking status',
							'[product_name]' 	=> 'Product Name',
							'[booking_cost]' 	=> 'Booking Amt',
							'[booking_start]' 	=> 'Booking Start',
							'[booking_end]' 	=> 'Booking End',
							'[first_name]' 		=> 'Billing First Name',
							'[last_name]' 		=> 'Billing Last Name',
							'[booking_persons]' => 'Person Counts',
							'[resource_name]' 	=> 'Resource Name',
			);
			$ret_string = '';
			foreach($variables as $vk => $vv)
			{
				$ret_string .= sprintf( "<a href='#' val='%s'>%s</a> | " , $vk , __($vv,SmsAlertConstants::TEXT_DOMAIN));
			}
			return $ret_string;
	   }
		
		/*get woocommerce booking status*/
		public static  function get_booking_statuses() {
			$status = get_wc_booking_statuses( 'user', true );		
			return array_keys($status);
		}
		
		
	/*trigger sms when woocommerce booking status is changed*/	
	public static function triggerSms( $booking_id ) 
	{
		if ( $booking_id ) {
			
			// Only send the booking email for booking post types, not orders, etc
			if ( 'wc_booking' !== get_post_type( $booking_id ) ) {
				return;
			}
			
			$object = get_wc_booking( $booking_id );
			
			if ( ! is_object( $object )) {
				return;
			}
			
			$booking_status			= $object->status;
			$admin_message			= smsalert_get_option( 'wcbk_admin_sms_body_'.$booking_status, 'smsalert_wcbk_message', '');
			$is_enabled				= smsalert_get_option( 'wcbk_order_status_'.$booking_status, 'smsalert_wcbk_general');
			$admin_phone_number     = smsalert_get_option( 'sms_admin_phone', 'smsalert_message', '' );	
			$buyer_mob 				= $product_name = $order_id = $booking_amt= $first_name= $last_name='';
			$bookings				= get_post_custom($booking_id);
			$booking_start			= date('M d,Y H:i',strtotime(array_shift($bookings['_booking_start'])));
			$booking_end			= date('M d,Y H:i',strtotime(array_shift($bookings['_booking_end'])));
			$person_counts 			= $object->get_persons_total();
			$resource_name 			= ($object->get_resource()) ? $object->get_resource()->post_title : '';
			$booking_amt			= array_shift($bookings['_booking_cost']);
			$order  				= $object->get_order()->get_data();
			$buyer_mob 				= $order['billing']['phone'];
			$first_name 			= $order['billing']['first_name'];
			$last_name 				= $order['billing']['last_name'];
			
			if ( $object->get_product() ) {
				$product_name = $object->get_product()->get_title();
			}
			
			if ( $object->get_order() ) {
				$order_id = $object->get_order()->get_order_number();
			}
			
			$variables = array(
							'[order_id]' 		=> $order_id,
							'[booking_id]' 		=> $booking_id,
							'[booking_status]' 	=> $booking_status,
							'[product_name]' 	=> $product_name,
							'[booking_cost]' 	=> $booking_amt,
							'[booking_start]' 	=> $booking_start,
							'[booking_end]' 	=> $booking_end,
							'[first_name]' 		=> $first_name,
							'[last_name]' 		=> $last_name,
							'[store_name]' 		=> get_bloginfo(),
							'[booking_persons]' => $person_counts,
							'[resource_name]' 	=> $resource_name,
			);
			
			if($buyer_mob!='' && $is_enabled=='on')
			{
				$buyer_message=smsalert_get_option( 'wcbk_sms_body_'.$booking_status, 'smsalert_wcbk_message', '');
				$content = str_replace(array_keys($variables), array_values($variables), $buyer_message);
				$buyer_sms_data['number']   = $buyer_mob;
				$buyer_sms_data['sms_body'] =  $content;
				$buyer_response  = SmsAlertcURLOTP::sendsms($buyer_sms_data);
			}
			
			if(smsalert_get_option( 'wcbk_admin_notification_'.$booking_status, 'smsalert_wcbk_general') == 'on' && $admin_phone_number!='')
			{
				$admin_message = smsalert_get_option( 'wcbk_admin_sms_body_'.$booking_status, 'smsalert_wcbk_message', '');
				$content = str_replace(array_keys($variables), array_values($variables), $admin_message);
				$admin_sms_data['number']   = $admin_phone_number;
				$admin_sms_data['sms_body'] =  $content;
				$admin_response  = SmsAlertcURLOTP::sendsms($admin_sms_data);
			}
		}
	}
}
new SmsAlertWcBooking;
