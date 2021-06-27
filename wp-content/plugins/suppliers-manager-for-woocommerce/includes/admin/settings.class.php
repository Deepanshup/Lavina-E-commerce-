<?php

if (!class_exists('FT_SMFW_settings')) {
    class FT_SMFW_settings
    {
        // +-------------------+
		// | CLASS CONSTRUCTOR |
		// +-------------------+

		public function __construct() {
            add_action( 'admin_init', array($this, 'register_plugin_settings'));
        }

        // +---------------+
        // | CLASS METHODS |
        // +---------------+

        /**
         * Register settings
         */
        public function register_plugin_settings()
        {
            register_setting(FT_SMFW_POST_TYPE . '-settings-group', 'ft_smfw_alert_stock_min');
        }

        public function settings_page_callback()
        {
            ?>
            <div class="wrap tf_smfw tf_smfw_settings">
                <h1><?php _e("Settings", FT_SMFW_TEXT_DOMAIN); ?></h1>

                <form method="post" action="options.php">
                    <?php settings_fields(FT_SMFW_POST_TYPE . '-settings-group'); ?>
                    <?php do_settings_sections(FT_SMFW_POST_TYPE . '-settings-group'); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php _e("Low stock alert (nb of items)", FT_SMFW_TEXT_DOMAIN); ?></th>
                            <td>
                                <input type="text" name="ft_smfw_alert_stock_min" value="<?php echo esc_attr( get_option('ft_smfw_alert_stock_min')); ?>" />
                            </td>
                        </tr>
                    </table>

                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }
	}
}

// Launch plugin
new FT_SMFW_settings();
