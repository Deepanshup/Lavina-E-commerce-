<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class WooExtend_WBO extends WooExtendMenu {

	function __construct() {

		// setup admin menu
		add_action( 'admin_menu', array($this, 'wbo_admin_menu' ));

		// load front end scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'wbo_load_front_scripts') );

		// Enqueue about css
		add_action( 'admin_enqueue_scripts', array( $this, 'wbo_load_admin_script' ));

		// add shortcode
		add_shortcode('wbo_woo_bulk_order', array( $this, 'wbo_woo_bulk_order_shortcode'));

		// add to cart
		add_action( 'wp_ajax_wbo_add_to_cart', array( $this, 'wbo_add_to_cart'));
		add_action( 'wp_ajax_nopriv_wbo_add_to_cart', array( $this, 'wbo_add_to_cart'));
		
	}

	public function wbo_load_admin_script() {

		wp_enqueue_style( 'wbo-about', plugin_dir_url( __FILE__ ) . 'assets/css/about.css'  );
	}

	// add to cart
	public function wbo_add_to_cart() {

		$quantity = $_REQUEST['qty'];
		$product_id = $_REQUEST['p_id'];

		if(isset($_REQUEST['v_id']) && !empty($_REQUEST['v_id'])) {
			WC()->cart->add_to_cart( $product_id, $quantity, $_REQUEST['v_id']);
		} else {
			WC()->cart->add_to_cart( $product_id, $quantity);	
		}
		die();

	}

	// load front end script
	public function wbo_load_front_scripts() {

	   	$wbo_page = get_option( 'wbo_order_now_page' );

	   	// make sure our js is loaded only on bulk order page
	   	if(!is_page($wbo_page)) {
	   		return;
	   	}

	   	// Make sure jquery is added
		if ( ! wp_script_is( 'jquery', 'done' ) ) {
	     	wp_enqueue_script( 'jquery' );
	   	}

	   	// enqueue all required js & css
	    wp_enqueue_style( 'wbo-style', plugin_dir_url( __FILE__ ) . 'assets/css/front.css'  );
	    wp_enqueue_style( 'wbo-select2', plugin_dir_url( __FILE__ ) . 'assets/css/select2.css'  );
	    wp_enqueue_style( 'wbo-prettyphoto', plugin_dir_url( __FILE__ ) . 'assets/css/prettyPhoto.css'  );
	    wp_enqueue_script( 'wbo-js', plugin_dir_url( __FILE__ ) . 'assets/js/front.js', array('jquery'), '1.0.0', true );

	    wp_enqueue_script( 'wbo-select2', plugin_dir_url( __FILE__ ) . 'assets/js/select2.js', array('jquery'), '1.0.0', true );
	    wp_enqueue_script( 'wbo-prettyphoto', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.prettyPhoto.js', array('jquery'), '1.0.0', true );

	    $translation_array = array(
			'ajax_url' => admin_url('admin-ajax.php')
		);
		wp_localize_script( 'wbo-js', 'obj', $translation_array );
	}

	// register admin menu
	public function wbo_admin_menu() {

		add_submenu_page( 'wooextend', __('Bulk Order'), __('Bulk Order'), 'manage_options', 'wooextend-bulk-order', array( $this, 'wbo_configure_setup') ); 		
	}
	

	// This function shows setup controls in admin
	public function wbo_configure_setup() {

		$this->wbo_may_be_save_data();

		$arrData = $this->wbo_get_free_data();

		if($arrData['is']) {
			echo '<div class="notice notice-success is-dismissible" style="height:30px;padding-top:10px;">You are using our FREE version of Bulk Order. Buy PRO version for ONLY <strong><del>$15</del> $12 <a href="https://www.wooextend.com/product/woocommerce-bulk-order-pro/" target="_blank">HERE</a></strong> .</div>';
		} else {
			
		}
		$sort_by = isset($arrData['sort_by']) && !empty($arrData['sort_by'])?$arrData['sort_by']:'';
		$sort_order = isset($arrData['sort_order']) && !empty($arrData['sort_order'])?$arrData['sort_order']:'';
		$ids = isset($arrData['ids']) && !empty($arrData['ids'])?$arrData['ids']:'';

		?><form method="POST">
			<h2>Bulk Order Configuration</h2>
			<h4>DO NOT UPDATE SHORTCODE MANUALLY ON BULK ORDER PAGE. YOU CAN UPDATE SETTINGS HERE.</h4>

			<table>
				<tr>
					<th style="text-align:left;">
						Sort products by
					</th>
					<td>
						<ul class="wbo-no-list">
							<li><input type="radio" name="rdo_sort_by" value="title" <?php echo $sort_by == "title"?' checked="checked"':'';?> id="rdo_sort_by_title"/><label for="rdo_sort_by_title">Product Name</label></li>
							<li><input type="radio" name="rdo_sort_by" <?php echo $sort_by == "ID"?' checked="checked"':'';?> value="ID" id="rdo_sort_by_id"/><label for="rdo_sort_by_id">ID</label></li>
							<li><input type="radio" name="rdo_sort_by" value="menu_order" <?php echo $sort_by == "menu_order"?' checked="checked"':'';?> id="rdo_sort_by_menu_order"/><label for="rdo_sort_by_menu_order">Menu Order</label></li>
							<li><input type="radio" name="rdo_sort_by" <?php echo $sort_by == "post_date"?' checked="checked"':'';?> value="post_date" id="rdo_sort_by_post_date"/><label for="rdo_sort_by_post_date">Post Date</label></li>
						</ul>
					</td>
				</tr>
				<tr>
					<th style="text-align:left;">
						Sort order
					</th>
					<td>
						<ul class="wbo-no-list">
							<li><input type="radio" name="rdo_sort_order" value="ASC" <?php echo $sort_order == "ASC"?' checked="checked"':'';?> id="rdo_sort_by_asc"/><label for="rdo_sort_by_asc">ASC</label></li>
							<li><input type="radio" name="rdo_sort_order" <?php echo $sort_order == "DESC"?' checked="checked"':'';?> value="DESC" id="rdo_sort_by_desc"/><label for="rdo_sort_by_desc">DESC</label></li>
						</ul>
					</td>
				</tr>
				<tr>
					<th style="text-align:left;">
						Product IDs 
					</th>
					<td>
						<input type="text" name="txtProductIds" value="<?php echo $ids;?>" id="txtProductIds" placeholder="Leave empty to include all products" size="30">Separate with comma for multiple products (Leave empty to include all products)
					</td>
				</tr>
				<tr><td></td>
					<td>
						<input type="submit" name="save" value="Save" class="button-primary">
					</td>
				</tr>
			</table>
		</form><?php
	}

	// front end bulk order stuff
	public function wbo_woo_bulk_order_shortcode( $atts = []) {

		global $wpdb;

		$atts = array_change_key_case((array)$atts, CASE_LOWER);

		if(isset($_REQUEST['status']))
			return;

		// Order by
		$order_by_key = isset($atts['orderby']) && !empty($atts['orderby'])?$atts['orderby']:'title';
		$order_by = 'post_title';
		if($order_by_key == 'title') {
			$order_by = 'post_title';
		} else if($order_by_key == 'id') {
			$order_by = 'ID';
		} else if($order_by_key == 'menu_order') {
			$order_by = 'menu_order';
		} else if($order_by_key == 'date') {
			$order_by = 'post_date';
		}

		// Order
		$sortorder_by_key = isset($atts['order']) && !empty($atts['order'])?$atts['order']:'ASC';
		$sortorder = 'ASC';
		if($sortorder_by_key == 'DESC') {
			$sortorder = 'DESC';
		}

		$strProd = "SELECT p.ID, p.post_title, pm.meta_value attributes
					FROM {$wpdb->prefix}posts p
					LEFT JOIN {$wpdb->prefix}postmeta pm ON (pm.post_id = p.ID AND pm.meta_key = '_product_attributes')
					WHERE p.post_type = 'product' 
					AND p.post_status = 'publish'" . 
					(isset($atts['id']) && !empty($atts['id'])?" AND p.ID IN (" . $atts['id'] . ")":'') . 
					" ORDER BY p." . $order_by . " " . $sortorder;
		$arrProd = $wpdb->get_results($strProd);

		$arrProduct = array();
		foreach ($arrProd as $key => $value) {
			
			$fold_num = rand(-1000,-100);
			if(isset($value->attributes) && !empty($value->attributes)) {
				$arrAttr = unserialize($value->attributes);
				if(isset($arrAttr['foldernummer']) && !empty($arrAttr['foldernummer'])) {
					$fold_num = $arrAttr['foldernummer']['value'];
				}
			}
			$arrProduct[$fold_num]['name'] = $value->post_title;
			$arrProduct[$fold_num]['ID'] = $value->ID;
		}
		krsort($arrProduct, SORT_NUMERIC);

		$result = '<div class="wbo_wrapper">
					<div class="wbo_row wbo_headers">
						<div class="wbo_thumb">&nbsp;</div>
						<div class="wbo_name"><strong>' . __('Product name', 'woo-bulk-order') . '</strong></div>
						<div class="wbo_price"><strong>' . __('Price', 'woo-bulk-order') . '</strong></div>
						<div class="wbo_quantity"><strong>' . __('Quantity', 'woo-bulk-order') . '</strong></div>
						<div class="wbo_add_to_cart"></div>
						<div class="wbo_loader"></div>
						<div class="wbo_completed"></div>
						<div class="clear"></div>
					</div>';

		foreach ($arrProduct as $key => $value) {
			
			$is_placeholder = false;
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $value['ID'] ), 'single-post-thumbnail' );
			if(!isset($image[0]) || empty($image)) {
				$image[0] = wc_placeholder_img_src('single-post-thumbnail');
				$is_placeholder = true;
			}
			$product = wc_get_product($value['ID']);

			$loop = 0;
			$result .= '<div class="wbo_row">
				<div class="wbo_thumb">
					<a href="'. (!$is_placeholder?$image[0]:'javascript:void(0);') . '" ' . (!$is_placeholder?' rel="prettyPhoto"':'') . '><img src="'. $image[0] . '" title="' . $value->post_title . '" style="height:80px;width:80px;"/></a>
				</div>
				<div class="wbo_name">';

				if(is_a($product, 'WC_Product_Variable')) {
					$result .= '<select id=\'sel_bulk_order[' . $value['ID'] . ']\' name=\'sel_bulk_order[' . $value['ID'] . ']\' style="width:200px;" class="wbo-options"">
					<option value="" disabled="disabled">' . __('Choose Variation', 'woo-bulk-order') . '</option>';


					$objProduct = wc_get_product($value['ID']);
					$type = $objProduct->get_type();

					if($type == 'variable') {
						
						// Get variations
						$strVariation = "SELECT post_title, ID FROM {$wpdb->prefix}posts WHERE post_type = 'product_variation' AND post_parent = '" . $value['ID'] . "'";
						$arrVariation = $wpdb->get_results($strVariation);
						foreach ($arrVariation as $keyVar => $valueVar) {
							$valueOption = $valueVar->ID;

							$objVariation = new WC_Product_Variation($valueVar->ID);

							// make sure we pass correct price html
							$price_html = str_replace("'", '"', $objVariation->get_price_html());
							$result .=  '<option value="' . $valueOption  . '" attr-price=\'' . $price_html . '\'>' . $valueVar->post_title . '</option>';
						}
					}
				$result .= '</select>';

					$price_show = wc_price($product->get_price());
				} else {
					$result .= $value['name'];

					if($product->is_on_sale()) {
						$price_show = '<del>' . wc_price($product->get_regular_price()) . '</del> ' . wc_price($product->get_price());
					} else {
						$price_show = wc_price($product->get_price());
					}
				}
				$result .= '</div>
				<div class="wbo_price">' . $price_show . '</div>
				<div class="wbo_inner_wrap">
					<div class="wbo_quantity wbo_qty_' . $value['ID'] . '">
						<input type="number" name="qty_' . $value['ID'] . '" id="qty_' . $value['ID'] . '" min="1" value="1"/>
					</div>
					<div class="wbo_add_to_cart"><input type="button" name="add-to-cart" value="' . esc_html( $product->single_add_to_cart_text()) . '" class="single_add_to_cart_button button alt wbo_button" data-v_id="' . ($product->get_type() == 'variable'?$arrVariation[0]->ID:'') . '" data-p_id="' . $value['ID'] . '" id="btn_' . $value['ID'] . '"/>
					</div>
					<div class="wbo_loader loader_' . $value['ID'] . '" style="display:none">
						<div class="row">
							<div class="col-sm-2 col-xs-4 text-center">
								<div class="three-quarters-loader"></div>
							</div>
						</div>
					</div>
					<div class="wbo_completed complete_' . $value['ID'] . '" style="display:none">
						<img src="' . plugin_dir_url( __FILE__ ) . 'assets/images/complete.png' . '" style="height:25px;width:25px;">
					</div>
				</div>
				<div class="clear"></div>
			</div>';
		}
		$result .= '</div>';
		return $result;
	}
}
new WooExtend_WBO();





