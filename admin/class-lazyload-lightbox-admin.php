<?php

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
    private $msgs;

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
		
		if ($_POST) {
			$this->post_request();
		}

		require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/class.settings-api.php';
		$this->settings_api = new WeDevs_Settings_API;
		add_action( 'admin_init', array($this, 'admin_init') );
		add_action( 'admin_menu', array($this, 'admin_menu') );

		add_action( 'wp_ajax_update_all_effects', array($this,'update_all_effects') );

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
		add_options_page( 'Lazy Load & Lightbox', 'Lazy Load & Lightbox', 'delete_posts', 'lazyload-lightbox', array($this, 'plugin_page') );
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
				'id' => 'lazyload_lightbox_lazyload',
				'title' => __( 'Lazy Load Settings', 'lightbox-lazyload' ),
				'init_value' => array(
					"lazyload" => "1",
					"lazyload_all" => "1",
					'lazyload_image_strict_match' => '0',
					"use_footer_or_head" => "wp_footer",
					"icon" => "1",
				)
			),
			array(
				'id' => 'lazyload_lightbox_lightbox',
				'title' => __( 'Lightbox Settings', 'lightbox-lazyload' ),
				'init_value' => array(
					"lightbox" => '0',
					"applications" => array(),
				)
			),
			array(
				'id' => 'lazyload_lightbox_html',
				'title' => __( 'Custom HTML', 'lightbox-lazyload' ),
				'init_value' => array(
					'html' => '',
				)
			),
			array(
				'id' => 'lazyload_lightbox_lightbox_effects',
				'title' => __( 'Manage Lightbox Effects', 'lightbox-lazyload' ),
				'callback' => array($this, 'lightbox_effects_page'),
			),
			array(
				'id' => 'lazyload_lightbox_about',
				'title' => __( 'About', 'lightbox-lazyload' ),
				'html' => '<strong>Images Lazyload and Lightbox</strong> is used to lazy load images and add lightbox effects to images.',
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
			'lazyload_lightbox_lazyload' => array(
				array(
					'name' => 'lazyload',
					'label' => __( 'Use Lazy Load', 'lightbox-lazyload' ),
					'desc' => '',
					'type' => 'select',
					'default' => '1',
					'options' => array(
						'1' => __( 'Yes', 'lightbox-lazyload' ),
						'0' => __( 'No', 'lightbox-lazyload' )
					)
				),
				array(
					'name' => 'lazyload_all',
					'label' => __( 'Lazy Load All Images?', 'lightbox-lazyload' ),
					'desc' => '',
					'type' => 'select',
					'default' => '1',
					'options' => array(
						'1' => __( 'Yes', 'lightbox-lazyload' ),
						'0' => __( 'No', 'lightbox-lazyload' )
					)
				),
				array(
					'name' => 'lazyload_image_strict_match',
					'label' => __( 'Image Strict Match?', 'lightbox-lazyload' ),
					'desc' => '',
					'type' => 'select',
					'default' => '0',
					'options' => array(
						'1' => __( 'only images with "bmp|gif|jpeg|jpg|png" suffixes', 'lightbox-lazyload' ),
						'0' => __( 'all images', 'lightbox-lazyload' )
					)
				),
				array(
					'name' => 'use_footer_or_head',
					'label' => __( 'Action to Hook', 'lightbox-lazyload' ),
					'desc' => '',
					'type' => 'select',
					'default' => 'wp_footer',
					'options' => array(
						'wp_footer' => 'wp_footer',
						'wp_head' => 'wp_head'
					)
				),
				array(
					'name' => 'icon',
					'label' => __( 'Select an icon', 'lightbox-lazyload' ),
					'desc' => '',
					'type' => 'radio',
					'default' => $this->get_default_lazyload_icon_val(),
					'options' => $this->get_lazyload_icons()
				)
			),
			'lazyload_lightbox_lightbox' => array(
				array(
					'name' => 'lightbox',
					'label' => __( 'Use Lightbox', 'lightbox-lazyload' ),
					'desc' => '',
					'type' => 'select',
					'default' => '0',
					'options' => array(
						'1' => __( 'Yes', 'lightbox-lazyload' ),
						'0' => __( 'No', 'lightbox-lazyload' )
					)
				),
				array(
					'name' => 'applications',
					'label' => __( 'Applications', 'lightbox-lazyload' ),
					'desc' => '',
					'type' => 'html',
					'default' => $this->generate_field_applications(),
				)
			),
			'lazyload_lightbox_html' => array(
				array(
					'name' => 'html',
					'label' => __( 'Custom HTML', 'lightbox-lazyload' ),
					'desc' => '',
					'type' => 'html',
					'default' => $this->generate_field_html(),
				)
			)
		);

		return $settings_fields;
	}

	function plugin_page() {
		echo '<div class="wrap"><h2>'.__(LAZYLOAD_LIGHTBOX_PLUGIN_NAME.' Dashboard', 'lightbox-lazyload').'</h2>';

		echo '
<div class="clear" style="padding-top:10px;"></div>
<strong>'.__('Version', 'lightbox-lazyload').'</strong>: '.LAZYLOAD_LIGHTBOX_PLUGIN_VERSION.'
 &nbsp;&nbsp;|&nbsp;&nbsp; <strong>Author</strong>: <a href="http://www.brunoxu.com/" target="_blank">Bruno Xu</a>
 &nbsp;&nbsp;|&nbsp;&nbsp; <a href="'.LAZYLOAD_LIGHTBOX_PLUGIN_HOMEPAGE.'" target="_blank">'.__('Plugin Homepage', 'lightbox-lazyload').'</a>
 &nbsp;&nbsp;|&nbsp;&nbsp; <a href="'.LAZYLOAD_LIGHTBOX_PLUGIN_HOMEPAGE.'" target="_blank">'.__('Donate', 'lightbox-lazyload').'</a>
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

	function lightbox_effects_page() {
		$default_settings = apply_filters($this->lazyload_lightbox.'-get_default_settings', null);
		$configs = apply_filters($this->lazyload_lightbox.'-get_configs', null);
		$effects = apply_filters($this->lazyload_lightbox.'-get_effects', null);
		$all_effects = apply_filters($this->lazyload_lightbox.'-get_all_effects', null);
		$effects_checked = Effect_Tool::check_updates_for_effects();
		?>
<div class="inside">
	<p>
		<input type="button" class="button" name="op_check_updates" value="Check Updates" />
		<span class="op_check_updates_msg"></span>
	</p>
	<script type="text/javascript">
	jQuery(function($){
		var op_check_updates_show_error = function(str) {
			$('.op_check_updates_msg').addClass('error').html(str);
			$('input[name=op_check_updates]').removeAttr('disabled');
		}
		var op_check_updates_show_info = function(str) {
			$('.op_check_updates_msg').addClass('info').html(str);
			$('input[name=op_check_updates]').removeAttr('disabled');
		}
		var remote_url_effects = "<?php echo $default_settings['remote_url_effects']; ?>";
		$('input[name=op_check_updates]').click(function(){
			$(this).attr('disabled','disabled');
			$('.op_check_updates_msg').removeClass('error info').html('');
			$.getScript(remote_url_effects,function(){
				if ("object" == typeof available_effects) {
					$.ajax({
						type: "POST",
						url: ajaxurl,
						dataType: 'json',
						data: {
							'action': 'update_all_effects',
							'available_effects': available_effects
						},
						success: function(data){
							if (data && data.result) {
								op_check_updates_show_info(data.msg);
								if (data.refresh) {
									document.location.reload();
								}
							} else {
								op_check_updates_show_error('checking failed');
							}
						},
						error: function(data){
							op_check_updates_show_error('checking failed');
						}
					});
				} else {
					op_check_updates_show_error('unable to connect to remote data');
				}
			});
		});
	});
	</script>

<style>
.inline-form{display:inline;}
.effects-table tbody tr:nth-child(2n){background:#F1EEEE}
</style>

	<?php if ($effects_checked): ?>
	<table class="widefat effects-table">
		<thead>
		<tr>
			<th>Effect</th>
			<th>Status</th>
			<th>Operate</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($effects_checked as $effect): ?>
		<tr>
			<td><?php echo $effect['name'].' (v'.$effect['package_version'].')'
 			.(empty($effect['description'])?'':(' - '.$effect['description'])); ?></td>
			<td><?php echo $effect['status']; ?></td>
			<td><?php echo $effect['operate']; ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	There's no available effects yet.
	<?php endif; ?>

	<form method="post" enctype="multipart/form-data" class="wp-upload-form">
		<div class="clear" style="padding-top:10px;"></div>
		<hr/>
		<?php /*<p>If you has an external effect, you can install and use it immediatly.</p>*/ ?>
		<h4>Install an effect in .zip format</h4>
		<p class="install-help">If you have an effect in a .zip format, you may install it by uploading it here.</p>
		<p>
			<input type="file" name="effect_to_add" id="effect_to_add" />
			<input type="submit" name="op_upload_effects" id="op_upload_effects" class="button" value="Install Now" disabled="" />
		</p>
	</form>

	<form method="post">
		<div class="clear" style="padding-top:10px;"></div>
		<hr/>
		<h4>If you uploaded effects via ftp, please make sure clicked "Reload Effects" button to use them.</h4>
		<p>
			<input type="submit" class="button" name="op_reload_effects" value="Reload Effects" onclick="return confirm('Sure to reload effects?')" />
		</p>
	</form>

	<?php /*if ($effects): ?>
	<form method="post">
		<div class="clear" style="padding-top:10px;"></div>
		<hr/>
		<h4>Delete effects, be careful!</h4>
		<p>
			<select name="effect_to_delete">
				<?php foreach($effects as $effect_name=>$effect): ?>
				<option value="<?php echo htmlspecialchars($effect_name); ?>">
				<?php echo $effect['name']; ?>
				</option>
				<?php endforeach; ?>
			</select>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" class="button" name="op_delete_effect" value="Delete Effect" onclick="return confirm('Sure to delete this effect?')" />
		</p>
	</form>
	<?php endif;*/ ?>
</div>
		<?php
	}

	function generate_field_applications() {
		$configs = apply_filters($this->lazyload_lightbox.'-get_configs', null);
		$effects = apply_filters($this->lazyload_lightbox.'-get_effects', null);
		$default_settings = apply_filters($this->lazyload_lightbox.'-get_default_settings', null);

		$app_list_str = '';
		if ($effects) {
			if (!empty($configs['lightbox']['applications'])) {
				$index = 0;
				foreach ($configs['lightbox']['applications'] as $application) {
					$app_list_str .= $this->get_app_info_box($application['scope'], $application['selector'], $application['effect'], $application['adapter'], $index++);
				}
			}
			if (empty($configs['lightbox']['applications']) || empty($app_list_str)) {
				$curr_scope = key($default_settings['scopes']);
				$curr_selector = $default_settings['default_lightbox_selector'];
				$curr_effect = key($effects);
				$curr_adapter = key($effects[$curr_effect]['adapters']);
				$index = 0;
				$app_list_str .= $this->get_app_info_box($curr_scope, $curr_selector, $curr_effect, $curr_adapter, $index);
			}
		}

		return '
<div class="lightboxapps clear">
	'.$app_list_str.'
</div>
<div class="lightboxapp_addbtn clear">
	<a class="button">Add a new application</a>
</div>
<style>
.lightboxapps{}
.lightboxapps ul{
float:left;
padding: 10px;
border: 1px solid #CCC2C2;
margin-bottom: 10px;
margin-right: 10px;
width: 40%;
}
.lightboxapps input[type=text]{width: 80%;}
.lightboxapps ul:first-child .column5 .button{visibility:hidden;}
</style>
<script type="text/javascript">
var effects = '.json_encode($effects).';
function onChangeEffect(domEffect){
	selected_effect = domEffect.value;
	_domEffect = jQuery(domEffect);
	_domAdapter = _domEffect.parent().next().find("select");
	_domAdapter.html("");
	for (var i in effects) {
		if (effects[i]["name"] == selected_effect) {
			var options_str = "";
		 	for (var j in effects[i]["adapters"]) {
		 		var adapter = effects[i]["adapters"][j];
		 		options_str += "<option value=\""+adapter["name"]+"\">"+adapter["name"]+" "+adapter["version"]+"</option>";
		 		_domAdapter.html(options_str);
			}
		}
	}
}
function onRemoveApplication(domEffect){
	_domEffect = jQuery(domEffect);
	_domEffect.parent().parent().remove();
}
jQuery(function($){
	var apps_count = 1;
	$(".lightboxapp_addbtn .button").click(function(){
		var html = $(".lightboxapps ul:first").html();
	 	var regexp = new RegExp("\\\\[applications\\\\]\\\\["+(apps_count-1)+"\\\\]","gi");
		html = html.replace(regexp,"[applications]["+apps_count+"]");
	 	html = "<ul>"+html+"</ul>";
		$(".lightboxapps").append(html);
		apps_count++;
		//$(".lightboxapps ul:last").find(".column3 select").trigger("change");
	});
});
</script>
';
	}

	function get_app_info_box($curr_scope, $curr_selector, $curr_effect, $curr_adapter, $index) {
		$effects = apply_filters($this->lazyload_lightbox.'-get_effects', null);
		$default_settings = apply_filters($this->lazyload_lightbox.'-get_default_settings', null);

		if (!isset($effects[$curr_effect])) {
			return '';
		}

		$scopes_options_str = '';
		foreach ($default_settings['scopes'] as $sval=>$scope) {
			$scopes_options_str .= '<option value="'.$sval.'"'
				.($sval==$curr_scope?' selected="selected"':'').'>'
				.$scope.'</option>';
		}

		$effects_options_str = '';
		$adapters_options_str = '';
		foreach ($effects as $effect_name=>$effect) {
			$effects_options_str .= '<option value="'.$effect['name'].'"'
				.($effect_name==$curr_effect?' selected="selected"':'').'>'
				.$effect['name'].'(package version:'.$effect['package_version'].')</option>';
		}
		foreach ($effects[$curr_effect]['adapters'] as $adapter_name=>$adapter) {
			$adapters_options_str .= '<option value="'.$adapter['name'].'"'
				.($adapter_name==$curr_adapter?' selected="selected"':'').'>'
				.$adapter['name'].'(adapter version:'.$adapter['version'].')</option>';
		}

		return '
<ul>
	<li class="column1">
 		<label>Select a scope: </label>
		<select name="lazyload_lightbox_lightbox[applications]['.$index.'][scope]">
			'.$scopes_options_str.'
		</select>
	</li>
	<li class="column2">
 		<label>Selector: </label>
		<input type="text" name="lazyload_lightbox_lightbox[applications]['.$index.'][selector]" value="'.$curr_selector.'">
	</li>
	<li class="column3">
 		<label>Effect: </label>
		<select name="lazyload_lightbox_lightbox[applications]['.$index.'][effect]" onchange="onChangeEffect(this)">
 			'.$effects_options_str.'
		</select>
	</li>
	<li class="column4">
 		<label>Adapter: </label>
		<select name="lazyload_lightbox_lightbox[applications]['.$index.'][adapter]">
			'.$adapters_options_str.'
		</select>
	</li>
	<li class="column5">
		<a class="button" onclick="onRemoveApplication(this)">Remove</a>
	</li>
</ul>
';
	}

	function generate_field_html() {
		$html_reference = '
<!-- cursor styles for lightbox images -->
<style type="text/css">
.lightbox_imgs{cursor:url(http://brunoxu.qiniudn.com/images%2Fcommon%2Fzoomin.cur), pointer;}
.lightbox_imgs:hover{opacity:0.5 !important;filter:alpha(opacity=50) !important;}
#fancybox-wrap{cursor:url(http://brunoxu.qiniudn.com/images%2Fcommon%2Fzoomout.cur), pointer;}
</style>

<!-- set max-width for images -->
<style type="text/css">
#content img,.content img,.archive img,.post img,.page img{
margin-top:3px;max-width:600px;
height:auto !important;
_width:expression(this.width>600?600:auto);
}
</style>

<!-- baidu tracking code -->
<div style="width:0;height:0;overflow:hidden;">
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src=\'" + _bdhmProtocol + "hm.baidu.com/h.js%3Fed87e845538b0fe86a4caf1d0018e458\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
</div>
';
		$configs = apply_filters($this->lazyload_lightbox.'-get_configs', null);
		return '
<div class="row">
<div style="float:left;width:40%">
	<textarea style="width:100%;height:280px;" class="regular-text" id="lazyload_lightbox_html[html]" name="lazyload_lightbox_html[html]">'.stripslashes($configs['html']['html']).'</textarea>
</div>
<div style="float:left;width:40%;height:280px;margin:0 0 0 15px;overflow:scroll;">
	<div class="tips"><pre style="margin-top: 0;"><b>Sample:</b>'.htmlspecialchars($html_reference).'</pre></div>
</div>
</div>';
	}

	function get_default_lazyload_icon_val() {
		$default_settings = apply_filters($this->lazyload_lightbox.'-get_default_settings', null);
		return key($default_settings['lazyload_icons']);
	}
	function get_lazyload_icons() {
		$default_settings = apply_filters($this->lazyload_lightbox.'-get_default_settings', null);
		$lazyload_icons = array();
		foreach ($default_settings['lazyload_icons'] as $lival=>$icon) {
			$lazyload_icons[$lival] = '<img src="'.LAZYLOAD_LIGHTBOX_PLUGIN_URL.$icon.'">';
		}
		return $lazyload_icons;
	}

	function update_all_effects() {
		$available_effects = $_POST['available_effects'];
		$return = Effect_Tool::update_all_effects($available_effects);
		echo json_encode($return);
		die();
	}
	
	function post_request() {
// 		$msgs = apply_filters($this->lazyload_lightbox.'-get_msgs', null);
		if (isset($_POST['op_reload_effects'])) {
			delete_option('lazyload_lightbox_effects');
			$this->msgs['success'][] = 'Reload effects successfully.';
		} elseif (isset($_POST['op_delete_effect'])) {
			if (empty($_POST['effect_to_delete'])) {
				$this->msgs['error'][] = 'Please select an effect.';
			} else {
				$effect_to_delete = $_POST['effect_to_delete'];
				$effect_to_delete_path = LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_DIR . $effect_to_delete;
				if (file_exists($effect_to_delete_path)) {
					require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/common-tool.php';
					if (Common_Tool::rmdir_recurse($effect_to_delete_path)) {
						delete_option('lazyload_lightbox_effects');
						$this->msgs['success'][] = 'Delete effect successfully.';
					} else {
						$this->msgs['error'][] = 'Delete effect failed.';
					}
				} else {
					$this->msgs['error'][] = 'Effect does not exist.';
				}
			}
		} elseif (isset($_POST['op_upload_effects'])) {
			$upload_error = '';
			if (empty($_FILES['effect_to_add'])) {
				$upload_error = 'Please select a file.';
			} else {
				$effect_to_add = $_FILES['effect_to_add'];
				if ($effect_to_add['error']) {
					$upload_error = 'Upload failed.';
				} elseif ($effect_to_add['size'] > 2*1024*1024) {
					$upload_error = 'The uploaded file exceeds the max_filesize_limitation: 2M';
				} elseif (! stristr(substr($effect_to_add['name'], -4), '.zip')) {// or ['type']=='application/x-zip-compressed'
					$upload_error = 'Please upload file in a .zip format.';
				} else {
					WP_Filesystem();
					$result = unzip_file($effect_to_add['tmp_name'], LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_DIR);
					if ($result instanceof WP_Error) {
						$upload_error = 'Unzip failed. ' . $result->get_error_message();
					}
				}
			}
			if ($upload_error) {
				$this->msgs['error'][] = $upload_error;
			} else {
				delete_option('lazyload_lightbox_effects');
				$this->msgs['success'][] = 'Effect installed successfully.';
			}
		} elseif (isset($_POST['op_upgrade_effect']) || isset($_POST['op_install_effect'])) {
			if (empty($_POST['effect']) || empty($_POST['url'])) {
				$this->msgs['error'][] = 'Post data error.';
			} else {
				$download_url = $_POST['url'];
				$effect_tar = $_POST['effect'];
				$effect_tar_path = LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_DIR . $effect_tar;
				$has_rmdir_error = 0;
				if (file_exists($effect_tar_path)) {
					require_once LAZYLOAD_LIGHTBOX_PLUGIN_DIR . 'includes/common-tool.php';
					if (!Common_Tool::rmdir_recurse($effect_to_delete_path)) {
						$this->msgs['error'][] = 'Delete effect failed.';
						$has_rmdir_error = 1;
					}
				}
				if (!$has_rmdir_error) {
					require_once ABSPATH . 'wp-admin/includes/file.php';
					$download_result = download_url($download_url, 10);
					if ($download_result instanceof WP_Error) {
						$this->msgs['error'][] = 'Download failed. ' . $download_result->get_error_message();
					} else {
						WP_Filesystem();
						$unzip_result = unzip_file($download_result, LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_DIR);
						if ($unzip_result instanceof WP_Error) {
							$this->msgs['error'][] = 'Unzip failed. ' . $result->get_error_message();
						} else {
							delete_option('lazyload_lightbox_effects');
							if (isset($_POST['op_upgrade_effect'])) {
								$this->msgs['success'][] = 'Upgrade effect successfully.';
							} else {
								$this->msgs['success'][] = 'Install effect successfully.';
							}
						}
						unlink($download_result);
					}
				}
			}
		}

	}

}
