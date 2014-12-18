<?php
/*
Adapter Name: single
Version: 1.0
Description: each image has a single popup view box, not a gallary view

Author: Bruno Xu
Author URI: http://www.brunoxu.com/
*/

if ( !class_exists( 'Slimbox2_205_Adapater_Single' ) ):
class Slimbox2_205_Adapater_Single {
	private $effect_identifier = 'slimbox2_205';

	private $include_no_link_imgs = TRUE;
	private $strict_match = TRUE;
	private $width_height_check = TRUE;

	private $used_effect;
	private $used_adapter;
	private $application;

	public function __construct($used_effect,$used_adapter,$application) {
		$this->used_effect = $used_effect;
		$this->used_adapter = $used_adapter;
		$this->application = $application;
	}

	function load_css_js() {
		wp_enqueue_style(
			$this->effect_identifier.'-css1',
			LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_URL.rawurlencode($this->used_effect["folder_name"]).'/css/slimbox2.css'
		);
		wp_enqueue_script(
			$this->effect_identifier.'-js1',
			LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_URL.rawurlencode($this->used_effect["folder_name"]).'/js/slimbox2.js'
		);
	}

	function apply() {
		$strict_match = $this->strict_match;
		$width_height_check = $this->width_height_check;
		if ($strict_match) {
			$regexp = '/.+(\.jpg)|(\.jpeg)|(\.png)|(\.gif)|(\.bmp)/i';
		} elseif ($config['effect_image_strict_match']) {
			$regexp = '/.+/';
			$width_height_check = FALSE;
		}

		print('
<!-- '.$this->used_effect["name"].' / '.$this->used_adapter["name"].' -->
<script type="text/javascript">
jQuery(function($){
$("'.$this->application["selector"].'").each(function(i){
	_self = $(this);

	'.($width_height_check?'selfWidth = _self.attr("width")?_self.attr("width"):_self.width();
	selfHeight = _self.attr("height")?_self.attr("height"):_self.height();
	if ((selfWidth && selfWidth<50) || (selfHeight && selfHeight<50)) {
		return;
	}':'').'

	if (this.parentNode.href) {
		aHref = this.parentNode.href;
		var b='.$regexp.';
		if (! b.test(aHref)) {
			return;
		}

		_self.addClass("lightbox_imgs");

		_parentA = $(this.parentNode);
		rel = _parentA.attr("rel");
		if (! rel) {
			rel = "";
		}
		if (rel.indexOf("lightbox") != 0) {
			_parentA.attr("rel","lightbox");
		}
	}'.($this->include_no_link_imgs?' else {
		imgsrc = "";
		if (_self.attr("src")) {
			imgsrc = _self.attr("src");
		}
		if (_self.attr("file")) {
			imgsrc = _self.attr("file");
		} else if (_self.attr("original")) {
			imgsrc = _self.attr("original");
		}

		if (imgsrc) {
			_self.addClass("lightbox_imgs");
			_self.wrap("<a href=\'"+imgsrc+"\' rel=\'lightbox\'></a>");
		}
	}':'').'
});
});
</script>
<!-- '.$this->used_effect["name"].' / '.$this->used_adapter["name"].' end -->
');
	}

	function apply2() {
		print('
<!-- '.$this->used_effect["name"].' / '.$this->used_adapter["name"].' -->
<script type="text/javascript">
jQuery(function($){
if (! window.'.$this->effect_identifier.') {
	if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
		$("a[rel^=\'lightbox\']").slimbox({imageFadeDuration:100,captionAnimationDuration:100,resizeDuration:100}, null, function(el) {
			return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		});
	}
	window.'.$this->effect_identifier.' = 1;
}
});
</script>
<!-- '.$this->used_effect["name"].' / '.$this->used_adapter["name"].' end -->
');
	}

}
endif;

/*
$configs  $effects  $default_settings
$used_effect  $used_adapter  $application
*/
$obj = new Slimbox2_205_Adapater_Single($used_effect,$used_adapter,$application);
add_action('wp_enqueue_scripts', array($obj,'load_css_js'));
add_action('wp_footer', array($obj,'apply'));
add_action('wp_footer', array($obj,'apply2'), 11);
