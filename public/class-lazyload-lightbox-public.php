<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Lazyload_Lightbox
 * @subpackage Lazyload_Lightbox/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Lazyload_Lightbox
 * @subpackage Lazyload_Lightbox/public
 * @author     Your Name <email@example.com>
 */
class Lazyload_Lightbox_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $lazyload_lightbox    The ID of this plugin.
	 */
	private $lazyload_lightbox;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $lazyload_lightbox       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $lazyload_lightbox, $version ) {

		$this->lazyload_lightbox = $lazyload_lightbox;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lazyload_Lightbox_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lazyload_Lightbox_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->lazyload_lightbox, plugin_dir_url( __FILE__ ) . 'css/lazyload-lightbox-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lazyload_Lightbox_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lazyload_Lightbox_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->lazyload_lightbox, plugin_dir_url( __FILE__ ) . 'js/lazyload-lightbox-public.js', array( 'jquery' ), $this->version, false );

	}

}
