<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Lazyload_Lightbox
 * @subpackage Lazyload_Lightbox/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Lazyload_Lightbox
 * @subpackage Lazyload_Lightbox/includes
 * @author     Your Name <email@example.com>
 */
class Lazyload_Lightbox {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Lazyload_Lightbox_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $lazyload_lightbox    The string used to uniquely identify this plugin.
	 */
	protected $lazyload_lightbox;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $msgs = FALSE;
	protected $configs = FALSE;
	protected $effects = FALSE;
	protected $all_effects = FALSE;
	protected $default_settings = FALSE;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->lazyload_lightbox = 'lazyload-lightbox';
		$this->version = LAZYLOAD_LIGHTBOX_PLUGIN_VERSION;

		$this->load_dependencies();
		$this->set_locale();

		add_filter($this->lazyload_lightbox.'-get_msgs', array($this, 'get_msgs'));
		add_filter($this->lazyload_lightbox.'-get_configs', array($this, 'get_configs'));
		add_filter($this->lazyload_lightbox.'-get_effects', array($this, 'get_effects'));
		add_filter($this->lazyload_lightbox.'-get_all_effects', array($this, 'get_all_effects'));
		add_filter($this->lazyload_lightbox.'-get_default_settings', array($this, 'get_default_settings'));

		if (is_admin()) {
			$this->define_admin_hooks();
		} elseif (in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
			
		} else {
			$this->define_public_hooks();
		}

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Lazyload_Lightbox_Loader. Orchestrates the hooks of the plugin.
	 * - Lazyload_Lightbox_i18n. Defines internationalization functionality.
	 * - Lazyload_Lightbox_Admin. Defines all hooks for the dashboard.
	 * - Lazyload_Lightbox_Public. Defines all hooks for the public side of the site.
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
		require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/class-lazyload-lightbox-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/class-lazyload-lightbox-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
// 		require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'admin/class-lazyload-lightbox-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
// 		require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'public/class-lazyload-lightbox-public.php';

		$this->loader = new Lazyload_Lightbox_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Lazyload_Lightbox_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Lazyload_Lightbox_i18n();
		$plugin_i18n->set_domain( $this->get_lazyload_lightbox() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'admin/class-lazyload-lightbox-admin.php';

		$plugin_admin = new Lazyload_Lightbox_Admin( $this->get_lazyload_lightbox(), $this->get_version() );

// 		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
// 		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'public/class-lazyload-lightbox-public.php';

		$plugin_public = new Lazyload_Lightbox_Public( $this->get_lazyload_lightbox(), $this->get_version() );

// 		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
// 		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	public function get_lazyload_lightbox() {
		return $this->lazyload_lightbox;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Lazyload_Lightbox_Loader    Orchestrates the hooks of the plugin.
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

	public function get_msgs() {
		return $this->msgs;
	}

	public function get_configs() {
		if ($this->configs === FALSE || $this->configs === '') {
			$this->configs = array(
				'lazyload' => get_option('lazyload_lightbox_lazyload'),
				'lightbox' => get_option('lazyload_lightbox_lightbox'),
				'html' => get_option('lazyload_lightbox_html'),
			);
		}
		return $this->configs;
	}

	public function get_effects() {
		if ($this->effects === FALSE) {
			require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/effect-tool.php';
			$this->effects = Effect_Tool::get_effects();
		}
		return $this->effects;
	}

	public function get_all_effects($input) {
		if ($this->all_effects === FALSE || $input) {
			require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/effect-tool.php';
			$this->all_effects = Effect_Tool::get_all_effects();
		}
		return $this->all_effects;
	}

	public function get_default_settings() {
		if ($this->default_settings === FALSE) {
			require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/default-setting.php';
			$this->default_settings = array(
				'scopes' => Default_Setting::get_scopes(),
				'default_lightbox_selector' => Default_Setting::get_default_lightbox_selector(),
				'lazyload_icons' => Default_Setting::get_lazyload_icons(),
				'remote_url_effects' => Default_Setting::get_remote_url_effects(),
			);
		}
		return $this->default_settings;
	}

}
