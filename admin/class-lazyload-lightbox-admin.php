<?php

require_once dirname( __FILE__ ) . '/../includes/class.settings-api.php';

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Lazyload_Lightbox
 * @subpackage Lazyload_Lightbox/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Lazyload_Lightbox
 * @subpackage Lazyload_Lightbox/admin
 * @author     Your Name <email@example.com>
 */
class Lazyload_Lightbox_Admin {

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

    private $settings_api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $lazyload_lightbox       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $lazyload_lightbox, $version ) {

		$this->lazyload_lightbox = $lazyload_lightbox;
		$this->version = $version;

		$this->settings_api = new WeDevs_Settings_API;
		add_action( 'admin_init', array($this, 'admin_init') );
		add_action( 'admin_menu', array($this, 'admin_menu') );

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lazyload_Lightbox_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lazyload_Lightbox_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->lazyload_lightbox, plugin_dir_url( __FILE__ ) . 'css/lazyload-lightbox-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lazyload_Lightbox_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lazyload_Lightbox_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->lazyload_lightbox, plugin_dir_url( __FILE__ ) . 'js/lazyload-lightbox-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function admin_menu() {
		add_options_page( 'Settings API', 'Settings API', 'delete_posts', 'settings_api_test', array($this, 'plugin_page') );
	}

	public function admin_init() {
		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );

		//initialize settings
		$this->settings_api->admin_init();
	}

	function get_settings_sections() {
		$sections = array(
			array(
				'id' => 'wedevs_basics',
				'title' => __( 'Basic Settings', 'wedevs' )
			),
			array(
				'id' => 'wedevs_advanced',
				'title' => __( 'Advanced Settings', 'wedevs' )
			),
			array(
				'id' => 'wedevs_others',
				'title' => __( 'Other Settings', 'wpuf' )
			)
		);
		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	function get_settings_fields() {
		$settings_fields = array(
			'wedevs_basics' => array(
				array(
					'name' => 'text_val',
					'label' => __( 'Text Input (integer validation)', 'wedevs' ),
					'desc' => __( 'Text input description', 'wedevs' ),
					'type' => 'text',
					'default' => 'Title',
					'sanitize_callback' => 'intval'
				),
				array(
					'name' => 'textarea',
					'label' => __( 'Textarea Input', 'wedevs' ),
					'desc' => __( 'Textarea description', 'wedevs' ),
					'type' => 'textarea'
				),
				array(
					'name' => 'checkbox',
					'label' => __( 'Checkbox', 'wedevs' ),
					'desc' => __( 'Checkbox Label', 'wedevs' ),
					'type' => 'checkbox'
				),
				array(
					'name' => 'radio',
					'label' => __( 'Radio Button', 'wedevs' ),
					'desc' => __( 'A radio button', 'wedevs' ),
					'type' => 'radio',
					'options' => array(
						'yes' => 'Yes',
						'no' => 'No'
					)
				),
				array(
					'name' => 'multicheck',
					'label' => __( 'Multile checkbox', 'wedevs' ),
					'desc' => __( 'Multi checkbox description', 'wedevs' ),
					'type' => 'multicheck',
					'options' => array(
						'one' => 'One',
						'two' => 'Two',
						'three' => 'Three',
						'four' => 'Four'
					)
				),
				array(
					'name' => 'selectbox',
					'label' => __( 'A Dropdown', 'wedevs' ),
					'desc' => __( 'Dropdown description', 'wedevs' ),
					'type' => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => 'Yes',
						'no' => 'No'
					)
				),
				array(
					'name' => 'password',
					'label' => __( 'Password', 'wedevs' ),
					'desc' => __( 'Password description', 'wedevs' ),
					'type' => 'password',
					'default' => ''
				),
				array(
					'name' => 'file',
					'label' => __( 'File', 'wedevs' ),
					'desc' => __( 'File description', 'wedevs' ),
					'type' => 'file',
					'default' => ''
				)
			),
			'wedevs_advanced' => array(
				array(
					'name' => 'color',
					'label' => __( 'Color', 'wedevs' ),
					'desc' => __( 'Color description', 'wedevs' ),
					'type' => 'color',
					'default' => ''
				),
				array(
					'name' => 'password',
					'label' => __( 'Password', 'wedevs' ),
					'desc' => __( 'Password description', 'wedevs' ),
					'type' => 'password',
					'default' => ''
				),
				array(
					'name' => 'wysiwyg',
					'label' => __( 'Advanced Editor', 'wedevs' ),
					'desc' => __( 'WP_Editor description', 'wedevs' ),
					'type' => 'wysiwyg',
					'default' => ''
				),
				array(
					'name' => 'multicheck',
					'label' => __( 'Multile checkbox', 'wedevs' ),
					'desc' => __( 'Multi checkbox description', 'wedevs' ),
					'type' => 'multicheck',
					'default' => array('one' => 'one', 'four' => 'four'),
					'options' => array(
						'one' => 'One',
						'two' => 'Two',
						'three' => 'Three',
						'four' => 'Four'
					)
				),
				array(
					'name' => 'selectbox',
					'label' => __( 'A Dropdown', 'wedevs' ),
					'desc' => __( 'Dropdown description', 'wedevs' ),
					'type' => 'select',
					'options' => array(
						'yes' => 'Yes',
						'no' => 'No'
					)
				),
				array(
					'name' => 'password',
					'label' => __( 'Password', 'wedevs' ),
					'desc' => __( 'Password description', 'wedevs' ),
					'type' => 'password',
					'default' => ''
				),
				array(
					'name' => 'file',
					'label' => __( 'File', 'wedevs' ),
					'desc' => __( 'File description', 'wedevs' ),
					'type' => 'file',
					'default' => ''
				)
			),
			'wedevs_others' => array(
				array(
					'name' => 'text',
					'label' => __( 'Text Input', 'wedevs' ),
					'desc' => __( 'Text input description', 'wedevs' ),
					'type' => 'text',
					'default' => 'Title'
				),
				array(
					'name' => 'textarea',
					'label' => __( 'Textarea Input', 'wedevs' ),
					'desc' => __( 'Textarea description', 'wedevs' ),
					'type' => 'textarea'
				),
				array(
					'name' => 'checkbox',
					'label' => __( 'Checkbox', 'wedevs' ),
					'desc' => __( 'Checkbox Label', 'wedevs' ),
					'type' => 'checkbox'
				),
				array(
					'name' => 'radio',
					'label' => __( 'Radio Button', 'wedevs' ),
					'desc' => __( 'A radio button', 'wedevs' ),
					'type' => 'radio',
					'options' => array(
						'yes' => 'Yes',
						'no' => 'No'
					)
				),
				array(
					'name' => 'multicheck',
					'label' => __( 'Multile checkbox', 'wedevs' ),
					'desc' => __( 'Multi checkbox description', 'wedevs' ),
					'type' => 'multicheck',
					'options' => array(
						'one' => 'One',
						'two' => 'Two',
						'three' => 'Three',
						'four' => 'Four'
					)
				),
				array(
					'name' => 'selectbox',
					'label' => __( 'A Dropdown', 'wedevs' ),
					'desc' => __( 'Dropdown description', 'wedevs' ),
					'type' => 'select',
					'options' => array(
						'yes' => 'Yes',
						'no' => 'No'
					)
				),
				array(
					'name' => 'password',
					'label' => __( 'Password', 'wedevs' ),
					'desc' => __( 'Password description', 'wedevs' ),
					'type' => 'password',
					'default' => ''
				),
				array(
					'name' => 'file',
					'label' => __( 'File', 'wedevs' ),
					'desc' => __( 'File description', 'wedevs' ),
					'type' => 'file',
					'default' => ''
				),
				array(
					'name' => 'custom_filed',
					'label' => __( 'custom_filed', 'wedevs' ),
					'desc' => '',
					'type' => 'html',
					'default' => '<div>This is a custom filed: <input type="text" name="jsdfjkdsf" /></div>'
				)
			)
		);

		return $settings_fields;
	}

	function plugin_page() {
		echo '<div class="wrap"><h2>'.__('Images Lazyload and Lightbox Setting', 'wedevs').'</h2>';

		echo '
<div class="clear" style="padding-top:10px;"></div>
<p><strong>Plugin Version :</strong> 1.0</p>
<p><strong>Plugin Homepage :</strong> <a href="http://www.brunoxu.com/images-lazyload-and-slideshow.html" target="_blank">http://www.brunoxu.com/images-lazyload-and-slideshow.html</a></p>
<div class="clear" style="padding-top:10px;"></div>
';
		
		$this->settings_api->show_navigation();
		$this->settings_api->show_forms();

		echo '</div>';
	}

	/**
	 * Get all the pages
	 *
	 * @return array page names with key value pairs
	 */
	function get_pages() {
		$pages = get_pages();
		$pages_options = array();
		if ( $pages ) {
			foreach ($pages as $page) {
				$pages_options[$page->ID] = $page->post_title;
			}
		}

		return $pages_options;
	}

}
