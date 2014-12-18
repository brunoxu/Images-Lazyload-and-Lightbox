<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.0.0
 * @package    Lazyload_Lightbox
 * @subpackage Lazyload_Lightbox/includes
 * @author     Your Name <email@example.com>
 */
class Lazyload_Lightbox_Uninstaller {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {
		delete_option('lazyload_lightbox_effects');
		delete_option('lazyload_lightbox_lazyload');
		delete_option('lazyload_lightbox_lightbox');
		delete_option('lazyload_lightbox_html');
		delete_option('lazyload_lightbox_all_effects');
	}

}
