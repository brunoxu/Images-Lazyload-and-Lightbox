<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Lazyload_Lightbox
 *
 * @wordpress-plugin
 * Plugin Name:       Images Lazyload and Lightbox
 * Plugin URI:        http://www.brunoxu.com/images-lazyload-and-lightbox.html
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress dashboard.
 * Version:           1.0
 * Author:            Bruno Xu
 * Author URI:        http://www.brunoxu.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lazyload-lightbox
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('LAZYLOAD_LIGHTBOX_PLUGIN_NAME', 'Images Lazyload and Lightbox');
define('LAZYLOAD_LIGHTBOX_PLUGIN_VERSION', '1.0');
define('LAZYLOAD_LIGHTBOX_PLUGIN_HOMEPAGE', 'http://www.brunoxu.com/images-lazyload-and-lightbox.html');
define('LAZYLOAD_LIGHTBOX_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('LAZYLOAD_LIGHTBOX_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_URL', LAZYLOAD_LIGHTBOX_PLUGIN_URL.'effects/');
define('LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_DIR', LAZYLOAD_LIGHTBOX_PLUGIN_DIR.'effects/');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lazyload-lightbox-activator.php
 */
function activate_lazyload_lightbox() {
	require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/class-lazyload-lightbox-activator.php';
	Lazyload_Lightbox_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lazyload-lightbox-deactivator.php
 */
function deactivate_lazyload_lightbox() {
	require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/class-lazyload-lightbox-deactivator.php';
	Lazyload_Lightbox_Deactivator::deactivate();
}

/**
 * The code that runs during plugin uninstall.
 * This action is documented in includes/class-lazyload-lightbox-uninstaller.php
 */
function uninstall_lazyload_lightbox() {
	require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/class-lazyload-lightbox-uninstaller.php';
	Lazyload_Lightbox_Uninstaller::uninstall();
}

register_activation_hook( __FILE__, 'activate_lazyload_lightbox' );
register_deactivation_hook( __FILE__, 'deactivate_lazyload_lightbox' );
register_uninstall_hook( __FILE__, 'uninstall_lazyload_lightbox' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/class-lazyload-lightbox.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lazyload_lightbox() {

	$plugin = new Lazyload_Lightbox();
	$plugin->run();

}
run_lazyload_lightbox();
