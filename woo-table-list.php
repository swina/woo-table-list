<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wp.moodgiver.com
 * @since             1.0.0
 * @package           Woo_Table_List
 *
 * @wordpress-plugin
 * Plugin Name:       Woo Table List
 * Plugin URI:        https://wp.moodgiver.com
 * Description:       Add a table list view to your Woocommerce store. Woo Table List plugin doesn't change any Woocommerce or theme template. Your template view is always available and users can switch to your template view just with a click. Customize fields output and Add to cart action directly from the list view.
 * Version:           1.0.0
 * Author:            Antonio Nardone
 * Author URI:        https://wp.moodgiver.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo_table_list
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOO_TABLE_LIST_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-table-list-activator.php
 */
function activate_woo_table_list() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-table-list-activator.php';
	Woo_Table_List_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-table-list-deactivator.php
 */
function deactivate_woo_table_list() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-table-list-deactivator.php';
	Woo_Table_List_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_table_list' );
register_deactivation_hook( __FILE__, 'deactivate_woo_table_list' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-table-list.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_table_list() {

	$plugin = new Woo_Table_List();
	$plugin->run();

}
run_woo_table_list();
