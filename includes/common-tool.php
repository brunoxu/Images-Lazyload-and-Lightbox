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
if ( !class_exists( 'Common_Tool' ) ):
class Common_Tool {

    public static function rmdir_recurse($dir) {
		if ($objs = glob($dir . "/*")) {
			foreach ($objs as $obj) {
				is_dir($obj)? self::rmdir_recurse($obj) : unlink($obj);
			}
		}
		rmdir($dir);
		return true;
    }

}
endif;
