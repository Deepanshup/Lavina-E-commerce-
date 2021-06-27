<?php

if (!class_exists('FT_SMFW_editor')) {
    class FT_SMFW_editor
    {
        // +-------------------+
		// | CLASS CONSTRUCTOR |
		// +-------------------+

		public function __construct()
		{
            $this->init_hooks(); // Init hooks in Wordpress
            $this->init_filters(); // Init filters in Wordpress
		}

        // +---------------+
        // | CLASS METHODS |
        // +---------------+

    	/**
    	 * Initiate plugin hooks in Wordpress
    	 */
        public function init_hooks()
        {
            // add_action('admin_init', array($this, 'admin_init'));
            add_action('save_post_' . FT_SMFW_POST_TYPE, array($this, 'save_post'), 10, 3);
            add_action('admin_menu', array($this, 'add_options_page'));
            add_action('add_meta_boxes', array($this, 'add_metaboxes'));
        }

    	/**
    	 * Initiate plugin filters in Wordpress
    	 */
        public function init_filters()
        {
            add_filter('enter_title_here', array($this, 'change_enter_title_here'));
            add_filter('gettext', array($this, 'change_publish_button'), 10, 2);
        }

        /**
         * Function used when the admin is initiated
         */
        function add_metaboxes()
        {
            global $post;

            // Supplier informations
            add_meta_box('ft_smfw_post_informations',  __('Supplier informations', FT_SMFW_TEXT_DOMAIN), array($this, 'post_informations_metabox'), FT_SMFW_POST_TYPE, 'normal', 'low');

            if ("publish" == $post->post_status) {
                // Supplier linked products
                add_meta_box('ft_smfw_wc_products_linked', __('Supplier products', FT_SMFW_TEXT_DOMAIN), array($this, 'products_linked_metabox'), FT_SMFW_POST_TYPE, 'side', 'low');

                // Add supplier menu
                add_action('edit_form_top', array($this, 'display_supplier_menu'));
            }
        }

        /**
         * Display supplier menu on editor pages (supplier and supplier order)
         */
        public function display_supplier_menu($post)
        {
            switch ($post->post_type) {
                case FT_SMFW_POST_TYPE:
                    FT_SMFW_editor::print_supplier_menu($post->ID, 'informations');
                    break;
            }
        }

        /**
         * Change title field placeholder in editor
         */
        function change_enter_title_here($title)
        {
            $screen = get_current_screen();

            if (FT_SMFW_POST_TYPE == $screen->post_type) {
                $title = __('Supplier name', FT_SMFW_TEXT_DOMAIN);
            }

            return $title;
        }

        /**
         * Change title field placeholder in editor
         */
        function change_publish_button($translation, $text)
        {
            if ('Publish' == $text) return __('Save', FT_SMFW_TEXT_DOMAIN);
            return $translation;
        }

        /**
         * Show informations metabox in the editor
         */
        function post_informations_metabox($post)
        {
            $supplier = new FT_SMFW_Supplier($post->ID);

            ?>
            <div class="informations-form-fields">
                <div class="form-field">
                    <label for="ft_smfw_business_name_field"><?php _e('Business name', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                        type="text"
                        id="ft_smfw_business_name_field"
                        name="ft_smfw_business_name"
                        value="<?php _e($supplier->getBusinessName()); ?>"
                        placeholder="<?php _e('Business name', FT_SMFW_TEXT_DOMAIN); ?>"
                        />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_website_field"><?php _e('Website', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                    type="text"
                    id="ft_smfw_website_field"
                    name="ft_smfw_website"
                    value="<?php _e($supplier->getWebsite()); ?>"
                    placeholder="<?php _e('Website', FT_SMFW_TEXT_DOMAIN); ?>"
                    />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_email_field"><?php _e('Email', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                    type="text"
                    id="ft_smfw_email_field"
                    name="ft_smfw_email"
                    value="<?php _e($supplier->getEmail()); ?>"
                    placeholder="<?php _e('Email', FT_SMFW_TEXT_DOMAIN); ?>"
                    />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_phone_field"><?php _e('Phone', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                    type="text"
                    id="ft_smfw_phone_field"
                    name="ft_smfw_phone"
                    value="<?php _e($supplier->getPhone()); ?>"
                    placeholder="<?php _e('Phone', FT_SMFW_TEXT_DOMAIN); ?>"
                    />
                </div>

                <div class="form-title">
                    <h4><?php _e('Direct contact', FT_SMFW_TEXT_DOMAIN); ?></h4>
                </div>
                <div class="form-field">
                    <label for="ft_smfw_direct_name_field"><?php _e('Contact name', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                        type="text"
                        id="ft_smfw_direct_name_field"
                        name="ft_smfw_direct_name"
                        value="<?php _e($supplier->getDirectName()); ?>"
                        placeholder="<?php _e('Contact name', FT_SMFW_TEXT_DOMAIN); ?>"
                        />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_direct_email_field"><?php _e('Email', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                    type="text"
                    id="ft_smfw_direct_email_field"
                    name="ft_smfw_direct_email"
                    value="<?php _e($supplier->getDirectEmail()); ?>"
                    placeholder="<?php _e('Email', FT_SMFW_TEXT_DOMAIN); ?>"
                    />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_direct_phone_field"><?php _e('Phone', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                    type="text"
                    id="ft_smfw_direct_phone_field"
                    name="ft_smfw_direct_phone"
                    value="<?php _e($supplier->getDirectPhone()); ?>"
                    placeholder="<?php _e('Phone', FT_SMFW_TEXT_DOMAIN); ?>"
                    />
                </div>

                <div class="form-title">
                    <h4><?php _e('Address', FT_SMFW_TEXT_DOMAIN); ?></h4>
                </div>
                <div class="form-field">
                    <label for="ft_smfw_street_field"><?php _e('Street', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                        type="text"
                        id="ft_smfw_street_field"
                        name="ft_smfw_street"
                        value="<?php _e($supplier->getStreet()); ?>"
                        placeholder="<?php _e('Street', FT_SMFW_TEXT_DOMAIN); ?>"
                        />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_zipcode_field"><?php _e('Zipcode', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                        type="text"
                        id="ft_smfw_zipcode_field"
                        name="ft_smfw_zipcode"
                        value="<?php _e($supplier->getZipcode()); ?>"
                        placeholder="<?php _e('Zipcode', FT_SMFW_TEXT_DOMAIN); ?>"
                        />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_city_field"><?php _e('City', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                        type="text"
                        id="ft_smfw_city_field"
                        name="ft_smfw_city"
                        value="<?php _e($supplier->getCity()); ?>"
                        placeholder="<?php _e('City', FT_SMFW_TEXT_DOMAIN); ?>"
                        />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_state_field"><?php _e('State', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                        type="text"
                        id="ft_smfw_state_field"
                        name="ft_smfw_state"
                        value="<?php _e($supplier->getState()); ?>"
                        placeholder="<?php _e('State', FT_SMFW_TEXT_DOMAIN); ?>"
                        />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_country_field"><?php _e('Country', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                        type="text"
                        id="ft_smfw_country_field"
                        name="ft_smfw_country"
                        value="<?php _e($supplier->getCountry()); ?>"
                        placeholder="<?php _e('Country', FT_SMFW_TEXT_DOMAIN); ?>"
                        />
                </div>

                <div class="form-title">
                    <h4><?php _e('More', FT_SMFW_TEXT_DOMAIN); ?></h4>
                </div>
                <div class="form-field">
                    <label for="ft_smfw_delivery_time_field"><?php _e('Delivery time', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                        type="text"
                        id="ft_smfw_delivery_time_field"
                        name="ft_smfw_delivery_time"
                        value="<?php _e($supplier->getDeliveryTime()); ?>"
                        placeholder="<?php _e('Delivery time', FT_SMFW_TEXT_DOMAIN); ?>"
                        />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_free_delivery_field"><?php _e('Free delivery', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <input
                        type="text"
                        id="ft_smfw_free_delivery_field"
                        name="ft_smfw_free_delivery"
                        value="<?php _e($supplier->getFreeDelivery()); ?>"
                        placeholder="<?php _e('Free delivery', FT_SMFW_TEXT_DOMAIN); ?>"
                        />
                </div>
                <div class="form-field">
                    <label for="ft_smfw_comment_field"><?php _e('Comment', FT_SMFW_TEXT_DOMAIN); ?></label>
                    <textarea
                        id="ft_smfw_comment_field"
                        name="ft_smfw_comment"
                        placeholder="<?php _e('Comment', FT_SMFW_TEXT_DOMAIN); ?>"><?php _e($supplier->getComment()); ?></textarea>
                </div>
            </div>
            <?php
        }

        /**
         * Hook called when a post is saved
         */
        function save_post($post_id)
        {
        	// If this is just a revision, don't continue
        	if (wp_is_post_revision($post_id)) return;

            // Update datas
            $supplier = new FT_SMFW_Supplier($post_id);

            if (isset($_POST['ft_smfw_business_name'])) {
                $supplier->setBusinessName(sanitize_text_field($_POST['ft_smfw_business_name']));
            }
            if (isset($_POST['ft_smfw_website'])) {
                $supplier->setWebsite(sanitize_text_field($_POST['ft_smfw_website']));
            }
            if (isset($_POST['ft_smfw_email'])) {
                $supplier->setEmail(sanitize_text_field($_POST['ft_smfw_email']));
            }
            if (isset($_POST['ft_smfw_phone'])) {
                $supplier->setPhone(sanitize_text_field($_POST['ft_smfw_phone']));
            }
            if (isset($_POST['ft_smfw_direct_name'])) {
                $supplier->setDirectName(sanitize_text_field($_POST['ft_smfw_direct_name']));
            }
            if (isset($_POST['ft_smfw_direct_email'])) {
                $supplier->setDirectEmail(sanitize_text_field($_POST['ft_smfw_direct_email']));
            }
            if (isset($_POST['ft_smfw_direct_phone'])) {
                $supplier->setDirectPhone(sanitize_text_field($_POST['ft_smfw_direct_phone']));
            }
            if (isset($_POST['ft_smfw_street'])) {
                $supplier->setStreet(sanitize_text_field($_POST['ft_smfw_street']));
            }
            if (isset($_POST['ft_smfw_zipcode'])) {
                $supplier->setZipcode(sanitize_text_field($_POST['ft_smfw_zipcode']));
            }
            if (isset($_POST['ft_smfw_city'])) {
                $supplier->setCity(sanitize_text_field($_POST['ft_smfw_city']));
            }
            if (isset($_POST['ft_smfw_state'])) {
                $supplier->setState(sanitize_text_field($_POST['ft_smfw_state']));
            }
            if (isset($_POST['ft_smfw_country'])) {
                $supplier->setCountry(sanitize_text_field($_POST['ft_smfw_country']));
            }
            if (isset($_POST['ft_smfw_delivery_time'])) {
                $supplier->setDeliveryTime(sanitize_text_field($_POST['ft_smfw_delivery_time']));
            }
            if (isset($_POST['ft_smfw_free_delivery'])) {
                $supplier->setFreeDelivery(sanitize_text_field($_POST['ft_smfw_free_delivery']));
            }
            if (isset($_POST['ft_smfw_comment'])) {
                $supplier->setComment(sanitize_text_field($_POST['ft_smfw_comment']));
            }

            $supplier->save();
	    }

        /**
         * Show products metabox in the editor
         */
        function products_linked_metabox($post)
        {
            $supplier = new FT_SMFW_Supplier($post->ID);

            // Get informations about supplier's products
            $products = $supplier->getProducts();
            $low_stock_products = $supplier->getLowStockProducts();

            // Create URL to show supplier's products page
            $url = add_query_arg(
                array('post_id' => $post->ID),
                menu_page_url(FT_SMFW_POST_TYPE . '_supplier_products', false)
            );
            ?>

            <div class="ft_smfw_wc_products_linked">
                <div class="stats">
                    <div class="nb_products">
                        <p class="number"><?php echo count($products); ?></p>
                        <p class="label"><?php echo _n("product", "products", count($products), FT_SMFW_TEXT_DOMAIN); ?></p>
                    </div>
                    <div class="nb_low_stocks">
                        <p class="number"><?php echo count($low_stock_products); ?></p>
                        <p class="label"><?php echo _n("low stock product", "low stock products", count($low_stock_products), FT_SMFW_TEXT_DOMAIN); ?></p>
                    </div>
                </div>
                <a href="<?php echo esc_url($url); ?>" class="button-secondary"><?php _e("Show products", FT_SMFW_TEXT_DOMAIN); ?></a>
            </div>
            <?php
        }

        /**
         * Add subpages (products, orders, etc.)
         */
        public function add_options_page()
        {
            // Supplier products subpage
            add_submenu_page(
                null,
                "",
                "",
                'manage_options',
                FT_SMFW_POST_TYPE . '_supplier_products',
                array($this, 'render_supplier_products')
            );
        }

        /**
         * Supplier product subpage content
         */
        public function render_supplier_products()
        {
            $post_id = $_REQUEST['post_id'];

            if ($post_id && get_post_type($post_id)) {
                include_once(FT_SMFW_ABSPATH . 'includes/views/supplier-products.php');
            } else {
                echo "bug";
            }
        }

        /**
         * Return supplier menu tabs
         * (used in PRO plugin to add "Orders" tab)
         */
        public static function get_tabs($post_id)
        {
            $tabs = array();

            $supplier = new FT_SMFW_Supplier($post_id);
            $products = $supplier->getProducts();
            $nb_products = count($products);

            $products_url = add_query_arg(
                array('post_id' => $supplier->getId()),
                menu_page_url(FT_SMFW_POST_TYPE . '_supplier_products', false)
            );

            $tabs['informations'] = [
                'label' => __("Supplier informations", FT_SMFW_TEXT_DOMAIN),
                'url' => $supplier->getEditPermalink(),
            ];

            $tabs['products'] = [
                'label' => sprintf(_n("%s product", "%s products", $nb_products, FT_SMFW_TEXT_DOMAIN), $nb_products),
                'url' => $products_url,
            ];

            $tabs = apply_filters(FT_SMFW_POST_TYPE . '_get_tabs_array', $tabs, $post_id);

            return $tabs;
        }

        /**
         * Print supplier menu
         */
        public static function print_supplier_menu($post_id, $current = 'informations')
        {
            $tabs = FT_SMFW_editor::get_tabs($post_id);
            ?>
            <div class="smfw_supplier_menu">
                <ul>
                    <?php foreach($tabs as $index => $tab) :
                        if ($current == $index) :
                            ?>
                            <li class="current">
                                <a href="<?php echo esc_url($tab['url']); ?>"><?php echo $tab['label']; ?></a>
                            </li>
                        <?php else : ?>
                            <li>
                                <a href="<?php echo esc_url($tab['url']); ?>"><?php echo $tab['label']; ?></a>
                            </li>
                        <?php
                        endif;
                    endforeach; ?>
                </ul>
            </div>
            <?php
        }
	}
}

// Launch plugin
new FT_SMFW_editor();
