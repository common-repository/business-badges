<?php
/*
Author: Qualisure
Author URI: http://qualisure.es/
*/

class BusinessBadgesAdmin {
    private static $initiated = false;
    private static $notices = array();
    /**
     * text_domain
     *
     * (default value: 'business-badges')
     *
     * @var string
     * @access private
     * @static
     */
    private static $text_domain = 'business-badges';
    private static $settings_page = 'business-badges-settings';

    public static function init() {
        if ( ! self::$initiated ) {
            self::BB_init_hooks();
        }


    }

    public static function BB_init_hooks() {
        self::$initiated = true;

        add_action( 'BB_admin_init', array( 'BB_Admin', 'BB_admin_init' ) );
        add_action( 'BB_admin_menu', array( 'BB_Admin', 'BB_admin_menu' ) ); # Priority 5, so it's called before Jetpack's admin_menu.
    }



    public static function BB_admin_init() {
        load_plugin_textdomain( 'BusinessBadgesAdmin' );
        add_meta_box( 'BusinessBadges-status', __('Comment History', 'BusinessBadges'), array( 'BusinessBadgesAdmin', 'comment_status_meta_box' ), 'comment', 'normal' );
    }

    public static function BB_admin_menu() {
        ?>
        <div class="wrap">
            <h2>Plugin con parte de administración</h2>
            <h3>Manejar opciones de administración</h3>
            <form method="post" action="options.php">
                <?php settings_fields( 'BB_group_admin' ); ?>
                <?php do_settings_sections( 'BB_group_admin' ); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Título</th>
                        <td><input type="text" name="BB_admin_title" value="<?php echo get_option('BB_admin_title'); ?>" /></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Número de elementos</th>
                        <td><input type="number" name="BB_admin_number" value="<?php echo get_option('BB_admin_number'); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
    <?php
    }







    static function BB_include_admin_scripts() {

        // CSS

        wp_register_style('wpsite_follow_us_settings_css', WPSITE_FOLLOW_US_PLUGIN_URL . '/admin/css/settings.css');
        wp_enqueue_style('wpsite_follow_us_settings_css');

        wp_register_style('nnr_bootstrap_css', WPSITE_FOLLOW_US_PLUGIN_URL . '/admin/css/nnr-bootstrap.min.css');
        wp_enqueue_style('nnr_bootstrap_css');

        wp_register_style('wpsite_follow_us_sortables_css', WPSITE_FOLLOW_US_PLUGIN_URL . '/admin/css/sortables.css');
        wp_enqueue_style('wpsite_follow_us_sortables_css');

        wp_register_style('wpsite_follow_us_fontawesome', WPSITE_FOLLOW_US_PLUGIN_URL . '/admin/fonts/font-awesome.min.css');
        wp_enqueue_style('wpsite_follow_us_fontawesome');

        // Scripts

        wp_enqueue_script(self::$prefix . 'bootstrap_js', WPSITE_FOLLOW_US_PLUGIN_URL . '/admin/js/bootstrap.js', array('jquery'));
        wp_enqueue_script(self::$prefix . 'admin_js', WPSITE_FOLLOW_US_PLUGIN_URL . '/admin/js/admin.js', array('jquery'));
    }

    static function BB_admin_settings() {


    }

    static function add_menu_page(){
        $settings_page_load = add_submenu_page(
            'options-general.php', 										// parent slug
            __('Business Badges', self::$text_domain), 				// Page title
            __('Business Badges', self::$text_domain), 				// Menu name
            'manage_options', 											// Capabilities
            self::$settings_page, 										// slug
            array('BusinessBadgesAdmin', 'BB_admin_settings')	// Callback function
        );
        add_action("admin_print_scripts-$settings_page_load", array('BusinessBadgesAdmin', 'BB_include_admin_scripts'));

    }

}