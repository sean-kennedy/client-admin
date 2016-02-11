<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://seankennedy.com.au/
 * @since      1.0.0
 *
 * @package    Client_Admin
 * @subpackage Client_Admin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Client_Admin
 * @subpackage Client_Admin/admin
 * @author     Sean Kennedy <sean@seankennedy.com.au>
 */
 
class Client_Admin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'client_admin';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Ajax function for updating user meta
	 *
	 * @since  1.0.0
	 */
	public function ajax_update_user_meta() {
		
		$show_advanced = get_user_meta(get_current_user_id(), $this->option_name . '_show_advanced_settings', true);
		
		if ($show_advanced) {
			$new_value = 0;
		} else {
			$new_value = 1;
		}
	
		update_user_meta(get_current_user_id(), $this->option_name . '_show_advanced_settings', $new_value);

	}
	
	public function add_advanced_settings_body_class( $classes ) {
		
		$show_advanced = get_user_meta(get_current_user_id(), $this->option_name . '_show_advanced_settings', true);
		
		if ($show_advanced) {
			$classes .= $this->option_name . '_show_advanced_settings';
		} else {
			$classes .= $this->option_name . '_hide_advanced_settings';
		}
		
		return $classes;
		
	}
	
	/**
	 * Add advanced settings field to user profile
	 *
	 * @since  1.0.0
	 */
	public function add_advanced_settings_field_to_user_profile( $profile ) {
		
		$show_advanced = get_user_meta($profile->ID, $this->option_name . '_show_advanced_settings', true);
		
		?>
		<tr>
			<th scope="row">Advanced Settings</th>
			<td>
				<label for="<?php echo $this->option_name . '_show_advanced_settings'; ?>">
					<input type="checkbox" name="<?php echo $this->option_name . '_show_advanced_settings'; ?>" id="<?php echo $this->option_name . '_show_advanced_settings'; ?>" value="1" <?php checked( $show_advanced, '1', true ); ?>>
					<?php _e( 'Show advanced settings in the admin area', 'client-admin' ); ?>
				</label>
			</td>
		</tr><?php
		
	}
	
	/**
	 * Advanced settings field update function
	 *
	 * @since  1.0.0
	 */
	public function advanced_settings_field_to_user_profile_update() {
		
		$user_id = get_current_user_id();
		
		if (isset($_POST[$this->option_name . '_show_advanced_settings'])) {
			update_user_meta($user_id, $this->option_name . '_show_advanced_settings', 1);
		} else {
			update_user_meta($user_id, $this->option_name . '_show_advanced_settings', 0);
		}
		
	}
	
	/**
	 * Add a Menu button to the main siebar navigation
	 *
	 * @since  1.0.0
	 */
	public function add_menu_button() {
	
		add_menu_page('Menus', 'Menus', 'manage_options', 'nav-menus.php', '', 'dashicons-menu', 58);

	}
	
	/**
	 * Add a toggle button to the User dropdown menu
	 *
	 * @since  1.0.0
	 */
	public function add_toggle_button() {
		
		global $wp_admin_bar;
		
		$wp_admin_bar->add_menu(array(
			'id' => $this->option_name . '_advanced_settings_toggle',
			'parent' => 'user-actions',
			'title' => __('Toggle Advanced Settings'),
			'href' => admin_url('profile.php?advanced_settings=toggle'),
			'meta' => false
		));
	
	}
	
	/**
	 * New user default settings
	 *
	 * @since  1.0.0
	 */
	public function new_user_defaults( $user_id ) {
		
		// Admin bar
		update_user_meta($user_id, 'show_admin_bar_front', 0);
		
		// Welcome Panel
		update_user_meta($user_id, 'show_welcome_panel', 0);
		
		// Admin Color
		update_user_meta($user_id, 'admin_color', 'midnight');
		
		// Per page counts
		update_user_meta($user_id, 'upload_per_page', 200);
		update_user_meta($user_id, 'edit_page_per_page', 200);
		update_user_meta($user_id, 'edit_post_per_page', 200);
		
		// Yoast SEO
		update_user_meta($user_id, 'wpseo_ignore_tour', 1);
		
		// Menus metabox screen options
		update_user_option($user_id, 'metaboxhidden_nav-menus', array('add-post-type-post', 'add-custom-links', 'add-post_format', 'add-category', 'add-post_tag'));
		
	}
	
	/**
	 * Hide login logo
	 *
	 * @since  1.0.0
	 */
	public function hide_login_logo() {
		echo '<style> #login h1 { display: none; } </style>';
	}
	
	/**
	 * Front end admin bar CSS styles
	 *
	 * @since  1.0.0
	 */
	public function front_end_admin_bar_css() {
		
		if (is_user_logged_in()) {
			echo '<style>
				#wp-admin-bar-wp-logo,
				#wp-admin-bar-wpseo-menu,
				#wp-admin-bar-itsec_admin_bar_menu {
					display: none;
				}
			</style>';
		}
		
	}
	
	/**
	 * Clean up dashboard boxes
	 *
	 * @since  1.0.0
	 */
	public function dashboard_cleanup() {
		
	    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
	    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
	    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
	    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
	    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
	    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
	    remove_meta_box('dashboard_primary', 'dashboard', 'side');
	    remove_meta_box('dashboard_secondary', 'dashboard', 'side');
	    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
	    remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');
	    remove_meta_box('mandrill_widget', 'dashboard', 'normal');
	    
	}
	
	/**
	 * Force expanded toolbar for tinymce
	 *
	 * @since  1.0.0
	 */
	public function tinymce_force_toolbar_toggle($args) {
		
		$args['wordpress_adv_hidden'] = false;
		
		return $args;
		
	}
	
	/**
	 * Add custom admin footer text
	 *
	 * @since  1.0.0
	 */
    public function custom_admin_footer_text() {
	    
	    $footer_text = get_option( $this->option_name . '_footer_text' );
	    
    	echo $footer_text;
    	
    }

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {
	
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Client Admin Settings', 'client-admin' ),
			__( 'Client Admin', 'client-admin' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	
	}
	
	/**
	 * Render the options page
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		
		include_once 'partials/client-admin-admin-display.php';
		
	}
	
	/**
	 * Register all related settings of this plugin
	 *
	 * @since  1.0.0
	 */
	public function register_setting() {
		
		add_settings_section(
			$this->option_name . '_general',
			false,
			false,
			$this->plugin_name
		);
		
		add_settings_field(
			$this->option_name . '_footer_text',
			__( 'Admin Footer Text', 'client-admin' ),
			array( $this, $this->option_name . '_footer_text_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_footer_text' )
		);
		
		add_settings_field(
			$this->option_name . '_custom_css',
			__( 'Custom Admin CSS', 'client-admin' ),
			array( $this, $this->option_name . '_custom_css_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_custom_css' )
		);
		
		register_setting( $this->plugin_name, $this->option_name . '_footer_text' );
		register_setting( $this->plugin_name, $this->option_name . '_custom_css' );
		
	}
	
	/**
	 * Render the footer text input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function client_admin_footer_text_cb() {
		
		$footer_text = get_option( $this->option_name . '_footer_text' );
		
		echo '<input type="text" name="' . $this->option_name . '_footer_text' . '" id="' . $this->option_name . '_footer_text' . '" value="' . esc_html($footer_text) . '">';
		
	}
	
	/**
	 * Render the custom css textarea input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function client_admin_custom_css_cb() {
		
		$custom_css = get_option( $this->option_name . '_custom_css' );
		
		echo '<textarea name="' . $this->option_name . '_custom_css' . '" id="' . $this->option_name . '_custom_css' . '">' . esc_html($custom_css) . '</textarea>';
		
	}
	
	/**
	 * Output custom CSS from settings page to admin
	 *
	 * @since  1.0.0
	 */
	public function output_custom_css() {
		
		$custom_css = get_option( $this->option_name . '_custom_css' );
		
		if ($custom_css) {
			
			echo '<style>' . $custom_css . '</style>';
			
		}
		
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name . '_admin', plugin_dir_url( __FILE__ ) . 'css/client-admin-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'advanced_admin', plugin_dir_url( __FILE__ ) . 'css/client-admin-advanced-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/client-admin-admin.js', array( 'jquery' ), $this->version, true );

	}

}
