<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://tidyhive.com
 * @since             1.0.0
 * @package           Thfp
 *
 * @wordpress-plugin
 * Plugin Name:       Tidyhive Featured Posts
 * Plugin URI:        https://tidyhive.com
 * Description:       Set featured posts with single click, no page refresh from admin panel.
 * Version:           1.5.0
 * Author:            Mohit Chawla
 * Author URI:        https://tidyhive.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       thfp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-thfp-activator.php
 */
function activate_thfp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-thfp-activator.php';
	Thfp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-thfp-deactivator.php
 */
function deactivate_thfp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-thfp-deactivator.php';
	Thfp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_thfp' );
register_deactivation_hook( __FILE__, 'deactivate_thfp' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-thfp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_thfp() {

	$plugin = new Thfp();
	$plugin->run();

}
run_thfp();
