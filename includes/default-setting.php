<?php

/**
 * effect tool class
 *
 * @version 1.0
 *
 * @author Bruno Xu <xuguangzhi2003@126.com>
 * @link http://www.brunoxu.com/
 * 
 */
if ( !class_exists( 'Default_Setting' ) ):
class Default_Setting {

    public static function get_remote_url_effects() {
    	return 'http://xiaoxu125634.github.io/Images-Lazyload-and-Lightbox/data/available_effects.json';
    }

    public static function get_scopes() {
		return array(
			'ap' => 'All Pages',
			'hm' => 'Home',
			'pp' => 'Post Page',
			'sp' => 'Single Page',
			'ca' => 'Category',
			'sh' => 'Search',
		);
    }

    public static function get_default_lightbox_selector() {
		return '#content img,.content img,.archive img,.post img,.page img';
    }

    public static function get_lazyload_icons() {
		return array(
			'1' => 'assets/loading.gif',
			'2' => 'assets/loading2.gif',
			'3' => 'assets/loading3.gif',
		);
    }

}
endif;
