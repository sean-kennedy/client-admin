<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://seankennedy.com.au/
 * @since             1.0.0
 * @package           Client_Admin
 *
 * @wordpress-plugin
 * Plugin Name:       Client Admin
 * Plugin URI:        https://github.com/sean-kennedy/client-admin/
 * Description:       Settings and styles for a minimal Wordpress admin area for clients.
 * Version:           1.1.3
 * Author:            Sean Kennedy
 * Author URI:        https://seankennedy.com.au/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       client-admin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin update checker
 */
require 'plugin-update-checker/plugin-update-checker.php';

$plugin_updater = PucFactory::getLatestClassVersion('PucGitHubChecker');

$client_admin_update_checker = new $plugin_updater(
    'https://github.com/sean-kennedy/client-admin/',
    __FILE__,
    'master'
);

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-client-admin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_client_admin() {

	$plugin = new Client_Admin();
	$plugin->run();

}
run_client_admin();
