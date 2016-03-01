<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://seankennedy.com.au/
 * @since      1.0.0
 *
 * @package    Client_Admin
 * @subpackage Client_Admin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Client_Admin
 * @subpackage Client_Admin/includes
 * @author     Sean Kennedy <sean@seankennedy.com.au>
 */
class Client_Admin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Client_Admin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'client-admin';
		$this->version = '1.1.3';

		$this->load_dependencies();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Client_Admin_Loader. Orchestrates the hooks of the plugin.
	 * - Client_Admin_i18n. Defines internationalization functionality.
	 * - Client_Admin_Admin. Defines all hooks for the admin area.
	 * - Client_Admin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-client-admin-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-client-admin-admin.php';

		$this->loader = new Client_Admin_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Client_Admin_Admin( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_button' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_setting' );
		
		$this->loader->add_action( 'wp_ajax_client_admin_toggle_advanced_setting', $plugin_admin, 'ajax_update_user_meta' );
		$this->loader->add_action( 'wp_ajax_nopriv_client_admin_toggle_advanced_setting', $plugin_admin, 'ajax_update_user_meta' );
		
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'output_custom_css' );
		$this->loader->add_action( 'admin_body_class', $plugin_admin, 'add_advanced_settings_body_class' );
		$this->loader->add_action( 'personal_options', $plugin_admin, 'add_advanced_settings_field_to_user_profile' );
		$this->loader->add_action( 'personal_options_update', $plugin_admin, 'advanced_settings_field_to_user_profile_update' );
		$this->loader->add_action( 'edit_user_profile_update', $plugin_admin, 'advanced_settings_field_to_user_profile_update' );
		
		$this->loader->add_action( 'wp_before_admin_bar_render', $plugin_admin, 'add_toggle_button' );
		$this->loader->add_action( 'user_register', $plugin_admin, 'new_user_defaults' );
		$this->loader->add_action( 'login_head', $plugin_admin, 'hide_login_logo' );
		$this->loader->add_action( 'wp_head', $plugin_admin, 'front_end_admin_bar_css' );
		$this->loader->add_action( 'tiny_mce_before_init', $plugin_admin, 'tinymce_force_toolbar_toggle' );
		$this->loader->add_action( 'wp_dashboard_setup', $plugin_admin, 'dashboard_cleanup' );
		$this->loader->add_action( 'admin_footer_text', $plugin_admin, 'custom_admin_footer_text' );
		
		$this->loader->add_action( 'plugin_action_links_client-admin/client-admin.php', $plugin_admin, 'add_plugin_action_links' );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Client_Admin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
