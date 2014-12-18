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
if ( !class_exists( 'Effect_Tool' ) ):
class Effect_Tool {

	public static function get_all_effects() {
		$all_effects = get_option('lazyload_lightbox_all_effects');
		if ($all_effects===FALSE) {
			$all_effects = array(
				array(
					'name' => 'slimbox2 2.05',
					'package_version' => '1.0',
					'download_url' => 'http://xiaoxu125634.github.io/Images-Lazyload-and-Lightbox/data/slimbox2_2.05(v1.0).zip',
					'description' => ''
				)
			);

			update_option('lazyload_lightbox_all_effects', $all_effects);
		}
		return $all_effects;
	}

	public static function update_all_effects($remote_data=null) {
		$all_effects = self::get_all_effects();
		$effects = self::get_effects();

		$return = array(
			'result' => 1,
			'msg' => '',
			'refresh' => 0,
		);
		$msgs = array();
		$refresh = 0;
		$do_save = 0;

		foreach ($all_effects as &$a_effect) {
			foreach ($remote_data as &$r_effect) {
				if ($a_effect['name'] == $r_effect['name']) {
					if ($a_effect['package_version'] < $r_effect['package_version']) {
						$a_effect['package_version'] = $r_effect['package_version'];
						$a_effect['download_url'] = $r_effect['download_url'];
						$refresh = 1;
					}
					if (isset($r_effect['deprecated']) && !isset($a_effect['deprecated'])) {
						$a_effect['deprecated'] = 1;
						$refresh = 1;
					}
					if ($a_effect['description'] != $r_effect['description']) {
						$a_effect['description'] = $r_effect['description'];
						$do_save = 1;
					}
					$r_effect['compared'] = 1;
					break;
				}
			}
		}
		foreach ($remote_data as $r_key=>$r_effect) {
			if (empty($r_effect['compared']) && empty($r_effect['deprecated'])) {
				$all_effects[$r_key] = $r_effect;
				$refresh = 1;
			}
		}
		if ($refresh || $do_save) {
			update_option('lazyload_lightbox_all_effects', $all_effects);
			//apply_filters('lazyload-lightbox'.'-get_all_effects', true);
		}

		if ($refresh) {
			$return['msg'] = 'Effects library has been updated, please refresh the page';
		} else {
			$return['msg'] = 'Effects library is up to date';
		}
		$return['refresh'] = $refresh;

		return $return;
	}

	public static function check_updates_for_effects() {
		$all_effects = self::get_all_effects();
		$effects = self::get_effects();

		foreach ($all_effects as &$a_effect) {
			$a_effect['installed'] = 0;
			foreach ($effects as &$l_effect) {
				if ($a_effect['name'] == $l_effect['name']) {
					$a_effect['installed'] = 1;
					$a_effect['installed_version'] = $l_effect['package_version'];
				}
			}
		}
		foreach ($all_effects as &$a_effect) {
			if ($a_effect['installed']) {
				if ($a_effect['installed_version'] < $a_effect['package_version']) {
					if (isset($a_effect['deprecated'])) {
						$a_effect['status'] = 'deprecated';
						$a_effect['operate'] = '';
					} else {
						$a_effect['status'] = 'new version v'.$a_effect['package_version'].' available';
						$a_effect['operate'] = '
<form method="post" class="inline-form">
	<input type="hidden" name="effect" value="'.$a_effect['name'].'">
	<input type="hidden" name="url" value="'.$a_effect['download_url'].'">
	<input type="submit" class="button" name="op_upgrade_effect" value="Upgrade">
</form>
';
					}
				} else {
					$a_effect['status'] = 'up to date';
					$a_effect['operate'] = '';
				}
				$a_effect['operate'] .= '
<form method="post" class="inline-form">
	<input type="hidden" name="effect_to_delete" value="'.$a_effect['name'].'">
	<input type="submit" class="button" name="op_delete_effect" value="Delete" onclick="return confirm(\'Sure to delete this effect?\')">
</form>
';
			} else {
				$a_effect['status'] = 'not installed';
				$a_effect['operate'] = '
<form method="post" class="inline-form">
	<input type="hidden" name="effect" value="'.$a_effect['name'].'">
	<input type="hidden" name="url" value="'.$a_effect['download_url'].'">
	<input type="submit" class="button" name="op_install_effect" value="Install">
</form>
';
			}
		}

		return $all_effects;
	}

	public static function init_effects() {
		$available_effects = self::reload_effects();
		update_option('lazyload_lightbox_effects', $available_effects);
	}

	public static function get_effects() {
		$need_reload = FALSE;
		$need_handle = FALSE;
		$need_save = FALSE;

		$effects = get_option('lazyload_lightbox_effects');
		if ($effects===FALSE) {
			$need_reload = TRUE;
			$need_handle = TRUE;
			$need_save = TRUE;
		}

		// reload effects
		if ($need_reload) {
			$effects = self::reload_effects();
		}

		// handle available_effects
		if ($need_handle) {
			$effects = self::handle_effects($effects);
		}

		// save available_effects
		if ($need_save) {
			update_option('lazyload_lightbox_effects', $effects);
		}

		// return available_effects
		return $effects;
	}

	public static function reload_effects() {
		$available_effects = array();

		$files = scandir(LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_DIR);
		if (!empty($files)) {
			foreach ($files as $file) {
				if ($file != '.' && $file != '..') {
					$bootstrap = LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_DIR . $file . '/bootstrap.php';
					if (is_file($bootstrap)) {
						require_once $bootstrap;
					}
				}
			}
		}

		return $available_effects;
	}

	public static function handle_effects($effects) {
		$effects_tmp = array();
		foreach ($effects as $effect_name=>&$effect) {
			if (empty($effect['adapters'])) {
				continue;
			}
			if (empty($effect['name'])) {
				continue;
			}
			$effect['name_key'] = md5($effect['name']);
			if (empty($effect['folder_name'])) {
				$effect['folder_name'] = $effect['name'];
			}
			$effect['path'] = LAZYLOAD_LIGHTBOX_PLUGIN_EFFECTS_DIR . $effect['folder_name'] . '/';
			if (! is_dir($effect['path'])) {
				continue;
			}
			$adapters_tmp = array();
			foreach ($effect['adapters'] as $adapter_name=>&$adapter) {
				if (empty($adapter['name'])) {
					continue;
				}
				if (empty($adapter['file_name'])) {
					$adapter['file_name'] = $adapter['name'];
				}
				$adapter['path'] = $effect['path'] . $adapter['file_name'] . '.php';
				if (! is_file($adapter['path'])) {
					continue;
				}
				$adapters_tmp[$adapter_name] = $adapter;
			}
			if (empty($adapters_tmp)) {
				continue;
			}
			$effect['adapters'] = $adapters_tmp;
			$effects_tmp[$effect_name] = $effect;
		}
		return $effects_tmp;
	}

}
endif;
