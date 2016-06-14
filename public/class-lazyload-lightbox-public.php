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

		add_action('wp_enqueue_scripts', 'lazyload_lightbox_script');
		function lazyload_lightbox_script()
		{
			wp_enqueue_script('jquery');
		}

		$this->apply_lazyload();
		$this->apply_lightbox();
		$this->apply_html();

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

	function apply_lazyload() {
		add_action('template_redirect', 'lazyload_lightbox_enqueue_scripts');
		function lazyload_lightbox_enqueue_scripts() {
			$skip_lazyload = apply_filters('lazyload_lightbox_skip_lazyload', false);

			// don't lazyload for feeds, previews
			if( $skip_lazyload || is_feed() || is_preview() ) {
				return;
			}

			wp_enqueue_style('responsively-lazy', plugin_dir_url( __FILE__ ).'responsively-lazy/1.2.1/responsivelyLazy.min.css');
			wp_enqueue_script('responsively-lazy', plugin_dir_url( __FILE__ ).'responsively-lazy/1.2.1/responsivelyLazy.min.js');
		}

		global $configs;
		$configs = apply_filters($this->lazyload_lightbox.'-get_configs', null);
		if (!$configs['lazyload']['lazyload']) return;

		global $default_settings;
		$default_settings = apply_filters($this->lazyload_lightbox.'-get_default_settings', null);

		if ($configs['lazyload']["lazyload_all"]) {
			add_action('template_redirect','lazyload_lightbox_lazyload_obstart');
			function lazyload_lightbox_lazyload_obstart() {
				ob_start('lazyload_lightbox_lazyload_obend');
			}
			function lazyload_lightbox_lazyload_obend($content) {
				return lazyload_lightbox_lazyload_content_filter($content);
			}
		} else {
			add_filter('the_content', 'lazyload_lightbox_lazyload_content_filter');
		}
		function lazyload_lightbox_lazyload_content_filter($content)
		{
			$skip_lazyload = apply_filters('lazyload_lightbox_skip_lazyload', false);

			// don't lazyload for feeds, previews
			if( $skip_lazyload || is_feed() || is_preview() ) {
				return $content;
			}

			global $configs;

			if ($configs['lazyload']['lazyload_image_strict_match']) {
				$regexp = "/<img([^<>]*)\.(bmp|gif|jpeg|jpg|png)([^<>]*)>/i";
			} else {
				$regexp = "/<img([^<>]*)>/i";
			}

			$content = preg_replace_callback(
				$regexp,
				"lazyload_lightbox_lazyimg_str_handler",
				$content
			);

			return $content;
		}
		function lazyload_lightbox_lazyimg_str_handler($matches)
		{
			$lazyimg_str = $matches[0];

			// no need to use lazy load
			if (stripos($lazyimg_str, 'src=') === FALSE) {
				return $lazyimg_str;
			}
			if (stripos($lazyimg_str, 'skip_lazyload') !== FALSE) {
				return $lazyimg_str;
			}
			if (preg_match("/\/plugins\/wp-postratings\//i", $lazyimg_str)) {
				return $lazyimg_str;
			}

			if (preg_match("/width=/i", $lazyimg_str)
					|| preg_match("/width:/i", $lazyimg_str)
					|| preg_match("/height=/i", $lazyimg_str)
					|| preg_match("/height:/i", $lazyimg_str)) {
				$alt_image_src = LAZYLOAD_LIGHTBOX_PLUGIN_URL."assets/blank_1x1.gif";
			} else {
				if (preg_match("/\/smilies\//i", $lazyimg_str)
						|| preg_match("/\/smiles\//i", $lazyimg_str)
						|| preg_match("/\/avatar\//i", $lazyimg_str)
						|| preg_match("/\/avatars\//i", $lazyimg_str)) {
					$alt_image_src = LAZYLOAD_LIGHTBOX_PLUGIN_URL."assets/blank_1x1.gif";
				} else {
					$alt_image_src = LAZYLOAD_LIGHTBOX_PLUGIN_URL."assets/blank_250x250.gif";
				}
			}

			if (stripos($lazyimg_str, "class=") === FALSE) {
				$lazyimg_str = preg_replace(
					"/<img(.*)>/i",
					'<img class="ls_lazyimg"$1>',
					$lazyimg_str
				);
			} else {
				$lazyimg_str = preg_replace(
					"/<img(.*)class=['\"]([\w\-\s]*)['\"](.*)>/i",
					'<img$1class="$2 ls_lazyimg"$3>',
					$lazyimg_str
				);
			}

			if (stripos($lazyimg_str,'srcset=')) {
				if (!stripos($lazyimg_str,'data-srcset=')) {
					$regexp = "/<img([^<>]*)srcset=['\"]([^<>'\"]*)['\"]([^<>]*)>/i";
					$replace = '<img$1srcset="data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="$2"$3>';
					$lazyimg_str = preg_replace(
						$regexp,
						$replace,
						$lazyimg_str
					);
					$lazyimg_str = str_ireplace('ls_lazyimg','responsively-lazy',$lazyimg_str);
				}
				$lazyimg_str = str_ireplace('ls_lazyimg','',$lazyimg_str);
			} else {
				$regexp = "/<img([^<>]*)src=['\"]([^<>'\"]*)['\"]([^<>]*)>/i";
				$replace = '<img$1src="'.$alt_image_src.'" file="$2"$3><noscript>'.$matches[0].'</noscript>';
				$lazyimg_str = preg_replace(
					$regexp,
					$replace,
					$lazyimg_str
				);
			}
	
			return $lazyimg_str;
		}

		add_action($configs['lazyload']["use_footer_or_head"], 'lazyload_lightbox_lazyload_css_and_js');
		function lazyload_lightbox_lazyload_css_and_js()
		{
			global $configs, $default_settings;
			print('
<!-- '.LAZYLOAD_LIGHTBOX_PLUGIN_NAME.' '.LAZYLOAD_LIGHTBOX_PLUGIN_VERSION.' - lazyload css and js -->
<style type="text/css">
.ls_lazyimg{
opacity:0.1;filter:alpha(opacity=10);
background:url('.LAZYLOAD_LIGHTBOX_PLUGIN_URL.$default_settings['lazyload_icons'][$configs['lazyload']["icon"]].') no-repeat center center;
}
</style>

<noscript>
<style type="text/css">
.ls_lazyimg{display:none;}
</style>
</noscript>

<script type="text/javascript">
Array.prototype.S = String.fromCharCode(2);
Array.prototype.in_array = function(e) {
	var r = new RegExp(this.S+e+this.S);
	return (r.test(this.S+this.join(this.S)+this.S));
};

Array.prototype.pull=function(content){
	for(var i=0,n=0;i<this.length;i++){
		if(this[i]!=content){
			this[n++]=this[i];
		}
	}
	this.length-=1;
};

jQuery(document).ready(function($) {
$(document).bind("lazyimgs",function(){
	if (!window._lazyimgs) {
		window._lazyimgs = $("img.ls_lazyimg");
	} else {
		var _lazyimgs_new = $("img.ls_lazyimg:not([lazyloadindexed=1])");
		if (_lazyimgs_new.length > 0) {
			window._lazyimgs.add(_lazyimgs_new);
		}
	}
	window._lazyimgs.attr("lazyloadindexed", 1);
});
$(document).trigger("lazyimgs");
if (_lazyimgs.length == 0) {
	return;
}
var toload_inds = [];
var loaded_inds = [];
var failed_inds = [];
var failed_count = {};
var lazyload = function() {
	if (loaded_inds.length==_lazyimgs.length) {
		return;
	}
	var threshold = 200;
	_lazyimgs.each(function(i){
		_self = $(this);
		if ( _self.attr("lazyloadpass")===undefined && _self.attr("file")
			&& ( !_self.attr("src") || (_self.attr("src") && _self.attr("file")!=_self.attr("src")) )
			) {
			if( (_self.offset().top) < ($(window).height()+$(document).scrollTop()+threshold)
				&& (_self.offset().left) < ($(window).width()+$(document).scrollLeft()+threshold)
				&& (_self.offset().top) > ($(document).scrollTop()-threshold)
				&& (_self.offset().left) > ($(document).scrollLeft()-threshold)
				) {
				if (toload_inds.in_array(i)) {
					return;
				}
				toload_inds.push(i);
				if (failed_count["count"+i] === undefined) {
					failed_count["count"+i] = 0;
				}
				_self.css("opacity",1);
				$("<img ind=\""+i+"\"/>").bind("load", function(){
					var ind = $(this).attr("ind");
					if (loaded_inds.in_array(ind)) {
						return;
					}
					loaded_inds.push(ind);
					var _img = _lazyimgs.eq(ind);
					_img.attr("src",_img.attr("file")).css("background-image","none").attr("lazyloadpass","1");
				}).bind("error", function(){
					var ind = $(this).attr("ind");
					if (!failed_inds.in_array(ind)) {
						failed_inds.push(ind);
					}
					failed_count["count"+ind]++;
					if (failed_count["count"+ind] < 2) {
						toload_inds.pull(ind);
					}
				}).attr("src", _self.attr("file"));
			}
		}
	});
}
lazyload();
var ins;
$(window).scroll(function(){clearTimeout(ins);ins=setTimeout(lazyload,100);});
$(window).resize(function(){clearTimeout(ins);ins=setTimeout(lazyload,100);});
});

jQuery(function($) {
var calc_image_height = function(_img) {
	var width = _img.attr("width");
	var height = _img.attr("height");
	if ( !(width && height && width>=300) ) return;
	var now_width = _img.width();
	var now_height = parseInt(height * (now_width/width));
	_img.css("height", now_height);
}
var fix_images_height = function() {
	_lazyimgs.each(function() {
		calc_image_height($(this));
	});
}
fix_images_height();
$(window).resize(fix_images_height);
});
</script>
<!-- '.LAZYLOAD_LIGHTBOX_PLUGIN_NAME.' '.LAZYLOAD_LIGHTBOX_PLUGIN_VERSION.' - lazyload css and js END -->
');
		}
	}

	function apply_lightbox() {
		add_action('template_redirect', array($this,'apply_lightbox_real'));
	}
	function apply_lightbox_real() {
		$configs = apply_filters($this->lazyload_lightbox.'-get_configs', null);
		if (!$configs['lightbox']['lightbox']) return;
		if (empty($configs['lightbox']['applications'])) return;

		$effects = apply_filters($this->lazyload_lightbox.'-get_effects', null);
		$default_settings = apply_filters($this->lazyload_lightbox.'-get_default_settings', null);

		foreach ($configs['lightbox']['applications'] as $application) {
			if (!isset($default_settings['scopes'][$application['scope']])) {
				continue;
			}

			if ($application['scope']=='hm') {
				if (!is_home()) continue;
			} elseif ($application['scope']=='pp') {
				if (!is_single()) continue;
			} elseif ($application['scope']=='sp') {
				if (!is_page()) continue;
			} elseif ($application['scope']=='ca') {
				if (!is_category()) continue;
			} elseif ($application['scope']=='sh') {
				if (!is_search()) continue;
			}

			if (!empty($application['selector'])
					&& isset($effects[$application['effect']])
					&& isset($effects[$application['effect']]['adapters'][$application['adapter']])
					) {
				$used_effect = $effects[$application['effect']];
				$used_adapter = $used_effect['adapters'][$application['adapter']];
				if ($used_adapter && file_exists($used_adapter['path'])) {
					require $used_adapter['path'];
				}
			}
		}
	}

	function apply_html() {
		global $configs;
		$configs = apply_filters($this->lazyload_lightbox.'-get_configs', null);
		if (!$configs['html']['html']) return;
		
		if (stristr($configs['html']["html"], '<div')!==FALSE
				|| stristr($configs['html']["html"], '<p')!==FALSE
				|| stristr($configs['html']["html"], '<span')!==FALSE
				) {
			add_action('wp_footer', 'lazyload_lightbox_add_html');
		} else {
			add_action($configs['lazyload']['use_footer_or_head'], 'lazyload_lightbox_add_html');
		}
		function lazyload_lightbox_add_html()
		{
			global $configs;
	
			print('
<!-- '.LAZYLOAD_LIGHTBOX_PLUGIN_NAME.' '.LAZYLOAD_LIGHTBOX_PLUGIN_VERSION.' custom html -->
'.stripslashes($configs['html']['html']).'
<!-- '.LAZYLOAD_LIGHTBOX_PLUGIN_NAME.' '.LAZYLOAD_LIGHTBOX_PLUGIN_VERSION.' custom html END -->
');
		}
	}

}
